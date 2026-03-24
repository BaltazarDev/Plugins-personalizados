<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>

<!-- ══ MODAL POST-RESERVA ══ -->
<div class="tbwc-modal-overlay" id="tbwc-modal-overlay" aria-hidden="true">
    <div class="tbwc-modal" id="tbwc-modal" role="dialog" aria-label="Agregar productos a tu cita">

        <!-- Confirmación de cita -->
        <div class="tbwc-modal-confirm-banner">
            <span class="tbwc-modal-check">✅</span>
            <div>
                <strong id="tbwc-modal-success-msg">¡Tu cita está confirmada!</strong>
                <span id="tbwc-modal-appt-info"></span>
            </div>
        </div>

        <!-- Título -->
        <div class="tbwc-modal-head">
            <h3 class="tbwc-modal-title" id="tbwc-modal-title">
                <?= esc_html( get_option('tbwc_modal_title', '¿Agregar algo para tu cita?') ) ?>
            </h3>
        </div>

        <!-- Loading -->
        <div class="tbwc-modal-loading" id="tbwc-modal-loading">
            <div class="tbwc-spinner"></div>
            <span>Cargando productos…</span>
        </div>

        <!-- Lista de productos -->
        <ul class="tbwc-modal-products" id="tbwc-modal-products"></ul>

        <!-- Footer -->
        <div class="tbwc-modal-footer">
            <div class="tbwc-modal-total-row" id="tbwc-modal-total-row" style="display:none">
                <span>Total seleccionado</span>
                <strong id="tbwc-modal-total">$0</strong>
            </div>
            <p class="tbwc-modal-pay-note" id="tbwc-modal-subtitle">
                <?= esc_html( get_option('tbwc_modal_subtitle', 'Pagarás cuando llegues al estudio.') ) ?>
            </p>
            <div class="tbwc-modal-actions">
                <button class="tbwc-modal-btn-confirm" id="tbwc-modal-confirm" disabled>
                    <span class="tbwc-modal-btn-text"><?= esc_html( get_option('tbwc_modal_confirm', 'Confirmar productos') ) ?></span>
                    <span class="tbwc-modal-btn-loading" style="display:none">Procesando…</span>
                </button>
                <button class="tbwc-modal-btn-skip" id="tbwc-modal-skip">
                    <?= esc_html( get_option('tbwc_modal_skip', 'Solo mi cita, gracias') ) ?>
                </button>
            </div>
        </div>

        <!-- Éxito final -->
        <div class="tbwc-modal-done" id="tbwc-modal-done" style="display:none">
            <div class="tbwc-done-icon">🎉</div>
            <h3>¡Todo listo!</h3>
            <p id="tbwc-modal-done-msg">Tus productos estarán esperándote. Pagas cuando llegues a tu cita.</p>
            <button class="tbwc-modal-btn-close" id="tbwc-modal-done-close">Cerrar</button>
        </div>

    </div>
</div>

<!-- Template de producto en el modal -->
<template id="tbwc-modal-prod-tpl">
    <li class="tbwc-modal-prod" data-id="" data-price="">
        <img class="tbwc-modal-prod-img" src="" alt="">
        <div class="tbwc-modal-prod-info">
            <span class="tbwc-modal-prod-name"></span>
            <span class="tbwc-modal-prod-desc"></span>
            <span class="tbwc-modal-prod-price"></span>
        </div>
        <div class="tbwc-modal-prod-qty">
            <button class="tbwc-mq-btn tbwc-mq-minus" aria-label="Restar">−</button>
            <span class="tbwc-mq-val">0</span>
            <button class="tbwc-mq-btn tbwc-mq-plus" aria-label="Sumar">+</button>
        </div>
    </li>
</template>
