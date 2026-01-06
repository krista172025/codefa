<?php
/**
 * Order Ticket Modal Template
 * Author: Telegram @l1ghtsun
 */
if (!defined('ABSPATH')) exit;

if (!isset($order) || !$order) {
    echo '<div class="mst-error">❌ ' . __('Заказ не найден', 'mst-auth-lk') . '</div>';
    return;
}

$items = $order->get_items();
$first_item = reset($items);
$product = $first_item ? $first_item->get_product() : null;
$product_name = $first_item ? $first_item->get_name() : __('Экскурсия', 'mst-auth-lk');

// Получаем данные о гиде
$guide_id = $product ? get_post_meta($product->get_id(), '_mst_guide_id', true) : null;
$guide = $guide_id ? get_userdata($guide_id) : null;
$guide_name = $guide ? $guide->display_name : '';
$guide_phone = $guide ? get_user_meta($guide_id, 'billing_phone', true) : '';

// Генерируем QR код (упрощенная версия - ссылка на заказ)
$order_url = $order->get_view_order_url();
$qr_data = urlencode($order_url);
?>

<div class="mst-ticket-container">
    <div class="mst-ticket-header">
        <div class="mst-ticket-logo">
            🎫
        </div>
        <div class="mst-ticket-title-block">
            <h2 class="mst-ticket-title"><?php _e('Электронный билет', 'mst-auth-lk'); ?></h2>
            <p class="mst-ticket-subtitle"><?php printf(__('Заказ #%s', 'mst-auth-lk'), $order->get_id()); ?></p>
        </div>
    </div>
    
    <div class="mst-ticket-body">
        <div class="mst-ticket-main">
            <div class="mst-ticket-tour-name">
                <?php echo esc_html($product_name); ?>
            </div>
            
            <div class="mst-ticket-details">
                <div class="mst-ticket-detail">
                    <span class="mst-ticket-label">📅 <?php _e('Дата заказа', 'mst-auth-lk'); ?></span>
                    <span class="mst-ticket-value"><?php echo $order->get_date_created()->date_i18n('d F Y'); ?></span>
                </div>
                
                <div class="mst-ticket-detail">
                    <span class="mst-ticket-label">👤 <?php _e('Гость', 'mst-auth-lk'); ?></span>
                    <span class="mst-ticket-value"><?php echo esc_html($order->get_billing_first_name() . ' ' . $order->get_billing_last_name()); ?></span>
                </div>
                
                <div class="mst-ticket-detail">
                    <span class="mst-ticket-label">👥 <?php _e('Количество', 'mst-auth-lk'); ?></span>
                    <span class="mst-ticket-value"><?php echo $order->get_item_count(); ?> <?php _e('чел.', 'mst-auth-lk'); ?></span>
                </div>
                
                <?php if ($guide_name): ?>
                <div class="mst-ticket-detail">
                    <span class="mst-ticket-label">🎯 <?php _e('Ваш гид', 'mst-auth-lk'); ?></span>
                    <span class="mst-ticket-value"><?php echo esc_html($guide_name); ?></span>
                </div>
                <?php endif; ?>
                
                <?php if ($guide_phone): ?>
                <div class="mst-ticket-detail">
                    <span class="mst-ticket-label">📞 <?php _e('Телефон гида', 'mst-auth-lk'); ?></span>
                    <span class="mst-ticket-value"><?php echo esc_html($guide_phone); ?></span>
                </div>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="mst-ticket-qr">
            <img src="https://api.qrserver.com/v1/create-qr-code/?size=120x120&data=<?php echo $qr_data; ?>" alt="QR Code" class="mst-qr-image">
            <p class="mst-qr-hint"><?php _e('Покажите этот QR-код гиду', 'mst-auth-lk'); ?></p>
        </div>
    </div>
    
    <div class="mst-ticket-footer">
        <div class="mst-ticket-status <?php echo esc_attr($order->get_status()); ?>">
            <?php 
            $status_icons = [
                'completed' => '✅',
                'processing' => '⏳',
                'on-hold' => '⏸️',
                'pending' => '🕐',
                'cancelled' => '❌',
                'failed' => '❌'
            ];
            echo $status_icons[$order->get_status()] ?? '📋';
            ?>
            <?php echo esc_html(wc_get_order_status_name($order->get_status())); ?>
        </div>
        
        <div class="mst-ticket-total">
            <?php _e('Оплачено:', 'mst-auth-lk'); ?> <strong><?php echo wc_price($order->get_total()); ?></strong>
        </div>
    </div>
    
    <div class="mst-ticket-actions">
        <button type="button" class="mst-lk-btn mst-lk-btn-primary mst-print-ticket" onclick="window.print();">
            🖨️ <?php _e('Распечатать', 'mst-auth-lk'); ?>
        </button>
    </div>
</div>

<style>
.mst-ticket-container {
    background: linear-gradient(135deg, #f8f9ff 0%, #fff 100%);
    border-radius: 20px;
    overflow: hidden;
    border: 2px dashed #8b5cf6;
    position: relative;
}

.mst-ticket-header {
    background: linear-gradient(135deg, #8b5cf6 0%, #6366f1 100%);
    color: white;
    padding: 24px;
    display: flex;
    align-items: center;
    gap: 16px;
}

.mst-ticket-logo {
    font-size: 48px;
    line-height: 1;
}

.mst-ticket-title {
    margin: 0;
    font-size: 24px;
    font-weight: 700;
}

.mst-ticket-subtitle {
    margin: 4px 0 0;
    opacity: 0.9;
    font-size: 14px;
}

.mst-ticket-body {
    padding: 24px;
    display: flex;
    gap: 24px;
    align-items: flex-start;
}

.mst-ticket-main {
    flex: 1;
}

.mst-ticket-tour-name {
    font-size: 20px;
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 20px;
    line-height: 1.3;
}

.mst-ticket-details {
    display: grid;
    gap: 12px;
}

.mst-ticket-detail {
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.mst-ticket-label {
    font-size: 12px;
    color: #6b7280;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.mst-ticket-value {
    font-size: 15px;
    font-weight: 600;
    color: #1f2937;
}

.mst-ticket-qr {
    text-align: center;
    padding: 16px;
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
}

.mst-qr-image {
    width: 120px;
    height: 120px;
    display: block;
    margin: 0 auto 8px;
}

.mst-qr-hint {
    margin: 0;
    font-size: 11px;
    color: #6b7280;
}

.mst-ticket-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 16px 24px;
    background: #f3f4f6;
    border-top: 1px dashed #d1d5db;
}

.mst-ticket-status {
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 600;
}

.mst-ticket-status.completed { background: #d1fae5; color: #059669; }
.mst-ticket-status.processing { background: #dbeafe; color: #2563eb; }
.mst-ticket-status.on-hold, .mst-ticket-status.pending { background: #fef3c7; color: #d97706; }
.mst-ticket-status.cancelled, .mst-ticket-status.failed { background: #fee2e2; color: #dc2626; }

.mst-ticket-total {
    font-size: 15px;
    color: #374151;
}

.mst-ticket-total strong {
    font-size: 18px;
    color: #1f2937;
}

.mst-ticket-actions {
    padding: 16px 24px 24px;
    text-align: center;
}

@media print {
    .mst-lk-modal-close,
    .mst-ticket-actions { display: none !important; }
    .mst-ticket-container { border: 1px solid #000; }
}

@media (max-width: 560px) {
    .mst-ticket-body { flex-direction: column; }
    .mst-ticket-qr { align-self: center; }
}
</style>
