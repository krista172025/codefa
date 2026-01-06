<?php
/**
 * Order Details Modal Template
 * Author: Telegram @l1ghtsun
 */
if (!defined('ABSPATH')) exit;

if (!isset($order) || !$order) {
    echo '<div class="mst-error">❌ ' . __('Заказ не найден', 'mst-auth-lk') . '</div>';
    return;
}
?>

<div class="mst-order-modal-content">
    <h2 class="mst-modal-title">
        📋 <?php printf(__('Заказ #%s', 'mst-auth-lk'), $order->get_id()); ?>
    </h2>
    
    <div class="mst-order-status-row">
        <span class="mst-order-status-badge <?php echo esc_attr($order->get_status()); ?>">
            <?php echo esc_html(wc_get_order_status_name($order->get_status())); ?>
        </span>
        <span class="mst-order-date">
            <?php echo $order->get_date_created()->date_i18n('d F Y, H:i'); ?>
        </span>
    </div>
    
    <div class="mst-order-section">
        <h3><?php _e('Товары', 'mst-auth-lk'); ?></h3>
        <div class="mst-order-items">
            <?php foreach ($order->get_items() as $item): 
                $product = $item->get_product();
                $thumbnail = $product ? $product->get_image('thumbnail') : '';
            ?>
            <div class="mst-order-item">
                <?php if ($thumbnail): ?>
                <div class="mst-order-item-image"><?php echo $thumbnail; ?></div>
                <?php endif; ?>
                <div class="mst-order-item-info">
                    <div class="mst-order-item-name"><?php echo esc_html($item->get_name()); ?></div>
                    <div class="mst-order-item-meta">
                        <?php _e('Количество:', 'mst-auth-lk'); ?> <?php echo $item->get_quantity(); ?> × 
                        <?php echo wc_price($item->get_total() / $item->get_quantity()); ?>
                    </div>
                </div>
                <div class="mst-order-item-total">
                    <?php echo wc_price($item->get_total()); ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    
    <div class="mst-order-section">
        <h3><?php _e('Итого', 'mst-auth-lk'); ?></h3>
        <div class="mst-order-totals">
            <div class="mst-order-total-row">
                <span><?php _e('Подытог:', 'mst-auth-lk'); ?></span>
                <span><?php echo wc_price($order->get_subtotal()); ?></span>
            </div>
            <?php if ($order->get_discount_total() > 0): ?>
            <div class="mst-order-total-row mst-discount">
                <span><?php _e('Скидка:', 'mst-auth-lk'); ?></span>
                <span>-<?php echo wc_price($order->get_discount_total()); ?></span>
            </div>
            <?php endif; ?>
            <div class="mst-order-total-row mst-grand-total">
                <span><?php _e('Итого:', 'mst-auth-lk'); ?></span>
                <span><?php echo wc_price($order->get_total()); ?></span>
            </div>
        </div>
    </div>
    
    <?php 
    $billing_phone = $order->get_billing_phone();
    $billing_email = $order->get_billing_email();
    $billing_address = $order->get_formatted_billing_address();
    
    if ($billing_phone || $billing_email || $billing_address): 
    ?>
    <div class="mst-order-section">
        <h3><?php _e('Контактная информация', 'mst-auth-lk'); ?></h3>
        <div class="mst-order-contact-info">
            <?php if ($billing_phone): ?>
            <p>📞 <?php echo esc_html($billing_phone); ?></p>
            <?php endif; ?>
            <?php if ($billing_email): ?>
            <p>📧 <?php echo esc_html($billing_email); ?></p>
            <?php endif; ?>
            <?php if ($billing_address): ?>
            <p>📍 <?php echo $billing_address; ?></p>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>
    <!-- ТЕКСТОВЫЙ БЛОК НИЖЕ -->
    <div class="mst-order-section mst-order-note">
        <p>
            ℹ️ Чек и информация о местонахождении тура находятся в разделе
            <strong>«Бронирования»</strong>.
        </p>
    </div>
    
    <?php 
    $order_notes = $order->get_customer_note();
    if ($order_notes): 
    ?>
    <div class="mst-order-section">
        <h3><?php _e('Примечание', 'mst-auth-lk'); ?></h3>
        <p class="mst-order-note"><?php echo esc_html($order_notes); ?></p>
    </div>
    <?php endif; ?>
</div>

<style>
.mst-order-modal-content { padding: 10px 0; }
.mst-modal-title { margin: 0 0 16px; font-size: 22px; font-weight: 700; }
.mst-order-status-row { display: flex; align-items: center; gap: 16px; margin-bottom: 24px; }
.mst-order-status-badge { padding: 6px 14px; border-radius: 20px; font-size: 13px; font-weight: 600; }
.mst-order-status-badge.completed { background: #d1fae5; color: #059669; }
.mst-order-status-badge.processing { background: #dbeafe; color: #2563eb; }
.mst-order-status-badge.on-hold { background: #fef3c7; color: #d97706; }
.mst-order-status-badge.pending { background: #fef3c7; color: #d97706; }
.mst-order-status-badge.cancelled, .mst-order-status-badge.failed { background: #fee2e2; color: #dc2626; }
.mst-order-date { color: #6b7280; font-size: 14px; }
.mst-order-section { margin-bottom: 24px; padding-bottom: 24px; border-bottom: 1px solid #e5e7eb; }
.mst-order-section:last-child { border-bottom: none; margin-bottom: 0; padding-bottom: 0; }
.mst-order-section h3 { margin: 0 0 12px; font-size: 15px; font-weight: 600; color: #374151; }
.mst-order-items { display: flex; flex-direction: column; gap: 12px; }
.mst-order-item { display: flex; align-items: center; gap: 12px; padding: 12px; background: #f9fafb; border-radius: 12px; }
.mst-order-item-image { width: 60px; height: 60px; border-radius: 8px; overflow: hidden; flex-shrink: 0; }
.mst-order-item-image img { width: 100%; height: 100%; object-fit: cover; }
.mst-order-item-info { flex: 1; min-width: 0; }
.mst-order-item-name { font-weight: 600; color: #1f2937; margin-bottom: 4px; }
.mst-order-item-meta { font-size: 13px; color: #6b7280; }
.mst-order-item-total { font-weight: 700; color: #1f2937; }
.mst-order-totals { display: flex; flex-direction: column; gap: 8px; }
.mst-order-total-row { display: flex; justify-content: space-between; font-size: 14px; }
.mst-order-total-row.mst-discount { color: #059669; }
.mst-order-total-row.mst-grand-total { font-size: 18px; font-weight: 700; padding-top: 8px; border-top: 1px solid #e5e7eb; }
.mst-order-contact-info p { margin: 0 0 8px; font-size: 14px; color: #374151; }
.mst-order-contact-info p:last-child { margin-bottom: 0; }
.mst-order-note { margin: 0; font-size: 14px; color: #6b7280; font-style: italic; }
</style>
