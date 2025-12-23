<?php if (!defined('ABSPATH')) exit; ?>

<div class="mst-modal-inner">
    <div class="mst-modal-header">
        <h2>📅 Бронирование #<?php echo $booking->id; ?></h2>
    </div>
    
    <div class="mst-modal-body">
        <div class="mst-booking-summary">
            <div class="mst-summary-item">
                <span class="mst-summary-label">ДАТА</span>
                <span class="mst-summary-value"><?php echo $booking->nice_start_date; ?></span>
            </div>
            
            <div class="mst-summary-item">
                <span class="mst-summary-label">СТАТУС</span>
                <span class="mst-lk-order-status <?php echo $booking->status; ?>">
                    <?php echo OsBookingHelper::get_nice_status_name($booking->status); ?>
                </span>
            </div>
            
            <div class="mst-summary-item">
                <span class="mst-summary-label">СУММА</span>
                <span class="mst-summary-value mst-price"><?php echo OsMoneyHelper::format_price($booking->price); ?></span>
            </div>
        </div>
        
        <h3 class="mst-section-heading">Детали бронирования</h3>
        
        <div class="mst-booking-details">
            <div class="mst-info-row">
                <strong>Услуга:</strong>
                <span><?php echo $booking->service->name; ?></span>
            </div>
            
            <?php if ($booking->agent_id): ?>
            <div class="mst-info-row">
                <strong>Специалист:</strong>
                <span><?php echo $booking->agent->full_name; ?></span>
            </div>
            <?php endif; ?>
            
            <div class="mst-info-row">
                <strong>Дата и время:</strong>
                <span><?php echo $booking->nice_start_date_time; ?></span>
            </div>
            
            <?php if ($booking->location_id): ?>
            <div class="mst-info-row">
                <strong>Локация:</strong>
                <span><?php echo $booking->location->name; ?></span>
            </div>
            <?php endif; ?>
        </div>
        
        <?php if ($booking->customer->full_name): ?>
        <h3 class="mst-section-heading">Информация о клиенте</h3>
        
        <div class="mst-customer-info">
            <div class="mst-info-row">
                <strong>Имя:</strong>
                <span><?php echo $booking->customer->full_name; ?></span>
            </div>
            
            <?php if ($booking->customer->email): ?>
            <div class="mst-info-row">
                <strong>Email:</strong>
                <span><?php echo $booking->customer->email; ?></span>
            </div>
            <?php endif; ?>
            
            <?php if ($booking->customer->phone): ?>
            <div class="mst-info-row">
                <strong>Телефон:</strong>
                <span><?php echo $booking->customer->phone; ?></span>
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