<?php
/**
 * Shop Grid Card Template
 * Used by AJAX filter to maintain full card structure
 */

if (!defined('ABSPATH')) exit;

$product = wc_get_product(get_the_ID());
if (!$product) return;

$product_id = $product->get_id();
$image = wp_get_attachment_image_src(get_post_thumbnail_id($product_id), 'medium');
$image_url = $image ? $image[0] : wc_placeholder_img_src('medium');
$rating = $product->get_average_rating();
$rating_count = $product->get_review_count();
$price_html = $product->get_price_html();

// Get location from WooCommerce attribute
$location = $product->get_attribute('pa_location');

// Get badges from WooCommerce attributes
$badge_1 = $product->get_attribute('pa_tour-type');
$badge_2 = $product->get_attribute('pa_duration');
$badge_3 = $product->get_attribute('pa_transport');

// Default styling values - can be overridden via filter hook
$card_bg_color = apply_filters('mst_shop_grid_card_bg', '#ffffff');
$badge_bg_color = apply_filters('mst_shop_grid_badge_bg', 'rgba(255, 255, 255, 0.85)');
$badge_text_color = apply_filters('mst_shop_grid_badge_text', '#333333');
$badge_border_radius = apply_filters('mst_shop_grid_badge_radius', 20);
$title_color = apply_filters('mst_shop_grid_title_color', '#1d1d1f');
$location_icon_color = apply_filters('mst_shop_grid_location_icon', 'hsl(45, 98%, 50%)');
$location_text_color = apply_filters('mst_shop_grid_location_text', '#666666');
$star_color = apply_filters('mst_shop_grid_star_color', 'hsl(45, 98%, 50%)');
$price_color = apply_filters('mst_shop_grid_price_color', '#00c896');
$button_bg_color = apply_filters('mst_shop_grid_button_bg', '#00c896');
$button_text_color = apply_filters('mst_shop_grid_button_text', '#ffffff');
$button_text = apply_filters('mst_shop_grid_button_text', __('Подробнее', 'mst-shop-filters'));

// Wishlist settings
$wishlist_bg = apply_filters('mst_shop_grid_wishlist_bg', 'rgba(255,255,255,0.85)');
$wishlist_hover_bg = apply_filters('mst_shop_grid_wishlist_hover', 'rgba(255,255,255,0.95)');
$wishlist_icon_color = apply_filters('mst_shop_grid_wishlist_icon', '#ffffff');
$wishlist_stroke = apply_filters('mst_shop_grid_wishlist_stroke', 'hsl(0, 80%, 60%)');
$wishlist_size = apply_filters('mst_shop_grid_wishlist_size', 36);
$wishlist_icon_size = apply_filters('mst_shop_grid_wishlist_icon_size', 18);
$wishlist_blur = apply_filters('mst_shop_grid_wishlist_blur', 12);

// Guide settings
$guide_border_color = apply_filters('mst_shop_grid_guide_border', '#ffffff');
$guide_hover_border = apply_filters('mst_shop_grid_guide_hover', 'hsl(45, 98%, 60%)');
$default_guide_photo = apply_filters('mst_shop_grid_default_guide_photo', '');

// Card hover glow settings
$card_hover_glow_color = apply_filters('mst_shop_grid_card_hover_glow', 'rgba(255, 255, 255, 0.15)');
$card_hover_glow_size = apply_filters('mst_shop_grid_card_hover_glow_size', 8);
$card_hover_border_color = apply_filters('mst_shop_grid_card_hover_border', 'rgba(255, 255, 255, 0.25)');
?>
<div class="mst-shop-grid-card mst-liquid-glass" 
     style="background-color: <?php echo esc_attr($card_bg_color); ?>; overflow: hidden; --card-hover-glow-color: <?php echo esc_attr($card_hover_glow_color); ?>; --card-hover-glow-size: <?php echo esc_attr($card_hover_glow_size); ?>px; --card-hover-border-color: <?php echo esc_attr($card_hover_border_color); ?>;">
    <!-- Image with Badges and Wishlist -->
    <div class="mst-shop-grid-image">
        <a href="<?php echo esc_url(get_permalink($product_id)); ?>">
            <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($product->get_name()); ?>">
        </a>
        
        <!-- BADGES -->
        <div class="mst-shop-grid-badges mst-badges-auto-position" 
             style="position: absolute; top: 12px; left: 12px; display: flex; flex-wrap: wrap; gap: 6px; z-index: 2; max-width: calc(100% - 60px);">
            <?php if (!empty($badge_1)): ?>
            <span class="mst-shop-grid-badge mst-follow-glow" 
                  style="background: <?php echo esc_attr($badge_bg_color); ?>; color: <?php echo esc_attr($badge_text_color); ?>; backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px); border-radius: <?php echo esc_attr($badge_border_radius); ?>px; border: 1px solid rgba(255,255,255,0.3); padding: 6px 12px; font-size: 12px; display: inline-flex; align-items: center; gap: 6px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                <?php echo esc_html($badge_1); ?>
            </span>
            <?php endif; ?>
            
            <?php if (!empty($badge_2)): ?>
            <span class="mst-shop-grid-badge mst-follow-glow" 
                  style="background: <?php echo esc_attr($badge_bg_color); ?>; color: <?php echo esc_attr($badge_text_color); ?>; backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px); border-radius: <?php echo esc_attr($badge_border_radius); ?>px; border: 1px solid rgba(255,255,255,0.3); padding: 6px 12px; font-size: 12px; display: inline-flex; align-items: center; gap: 6px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                <?php echo esc_html($badge_2); ?>
            </span>
            <?php endif; ?>
            
            <?php if (!empty($badge_3)): ?>
            <span class="mst-shop-grid-badge mst-follow-glow" 
                  style="background: <?php echo esc_attr($badge_bg_color); ?>; color: <?php echo esc_attr($badge_text_color); ?>; backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px); border-radius: <?php echo esc_attr($badge_border_radius); ?>px; border: 1px solid rgba(255,255,255,0.3); padding: 6px 12px; font-size: 12px; display: inline-flex; align-items: center; gap: 6px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="3" width="15" height="13" rx="2" ry="2"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                <?php echo esc_html($badge_3); ?>
            </span>
            <?php endif; ?>
        </div>
        
        <!-- WISHLIST BUTTON -->
        <?php 
        $wishlist_style = 'position: absolute; top: 12px; right: 12px; z-index: 2; width: ' . esc_attr($wishlist_size) . 'px; height: ' . esc_attr($wishlist_size) . 'px; background: ' . esc_attr($wishlist_bg) . '; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 1px solid rgba(255,255,255,0.4); cursor: pointer; transition: all 0.3s ease; padding: 0; backdrop-filter: blur(' . esc_attr($wishlist_blur) . 'px); -webkit-backdrop-filter: blur(' . esc_attr($wishlist_blur) . 'px); box-shadow: 0 4px 12px rgba(0,0,0,0.08), inset 0 1px 2px rgba(255,255,255,0.6);';
        ?>
        <button type="button"
               class="mst-shop-grid-wishlist mst-wishlist-btn mst-follow-glow"
               data-product-id="<?php echo esc_attr($product_id); ?>"
               data-hover-bg="<?php echo esc_attr($wishlist_hover_bg); ?>"
               style="<?php echo $wishlist_style; ?>"
               aria-label="Add to wishlist">
            <svg xmlns="http://www.w3.org/2000/svg" width="<?php echo esc_attr($wishlist_icon_size); ?>" height="<?php echo esc_attr($wishlist_icon_size); ?>" viewBox="0 0 24 24" fill="<?php echo esc_attr($wishlist_icon_color); ?>" stroke="<?php echo esc_attr($wishlist_stroke); ?>" stroke-width="2" class="mst-heart-icon"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/></svg>
        </button>
    </div>
    
    <!-- Content -->
    <div class="mst-shop-grid-content">
        <h3 class="mst-shop-grid-title" style="color: <?php echo esc_attr($title_color); ?>;">
            <a href="<?php echo esc_url(get_permalink($product_id)); ?>" style="color: inherit;">
                <?php echo esc_html($product->get_name()); ?>
            </a>
        </h3>
        
        <?php if (!empty($location)): ?>
        <div class="mst-shop-grid-location" style="display: flex; align-items: center; gap: 4px; margin-bottom: 8px;">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="<?php echo esc_attr($location_icon_color); ?>"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3" fill="white"/></svg>
            <span style="color: <?php echo esc_attr($location_text_color); ?>; font-size: 13px;"><?php echo esc_html($location); ?></span>
        </div>
        <?php endif; ?>
        
        <div class="mst-shop-grid-meta">
            <div class="mst-shop-grid-rating">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="<?php echo esc_attr($star_color); ?>"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                <span><?php echo esc_html($rating ? number_format($rating, 1) : '5.0'); ?></span>
                <span class="mst-shop-grid-reviews">(<?php echo esc_html($rating_count ?: '0'); ?>)</span>
            </div>
            
            <div class="mst-shop-grid-price" style="color: <?php echo esc_attr($price_color); ?>;">
                <?php echo $price_html; ?>
            </div>
        </div>
        
        <!-- Footer: Button + Guide -->
        <div class="mst-shop-grid-button-wrapper">
            <a href="<?php echo esc_url(get_permalink($product_id)); ?>" 
               class="mst-shop-grid-button mst-follow-glow" 
               style="background: <?php echo esc_attr($button_bg_color); ?>; color: <?php echo esc_attr($button_text_color); ?>;">
                <?php echo esc_html($button_text); ?>
            </a>
            <?php if (!empty($default_guide_photo)): ?>
            <a href="#" 
               class="mst-shop-grid-guide-inside mst-follow-glow" 
               style="border-color: <?php echo esc_attr($guide_border_color); ?>;"
               data-hover-border="<?php echo esc_attr($guide_hover_border); ?>"
               title="<?php echo esc_attr(__('Гид', 'mst-shop-filters')); ?>">
                <img src="<?php echo esc_url($default_guide_photo); ?>" alt="<?php echo esc_attr(__('Гид', 'mst-shop-filters')); ?>">
            </a>
            <?php endif; ?>
        </div>
    </div>
</div>
