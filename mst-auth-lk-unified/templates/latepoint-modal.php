<?php
/**
 * LatePoint Booking Modal Template
 * Author: Telegram @l1ghtsun
 */
if (!defined('ABSPATH')) exit;

if (!isset($booking) || !$booking->id) {
    echo '<div class="mst-error">❌ ' . __('Бронирование не найдено', 'mst-auth-lk') . '</div>';
    return;
}

// Получаем данные бронирования
$service = new OsServiceModel($booking->service_id);
$agent = new OsAgentModel($booking->agent_id);
$location = new OsLocationModel($booking->location_id);

$booking_date = $booking->start_date;
$booking_time = $booking->format_start_time();
$booking_duration = $booking->get_duration();
$booking_status = $booking->status;

$status_labels = [
    'approved' => __('Подтверждено', 'mst-auth-lk'),
    'pending' => __('Ожидает подтверждения', 'mst-auth-lk'),
    'cancelled' => __('Отменено', 'mst-auth-lk'),
    'completed' => __('Завершено', 'mst-auth-lk'),
];

$status_colors = [
    'approved' => '#059669',
    'pending' => '#d97706',
    'cancelled' => '#dc2626',
    'completed' => '#2563eb',
];
?>

<div class="mst-latepoint-modal-content">
    <h2 class="mst-modal-title">
        📅 <?php _e('Детали бронирования', 'mst-auth-lk'); ?>
    </h2>
    
    <div class="mst-latepoint-status" style="background: <?php echo esc_attr($status_colors[$booking_status] ?? '#6b7280'); ?>20; color: <?php echo esc_attr($status_colors[$booking_status] ?? '#6b7280'); ?>">
        <?php echo esc_html($status_labels[$booking_status] ?? $booking_status); ?>
    </div>
    
    <div class="mst-latepoint-section">
        <h3><?php echo esc_html($service->name); ?></h3>
        
        <div class="mst-latepoint-details">
            <div class="mst-latepoint-detail">
                <span class="mst-latepoint-icon">📆</span>
                <div>
                    <span class="mst-latepoint-label"><?php _e('Дата', 'mst-auth-lk'); ?></span>
                    <span class="mst-latepoint-value"><?php echo date_i18n('d F Y', strtotime($booking_date)); ?></span>
                </div>
            </div>
            
            <div class="mst-latepoint-detail">
                <span class="mst-latepoint-icon">⏰</span>
                <div>
                    <span class="mst-latepoint-label"><?php _e('Время', 'mst-auth-lk'); ?></span>
                    <span class="mst-latepoint-value"><?php echo esc_html($booking_time); ?></span>
                </div>
            </div>
            
            <div class="mst-latepoint-detail">
                <span class="mst-latepoint-icon">⏱️</span>
                <div>
                    <span class="mst-latepoint-label"><?php _e('Длительность', 'mst-auth-lk'); ?></span>
                    <span class="mst-latepoint-value"><?php echo esc_html($booking_duration); ?> <?php _e('мин', 'mst-auth-lk'); ?></span>
                </div>
            </div>
            
            <?php if ($agent->id): ?>
            <div class="mst-latepoint-detail">
                <span class="mst-latepoint-icon">👤</span>
                <div>
                    <span class="mst-latepoint-label"><?php _e('Специалист', 'mst-auth-lk'); ?></span>
                    <span class="mst-latepoint-value"><?php echo esc_html($agent->full_name); ?></span>
                </div>
            </div>
            <?php endif; ?>
            
            <?php if ($location->id): ?>
            <div class="mst-latepoint-detail">
                <span class="mst-latepoint-icon">📍</span>
                <div>
                    <span class="mst-latepoint-label"><?php _e('Локация', 'mst-auth-lk'); ?></span>
                    <span class="mst-latepoint-value"><?php echo esc_html($location->name); ?></span>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
    
    <?php if ($booking->price > 0): ?>
    <div class="mst-latepoint-section">
        <div class="mst-latepoint-price-row">
            <span><?php _e('Стоимость:', 'mst-auth-lk'); ?></span>
            <span class="mst-latepoint-price"><?php echo OsMoneyHelper::format_price($booking->price); ?></span>
        </div>
        
        <?php if ($booking->payment_status): ?>
        <div class="mst-latepoint-payment-status">
            <?php 
            $payment_icons = [
                'fully_paid' => '✅',
                'partially_paid' => '⚠️',
                'not_paid' => '❌',
            ];
            echo $payment_icons[$booking->payment_status] ?? '💳';
            ?>
            <?php 
            $payment_labels = [
                'fully_paid' => __('Оплачено', 'mst-auth-lk'),
                'partially_paid' => __('Частично оплачено', 'mst-auth-lk'),
                'not_paid' => __('Не оплачено', 'mst-auth-lk'),
            ];
            echo esc_html($payment_labels[$booking->payment_status] ?? $booking->payment_status);
            ?>
        </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>
    
    <?php if ($booking_status === 'approved' || $booking_status === 'pending'): ?>
    <div class="mst-latepoint-actions">
        <button type="button" class="mst-lk-btn mst-lk-btn-outline mst-latepoint-cancel" data-booking-id="<?php echo $booking->id; ?>">
            ❌ <?php _e('Отменить бронирование', 'mst-auth-lk'); ?>
        </button>
    </div>
    <?php endif; ?>
</div>

<style>
.mst-latepoint-modal-content { padding: 10px 0; }
.mst-modal-title { margin: 0 0 16px; font-size: 22px; font-weight: 700; }

.mst-latepoint-status {
    display: inline-block;
    padding: 8px 16px;
    border-radius: 20px;
    font-size: 14px;
    font-weight: 600;
    margin-bottom: 24px;
}

.mst-latepoint-section {
    margin-bottom: 24px;
    padding-bottom: 24px;
    border-bottom: 1px solid #e5e7eb;
}

.mst-latepoint-section:last-of-type {
    border-bottom: none;
    margin-bottom: 0;
    padding-bottom: 0;
}

.mst-latepoint-section h3 {
    margin: 0 0 16px;
    font-size: 18px;
    font-weight: 600;
    color: #1f2937;
}

.mst-latepoint-details {
    display: grid;
    gap: 16px;
}

.mst-latepoint-detail {
    display: flex;
    align-items: flex-start;
    gap: 12px;
}

.mst-latepoint-icon {
    font-size: 20px;
    flex-shrink: 0;
}

.mst-latepoint-label {
    display: block;
    font-size: 12px;
    color: #6b7280;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 2px;
}

.mst-latepoint-value {
    display: block;
    font-size: 15px;
    font-weight: 600;
    color: #1f2937;
}

.mst-latepoint-price-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 16px;
}

.mst-latepoint-price {
    font-size: 22px;
    font-weight: 700;
    color: #1f2937;
}

.mst-latepoint-payment-status {
    margin-top: 12px;
    font-size: 14px;
    color: #6b7280;
}

.mst-latepoint-actions {
    margin-top: 24px;
    padding-top: 24px;
    border-top: 1px solid #e5e7eb;
}
</style>
