<?php
/**
 * Profile Template - Личный кабинет
 * Author: Telegram @l1ghtsun
 * 
 * UPDATED v4.0.3:
 * - Wishlist redesign like shop-grid
 * - Hidden LatePoint "Новая встреча" tab
 * - Added "Clear all wishlist" button
 */
if (!defined('ABSPATH')) exit;

$user = wp_get_current_user();
$custom_avatar = get_user_meta($user->ID, 'mst_lk_avatar', true);
$avatar_url = $custom_avatar ? wp_get_attachment_url($custom_avatar) : get_avatar_url($user->ID, ['size' => 400]);
$user_bonuses = get_user_meta($user->ID, 'mst_user_bonuses', true) ?: 0;
$user_status = get_user_meta($user->ID, 'mst_user_status', true) ?: 'bronze';
$user_status_label = get_user_meta($user->ID, 'mst_user_status_label', true) ?: __('Бронзовый статус', 'mst-auth-lk');

$status_colors = [
    'bronze' => '#CD7F32',
    'silver' => '#C0C0C0', 
    'gold' => '#FFD700',
    'guide' => '#9952E0'
];
$border_color = $status_colors[$user_status] ?? '#CD7F32';

// Получаем настройки табов
$settings = get_option('mst_auth_lk_settings', []);
$tabs = $tabs ?? $settings['tabs'] ?? [
    'orders' => ['icon' => '📦', 'label' => __('Мои заказы', 'mst-auth-lk'), 'enabled' => true],
    'bookings' => ['icon' => '📅', 'label' => __('Бронирования', 'mst-auth-lk'), 'enabled' => true],
    'affiliate' => ['icon' => '💰', 'label' => __('Реферальная программа', 'mst-auth-lk'), 'enabled' => true],
    'wishlist' => ['icon' => '❤️', 'label' => __('Избранное', 'mst-auth-lk'), 'enabled' => true],
];
?>

<div class="mst-lk-full-wrapper">
    <!-- ВЕРХНИЙ БЛОК -->
    <div class="mst-lk-top-profile">
        <div class="mst-lk-top-inner">
            <div class="mst-lk-profile-left">
                <div class="mst-lk-avatar-section">
                    <div class="mst-lk-avatar-circle" style="--border-color: <?php echo esc_attr($border_color); ?>">
                        <img src="<?php echo esc_url($avatar_url); ?>" alt="<?php echo esc_attr($user->display_name); ?>" class="mst-lk-avatar" id="mst-user-avatar">
                    </div>
                    <label for="mst-avatar-input" class="mst-lk-avatar-edit-btn">
                        📷 <?php _e('Изменить фото', 'mst-auth-lk'); ?>
                        <input type="file" id="mst-avatar-input" accept="image/*" style="display:none;">
                    </label>
                </div>
                
                <div class="mst-lk-profile-info">
                    <h2 class="mst-lk-user-name"><?php echo esc_html($user->display_name); ?></h2>
                    <p class="mst-lk-user-email"><?php echo esc_html($user->user_email); ?></p>
                    
                    <div class="mst-lk-user-badges">
                        <span class="mst-lk-badge">
                            <span class="badge-icon">👑</span>
                            <?php echo esc_html($user_status_label); ?>
                        </span>
                        <span class="mst-lk-badge">
                            <span class="badge-icon">💎</span>
                            <?php echo number_format($user_bonuses, 0, ',', ' '); ?> <?php _e('бонусов', 'mst-auth-lk'); ?>
                        </span>
                    </div>
                </div>
            </div>
            
            <div class="mst-lk-profile-right">
                <a href="#" class="mst-lk-edit-btn mst-lk-nav-item-trigger" data-section="profile">
                    <?php _e('Редактировать профиль', 'mst-auth-lk'); ?>
                </a>
                <a href="<?php echo wp_logout_url(home_url()); ?>" class="mst-lk-logout-btn">
                    <?php _e('Выйти', 'mst-auth-lk'); ?>
                </a>
            </div>
        </div>
    </div>
    
    <!-- НИЖНИЙ БЛОК -->
    <div class="mst-lk-bottom-wrapper">
        <!-- БОКОВОЕ МЕНЮ -->
        <aside class="mst-lk-sidebar">
            <nav class="mst-lk-nav">
                <?php foreach ($tabs as $key => $tab): 
                    if (empty($tab['enabled'])) continue;
                ?>
                <a href="#<?php echo esc_attr($key); ?>" class="mst-lk-nav-item" data-section="<?php echo esc_attr($key); ?>">
                    <span class="mst-lk-nav-icon"><?php echo esc_html($tab['icon'] ?? '📄'); ?></span>
                    <span><?php echo esc_html($tab['label']); ?></span>
                </a>
                <?php endforeach; ?>
            </nav>
        </aside>
        
        <!-- КОНТЕНТ -->
        <main class="mst-lk-content">
            
            <!-- Мои заказы -->
            <?php if (!empty($tabs['orders']['enabled'])): ?>
            <section class="mst-lk-section" data-section-id="orders">
                <div class="mst-lk-section-header">
                    <h2 class="mst-lk-section-title">
                        <?php echo esc_html($tabs['orders']['icon'] ?? '📦'); ?> <?php echo esc_html($tabs['orders']['label']); ?>
                    </h2>
                </div>
                
                <?php
                if (class_exists('WooCommerce')) {
                    $customer_orders = wc_get_orders([
                        'customer' => $user->ID,
                        'limit' => 20,
                        'orderby' => 'date',
                        'order' => 'DESC'
                    ]);
                    
                    if ($customer_orders): ?>
                        <div class="mst-lk-orders-list">
                            <?php foreach ($customer_orders as $order): 
                                $items = $order->get_items();
                                $first_item = reset($items);
                                $product_name = $first_item ? $first_item->get_name() : __('Заказ', 'mst-auth-lk');
                                $product = $first_item ? $first_item->get_product() : null;
                                $thumbnail = $product ? $product->get_image('thumbnail') : '';
                                $order_status = $order->get_status();
                                
                                // FIXED: Определяем можно ли оставить отзыв - только для выполненных заказов
                                $can_review = in_array($order_status, ['completed', 'wc-completed']);
                            ?>
                            <div class="mst-lk-order-card-horizontal">
                                <?php if ($thumbnail): ?>
                                <div class="mst-order-image-box">
                                    <?php echo $thumbnail; ?>
                                </div>
                                <?php endif; ?>
                                
                                <div class="mst-order-content-box">
                                    <div class="mst-order-header-row">
                                        <div class="mst-order-info-box">
                                            <h3 class="mst-order-name-text"><?php echo esc_html($product_name); ?></h3>
                                            <p class="mst-order-date-text">
                                                <?php _e('Дата:', 'mst-auth-lk'); ?> <?php echo $order->get_date_created()->date_i18n('d F Y'); ?> • <?php echo $order->get_item_count(); ?> <?php _e('товара', 'mst-auth-lk'); ?>
                                            </p>
                                        </div>
                                        
                                        <span class="mst-lk-order-status <?php echo esc_attr($order_status); ?>">
                                            <?php echo esc_html(wc_get_order_status_name($order_status)); ?>
                                        </span>
                                    </div>
                                    
                                    <div class="mst-order-divider"></div>
                                    
                                    <div class="mst-order-buttons-row">
                                        <button type="button" class="mst-lk-btn mst-lk-btn-primary mst-lk-view-ticket" data-order-id="<?php echo $order->get_id(); ?>">
                                            🎫 <?php _e('Открыть билет', 'mst-auth-lk'); ?>
                                        </button>
                                        
                                        <button type="button" class="mst-lk-btn mst-lk-btn-outline mst-lk-view-order" data-order-id="<?php echo $order->get_id(); ?>">
                                            📋 <?php _e('Подробнее', 'mst-auth-lk'); ?>
                                        </button>
                                        
                                        <button type="button" class="mst-lk-btn mst-lk-btn-outline mst-lk-download-gift" data-order-id="<?php echo $order->get_id(); ?>">
                                            💝 <?php _e('Скачать подарок', 'mst-auth-lk'); ?>
                                        </button>
                                        
                                        <?php if ($can_review): ?>
                                        <button type="button" class="mst-lk-btn mst-lk-btn-outline mst-lk-open-review" 
                                                data-product-id="<?php echo $product ? $product->get_id() : 0; ?>"
                                                data-order-id="<?php echo $order->get_id(); ?>">
                                            ⭐ <?php _e('Оставить отзыв', 'mst-auth-lk'); ?>
                                        </button>
                                        <?php else: ?>
                                        <button type="button" class="mst-lk-btn mst-lk-btn-outline mst-lk-review-disabled" disabled title="<?php _e('Отзыв можно оставить после выполнения заказа', 'mst-auth-lk'); ?>">
                                            ⭐ <?php _e('Оставить отзыв', 'mst-auth-lk'); ?>
                                        </button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="mst-lk-empty-state">
                            <div class="mst-lk-empty-icon">📦</div>
                            <p><?php _e('У вас пока нет заказов', 'mst-auth-lk'); ?></p>
                            <a href="<?php echo home_url('/shop'); ?>" class="mst-lk-btn mst-lk-btn-primary" style="margin-top:20px;">
                                🛍️ <?php _e('Перейти в магазин', 'mst-auth-lk'); ?>
                            </a>
                        </div>
                    <?php endif;
                } else {
                    echo '<div class="mst-lk-empty-state"><p>' . __('WooCommerce не установлен', 'mst-auth-lk') . '</p></div>';
                }
                ?>
            </section>
            <?php endif; ?>
            
            <!-- Бронирования -->
            <?php if (!empty($tabs['bookings']['enabled'])): ?>
            <section class="mst-lk-section" data-section-id="bookings">
                <div class="mst-lk-section-header">
                    <h2 class="mst-lk-section-title">
                        <?php echo esc_html($tabs['bookings']['icon'] ?? '📅'); ?> <?php echo esc_html($tabs['bookings']['label']); ?>
                    </h2>
                </div>
                
                <?php
                if (class_exists('OsBookingController') || defined('LATEPOINT_VERSION')) {
                    echo do_shortcode('[latepoint_customer_dashboard]');
                } else {
                    echo '<div class="mst-lk-empty-state"><div class="mst-lk-empty-icon">📅</div><p>' . __('LatePoint не установлен', 'mst-auth-lk') . '</p></div>';
                }
                ?>
            </section>
            <?php endif; ?>
            
            <!-- Реферальная программа -->
            <?php if (!empty($tabs['affiliate']['enabled'])): ?>
            <section class="mst-lk-section" data-section-id="affiliate">
                <div class="mst-lk-section-header">
                    <h2 class="mst-lk-section-title">
                        <?php echo esc_html($tabs['affiliate']['icon'] ?? '💰'); ?> <?php echo esc_html($tabs['affiliate']['label']); ?>
                    </h2>
                </div>
                
                <?php
                if (class_exists('AFWC') || function_exists('afwc_get_instance') || shortcode_exists('afwc_dashboard')) {
                    echo do_shortcode('[afwc_dashboard]');
                } else {
                    echo '<div class="mst-lk-empty-state"><div class="mst-lk-empty-icon">💰</div><p>' . __('Affiliate плагин не установлен', 'mst-auth-lk') . '</p></div>';
                }
                ?>
            </section>
            <?php endif; ?>
            
            <!-- Избранное (Wishlist) - REDESIGNED like shop-grid -->
            <?php if (!empty($tabs['wishlist']['enabled'])): ?>
            <section class="mst-lk-section" data-section-id="wishlist">
                <div class="mst-lk-section-header">
                    <h2 class="mst-lk-section-title">
                        <?php echo esc_html($tabs['wishlist']['icon'] ?? '❤️'); ?> <?php echo esc_html($tabs['wishlist']['label']); ?>
                    </h2>
                    <?php
                    $wishlist_data = get_user_meta($user->ID, 'xstore_wishlist_ids_0', true);
                    $has_items = !empty($wishlist_data);
                    if ($has_items): ?>
                    <button type="button" class="mst-lk-btn mst-lk-btn-outline mst-clear-wishlist" id="mst-clear-wishlist">
                        🗑️ <?php _e('Удалить все', 'mst-auth-lk'); ?>
                    </button>
                    <?php endif; ?>
                </div>
                
                <?php
                if ($wishlist_data) {
                    $items = explode('|', $wishlist_data);
                    $product_ids = [];
                    
                    foreach ($items as $item) {
                        $decoded = json_decode($item, true);
                        if ($decoded && isset($decoded['id'])) {
                            $product_ids[] = $decoded['id'];
                        }
                    }
                    
                    if (!empty($product_ids)): ?>
                        <div class="mst-wishlist-grid-new">
                            <?php foreach ($product_ids as $product_id):
                                $product = wc_get_product($product_id);
                                if (!$product) continue;
                                
                                $image_url = wp_get_attachment_image_src(get_post_thumbnail_id($product_id), 'medium');
                                $image_url = $image_url ? $image_url[0] : wc_placeholder_img_src('medium');
                                $rating = $product->get_average_rating();
                                $review_count = $product->get_review_count();
                                
                                // Get city from pa_city attribute
                                $city = '';
                                $city_terms = wp_get_post_terms($product_id, 'pa_city');
                                if (!is_wp_error($city_terms) && !empty($city_terms)) {
                                    $city = $city_terms[0]->name;
                                }
                            ?>
                            <div class="mst-wishlist-card" data-product-id="<?php echo $product_id; ?>">
                                <!-- Image -->
                                <div class="mst-wishlist-card-image">
                                    <a href="<?php echo get_permalink($product_id); ?>">
                                        <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($product->get_name()); ?>">
                                    </a>
                                    <button type="button" class="mst-wishlist-heart mst-remove-from-wishlist" data-product-id="<?php echo $product_id; ?>" title="<?php _e('Удалить из избранного', 'mst-auth-lk'); ?>">
                                        ❤️
                                    </button>
                                </div>
                                
                                <!-- Content -->
                                <div class="mst-wishlist-card-content">
                                    <!-- Top row: Title left, Price right -->
                                    <div class="mst-wishlist-card-top">
                                        <h3 class="mst-wishlist-card-title">
                                            <a href="<?php echo get_permalink($product_id); ?>">
                                                <?php echo esc_html($product->get_name()); ?>
                                            </a>
                                        </h3>
                                        <div class="mst-wishlist-card-price">
                                            <?php echo $product->get_price_html(); ?>
                                        </div>
                                    </div>
                                    
                                    <!-- Bottom row: City left, Reviews right -->
                                    <div class="mst-wishlist-card-bottom">
                                        <div class="mst-wishlist-card-city">
                                            <?php if ($city): ?>
                                                📍 <?php echo esc_html($city); ?>
                                            <?php else: ?>
                                                &nbsp;
                                            <?php endif; ?>
                                        </div>
                                        <div class="mst-wishlist-card-reviews">
                                            <span class="mst-star">★</span>
                                            <span class="mst-rating-value"><?php echo esc_html($rating ?: '5.0'); ?></span>
                                            <?php if ($review_count): ?>
                                            <span class="mst-review-count">(<?php echo $review_count; ?>)</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    
                                    <!-- Button - only "Подробнее" -->
                                    <div class="mst-wishlist-card-actions">
                                        <a href="<?php echo get_permalink($product_id); ?>" class="mst-lk-btn mst-lk-btn-primary mst-wishlist-btn-details" style="width: 100%;">
                                            <?php _e('Подробнее', 'mst-auth-lk'); ?>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="mst-lk-empty-state">
                            <div class="mst-lk-empty-icon">❤️</div>
                            <p><?php _e('Ваш список желаний пуст', 'mst-auth-lk'); ?></p>
                            <a href="<?php echo home_url('/shop'); ?>" class="mst-lk-btn mst-lk-btn-primary" style="margin-top:20px;">
                                🛍️ <?php _e('Перейти в магазин', 'mst-auth-lk'); ?>
                            </a>
                        </div>
                    <?php endif;
                } else { ?>
                    <div class="mst-lk-empty-state">
                        <div class="mst-lk-empty-icon">❤️</div>
                        <p><?php _e('Ваш список желаний пуст', 'mst-auth-lk'); ?></p>
                        <a href="<?php echo home_url('/shop'); ?>" class="mst-lk-btn mst-lk-btn-primary" style="margin-top:20px;">
                            🛍️ <?php _e('Перейти в магазин', 'mst-auth-lk'); ?>
                        </a>
                    </div>
                <?php } ?>
            </section>
            <?php endif; ?>
            
            <!-- ФОРМА РЕДАКТИРОВАНИЯ ПРОФИЛЯ -->
            <section class="mst-lk-section" data-section-id="profile">
                <div class="mst-lk-section-header">
                    <h2 class="mst-lk-section-title">👤 <?php _e('Редактировать профиль', 'mst-auth-lk'); ?></h2>
                </div>
                
                <div class="mst-lk-profile-form-container">
                    <form id="mst-profile-form" class="mst-modern-form">
                        <div class="mst-form-row">
                            <div class="mst-form-group">
                                <label><?php _e('Имя', 'mst-auth-lk'); ?></label>
                                <input type="text" name="first_name" value="<?php echo esc_attr(get_user_meta($user->ID, 'first_name', true)); ?>" class="mst-form-control" required>
                            </div>
                            
                            <div class="mst-form-group">
                                <label><?php _e('Фамилия', 'mst-auth-lk'); ?></label>
                                <input type="text" name="last_name" value="<?php echo esc_attr(get_user_meta($user->ID, 'last_name', true)); ?>" class="mst-form-control">
                            </div>
                        </div>
                        
                        <div class="mst-form-row">
                            <div class="mst-form-group">
                                <label>Email</label>
                                <input type="email" name="user_email" value="<?php echo esc_attr($user->user_email); ?>" class="mst-form-control" required>
                            </div>
                            
                            <div class="mst-form-group">
                                <label><?php _e('Телефон', 'mst-auth-lk'); ?></label>
                                <input type="tel" name="billing_phone" id="mst-phone-input" value="<?php echo esc_attr(get_user_meta($user->ID, 'billing_phone', true)); ?>" class="mst-form-control" placeholder="+7 (999) 123-45-67">
                            </div>
                        </div>
                        
                        <div class="mst-form-row">
                            <div class="mst-form-group">
                                <label><?php _e('Новый пароль', 'mst-auth-lk'); ?></label>
                                <input type="password" name="new_password" class="mst-form-control" placeholder="<?php _e('Оставьте пустым, если не меняете', 'mst-auth-lk'); ?>">
                            </div>
                            
                            <div class="mst-form-group">
                                <label><?php _e('Подтвердите пароль', 'mst-auth-lk'); ?></label>
                                <input type="password" name="confirm_password" class="mst-form-control" placeholder="<?php _e('Повторите новый пароль', 'mst-auth-lk'); ?>">
                            </div>
                        </div>
                        
                        <button type="submit" class="mst-lk-btn mst-lk-btn-primary">
                            💾 <?php _e('Сохранить изменения', 'mst-auth-lk'); ?>
                        </button>
                    </form>
                    
                    <!-- Настройки безопасности -->
                    <div class="mst-security-settings" style="margin-top: 32px; padding-top: 32px; border-top: 1px solid #e5e7eb;">
                        <h3 style="margin: 0 0 16px; font-size: 18px; font-weight: 600;">
                            🔐 <?php _e('Настройки безопасности', 'mst-auth-lk'); ?>
                        </h3>
                        
                        <?php $otp_disabled = get_user_meta($user->ID, 'mst_otp_disabled', true); ?>
                        <div class="mst-security-option">
                            <div class="mst-security-option-info">
                                <strong><?php _e('Подтверждение входа по email', 'mst-auth-lk'); ?></strong>
                                <p style="margin: 4px 0 0; font-size: 14px; color: #6b7280;">
                                    <?php _e('При входе с нового IP-адреса отправляется код подтверждения на ваш email', 'mst-auth-lk'); ?>
                                </p>
                            </div>
                            <label class="mst-toggle-switch">
                                <input type="checkbox" id="mst-otp-toggle" <?php checked(!$otp_disabled); ?>>
                                <span class="mst-toggle-slider"></span>
                            </label>
                        </div>
                        
                        <?php 
                        $trusted_ips = get_user_meta($user->ID, 'mst_trusted_ips', true);
                        if (is_array($trusted_ips) && !empty($trusted_ips)):
                        ?>
                        <div class="mst-trusted-devices" style="margin-top: 20px;">
                            <strong style="display: block; margin-bottom: 8px;"><?php _e('Доверенные устройства', 'mst-auth-lk'); ?></strong>
                            <p style="font-size: 14px; color: #6b7280; margin-bottom: 12px;">
                                <?php printf(__('Запомнено устройств: %d', 'mst-auth-lk'), count($trusted_ips)); ?>
                            </p>
                            <button type="button" class="mst-lk-btn mst-lk-btn-outline mst-clear-trusted-ips" style="font-size: 14px; padding: 8px 16px;">
                                🗑️ <?php _e('Сбросить все доверенные устройства', 'mst-auth-lk'); ?>
                            </button>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </section>
            
        </main>
    </div>
</div>

<!-- МОДАЛЬНЫЕ ОКНА -->
<div id="mst-lk-order-modal" class="mst-lk-modal">
    <button type="button" class="mst-lk-modal-close">×</button>
    <div class="mst-lk-modal-content">
        <div class="mst-lk-modal-body"></div>
    </div>
</div>

<div id="mst-lk-ticket-modal" class="mst-lk-modal">
    <button type="button" class="mst-lk-modal-close">×</button>
    <div class="mst-lk-modal-content">
        <div class="mst-lk-modal-body"></div>
    </div>
</div>

<div id="mst-lk-review-modal" class="mst-lk-modal">
    <button type="button" class="mst-lk-modal-close">×</button>
    <div class="mst-lk-modal-content">
        <div class="mst-lk-modal-body">
            <h2 style="margin:0 0 20px;">⭐ <?php _e('Оставить отзыв', 'mst-auth-lk'); ?></h2>
            
            <div id="review-guide-info" style="margin-bottom:20px;"></div>
            
            <form id="mst-review-form" enctype="multipart/form-data">
                <input type="hidden" name="product_id" id="review-product-id" value="">
                <input type="hidden" name="order_id" id="review-order-id" value="">
                
                <!-- Выбор гида -->
                <div class="mst-form-group" style="margin-bottom:20px;">
                    <label><?php _e('Гид экскурсии', 'mst-auth-lk'); ?></label>
                    <select name="guide_id" id="review-guide-id" class="mst-form-control">
                        <option value=""><?php _e('Автоматически (из товара)', 'mst-auth-lk'); ?></option>
                        <?php
                        // Получаем всех гидов
                        $guides = get_users([
                            'meta_key' => 'mst_user_status',
                            'meta_value' => ['guide', 'gold', 'silver', 'bronze'],
                            'meta_compare' => 'IN',
                        ]);
                        foreach ($guides as $g):
                            $g_city = get_user_meta($g->ID, 'mst_guide_city', true);
                        ?>
                        <option value="<?php echo esc_attr($g->ID); ?>">
                            <?php echo esc_html($g->display_name); ?>
                            <?php if ($g_city): ?>(<?php echo esc_html($g_city); ?>)<?php endif; ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                    <p class="mst-help-text" style="font-size:12px;color:#6b7280;margin-top:4px;">
                        <?php _e('Оставьте пустым для автоопределения или выберите гида', 'mst-auth-lk'); ?>
                    </p>
                </div>
                
                <div class="mst-form-group" style="margin-bottom:20px;">
                    <label><?php _e('Оценка', 'mst-auth-lk'); ?></label>
                    <div class="mst-star-rating">
                        <?php for ($i = 5; $i >= 1; $i--): ?>
                        <input type="radio" name="rating" value="<?php echo $i; ?>" id="star<?php echo $i; ?>" <?php echo $i === 5 ? 'checked' : ''; ?>>
                        <label for="star<?php echo $i; ?>" title="<?php echo $i; ?> звезд">★</label>
                        <?php endfor; ?>
                    </div>
                </div>
                
                <div class="mst-form-group" style="margin-bottom:20px;">
                    <label><?php _e('Ваш отзыв', 'mst-auth-lk'); ?></label>
                    <textarea name="comment" class="mst-form-control" rows="4" placeholder="<?php _e('Расскажите о вашем опыте...', 'mst-auth-lk'); ?>" required></textarea>
                </div>
                
                <!-- Загрузка фото -->
                <div class="mst-form-group" style="margin-bottom:20px;">
                    <label><?php _e('Фотографии', 'mst-auth-lk'); ?> <small style="color:#6b7280;">(<?php _e('до 5 фото', 'mst-auth-lk'); ?>)</small></label>
                    <div class="mst-review-photos-upload">
                        <input type="file" name="review_photos[]" id="review-photos-input" accept="image/*" multiple style="display:none;">
                        <label for="review-photos-input" class="mst-review-photos-btn">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                            <span><?php _e('Добавить фото', 'mst-auth-lk'); ?></span>
                        </label>
                        <div id="review-photos-preview" class="mst-review-photos-preview"></div>
                    </div>
                </div>
                
                <button type="submit" class="mst-lk-btn mst-lk-btn-primary" style="width:100%;">
                    <?php _e('Отправить отзыв', 'mst-auth-lk'); ?>
                </button>
            </form>
        </div>
    </div>
</div>

<style>
/* Star Rating */
.mst-star-rating {
    display: flex;
    flex-direction: row-reverse;
    justify-content: flex-end;
    gap: 4px;
}
.mst-star-rating input { display: none; }
.mst-star-rating label {
    cursor: pointer;
    font-size: 28px;
    color: #d1d5db;
    transition: color 0.2s;
}
.mst-star-rating label:hover,
.mst-star-rating label:hover ~ label,
.mst-star-rating input:checked ~ label {
    color: #fbbf24;
}

/* Guide Preview in Review */
.mst-review-guide-preview {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px;
    background: #f3f4f6;
    border-radius: 12px;
}
.mst-review-guide-preview img {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    object-fit: cover;
}
.mst-review-guide-preview strong {
    display: block;
    font-size: 15px;
    color: #1f2937;
}
.mst-review-guide-preview small {
    color: #6b7280;
    font-size: 13px;
}

/* Photo Upload */
.mst-review-photos-upload {
    display: flex;
    flex-direction: column;
    gap: 12px;
}
.mst-review-photos-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 16px;
    border: 2px dashed #d1d5db;
    border-radius: 12px;
    cursor: pointer;
    color: #6b7280;
    transition: all 0.2s;
}
.mst-review-photos-btn:hover {
    border-color: #9952E0;
    color: #9952E0;
    background: rgba(153, 82, 224, 0.05);
}
.mst-review-photos-preview {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}
.mst-review-photo-item {
    position: relative;
    width: 80px;
    height: 80px;
    border-radius: 8px;
    overflow: hidden;
}
.mst-review-photo-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.mst-review-photo-remove {
    position: absolute;
    top: 4px;
    right: 4px;
    width: 20px;
    height: 20px;
    background: rgba(0,0,0,0.6);
    color: white;
    border: none;
    border-radius: 50%;
    cursor: pointer;
    font-size: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Disabled review button */
.mst-lk-review-disabled {
    opacity: 0.5;
    cursor: not-allowed !important;
}
</style>

<script>
// Photo preview functionality
document.addEventListener('DOMContentLoaded', function() {
    const photoInput = document.getElementById('review-photos-input');
    const preview = document.getElementById('review-photos-preview');
    
    if (photoInput && preview) {
        photoInput.addEventListener('change', function() {
            preview.innerHTML = '';
            const files = Array.from(this.files).slice(0, 5);
            
            files.forEach((file, index) => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const div = document.createElement('div');
                    div.className = 'mst-review-photo-item';
                    div.innerHTML = `
                        <img src="${e.target.result}" alt="">
                        <button type="button" class="mst-review-photo-remove" data-index="${index}">×</button>
                    `;
                    preview.appendChild(div);
                };
                reader.readAsDataURL(file);
            });
        });
        
        preview.addEventListener('click', function(e) {
            if (e.target.classList.contains('mst-review-photo-remove')) {
                e.target.closest('.mst-review-photo-item').remove();
            }
        });
    }
    
    // FIXED: Обновление отображения выбранного гида в select
    const guideSelect = document.getElementById('review-guide-id');
    if (guideSelect) {
        guideSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const guideInfoDiv = document.getElementById('review-guide-info');
            
            if (this.value && guideInfoDiv) {
                guideInfoDiv.innerHTML = '<div class="mst-review-guide-preview"><strong>Выбранный гид: ' + selectedOption.text + '</strong></div>';
            } else if (guideInfoDiv) {
                guideInfoDiv.innerHTML = '';
            }
        });
    }
});
</script>
