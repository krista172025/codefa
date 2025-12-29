<?php 
/**
 * Author: Telegram @l1ghtsun
 * Author URI: https://t.me/l1ghtsun
 */
if (!defined('ABSPATH')) exit; 
?>

<div class="mst-modal-inner">
    <div class="mst-modal-header" style="padding: 30px; border-bottom: 1px solid #e5e7eb; background: linear-gradient(135deg, #9952E0 0%, #9952E0 100%);">
        <h2 style="margin: 0; color: #fff;">🎫 Билет #<?php echo $order->get_order_number(); ?></h2>
    </div>
    
    <div class="mst-modal-body" style="padding: 40px; max-height: 70vh; overflow-y: auto;">
        <div class="mst-ticket-view" style="text-align: center;">
            <!-- QR КОД -->
            <div class="mst-ticket-qr" style="width: 220px; height: 220px; margin: 0 auto 30px; background: linear-gradient(135deg, #f0f0f0 0%, #e0e0e0 100%); border-radius: 16px; display: flex; align-items: center; justify-content: center; border: 3px solid #9952E0; box-shadow: 0 8px 24px rgba(0, 200, 150, 0.2);">
                <?php
                // Генерация QR кода
                $qr_data = 'ORDER-' . $order->get_order_number() . '-' . $order->get_customer_id();
                $qr_url = 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' . urlencode($qr_data);
                ?>
                <img src="<?php echo esc_url($qr_url); ?>" alt="QR Code" style="width: 100%; height: 100%; border-radius: 12px;">
            </div>
            
            <!-- ИНФОРМАЦИЯ О БИЛЕТЕ -->
            <div class="mst-ticket-info">
                <h3 style="font-size: 26px; font-weight: 700; margin: 0 0 20px; color: #1d1d1f;"><?php 
                    $items = $order->get_items();
                    $first_item = reset($items);
                    echo $first_item ? esc_html($first_item->get_name()) : 'Заказ';
                ?></h3>
                
                <div style="background: #f9fafb; border-radius: 12px; padding: 20px; margin-bottom: 24px; text-align: left;">
                    <p style="font-size: 16px; color: #333; margin: 12px 0; display: flex; align-items: center; gap: 10px;">
                        <strong style="min-width: 140px;">📅 Дата:</strong> 
                        <span><?php echo $order->get_date_created()->date_i18n('d F Y, H:i'); ?></span>
                    </p>
                    
                    <p style="font-size: 16px; color: #333; margin: 12px 0; display: flex; align-items: center; gap: 10px;">
                        <strong style="min-width: 140px;">📍 Место встречи:</strong> 
                        <span><em>Уточняется администратором</em></span>
                    </p>
                    
                    <p style="font-size: 16px; color: #333; margin: 12px 0; display: flex; align-items: center; gap: 10px;">
                        <strong style="min-width: 140px;">🎯 Статус:</strong> 
                        <span class="mst-lk-order-status <?php echo esc_attr($order->get_status()); ?>" style="display: inline-block;">
                            <?php echo wc_get_order_status_name($order->get_status()); ?>
                        </span>
                    </p>
                    
                    <p style="font-size: 16px; color: #333; margin: 12px 0; display: flex; align-items: center; gap: 10px;">
                        <strong style="min-width: 140px;">💰 Сумма:</strong> 
                        <span style="color: #9952E0; font-weight: 700; font-size: 18px;"><?php echo $order->get_formatted_order_total(); ?></span>
                    </p>
                </div>
            </div>
            
            <div style="padding: 20px; background: #fff3cd; border-radius: 12px; border-left: 4px solid #ffc107; margin-top: 24px;">
                <p style="margin: 0; color: #856404; font-size: 14px;">
                    <strong>ℹ️ Важная информация:</strong><br>
                    Пожалуйста, предъявите этот QR-код на месте проведения мероприятия. Сохраните скриншот билета или имейте доступ к интернету.
                </p>
            </div>
        </div>
    </div>
    
    <div class="mst-modal-footer" style="padding: 20px 30px; border-top: 1px solid #e5e7eb; display: flex; justify-content: space-between; align-items: center; gap: 12px;">
        <button type="button" class="mst-lk-btn mst-lk-btn-primary" onclick="window.print();" style="background: linear-gradient(135deg, #9952E0 0%, #9952E0 100%);">
            🖨️ Распечатать
        </button>
        <button type="button" class="mst-lk-btn mst-lk-btn-outline mst-lk-modal-close">
            Закрыть
        </button>
    </div>
</div>

<style>
@media print {
    .mst-lk-modal-close,
    .mst-modal-footer {
        display: none !important;
    }
    .mst-modal-body {
        max-height: none !important;
    }
}
</style>