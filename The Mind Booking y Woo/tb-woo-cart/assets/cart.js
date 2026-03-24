/* global TBWC, jQuery */
(function ($) {
  'use strict';

  /* ══ ESTADO GLOBAL ══════════════════════════════════════════════════ */
  var state = {
    drawerOpen: false,
    modalOpen:  false,
    cart:       { items:[], count:0, empty:true, total_fmt:'' },
    modal: {
      appointment_id: 0,
      client: {},
      products: [],       // productos del servidor
      selected: {},       // { product_id: qty }
    }
  };

  /* ══ REFERENCIAS DOM ════════════════════════════════════════════════ */
  var $overlay    = $('#tbwc-overlay');
  var $drawer     = $('#tbwc-drawer');
  var $drawerBody = $('#tbwc-drawer-body');
  var $itemsList  = $('#tbwc-items');
  var $emptyMsg   = $('#tbwc-empty');
  var $footer     = $('#tbwc-drawer-footer');
  var $drawerCnt  = $('#tbwc-drawer-count');
  var $total      = $('#tbwc-total');
  var $fab        = $('#tbwc-fab');
  var $fabCnt     = $('#tbwc-fab-count');
  var $itemTpl    = document.getElementById('tbwc-item-tpl');

  var $modalOverlay  = $('#tbwc-modal-overlay');
  var $modal         = $('#tbwc-modal');
  var $modalLoading  = $('#tbwc-modal-loading');
  var $modalProducts = $('#tbwc-modal-products');
  var $modalConfirm  = $('#tbwc-modal-confirm');
  var $modalSkip     = $('#tbwc-modal-skip');
  var $modalTotal    = $('#tbwc-modal-total');
  var $modalTotalRow = $('#tbwc-modal-total-row');
  var $modalDone     = $('#tbwc-modal-done');
  var $modalDoneMsg  = $('#tbwc-modal-done-msg');
  var $modalDoneClose= $('#tbwc-modal-done-close');
  var $modalProdTpl  = document.getElementById('tbwc-modal-prod-tpl');

  /* ══ DRAWER — ABRIR / CERRAR ════════════════════════════════════════ */
  function openDrawer() {
    state.drawerOpen = true;
    $drawer.addClass('open').attr('aria-hidden', 'false');
    $overlay.addClass('open');
    $('body').css('overflow', 'hidden');
  }
  function closeDrawer() {
    state.drawerOpen = false;
    $drawer.removeClass('open').attr('aria-hidden', 'true');
    $overlay.removeClass('open');
    $('body').css('overflow', '');
  }

  /* ══ MODAL — ABRIR / CERRAR ════════════════════════════════════════ */
  function openModal(data) {
    state.modal.appointment_id = data.appointment_id;
    state.modal.client         = data.client || {};
    state.modal.selected       = {};
    state.modalOpen = true;

    // Texto de éxito personalizado
    $('#tbwc-modal-success-msg').text(data.msg || '¡Tu cita está confirmada!');
    $('#tbwc-modal-title').text(data.modal_title || '¿Agregar algo para tu cita?');
    $('#tbwc-modal-subtitle').text(data.modal_subtitle || 'Pagarás cuando llegues al estudio.');
    $('#tbwc-modal-confirm .tbwc-modal-btn-text').text(data.modal_confirm || 'Confirmar productos');
    $('#tbwc-modal-skip').text(data.modal_skip || 'Solo mi cita, gracias');

    $modalConfirm.prop('disabled', true);
    $modalTotalRow.hide();
    $modalDone.hide();
    $modalProducts.hide();
    $modalLoading.show();
    $modalOverlay.addClass('open');
    $('body').css('overflow', 'hidden');

    loadModalProducts();
  }
  function closeModal() {
    state.modalOpen = false;
    $modalOverlay.removeClass('open');
    $('body').css('overflow', '');
    // Reset
    setTimeout(function() {
      $modalDone.hide();
      $modalProducts.show();
      $modalConfirm.prop('disabled', true);
      $modalTotalRow.hide();
    }, 300);
  }

  /* ══ CARGAR PRODUCTOS DEL MODAL ════════════════════════════════════ */
  function loadModalProducts() {
    $.post(TBWC.url, { action:'tbwc_get_modal_products', nonce:TBWC.nonce })
    .done(function(res) {
      $modalLoading.hide();
      if (!res.success || !res.data || !res.data.length) {
        $modalProducts.html('<li style="padding:30px;text-align:center;color:#9ca3af;font-size:.85rem">Sin productos disponibles.</li>').show();
        return;
      }
      state.modal.products = res.data;
      $modalProducts.empty();
      res.data.forEach(function(prod) {
        var clone = $modalProdTpl.content.cloneNode(true);
        var $li   = $(clone.querySelector('.tbwc-modal-prod'));
        $li.attr({ 'data-id': prod.id, 'data-price': prod.price });
        $li.find('.tbwc-modal-prod-img').attr({ src: prod.image, alt: prod.name });
        $li.find('.tbwc-modal-prod-name').text(prod.name);
        $li.find('.tbwc-modal-prod-desc').text(prod.description);
        $li.find('.tbwc-modal-prod-price').html(prod.price_html);
        $li.find('.tbwc-mq-val').text(0);
        $modalProducts.append($li);
      });
      $modalProducts.show();
    })
    .fail(function() {
      $modalLoading.hide();
      $modalProducts.html('<li style="padding:30px;text-align:center;color:#ef4444">Error al cargar productos.</li>').show();
    });
  }

  /* ══ MODAL — CANTIDAD DE PRODUCTO ══════════════════════════════════ */
  $('body').on('click', '.tbwc-mq-minus, .tbwc-mq-plus', function() {
    var $btn  = $(this);
    var $li   = $btn.closest('.tbwc-modal-prod');
    var pid   = parseInt($li.data('id'));
    var price = parseFloat($li.data('price'));
    var $val  = $li.find('.tbwc-mq-val');
    var qty   = parseInt($val.text()) || 0;

    if ($btn.hasClass('tbwc-mq-plus')) qty = Math.min(qty + 1, 10);
    else qty = Math.max(qty - 1, 0);

    $val.text(qty).toggleClass('active', qty > 0);

    if (qty > 0) state.modal.selected[pid] = qty;
    else delete state.modal.selected[pid];

    updateModalTotal();
  });

  function updateModalTotal() {
    var total  = 0;
    var hasAny = false;
    state.modal.products.forEach(function(prod) {
      var qty = state.modal.selected[prod.id] || 0;
      if (qty > 0) { total += prod.price * qty; hasAny = true; }
    });
    $modalConfirm.prop('disabled', !hasAny);
    if (hasAny) {
      $modalTotal.text(formatPrice(total));
      $modalTotalRow.show();
    } else {
      $modalTotalRow.hide();
    }
  }

  /* ══ MODAL — CONFIRMAR ══════════════════════════════════════════════ */
  $('body').on('click', '#tbwc-modal-confirm', function() {
    var $btn  = $(this);
    var items = [];
    Object.keys(state.modal.selected).forEach(function(pid) {
      items.push({ id: pid, qty: state.modal.selected[pid] });
    });
    if (!items.length) return;

    $btn.prop('disabled', true);
    $btn.find('.tbwc-modal-btn-text').hide();
    $btn.find('.tbwc-modal-btn-loading').show();

    $.post(TBWC.url, {
      action:         'tbwc_create_order',
      nonce:          TBWC.nonce,
      appointment_id: state.modal.appointment_id,
      items:          items,
      name:           state.modal.client.name  || '',
      email:          state.modal.client.email || '',
      phone:          state.modal.client.phone || '',
    })
    .done(function(res) {
      if (res.success) {
        showModalDone(res.data.msg);
      } else {
        alert(res.data.msg || 'Error al procesar la orden.');
        $btn.prop('disabled', false);
        $btn.find('.tbwc-modal-btn-text').show();
        $btn.find('.tbwc-modal-btn-loading').hide();
      }
    })
    .fail(function() {
      alert('Error de conexión.');
      $btn.prop('disabled', false);
      $btn.find('.tbwc-modal-btn-text').show();
      $btn.find('.tbwc-modal-btn-loading').hide();
    });
  });

  function showModalDone(msg) {
    $modalProducts.hide();
    $modal.find('.tbwc-modal-head').hide();
    $modal.find('.tbwc-modal-footer').hide();
    $modalDoneMsg.text(msg || 'Tus productos estarán esperándote.');
    $modalDone.show();
  }

  $('body').on('click', '#tbwc-modal-done-close, #tbwc-modal-skip', closeModal);

  /* ══ BOTÓN AGREGAR AL CARRITO ═══════════════════════════════════════ */
  $('body').on('click', '.tbwc-add-btn', function() {
    var $btn       = $(this);
    var product_id = $btn.data('product-id');
    var openDrawer_= $btn.data('open-drawer') === 'yes';
    if (!product_id || $btn.prop('disabled')) return;

    $btn.addClass('loading').prop('disabled', true);
    $btn.find('.tbwc-btn-inner').hide();
    $btn.find('.tbwc-btn-spinner').show();

    $.post(TBWC.url, { action:'tbwc_add_to_cart', nonce:TBWC.nonce, product_id:product_id, qty:1 })
    .done(function(res) {
      if (res.success) {
        updateCartUI(res.data.cart, res.data.count);
        // Feedback visual "Agregado"
        $btn.removeClass('loading').addClass('added').prop('disabled', false);
        $btn.find('.tbwc-btn-spinner').hide();
        $btn.find('.tbwc-btn-inner').text($btn.data('text-added')).show();
        setTimeout(function() {
          $btn.removeClass('added');
          $btn.find('.tbwc-btn-inner').text($btn.data('text-default'));
        }, 2200);
        if (openDrawer_) openDrawer();
      } else {
        resetBtn($btn);
        alert(res.data.msg || 'Error al agregar.');
      }
    })
    .fail(function() { resetBtn($btn); alert('Error de conexión.'); });
  });

  function resetBtn($btn) {
    $btn.removeClass('loading added').prop('disabled', false);
    $btn.find('.tbwc-btn-spinner').hide();
    $btn.find('.tbwc-btn-inner').text($btn.data('text-default')).show();
  }

  /* ══ DRAWER — CONTROLES ═════════════════════════════════════════════ */
  // Abrir
  $('body').on('click', '#tbwc-drawer-close, #tbwc-fab', function() {
    if ($(this).is('#tbwc-fab')) openDrawer(); else closeDrawer();
  });
  $overlay.on('click', closeDrawer);

  // Cerrar con Escape
  $(document).on('keydown', function(e) {
    if (e.key === 'Escape') {
      if (state.drawerOpen) closeDrawer();
      if (state.modalOpen)  closeModal();
    }
  });

  // Remover ítem
  $('body').on('click', '.tbwc-item-remove', function() {
    var $li  = $(this).closest('.tbwc-item');
    var key  = $li.data('key');
    $li.addClass('removing');
    setTimeout(function() {
      $.post(TBWC.url, { action:'tbwc_remove_from_cart', nonce:TBWC.nonce, cart_key:key })
      .done(function(res) { if(res.success) updateCartUI(res.data.cart, res.data.count); });
    }, 200);
  });

  // +/- cantidad en drawer
  $('body').on('click', '.tbwc-qty-minus, .tbwc-qty-plus', function() {
    var $btn = $(this);
    var $li  = $btn.closest('.tbwc-item');
    var key  = $li.data('key');
    var $val = $li.find('.tbwc-qty-val');
    var qty  = parseInt($val.text()) || 1;
    if ($btn.hasClass('tbwc-qty-plus')) qty++;
    else qty = Math.max(0, qty - 1);
    $val.text(qty);
    $.post(TBWC.url, { action:'tbwc_update_qty', nonce:TBWC.nonce, cart_key:key, qty:qty })
    .done(function(res) { if(res.success) updateCartUI(res.data.cart, res.data.count); });
  });

  /* ══ RENDER CARRITO ═════════════════════════════════════════════════ */
  function updateCartUI(cart, count) {
    state.cart = cart;
    var c = count || 0;

    // Contadores
    $drawerCnt.text(c);
    $fabCnt.text(c);

    // FAB visible solo si hay items
    if (c > 0) $fab.show(); else $fab.hide();

    if (cart.empty || !cart.items.length) {
      $itemsList.empty();
      $emptyMsg.show();
      $footer.hide();
      return;
    }
    $emptyMsg.hide();
    $footer.show();

    // Renderizar items
    $itemsList.empty();
    cart.items.forEach(function(item) {
      if (!$itemTpl) return;
      var clone = $itemTpl.content.cloneNode(true);
      var $li   = $(clone.querySelector('.tbwc-item'));
      $li.attr('data-key', item.key);
      $li.find('.tbwc-item-img').attr({ src: item.image, alt: item.name });
      $li.find('.tbwc-item-name').text(item.name);
      $li.find('.tbwc-item-price').html(item.price_fmt + ' c/u');
      $li.find('.tbwc-qty-val').text(item.qty);
      $itemsList.append($li);
    });

    // Total
    $total.html(cart.total_fmt);
  }

  /* ══ HOOK: RESERVA EXITOSA — mostrar modal ══════════════════════════
     El JS del plugin principal dispara el evento 'tb:booking:success'
     con los datos de la respuesta AJAX cuando la cita se guarda.
  ════════════════════════════════════════════════════════════════════ */
  $(document).on('tb:booking:success', function(e, data, formData) {
    if (!data.show_products) return;
    openModal({
      appointment_id: data.appointment_id,
      msg:            data.msg,
      modal_title:    data.modal_title,
      modal_subtitle: data.modal_subtitle,
      modal_confirm:  data.modal_confirm,
      modal_skip:     data.modal_skip,
      client: {
        name:  formData.name  || '',
        email: formData.email || '',
        phone: formData.phone || '',
      }
    });
  });

  /* ══ HELPER — Formatear precio ═══════════════════════════════════════ */
  function formatPrice(num) {
    return (TBWC.currency || '$') + parseFloat(num).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
  }

  /* ══ INIT ════════════════════════════════════════════════════════════ */
  $(function() {
    // Cargar carrito inicial si WooCommerce tiene sesión
    $.post(TBWC.url, { action:'tbwc_get_cart', nonce:TBWC.nonce })
    .done(function(res) {
      if (res.success) updateCartUI(res.data.cart, res.data.count);
    });
  });

})(jQuery);
