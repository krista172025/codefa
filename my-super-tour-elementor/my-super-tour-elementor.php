<?php
/**
 * Plugin Name: My Super Tour Elementor Widgets
 * Description: Custom Elementor widgets with liquid glass design, animations, and warm purple/yellow palette
 * Version: 1.0.0
 * Author: My Super Tour
 * Text Domain: my-super-tour-elementor
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

define('MST_ELEMENTOR_VERSION', '1.0.0');
define('MST_ELEMENTOR_PATH', plugin_dir_path(__FILE__));
define('MST_ELEMENTOR_URL', plugin_dir_url(__FILE__));

// Check if Elementor is installed and activated
function mst_elementor_check_elementor() {
    if (!did_action('elementor/loaded')) {
        add_action('admin_notices', 'mst_elementor_missing_notice');
        return false;
    }
    return true;
}

function mst_elementor_missing_notice() {
    echo '<div class="notice notice-warning is-dismissible">
        <p>' . __('My Super Tour Elementor Widgets requires Elementor to be installed and activated.', 'my-super-tour-elementor') . '</p>
    </div>';
}

// Initialize the plugin
function mst_elementor_init() {
    if (!mst_elementor_check_elementor()) {
        return;
    }

    // Register widget categories
    add_action('elementor/elements/categories_registered', 'mst_elementor_add_widget_categories');
    
    // Register widgets
    add_action('elementor/widgets/register', 'mst_elementor_register_widgets');
    
    // Enqueue styles and scripts
    add_action('wp_enqueue_scripts', 'mst_elementor_enqueue_assets');
    add_action('elementor/editor/before_enqueue_scripts', 'mst_elementor_editor_scripts');
    
    // AJAX handlers for wishlist
    add_action('wp_ajax_mst_toggle_wishlist', 'mst_handle_wishlist_ajax');
    add_action('wp_ajax_nopriv_mst_toggle_wishlist', 'mst_handle_wishlist_ajax');
}
add_action('plugins_loaded', 'mst_elementor_init');

// Add custom widget category
function mst_elementor_add_widget_categories($elements_manager) {
    $elements_manager->add_category(
        'my-super-tour',
        [
            'title' => __('My Super Tour', 'my-super-tour-elementor'),
            'icon' => 'fa fa-plane',
        ]
    );
}

// Register widgets
function mst_elementor_register_widgets($widgets_manager) {
    // Include widget files
    require_once MST_ELEMENTOR_PATH . 'widgets/liquid-glass-card.php';
    require_once MST_ELEMENTOR_PATH . 'widgets/floating-glass-orbs.php';
    require_once MST_ELEMENTOR_PATH . 'widgets/grainy-hero-section.php';
    require_once MST_ELEMENTOR_PATH . 'widgets/tour-card.php';
    require_once MST_ELEMENTOR_PATH . 'widgets/carousel.php';
    require_once MST_ELEMENTOR_PATH . 'widgets/trust-badge.php';
    require_once MST_ELEMENTOR_PATH . 'widgets/review-card.php';
    require_once MST_ELEMENTOR_PATH . 'widgets/category-card.php';
    require_once MST_ELEMENTOR_PATH . 'widgets/team-section.php';
    require_once MST_ELEMENTOR_PATH . 'widgets/contact-instagram.php';
    require_once MST_ELEMENTOR_PATH . 'widgets/promo-banner.php';
    require_once MST_ELEMENTOR_PATH . 'widgets/review-stats.php';
    require_once MST_ELEMENTOR_PATH . 'widgets/header.php';
    require_once MST_ELEMENTOR_PATH . 'widgets/footer.php';
    require_once MST_ELEMENTOR_PATH . 'widgets/stats-card.php';
    require_once MST_ELEMENTOR_PATH . 'widgets/feature-card.php';
    require_once MST_ELEMENTOR_PATH . 'widgets/feature-card-carousel.php';
    require_once MST_ELEMENTOR_PATH . 'widgets/guest-carousel.php';
    require_once MST_ELEMENTOR_PATH . 'widgets/marquee-section.php';
    require_once MST_ELEMENTOR_PATH . 'widgets/team-circle-section.php';
    require_once MST_ELEMENTOR_PATH . 'widgets/woo-tour-card.php';
    require_once MST_ELEMENTOR_PATH . 'widgets/guide-card.php';
    require_once MST_ELEMENTOR_PATH . 'widgets/thank-you-hero.php';
    require_once MST_ELEMENTOR_PATH . 'widgets/thank-you-video.php';
    require_once MST_ELEMENTOR_PATH . 'widgets/thank-you-benefits.php';
    require_once MST_ELEMENTOR_PATH . 'widgets/tour-carousel.php';
    require_once MST_ELEMENTOR_PATH . 'widgets/guide-carousel.php';
    require_once MST_ELEMENTOR_PATH . 'widgets/review-carousel.php';
    require_once MST_ELEMENTOR_PATH . 'widgets/woo-tour-carousel.php';
    require_once MST_ELEMENTOR_PATH . 'widgets/shop-grid.php';
    require_once MST_ELEMENTOR_PATH . 'widgets/accommodation-carousel.php';
    require_once MST_ELEMENTOR_PATH . 'widgets/reviews-section.php';
    require_once MST_ELEMENTOR_PATH . 'widgets/logo-explainer.php';
    require_once MST_ELEMENTOR_PATH . 'widgets/search-header.php';

    // Register widgets
    $widgets_manager->register(new \MST_Elementor\Widgets\Liquid_Glass_Card());
    $widgets_manager->register(new \MST_Elementor\Widgets\Floating_Glass_Orbs());
    $widgets_manager->register(new \MST_Elementor\Widgets\Grainy_Hero_Section());
    $widgets_manager->register(new \MST_Elementor\Widgets\Tour_Card());
    $widgets_manager->register(new \MST_Elementor\Widgets\Carousel());
    $widgets_manager->register(new \MST_Elementor\Widgets\Trust_Badge());
    $widgets_manager->register(new \MST_Elementor\Widgets\Review_Card());
    $widgets_manager->register(new \MST_Elementor\Widgets\Category_Card());
    $widgets_manager->register(new \MST_Elementor\Widgets\Team_Section());
    $widgets_manager->register(new \MST_Elementor\Widgets\Contact_Instagram());
    $widgets_manager->register(new \MST_Elementor\Widgets\Promo_Banner());
    $widgets_manager->register(new \MST_Elementor\Widgets\Review_Stats());
    $widgets_manager->register(new \MST_Elementor\Widgets\Header());
    $widgets_manager->register(new \MST_Elementor\Widgets\Footer());
    $widgets_manager->register(new \MST_Elementor\Widgets\Stats_Card());
    $widgets_manager->register(new \MST_Elementor\Widgets\Feature_Card());
    $widgets_manager->register(new \MST_Elementor\Widgets\Feature_Card_Carousel());
    $widgets_manager->register(new \MST_Elementor\Widgets\Guest_Carousel());
    $widgets_manager->register(new \MST_Elementor\Widgets\Marquee_Section());
    $widgets_manager->register(new \MST_Elementor\Widgets\Team_Circle_Section());
    $widgets_manager->register(new \MST_Elementor\Widgets\Woo_Tour_Card());
    $widgets_manager->register(new \MST_Elementor\Widgets\Guide_Card());
    $widgets_manager->register(new \MST_Elementor\Widgets\Thank_You_Hero());
    $widgets_manager->register(new \MST_Elementor\Widgets\Thank_You_Video());
    $widgets_manager->register(new \MST_Elementor\Widgets\Thank_You_Benefits());
    $widgets_manager->register(new \MST_Elementor\Widgets\Tour_Carousel());
    $widgets_manager->register(new \MST_Elementor\Widgets\Guide_Carousel());
    $widgets_manager->register(new \MST_Elementor\Widgets\Review_Carousel());
    $widgets_manager->register(new \MST_Elementor\Widgets\Woo_Tour_Carousel());
    $widgets_manager->register(new \MST_Elementor\Widgets\Shop_Grid());
    $widgets_manager->register(new \MST_Elementor\Widgets\Accommodation_Carousel());
    $widgets_manager->register(new \MySuperTourElementor\Widgets\Reviews_Section());
    $widgets_manager->register(new \MST_Elementor\Widgets\Logo_Explainer());
    $widgets_manager->register(new \MST_Elementor\Widgets\Search_Header());
}

// Enqueue frontend styles and scripts
function mst_elementor_enqueue_assets() {
    wp_enqueue_style(
        'mst-elementor-widgets',
        MST_ELEMENTOR_URL . 'assets/css/widgets.css',
        [],
        MST_ELEMENTOR_VERSION
    );

    wp_enqueue_script(
        'mst-elementor-widgets',
        MST_ELEMENTOR_URL . 'assets/js/widgets.js',
        ['jquery'],
        MST_ELEMENTOR_VERSION,
        true
    );
}

// Enqueue editor scripts
function mst_elementor_editor_scripts() {
    wp_enqueue_style(
        'mst-elementor-editor',
        MST_ELEMENTOR_URL . 'assets/css/widgets.css',
        [],
        MST_ELEMENTOR_VERSION
    );
}

// AJAX handler for wishlist toggle
function mst_handle_wishlist_ajax() {
    // Verify nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'mst_wishlist_nonce')) {
        wp_send_json_error('Invalid nonce');
        return;
    }
    
    // Get product ID
    $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
    if (!$product_id) {
        wp_send_json_error('Invalid product ID');
        return;
    }
    
    // Get current user
    $user_id = get_current_user_id();
    if (!$user_id) {
        wp_send_json_error('User not logged in');
        return;
    }
    
    // Check if adding or removing
    $add_to_wishlist = isset($_POST['add']) && $_POST['add'] === '1';
    
    // Get XStore wishlist meta key
    $wishlist_key = 'xstore_wishlist_ids_0';
    $wishlist_ids = get_user_meta($user_id, $wishlist_key, true);
    
    // Ensure it's an array
    if (!is_array($wishlist_ids)) {
        $wishlist_ids = !empty($wishlist_ids) ? array($wishlist_ids) : array();
    }
    
    // Add or remove product
    if ($add_to_wishlist) {
        if (!in_array($product_id, $wishlist_ids)) {
            $wishlist_ids[] = $product_id;
        }
    } else {
        $wishlist_ids = array_diff($wishlist_ids, array($product_id));
    }
    
    // Update user meta
    update_user_meta($user_id, $wishlist_key, array_values($wishlist_ids));
    
    // Return success with count
    wp_send_json_success(array(
        'count' => count($wishlist_ids),
        'product_id' => $product_id,
        'action' => $add_to_wishlist ? 'added' : 'removed'
    ));
}
