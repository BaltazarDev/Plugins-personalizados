<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>

<!-- ══ OVERLAY ══ -->
<div class="tbwc-overlay" id="tbwc-overlay" aria-hidden="true"></div>

<!-- ══ MINI CART DRAWER ══ -->
<div class="tbwc-drawer" id="tbwc-drawer" role="dialog" aria-label="Carrito" aria-hidden="true">

    <!-- Header -->
    <div class="tbwc-drawer-header">
        <div class="tbwc-drawer-title">
            <svg class="tbwc-icon-cart" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/>
            </svg>
            Tu Carrito
            <span class="tbwc-drawer-count" id="tbwc-drawer-count">0</span>
        </div>
        <button class="tbwc-drawer-close" id="tbwc-drawer-close" aria-label="Cerrar carrito">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
            </svg>
        </button>
    </div>

    <!-- Cuerpo -->
    <div class="tbwc-drawer-body" id="tbwc-drawer-body">

        <!-- Estado vacío -->
        <div class="tbwc-empty" id="tbwc-empty">
            <div class="tbwc-empty-icon">🛒</div>
            <p>Tu carrito está vacío</p>
            <span>Agrega productos desde el catálogo</span>
        </div>

        <!-- Lista de items -->
        <ul class="tbwc-items" id="tbwc-items"></ul>

    </div>

    <!-- Footer -->
    <div class="tbwc-drawer-footer" id="tbwc-drawer-footer" style="display:none">
        <div class="tbwc-total-row">
            <span class="tbwc-total-label">Total</span>
            <span class="tbwc-total-value" id="tbwc-total">$0</span>
        </div>
        <p class="tbwc-pay-note">Pagarás cuando llegues a tu cita.</p>
        <a class="tbwc-reserve-btn" id="tbwc-reserve-btn" href="<?= esc_url( get_option('tbwc_reserve_page_url', home_url('/')) ) ?>">
            Reservar mi Cita
            <svg viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
        </a>
    </div>
</div>

<!-- ══ BURBUJA FLOTANTE (contador) ══ -->
<button class="tbwc-fab" id="tbwc-fab" aria-label="Abrir carrito" style="display:none">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/>
    </svg>
    <span class="tbwc-fab-count" id="tbwc-fab-count">0</span>
</button>

<!-- Template de ítem (clonado por JS) -->
<template id="tbwc-item-tpl">
    <li class="tbwc-item" data-key="">
        <img class="tbwc-item-img" src="" alt="">
        <div class="tbwc-item-info">
            <span class="tbwc-item-name"></span>
            <span class="tbwc-item-price"></span>
            <div class="tbwc-qty-row">
                <button class="tbwc-qty-btn tbwc-qty-minus" aria-label="Restar">−</button>
                <span class="tbwc-qty-val"></span>
                <button class="tbwc-qty-btn tbwc-qty-plus" aria-label="Sumar">+</button>
            </div>
        </div>
        <button class="tbwc-item-remove" aria-label="Quitar">
            <svg viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
        </button>
    </li>
</template>
