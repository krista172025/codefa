<?php
/**
 * Author: Telegram @l1ghtsun
 * Author URI: https://t.me/l1ghtsun
 */
if (!defined('ABSPATH')) exit;

$user = wp_get_current_user();
$custom_avatar = get_user_meta($user->ID, 'mst_lk_avatar', true);
$avatar_url = $custom_avatar ? wp_get_attachment_url($custom_avatar) : get_avatar_url($user->ID, ['size' => 400]);
$user_bonuses = get_user_meta($user->ID, 'mst_user_bonuses', true) ?: 0;
$user_status = get_user_meta($user->ID, 'mst_user_status', true) ?: 'bronze';
$user_status_label = get_user_meta($user->ID, 'mst_user_status_label', true) ?: 'Бронзовый статус';

$tabs = $settings['tabs'] ?? [];

// Цвета рамок для статусов (ДОБАВЛЕН ЗЕЛЕНЫЙ ДЛЯ ГИДА)
$status_colors = [
    'bronze' => '#CD7F32',
    'silver' => '#C0C0C0', 
    'gold' => '#FFD700',
    'guide' => '#00c896' // ЗЕЛЕНАЯ РАМКА ДЛЯ ГИДА
];
$border_color = $status_colors[$user_status] ?? '#CD7F32';
?>

<div class="mst-lk-full-wrapper">
    <!-- ВЕРХНИЙ БЛОК -->
    <div class="mst-lk-top-profile">
        <div class="mst-lk-top-inner">
            <div class="mst-lk-profile-left">
                <div class="mst-lk-avatar-section">
                    <div class="mst-lk-avatar-circle" style="--border-color: <?php echo $border_color; ?>">
                        <img src="<?php echo esc_url($avatar_url); ?>" alt="<?php echo esc_attr($user->display_name); ?>" class="mst-lk-avatar" id="mst-user-avatar">
                    </div>
                    <label for="mst-avatar-input" class="mst-lk-avatar-edit-btn">
                        📷 Изменить фото
                        <input type="file" id="mst-avatar-input" accept="image/*" style="display:none;">
                    </label>
                </div>
                
                <div class="mst-lk-profile-info">
                    <h2 class="mst-lk-user-name"><?php echo esc_html($user->display_name); ?></h2>
                    <p class="mst-lk-user-email"><?php echo esc_html($user->user_email); ?></p>
                    
                    <div class="mst-lk-user-badges">
    <span class="mst-lk-badge" data-tooltip="Ваш текущий статус влияет на размер скидок и бонусов">
        <a href="<?php echo home_url('/faq-status'); ?>" class="mst-badge-link">
            <span class="badge-icon">👑</span>
            <?php echo esc_html($user_status_label); ?>
        </a>
    </span>
    <span class="mst-lk-badge" data-tooltip="Бонусы начисляются за покупки и можно использовать для оплаты">
        <a href="<?php echo home_url('/faq-bonuses'); ?>" class="mst-badge-link">
            <span class="badge-icon">💎</span>
            <?php echo number_format($user_bonuses, 0, ',', ' '); ?> бонусов
        </a>
    </span>
</div>
                </div>
            </div>
            
            <div class="mst-lk-profile-right">
                <a href="#" class="mst-lk-edit-btn mst-lk-nav-item-trigger" data-section="profile">
                    Редактировать профиль
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
                <a href="#<?php echo $key; ?>" class="mst-lk-nav-item" data-section="<?php echo $key; ?>">
                    <span class="mst-lk-nav-icon"><?php echo $tab['icon']; ?></span>
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
                        <?php echo $tabs['orders']['icon']; ?> <?php echo esc_html($tabs['orders']['label']); ?>
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
                                $product_name = $first_item ? $first_item->get_name() : 'Заказ';
                                $product = $first_item ? $first_item->get_product() : null;
                                $thumbnail = $product ? $product->get_image('thumbnail') : '';
                                $latepoint_booking_id = $order->get_meta('latepoint_booking_id');
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
                    Дата: <?php echo $order->get_date_created()->date_i18n('d F Y'); ?> • <?php echo $order->get_item_count(); ?> товара
                </p>
            </div>
            
            <span class="mst-lk-order-status <?php echo esc_attr($order->get_status()); ?>">
                <?php echo esc_html(wc_get_order_status_name($order->get_status())); ?>
            </span>
        </div>
		
        <div class="mst-order-divider"></div>
        <div class="mst-order-buttons-row">
            <button type="button" class="mst-lk-btn mst-lk-btn-primary mst-lk-view-ticket" 
                    data-order-id="<?php echo $order->get_id(); ?>">
                🎫 Открыть билет
            </button>
            
            <button type="button" class="mst-lk-btn mst-lk-btn-outline mst-lk-view-order" 
                    data-order-id="<?php echo $order->get_id(); ?>">
                📋 Подробнее
            </button>
            
            <button type="button" class="mst-lk-btn mst-lk-btn-outline mst-lk-download-gift" 
                    data-order-id="<?php echo $order->get_id(); ?>">
                💝 Скачать подарок
            </button>
            
            <button type="button" class="mst-lk-btn mst-lk-btn-outline mst-lk-open-review" 
                    data-product-id="<?php echo $product ? $product->get_id() : 0; ?>"
                    data-order-id="<?php echo $order->get_id(); ?>">
                ⭐ Оставить отзыв
            </button>
        </div>
    </div>
</div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="mst-lk-empty-state">
                            <div class="mst-lk-empty-icon">📦</div>
                            <p>У вас пока нет заказов</p>
                        </div>
                    <?php endif;
                }
                ?>
            </section>
            <?php endif; ?>
            
            <!-- Бронирования -->
            <?php if (!empty($tabs['bookings']['enabled'])): ?>
            <section class="mst-lk-section" data-section-id="bookings">
                <div class="mst-lk-section-header">
                    <h2 class="mst-lk-section-title">
                        <?php echo $tabs['bookings']['icon']; ?> <?php echo esc_html($tabs['bookings']['label']); ?>
                    </h2>
                </div>
                
                <?php
                if (class_exists('OsBookingController') || defined('LATEPOINT_VERSION')) {
                    echo do_shortcode('[latepoint_customer_dashboard]');
                } else {
                    echo '<div class="mst-lk-empty-state"><div class="mst-lk-empty-icon">📅</div><p>LatePoint не установлен</p></div>';
                }
                ?>
            </section>
            <?php endif; ?>
            
            <!-- Сообщения -->
            <?php if (!empty($tabs['messages']['enabled'])): ?>
            <section class="mst-lk-section" data-section-id="messages">
                <div class="mst-lk-section-header">
                    <h2 class="mst-lk-section-title">
                        <?php echo $tabs['messages']['icon']; ?> <?php echo esc_html($tabs['messages']['label']); ?>
                    </h2>
                </div>
                
                <div class="mst-lk-empty-state">
                    <div class="mst-lk-empty-icon">💬</div>
                    <p>Чат с менеджером скоро будет доступен</p>
                </div>
            </section>
            <?php endif; ?>
            
            <!-- Реферальная программа -->
            <?php if (!empty($tabs['affiliate']['enabled'])): ?>
            <section class="mst-lk-section" data-section-id="affiliate">
                <div class="mst-lk-section-header">
                    <h2 class="mst-lk-section-title">
                        <?php echo $tabs['affiliate']['icon']; ?> <?php echo esc_html($tabs['affiliate']['label']); ?>
                    </h2>
                </div>
                
                <?php
                if (class_exists('AFWC') || function_exists('afwc_get_instance') || shortcode_exists('afwc_dashboard')) {
                    echo do_shortcode('[afwc_dashboard]');
                } else {
                    echo '<div class="mst-lk-empty-state"><div class="mst-lk-empty-icon">💰</div><p>Плагин Affiliate не установлен</p></div>';
                }
                ?>
            </section>
            <?php endif; ?>
            
            <!-- Избранное -->
            <?php if (!empty($tabs['wishlist']['enabled'])): ?>
            <section class="mst-lk-section" data-section-id="wishlist">
                <div class="mst-lk-section-header">
                    <h2 class="mst-lk-section-title">
                        <?php echo $tabs['wishlist']['icon']; ?> <?php echo esc_html($tabs['wishlist']['label']); ?>
                    </h2>
                </div>
                
                <?php
                $wishlist_data = get_user_meta($user->ID, 'xstore_wishlist_ids_0', true);
                
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
                        <div class="mst-shop-grid mst-wishlist-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px;">
                            <?php foreach ($product_ids as $product_id):
                                $product = wc_get_product($product_id);
                                if (!$product) continue;
                                
                                $image_url = wp_get_attachment_image_src(get_post_thumbnail_id($product_id), 'medium');
                                $image_url = $image_url ? $image_url[0] : wc_placeholder_img_src('medium');
                                $rating = $product->get_average_rating();
                                $rating_count = $product->get_review_count();
                                
                                // Получаем гида
                                $guide_id = get_post_meta($product_id, '_mst_guide_id', true);
                                $guide_photo = '';
                                if ($guide_id) {
                                    $custom_avatar = get_user_meta($guide_id, 'mst_lk_avatar', true);
                                    $guide_photo = $custom_avatar ? wp_get_attachment_url($custom_avatar) : get_avatar_url($guide_id, ['size' => 80]);
                                }
                            ?>
                            <div class="mst-shop-grid-card mst-liquid-glass" style="background: #fff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 16px rgba(0,0,0,0.08);">
                                <div class="mst-shop-grid-image" style="position: relative; height: 180px;">
                                    <a href="<?php echo get_permalink($product_id); ?>">
                                        <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($product->get_name()); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                                    </a>
                                    
                                    <!-- Wishlist remove button -->
                                    <button type="button" class="mst-remove-from-wishlist" 
                                            data-product-id="<?php echo $product_id; ?>"
                                            style="position: absolute; top: 12px; right: 12px; width: 36px; height: 36px; background: rgba(255,255,255,0.9); border-radius: 50%; border: none; cursor: pointer; display: flex; align-items: center; justify-content: center;">
                                        ❤️
                                    </button>
                                </div>
                                
                                <div class="mst-shop-grid-content" style="padding: 16px;">
                                    <h3 style="font-size: 16px; font-weight: 600; margin: 0 0 8px;">
                                        <a href="<?php echo get_permalink($product_id); ?>" style="color: #1d1d1f; text-decoration: none;">
                                            <?php echo esc_html($product->get_name()); ?>
                                        </a>
                                    </h3>
                                    
                                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                                        <div style="display: flex; align-items: center; gap: 4px;">
                                            <span style="color: #ffc107;">★</span>
                                            <span style="font-weight: 600;"><?php echo esc_html($rating ?: '5.0'); ?></span>
                                            <span style="color: #999;">(<?php echo esc_html($rating_count ?: '0'); ?>)</span>
                                        </div>
                                        <div style="color: var(--mst-primary, #8b5cf6); font-weight: 700;">
                                            <?php echo $product->get_price_html(); ?>
                                        </div>
                                    </div>
                                    
                                    <div style="position: relative;">
                                        <a href="<?php echo get_permalink($product_id); ?>" 
                                           class="mst-lk-btn mst-lk-btn-primary"
                                           style="display: block; text-align: center;">
                                            Подробнее
                                        </a>
                                        <?php if ($guide_photo): ?>
                                        <img src="<?php echo esc_url($guide_photo); ?>" 
                                             alt="Гид" 
                                             style="position: absolute; right: -8px; bottom: -8px; width: 50px; height: 50px; border-radius: 50%; border: 3px solid #fff; object-fit: cover; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="mst-lk-empty-state">
                            <div class="mst-lk-empty-icon">❤️</div>
                            <p>Ваш список желаний пуст</p>
                            <a href="<?php echo home_url('/shop'); ?>" class="mst-lk-btn mst-lk-btn-primary" style="margin-top: 20px;">
                                🛍️ Перейти в магазин
                            </a>
                        </div>
                    <?php endif;
                } else { ?>
                    <div class="mst-lk-empty-state">
                        <div class="mst-lk-empty-icon">❤️</div>
                        <p>Ваш список желаний пуст</p>
                        <a href="<?php echo home_url('/shop'); ?>" class="mst-lk-btn mst-lk-btn-primary" style="margin-top: 20px;">
                            🛍️ Перейти в магазин
                        </a>
                    </div>
                <?php } ?>
            </section>
            <?php endif; ?>
            
            <!-- ИСПРАВЛЕННАЯ ФОРМА РЕДАКТИРОВАНИЯ ПРОФИЛЯ -->
            <section class="mst-lk-section" data-section-id="profile">
                <div class="mst-lk-section-header">
                    <h2 class="mst-lk-section-title">👤 Редактировать профиль</h2>
                </div>
                
                <div class="mst-lk-profile-form-container">
                    <form id="mst-profile-form" class="mst-modern-form">
                        <div class="mst-form-row">
                            <div class="mst-form-group">
                                <label>Имя</label>
                                <input type="text" name="first_name" value="<?php echo esc_attr(get_user_meta($user->ID, 'first_name', true)); ?>" class="mst-form-control" required>
                            </div>
                            
                            <div class="mst-form-group">
                                <label>Фамилия</label>
                                <input type="text" name="last_name" value="<?php echo esc_attr(get_user_meta($user->ID, 'last_name', true)); ?>" class="mst-form-control">
                            </div>
                        </div>
                        
                        <div class="mst-form-row">
                            <div class="mst-form-group">
                                <label>Email</label>
                                <input type="email" name="user_email" value="<?php echo esc_attr($user->user_email); ?>" class="mst-form-control" required>
                            </div>
                            
                            <div class="mst-form-group">
                                <label>Телефон</label>
                                <input type="tel" 
                                       name="billing_phone" 
                                       id="mst-phone-input"
                                       value="<?php echo esc_attr(get_user_meta($user->ID, 'billing_phone', true)); ?>" 
                                       class="mst-form-control" 
                                       placeholder="+7 (999) 123-45-67">
                            </div>
                        </div>
                        
                        <div class="mst-form-row">
                            <div class="mst-form-group">
                                <label>Новый пароль (оставьте пустым, если не хотите менять)</label>
                                <input type="password" name="new_password" class="mst-form-control" autocomplete="new-password">
                            </div>
                            
                            <div class="mst-form-group">
                                <label>Повторите пароль</label>
                                <input type="password" name="confirm_password" class="mst-form-control" autocomplete="new-password">
                            </div>
                        </div>
                        
                        <button type="submit" class="mst-lk-btn mst-lk-btn-primary mst-save-btn">💾 Сохранить изменения</button>
                    </form>
                </div>
            </section>
        </main>
    </div>
</div>

<!-- Модальное окно для "Подробнее" -->
<div id="mst-lk-order-modal" class="mst-lk-modal">
    <div class="mst-lk-modal-content">
        <button type="button" class="mst-lk-modal-close">×</button>
        <div class="mst-lk-modal-body"></div>
    </div>
</div>

<!-- Модальное окно для "Открыть билет" -->
<div id="mst-lk-ticket-modal" class="mst-lk-modal">
    <div class="mst-lk-modal-content">
        <button type="button" class="mst-lk-modal-close">×</button>
        <div class="mst-lk-modal-body"></div>
    </div>
</div>

<!-- Модальное окно LatePoint -->
<div id="mst-lk-latepoint-modal" class="mst-lk-modal">
    <div class="mst-lk-modal-content">
        <button type="button" class="mst-lk-modal-close">×</button>
        <div class="mst-lk-modal-body"></div>
    </div>
</div>

<!-- Модальное окно отзыва -->
<div id="mst-lk-review-modal" class="mst-lk-modal">
    <div class="mst-lk-modal-content">
        <button type="button" class="mst-lk-modal-close">×</button>
        <div class="mst-lk-modal-body">
            <h2>⭐ Оставить отзыв</h2>
            <form id="mst-review-form">
                <input type="hidden" name="product_id" id="review-product-id">
                
                <div class="mst-form-group">
                    <label>Оценка</label>
                    <div class="mst-star-rating">
                        <input type="radio" name="rating" value="5" id="star5" checked><label for="star5">★</label>
                        <input type="radio" name="rating" value="4" id="star4"><label for="star4">★</label>
                        <input type="radio" name="rating" value="3" id="star3"><label for="star3">★</label>
                        <input type="radio" name="rating" value="2" id="star2"><label for="star2">★</label>
                        <input type="radio" name="rating" value="1" id="star1"><label for="star1">★</label>
                    </div>
                </div>
                
                <div class="mst-form-group">
                    <label>Ваш отзыв</label>
                    <textarea name="comment" rows="4" placeholder="Расскажите о вашем опыте..." required></textarea>
                </div>
                
                <button type="submit" class="mst-lk-btn mst-lk-btn-primary">Отправить отзыв</button>
            </form>
        </div>
    </div>
</div>
</div>