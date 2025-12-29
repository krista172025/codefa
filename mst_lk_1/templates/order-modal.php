<?php if (!defined('ABSPATH')) exit; ?>

<div class="mst-modal-inner">
    <div class="mst-modal-header">
        <h2>📄 Заказ #<?php echo $order->get_order_number(); ?></h2>
    </div>
    
    <div class="mst-modal-body">
        <div class="mst-order-summary">
            <div class="mst-summary-item">
                <span class="mst-summary-label">ДАТА</span>
                <span class="mst-summary-value"><?php echo $order->get_date_created()->date_i18n('d.m.Y'); ?></span>
            </div>
            
            <div class="mst-summary-item">
                <span class="mst-summary-label">СТАТУС</span>
                <span class="mst-lk-order-status <?php echo $order->get_status(); ?>">
                    <?php echo wc_get_order_status_name($order->get_status()); ?>
                </span>
            </div>
            
            <div class="mst-summary-item">
                <span class="mst-summary-label">СУММА</span>
                <span class="mst-summary-value mst-price"><?php echo $order->get_formatted_order_total(); ?></span>
            </div>
        </div>
        
        <h3 class="mst-section-heading">Товары</h3>
        
        <div class="mst-order-items">
            <?php foreach ($order->get_items() as $item): 
                $product = $item->get_product();
            ?>
                <div class="mst-order-item">
                    <?php if ($product && $product->get_image_id()): ?>
                    <div class="mst-item-image">
                        <?php echo $product->get_image('thumbnail'); ?>
                    </div>
                    <?php endif; ?>
                    
                    <div class="mst-item-details">
                        <h4><?php echo $item->get_name(); ?></h4>
                        <p class="mst-item-meta">
                            <?php echo $item->get_quantity(); ?> × <?php echo wc_price($item->get_total() / $item->get_quantity()); ?>
                        </p>
                    </div>
                    
                    <div class="mst-item-total">
                        <?php echo wc_price($item->get_total()); ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <?php if ($order->get_billing_first_name()): ?>
        <h3 class="mst-section-heading">Информация о клиенте</h3>
        
        <div class="mst-customer-info">
            <div class="mst-info-row">
                <strong>Имя:</strong>
                <span><?php echo esc_html($order->get_billing_first_name() . ' ' . $order->get_billing_last_name()); ?></span>
            </div>
            
            <?php if ($order->get_billing_email()): ?>
            <div class="mst-info-row">
                <strong>Email:</strong>
                <span><?php echo esc_html($order->get_billing_email()); ?></span>
            </div>
            <?php endif; ?>
            
            <?php if ($order->get_billing_phone()): ?>
            <div class="mst-info-row">
                <strong>Телефон:</strong>
                <span><?php echo esc_html($order->get_billing_phone()); ?></span>
            </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>
    
    <div class="mst-modal-footer">
        <button type="button" class="mst-lk-btn mst-lk-btn-outline mst-lk-modal-close">
            Закрыть
        </button>
    </div>
</div>

<style>
.mst-modal-inner { padding: 0; }
.mst-modal-header { padding: 24px 32px; border-bottom: 1px solid #e5e7eb; }
.mst-modal-header h2 { margin: 0; font-size: 24px; font-weight: 700; color: #1d1d1f; }
.mst-modal-body { padding: 32px; max-height: 70vh; overflow-y: auto; }
.mst-modal-footer { padding: 20px 32px; border-top: 1px solid #e5e7eb; display: flex; justify-content: flex-end; gap: 12px; }

.mst-order-summary { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; background: #f9fafb; border-radius: 12px; padding: 24px; margin-bottom: 32px; }
.mst-summary-item { text-align: center; }
.mst-summary-label { display: block; font-size: 11px; color: #999; text-transform: uppercase; margin-bottom: 8px; letter-spacing: 0.5px; }
.mst-summary-value { display: block; font-size: 18px; font-weight: 700; color: #333; }
.mst-summary-value.mst-price { color: #00c896; font-size: 22px; }

.mst-section-heading { font-size: 18px; font-weight: 700; color: #1d1d1f; margin: 0 0 20px; }

.mst-order-items { display: flex; flex-direction: column; gap: 16px; margin-bottom: 32px; }
.mst-order-item { display: flex; gap: 16px; background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; padding: 16px; align-items: center; }
.mst-item-image { width: 80px; height: 80px; flex-shrink: 0; border-radius: 8px; overflow: hidden; }
.mst-item-image img { width: 100%; height: 100%; object-fit: cover; }
.mst-item-details { flex: 1; }
.mst-item-details h4 { margin: 0 0 6px; font-size: 16px; font-weight: 600; color: #333; }
.mst-item-meta { margin: 0; font-size: 14px; color: #666; }
.mst-item-total { font-size: 18px; font-weight: 700; color: #00c896; }

.mst-customer-info { background: #f9fafb; border-radius: 12px; padding: 20px; }
.mst-info-row { display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #e5e7eb; }
.mst-info-row:last-child { border-bottom: none; }
.mst-info-row strong { color: #666; font-weight: 600; }
.mst-info-row span { color: #333; }

.mst-loading { text-align: center; padding: 80px 20px; }
.spinner { border: 4px solid #f3f3f3; border-top: 4px solid #00c896; border-radius: 50%; width: 50px; height: 50px; animation: spin 1s linear infinite; margin: 0 auto 20px; }
@keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
.mst-loading p { color: #666; font-size: 16px; margin: 0; }
.mst-error { text-align: center; padding: 80px 20px; color: #ef4444; font-size: 18px; }
</style>