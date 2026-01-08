<?php
/**
 * Plugin Name: My Super Tour Elementor Widgets
 * Description: Custom Elementor widgets with liquid glass design, animations, and warm purple/yellow palette
 * Version: 1.1.01
 * Author: Telegram @l1ghtsun
 * Author URI: https://t.me/l1ghtsun
 * Text Domain: Widgets for elementor
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

define('MST_ELEMENTOR_VERSION', '1.1.1');
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
    require_once MST_ELEMENTOR_PATH . 'widgets/city-categories-section.php';
    require_once MST_ELEMENTOR_PATH . 'widgets/city-hero-section.php';
    require_once MST_ELEMENTOR_PATH . 'widgets/city-quiz-section.php';
    require_once MST_ELEMENTOR_PATH . 'widgets/city-tour-details-section.php';
    require_once MST_ELEMENTOR_PATH . 'widgets/tour-booking-card.php';
    require_once MST_ELEMENTOR_PATH . 'widgets/product-guide-block.php';
    require_once MST_ELEMENTOR_PATH . 'widgets/meeting-point-block.php';
    require_once MST_ELEMENTOR_PATH . 'widgets/booking-terms-block.php';
    require_once MST_ELEMENTOR_PATH . 'widgets/faq-block.php';
    require_once MST_ELEMENTOR_PATH . 'widgets/woo-product-gallery.php';
    require_once MST_ELEMENTOR_PATH . 'widgets/booking-confirmation-page.php';
    require_once MST_ELEMENTOR_PATH . 'widgets/mst-cart-widget.php';
    require_once MST_ELEMENTOR_PATH . 'widgets/mst-checkout-widget.php';
    require_once MST_ELEMENTOR_PATH . 'widgets/help-section.php';
    require_once MST_ELEMENTOR_PATH . 'widgets/single-product-blog.php';
    require_once MST_ELEMENTOR_PATH . 'widgets/single-blog-article.php';
    require_once MST_ELEMENTOR_PATH . 'widgets/single-product-toast.php';
    

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
    $widgets_manager->register(new \MySuperTour\Widgets\City_Categories_Section());
    $widgets_manager->register(new \MySuperTour\Widgets\City_Hero_Section());
    $widgets_manager->register(new \MySuperTour\Widgets\City_Quiz_Section());
    $widgets_manager->register(new \MySuperTour\Widgets\City_Tour_Details_Section());
    $widgets_manager->register(new MST_Elementor\Widgets\Tour_Booking_Card());
    $widgets_manager->register(new \MST_Elementor\Widgets\Product_Guide_Block());
    $widgets_manager->register(new \MST_Elementor\Widgets\Meeting_Point_Block());
    $widgets_manager->register(new \MST_Elementor\Widgets\Booking_Terms_Block());
    $widgets_manager->register(new \MST_Elementor\Widgets\FAQ_Block());
    $widgets_manager->register(new \MySuperTour\Widgets\Woo_Product_Gallery());
    $widgets_manager->register(new \MST_Elementor\Widgets\Booking_Confirmation_Page());
    $widgets_manager->register(new \MST_Elementor\Widgets\Mst_Cart_Widget());
    $widgets_manager->register(new \MST_Elementor\Widgets\Mst_Checkout_Widget());
    $widgets_manager->register(new \MySuperTour\Widgets\Help_Section());
    $widgets_manager->register(new \MST_Elementor\Widgets\Single_Product_Blog());
    $widgets_manager->register(new \MST_Elementor\Widgets\Single_Blog_Article());
    $widgets_manager->register(new \MST_Elementor\Widgets\Single_Product_Toast());
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
    
    // Enqueue Shop Grid script
    wp_enqueue_script(
        'mst-shop-grid',
        MST_ELEMENTOR_URL . 'assets/js/shop-grid.js',
        ['jquery'],
        MST_ELEMENTOR_VERSION,
        true
    );
    
    // Localize script with AJAX data
    wp_localize_script('mst-shop-grid', 'mstShopGrid', [
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'restUrl' => rest_url(),
        'nonce' => wp_create_nonce('mst_shop_grid_nonce'),
        'userId' => get_current_user_id()
    ]);
        
    // Localize widgets script with AJAX data for sidebars
    wp_localize_script('mst-elementor-widgets', 'mstData', [
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('mst_widgets_nonce'),
        'userId' => get_current_user_id()
    ]);
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

// AJAX Handlers for Wishlist
add_action('wp_ajax_mst_add_wishlist', 'mst_add_to_wishlist');
add_action('wp_ajax_nopriv_mst_add_wishlist', 'mst_add_to_wishlist');

function mst_add_to_wishlist() {
    check_ajax_referer('mst_shop_grid_nonce', 'nonce');
    
    if (!is_user_logged_in()) {
        wp_send_json_error('Необходимо войти в систему');
    }
    
    $user_id = get_current_user_id();
    $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
    
    if (!$product_id) {
        wp_send_json_error('Неверный ID товара');
    }
    
    // Get current wishlist
    $wishlist_data = get_user_meta($user_id, 'xstore_wishlist_ids_0', true);
    
    // Parse wishlist
    $items = [];
    if ($wishlist_data) {
        $items = explode('|', $wishlist_data);
    }
    
    // Check if already in wishlist
    foreach ($items as $item) {
        $decoded = json_decode($item, true);
        if ($decoded && isset($decoded['id']) && $decoded['id'] == $product_id) {
            wp_send_json_error('Товар уже в избранном');
        }
    }
    
    // Add to wishlist
    $items[] = json_encode(['id' => $product_id]);
    $new_wishlist_data = implode('|', $items);
    
    update_user_meta($user_id, 'xstore_wishlist_ids_0', $new_wishlist_data);
    
    wp_send_json_success(['message' => 'Товар добавлен в избранное']);
}

add_action('wp_ajax_mst_remove_wishlist', 'mst_remove_from_wishlist');
add_action('wp_ajax_nopriv_mst_remove_wishlist', 'mst_remove_from_wishlist');

function mst_remove_from_wishlist() {
    check_ajax_referer('mst_shop_grid_nonce', 'nonce');
    
    if (!is_user_logged_in()) {
        wp_send_json_error('Необходимо войти в систему');
    }
    
    $user_id = get_current_user_id();
    $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
    
    if (!$product_id) {
        wp_send_json_error('Неверный ID товара');
    }
    
    // Get current wishlist
    $wishlist_data = get_user_meta($user_id, 'xstore_wishlist_ids_0', true);
    
    if (!$wishlist_data) {
        wp_send_json_error('Wishlist пуст');
    }
    
    $items = explode('|', $wishlist_data);
    $new_items = [];
    
    foreach ($items as $item) {
        $decoded = json_decode($item, true);
        if ($decoded && isset($decoded['id']) && $decoded['id'] != $product_id) {
            $new_items[] = $item;
        }
    }
    
    if (empty($new_items)) {
        delete_user_meta($user_id, 'xstore_wishlist_ids_0');
        delete_user_meta($user_id, 'xstore_wishlist_u');
    } else {
        $new_wishlist_data = implode('|', $new_items);
        update_user_meta($user_id, 'xstore_wishlist_ids_0', $new_wishlist_data);
    }
    
    wp_send_json_success(['message' => 'Товар удален из избранного']);
}
// AJAX Handler for removing wishlist item from header sidebar
add_action('wp_ajax_mst_remove_wishlist_item', 'mst_remove_wishlist_item');

function mst_remove_wishlist_item() {
    check_ajax_referer('mst_widgets_nonce', 'nonce');
    
    if (!is_user_logged_in()) {
        wp_send_json_error('Необходимо войти в систему');
    }
    
    $user_id = get_current_user_id();
    $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
    
    if (!$product_id) {
        wp_send_json_error('Неверный ID товара');
    }
    
    $wishlist_data = get_user_meta($user_id, 'xstore_wishlist_ids_0', true);
    
    if (!$wishlist_data) {
        wp_send_json_error('Wishlist пуст');
    }
    
    $items = explode('|', $wishlist_data);
    $new_items = [];
    
    foreach ($items as $item) {
        $decoded = json_decode($item, true);
        if ($decoded && isset($decoded['id']) && $decoded['id'] != $product_id) {
            $new_items[] = $item;
        }
    }
    
    if (empty($new_items)) {
        delete_user_meta($user_id, 'xstore_wishlist_ids_0');
        delete_user_meta($user_id, 'xstore_wishlist_u');
    } else {
        update_user_meta($user_id, 'xstore_wishlist_ids_0', implode('|', $new_items));
    }
    
    wp_send_json_success(['count' => count($new_items)]);
}

// AJAX Handler for getting wishlist items
add_action('wp_ajax_mst_get_wishlist_items', 'mst_get_wishlist_items');

function mst_get_wishlist_items() {
    check_ajax_referer('mst_widgets_nonce', 'nonce');
    
    if (!is_user_logged_in()) {
        wp_send_json_success(['items' => []]);
    }
    
    $user_id = get_current_user_id();
    $wishlist_data = get_user_meta($user_id, 'xstore_wishlist_ids_0', true);
    
    $items = [];
    
    if ($wishlist_data) {
        $parsed = explode('|', $wishlist_data);
        
        foreach ($parsed as $item) {
            $decoded = json_decode($item, true);
            if ($decoded && isset($decoded['id'])) {
                $product = wc_get_product($decoded['id']);
                if (!$product) continue;
                
                $thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id($decoded['id']), 'thumbnail');
                
                $items[] = [
                    'id' => $decoded['id'],
                    'title' => $product->get_name(),
                    'price' => $product->get_price_html(),
                    'url' => get_permalink($decoded['id']),
                    'thumbnail' => $thumbnail ? $thumbnail[0] : wc_placeholder_img_src('thumbnail')
                ];
            }
        }
    }
    
    wp_send_json_success(['items' => $items]);
}


add_action('wp_ajax_mst_check_wishlist', 'mst_check_wishlist_status');
add_action('wp_ajax_nopriv_mst_check_wishlist', 'mst_check_wishlist_status');

function mst_check_wishlist_status() {
    check_ajax_referer('mst_shop_grid_nonce', 'nonce');
    
    if (!is_user_logged_in()) {
        wp_send_json_success([]);
    }
    
    $user_id = get_current_user_id();
    $product_ids = isset($_POST['product_ids']) && is_array($_POST['product_ids']) ? $_POST['product_ids'] : [];
    
    if (empty($product_ids)) {
        wp_send_json_success([]);
    }
    
    // Sanitize and validate product IDs
    $product_ids = array_map('intval', $product_ids);
    $product_ids = array_filter($product_ids, function($id) {
        return $id > 0;
    });
    
    if (empty($product_ids)) {
        wp_send_json_success([]);
    }
    
    // Get wishlist
    $wishlist_data = get_user_meta($user_id, 'xstore_wishlist_ids_0', true);
    
    if (!$wishlist_data) {
        wp_send_json_success([]);
    }
    
    $items = explode('|', $wishlist_data);
    $wishlist_ids = [];
    
    foreach ($items as $item) {
        $decoded = json_decode($item, true);
        if ($decoded && isset($decoded['id'])) {
            $wishlist_ids[] = intval($decoded['id']);
        }
    }
    
    // Filter to only requested products
    $result = array_values(array_intersect($wishlist_ids, $product_ids));
    
    wp_send_json_success($result);
}

// ========================================
// CUSTOM WALKER FOR NAVIGATION ARROWS
// ========================================

class MST_Walker_Nav_Menu_Arrows extends Walker_Nav_Menu {
    private $show_arrows;

    public function __construct($show_arrows = true) {
        $this->show_arrows = $show_arrows;
    }

    public function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        parent::start_el($output, $item, $depth, $args, $id);
        
        // Add dropdown arrow if item has children and arrows are enabled
        if ($this->show_arrows && in_array('menu-item-has-children', $item->classes)) {
            $arrow_svg = '<svg class="mst-menu-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>';
            $output = preg_replace('/<\/a>$/', $arrow_svg . '</a>', $output);
        }
    }
}


// ========================================
// SIDEBAR AJAX HANDLERS
// ========================================

// Remove cart item
add_action('wp_ajax_mst_remove_cart_item', 'mst_remove_cart_item_ajax');
add_action('wp_ajax_nopriv_mst_remove_cart_item', 'mst_remove_cart_item_ajax');

function mst_remove_cart_item_ajax() {
    $cart_key = sanitize_text_field($_POST['cart_key'] ?? '');
    
    if (empty($cart_key) || !function_exists('WC')) {
        wp_send_json_error(['message' => 'Invalid request']);
    }
    
    WC()->cart->remove_cart_item($cart_key);
    
    wp_send_json_success([
        'cart_count' => WC()->cart->get_cart_contents_count(),
        'cart_subtotal' => WC()->cart->get_cart_subtotal()
    ]);
}

// Get wishlist items
add_action('wp_ajax_mst_get_wishlist_items', 'mst_get_wishlist_items_ajax');
add_action('wp_ajax_nopriv_mst_get_wishlist_items', 'mst_get_wishlist_items_ajax');

function mst_get_wishlist_items_ajax() {
    $wishlist_data = isset($_COOKIE['mst_wishlist']) ? $_COOKIE['mst_wishlist'] : '';
    
    if (empty($wishlist_data)) {
        wp_send_json_success(['items' => []]);
    }
    
    $items_raw = explode('|', $wishlist_data);
    $items = [];
    
    foreach ($items_raw as $item) {
        $decoded = json_decode($item, true);
        if ($decoded && isset($decoded['id'])) {
            $product_id = intval($decoded['id']);
            $product = wc_get_product($product_id);
            
            if ($product) {
                $thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id($product_id), 'thumbnail');
                $items[] = [
                    'id' => $product_id,
                    'title' => $product->get_name(),
                    'price' => $product->get_price_html(),
                    'url' => get_permalink($product_id),
                    'thumbnail' => $thumbnail ? $thumbnail[0] : wc_placeholder_img_src('thumbnail')
                ];
            }
        }
    }
    
    wp_send_json_success(['items' => $items]);
}

// Remove wishlist item
add_action('wp_ajax_mst_remove_wishlist_item', 'mst_remove_wishlist_item_ajax');
add_action('wp_ajax_nopriv_mst_remove_wishlist_item', 'mst_remove_wishlist_item_ajax');

function mst_remove_wishlist_item_ajax() {
    $product_id = intval($_POST['product_id'] ?? 0);
    
    if (empty($product_id)) {
        wp_send_json_error(['message' => 'Invalid product ID']);
    }
    
    $wishlist_data = isset($_COOKIE['mst_wishlist']) ? $_COOKIE['mst_wishlist'] : '';
    
    if (empty($wishlist_data)) {
        wp_send_json_success(['count' => 0]);
    }
    
    $items_raw = explode('|', $wishlist_data);
    $new_items = [];
    
    foreach ($items_raw as $item) {
        $decoded = json_decode($item, true);
        if ($decoded && isset($decoded['id']) && intval($decoded['id']) !== $product_id) {
            $new_items[] = $item;
        }
    }
    
    $new_wishlist = implode('|', $new_items);
    setcookie('mst_wishlist', $new_wishlist, time() + (86400 * 30), '/');
    
    wp_send_json_success(['count' => count($new_items)]);
}