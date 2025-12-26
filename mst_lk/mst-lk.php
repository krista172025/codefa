<?php
/**
 * Plugin Name: MySuperTour - Личный Кабинет
 * Description: Единый личный кабинет для пользователей с заказами, бронированиями и реферальной программой
 * Version: 3.4.0
 * Author: Telegram @l1ghtsun
 * Author URI: https://t.me/l1ghtsun
 * Text Domain: mst-lk
 */

if (!defined('ABSPATH')) exit;

define('MST_LK_VERSION', '3.4.0');
define('MST_LK_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('MST_LK_PLUGIN_URL', plugin_dir_url(__FILE__));

class MST_LK {
    private static $instance = null;
    
    public static function instance() {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        // Load text domain for translations
        add_action('plugins_loaded', [$this, 'load_textdomain']);
        
        add_action('admin_menu', [$this, 'add_hub_menu'], 20);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_frontend_assets']);
        add_action('init', [$this, 'register_endpoints']);
        add_filter('query_vars', [$this, 'add_query_vars']);
        add_action('template_redirect', [$this, 'handle_guide_template']);
        add_shortcode('mst_lk', [$this, 'render_profile_shortcode']);
        add_filter('woocommerce_account_menu_items', [$this, 'add_my_account_menu'], 40);
        add_action('woocommerce_account_mst-profile_endpoint', [$this, 'render_my_account_content']);
        
        // Handle guide profile template redirect
        add_action('template_redirect', [$this, 'handle_guide_template']);
        
        // AJAX handlers
        add_action('wp_ajax_mst_lk_get_order_details', [$this, 'ajax_get_order_details']);
        add_action('wp_ajax_mst_lk_get_ticket', [$this, 'ajax_get_ticket']);
        add_action('wp_ajax_mst_lk_update_avatar', [$this, 'ajax_update_avatar']);
        add_action('wp_ajax_mst_lk_update_profile', [$this, 'ajax_update_profile']);
        add_action('wp_ajax_mst_lk_get_latepoint_booking', [$this, 'ajax_get_latepoint_booking']);
        add_action('wp_ajax_mst_lk_remove_from_wishlist', [$this, 'ajax_remove_from_wishlist']);
        add_action('wp_ajax_mst_lk_submit_review', [$this, 'ajax_submit_review']);
        add_action('wp_ajax_mst_lk_download_gift', [$this, 'ajax_download_gift']);
        
        // Admin actions
        add_action('show_user_profile', [$this, 'add_user_meta_fields']);
        add_action('edit_user_profile', [$this, 'add_user_meta_fields']);
        add_action('personal_options_update', [$this, 'save_user_meta_fields']);
        add_action('edit_user_profile_update', [$this, 'save_user_meta_fields']);
        
        // Подключаем систему гидов
        require_once MST_LK_PLUGIN_DIR . 'includes/guide-system.php';
        
        // Подключаем Reviews API
        require_once MST_LK_PLUGIN_DIR . 'includes/class-reviews-api.php';
        
        register_activation_hook(__FILE__, [$this, 'activate']);
    }
    
    public function load_textdomain() {
        load_plugin_textdomain('mst-lk', false, dirname(plugin_basename(__FILE__)) . '/languages/');
    }
    
    public function activate() {
        $default_settings = [
            'tabs' => [
                'orders' => ['icon' => '📦', 'label' => 'Мои заказы', 'enabled' => true],
                'bookings' => ['icon' => '📅', 'label' => 'Бронирования', 'enabled' => true],
                'messages' => ['icon' => '💬', 'label' => 'Сообщения', 'enabled' => true],
                'affiliate' => ['icon' => '💰', 'label' => 'Реферальная программа', 'enabled' => true],
                'wishlist' => ['icon' => '❤️', 'label' => 'Избранное', 'enabled' => true]
            ]
        ];
        
        if (!get_option('mst_lk_settings')) {
            update_option('mst_lk_settings', $default_settings);
        }
        
        $this->register_endpoints();
        flush_rewrite_rules();
    }
    
    public function register_endpoints() {
        add_rewrite_endpoint('mst-profile', EP_ROOT | EP_PAGES);
        
        // Russian version: /gid/{user_id}
        add_rewrite_rule(
            '^gid/([0-9]+)/?$',
            'index.php?mst_guide_page=1&mst_guide_id=$matches[1]',
            'top'
        );
        
        // English version: /guide/{user_id}
        add_rewrite_rule(
            '^guide/([0-9]+)/?$',
            'index.php?mst_guide_page=1&mst_guide_id=$matches[1]',
            'top'
        );
        
        // Add rewrite rule for guide profiles: /guide/123 -> ?guide_id=123 (legacy support)
        add_rewrite_rule('^guide/([0-9]+)/?$', 'index.php?guide_id=$matches[1]', 'top');
        add_rewrite_tag('%guide_id%', '([0-9]+)');
    }
    
    public function add_query_vars($vars) {
        $vars[] = 'mst_guide_page';
        $vars[] = 'mst_guide_id';
        $vars[] = 'guide_id';
        return $vars;
    }
    
    public function add_hub_menu() {
        add_submenu_page(
            'mysupertour-hub',
            'Личный Кабинет',
            '👤 Личный Кабинет',
            'manage_options',
            'mysupertour-lk-hub',
            [$this, 'render_admin_page']
        );
    }
    
    public function enqueue_frontend_assets() {
        if (is_account_page() || is_page() || has_shortcode(get_post_field('post_content', get_the_ID()), 'mst_lk')) {
            // Enqueue original styles
            wp_enqueue_style('mst-lk-style', MST_LK_PLUGIN_URL . 'assets/css/style.css', [], MST_LK_VERSION);
            
            // Enqueue modern design CSS
            wp_enqueue_style('mst-lk-modern', MST_LK_PLUGIN_URL . 'assets/css/lk-modern.css', ['mst-lk-style'], MST_LK_VERSION);
            
            wp_enqueue_script('mst-lk-script', MST_LK_PLUGIN_URL . 'assets/js/script.js', ['jquery'], MST_LK_VERSION, true);
            
            // Подключаем IMask для форматирования телефона
            wp_enqueue_script('imask', 'https://cdn.jsdelivr.net/npm/imask@7.1.3/dist/imask.min.js', [], '7.1.3', true);
            
            wp_localize_script('mst-lk-script', 'mstLK', [
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('mst_lk_nonce'),
                'shop_url' => home_url('/shop')
            ]);
        }
    }
    
    public function ajax_get_order_details() {
        check_ajax_referer('mst_lk_nonce', 'nonce');
        
        $order_id = intval($_POST['order_id']);
        $order = wc_get_order($order_id);
        
        if (!$order || $order->get_customer_id() !== get_current_user_id()) {
            wp_send_json_error('Заказ не найден');
        }
        
        ob_start();
        include MST_LK_PLUGIN_DIR . 'templates/order-modal.php';
        $html = ob_get_clean();
        
        wp_send_json_success(['html' => $html]);
    }
    
    public function ajax_get_ticket() {
        check_ajax_referer('mst_lk_nonce', 'nonce');
        
        $order_id = intval($_POST['order_id']);
        $order = wc_get_order($order_id);
        
        if (!$order || $order->get_customer_id() !== get_current_user_id()) {
            wp_send_json_error('Заказ не найден');
        }
        
        ob_start();
        include MST_LK_PLUGIN_DIR . 'templates/order-ticket-modal.php';
        $html = ob_get_clean();
        
        wp_send_json_success(['html' => $html]);
    }
    
    public function ajax_update_avatar() {
        check_ajax_referer('mst_lk_nonce', 'nonce');
        
        if (!isset($_FILES['avatar'])) {
            wp_send_json_error('Файл не загружен');
        }
        
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        require_once(ABSPATH . 'wp-admin/includes/media.php');
        
        $attachment_id = media_handle_upload('avatar', 0);
        
        if (is_wp_error($attachment_id)) {
            wp_send_json_error($attachment_id->get_error_message());
        }
        
        update_user_meta(get_current_user_id(), 'mst_lk_avatar', $attachment_id);
        
        $avatar_url = wp_get_attachment_url($attachment_id);
        wp_send_json_success(['url' => $avatar_url]);
    }
    
    public function ajax_update_profile() {
        check_ajax_referer('mst_lk_nonce', 'nonce');
        
        $user_id = get_current_user_id();
        
        // Сохраняем имя и фамилию отдельно
        update_user_meta($user_id, 'first_name', sanitize_text_field($_POST['first_name']));
        update_user_meta($user_id, 'last_name', sanitize_text_field($_POST['last_name']));
        
        // Сохраняем телефон (ИСПРАВЛЕНО)
        update_user_meta($user_id, 'billing_phone', sanitize_text_field($_POST['billing_phone']));
        
        $data = [
            'ID' => $user_id,
            'display_name' => sanitize_text_field($_POST['display_name']),
            'user_email' => sanitize_email($_POST['user_email'])
        ];
        
        if (!empty($_POST['new_password'])) {
            $data['user_pass'] = $_POST['new_password'];
        }
        
        $result = wp_update_user($data);
        
        if (is_wp_error($result)) {
            wp_send_json_error($result->get_error_message());
        }
        
        wp_send_json_success('Профиль обновлен');
    }
    
    public function ajax_get_latepoint_booking() {
        check_ajax_referer('mst_lk_nonce', 'nonce');
        
        if (!class_exists('OsBookingModel')) {
            wp_send_json_error('LatePoint не установлен');
        }
        
        $booking_id = intval($_POST['booking_id']);
        $booking = new OsBookingModel($booking_id);
        
        if (!$booking->id) {
            wp_send_json_error('Бронирование не найдено');
        }
        
        ob_start();
        include MST_LK_PLUGIN_DIR . 'templates/latepoint-modal.php';
        $html = ob_get_clean();
        
        wp_send_json_success(['html' => $html]);
    }
    
    public function ajax_remove_from_wishlist() {
        check_ajax_referer('mst_lk_nonce', 'nonce');
        
        $product_id = intval($_POST['product_id']);
        $user_id = get_current_user_id();
        
        if (!$user_id) {
            wp_send_json_error('Необходима авторизация');
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
            $new_wishlist_data = implode('|', $new_items);
            update_user_meta($user_id, 'xstore_wishlist_ids_0', $new_wishlist_data);
        }
        
        wp_send_json_success(['message' => 'Товар удален из избранного']);
    }
    
    public function ajax_submit_review() {
        check_ajax_referer('mst_lk_nonce', 'nonce');
        
        if (!is_user_logged_in()) {
            wp_send_json_error(__('Необходимо авторизоваться', 'mst-lk'));
        }
        
        $product_id = intval($_POST['product_id'] ?? 0);
        $rating = intval($_POST['rating'] ?? 5);
        $comment = sanitize_textarea_field($_POST['comment'] ?? '');
        
        // Проверка что пользователь купил товар - include on-hold status
        $user_id = get_current_user_id();
        $orders = wc_get_orders([
            'customer_id' => $user_id,
            'limit' => -1,
        ]);
        
        $has_purchased = false;
        $order_status = null;
        foreach ($orders as $order) {
            foreach ($order->get_items() as $item) {
                if ($item->get_product_id() == $product_id) {
                    $order_status = $order->get_status();
                    // Allow reviews for completed, processing, and on-hold orders
                    if (in_array($order_status, ['completed', 'processing', 'on-hold'])) {
                        $has_purchased = true;
                        break 2;
                    }
                }
            }
        }
        
        if (!$has_purchased) {
            // Provide specific messages based on order status
            if ($order_status === 'pending') {
                wp_send_json_error(__('⏳ Заказ ожидает оплаты. Вы сможете оставить отзыв после оплаты.', 'mst-lk'));
            } elseif ($order_status === 'on-hold') {
                wp_send_json_error(__('⏳ Подождите, пока ваша транзакция обработается', 'mst-lk'));
            } elseif ($order_status === 'cancelled' || $order_status === 'failed') {
                wp_send_json_error(__('❌ Этот заказ был отменен. Вы не можете оставить отзыв.', 'mst-lk'));
            } else {
                wp_send_json_error(__('❌ Вы не покупали этот тур', 'mst-lk'));
            }
        }
        
        // Проверка рейтинга
        if ($rating < 1 || $rating > 5) {
            wp_send_json_error(__('Некорректный рейтинг', 'mst-lk'));
        }
        
        // Создаём отзыв (комментарий с типом review)
        $user = get_userdata($user_id);
        $comment_data = [
            'comment_post_ID' => $product_id,
            'comment_author' => $user->display_name,
            'comment_author_email' => $user->user_email,
            'comment_content' => $comment,
            'comment_type' => 'review',
            'comment_approved' => 1, // Auto-approve for purchased products
            'user_id' => $user_id,
        ];
        
        $comment_id = wp_insert_comment($comment_data);
        
        if ($comment_id) {
            // Добавляем рейтинг
            update_comment_meta($comment_id, 'rating', $rating);
            
            // Update product rating count and average
            $this->update_product_review_stats($product_id);
            
            wp_send_json_success([
                'message' => __('Отзыв успешно добавлен', 'mst-lk'),
                'comment_id' => $comment_id
            ]);
        } else {
            wp_send_json_error(__('Ошибка при добавлении отзыва', 'mst-lk'));
        }
    }
    
    /**
     * Update product review statistics
     */
    private function update_product_review_stats($product_id) {
        $comments = get_comments([
            'post_id' => $product_id,
            'type' => 'review',
            'status' => 'approve',
        ]);
        
        if (count($comments) > 0) {
            $total = 0;
            foreach ($comments as $comment) {
                $rating = intval(get_comment_meta($comment->comment_ID, 'rating', true));
                $total += $rating ?: 5;
            }
            $average = $total / count($comments);
            
            update_post_meta($product_id, '_wc_average_rating', round($average, 2));
            update_post_meta($product_id, '_wc_review_count', count($comments));
            
            // Trigger WooCommerce to recount ratings
            if (function_exists('WC_Comments')) {
                WC_Comments::clear_transients($product_id);
            }
            
            // Clear product cache
            if (function_exists('wc_delete_product_transients')) {
                wc_delete_product_transients($product_id);
            }
        }
    }
    
    public function ajax_download_gift() {
        check_ajax_referer('mst_lk_nonce', 'nonce');
        
        $order_id = intval($_POST['order_id'] ?? 0);
        $order = wc_get_order($order_id);
        
        if (!$order || $order->get_customer_id() != get_current_user_id()) {
            wp_send_json_error(__('Заказ не найден', 'mst-lk'));
        }
        
        // Находим первый товар с подарком
        foreach ($order->get_items() as $item) {
            $product_id = $item->get_product_id();
            $gift_id = get_post_meta($product_id, '_mst_gift_file', true);
            
            if ($gift_id) {
                $gift_url = wp_get_attachment_url($gift_id);
                if ($gift_url) {
                    wp_send_json_success([
                        'url' => $gift_url,
                        'filename' => basename($gift_url)
                    ]);
                }
            }
        }
        
        wp_send_json_error(__('Подарок не найден', 'mst-lk'));
    }
    
    public function add_user_meta_fields($user) {
        $current_status = get_user_meta($user->ID, 'mst_user_status', true) ?: 'bronze';
        ?>
        <h3>MySuperTour - Личный Кабинет</h3>
        <table class="form-table">
            <tr>
                <th><label for="mst_user_bonuses">Бонусы</label></th>
                <td>
                    <input type="number" name="mst_user_bonuses" id="mst_user_bonuses" 
                           value="<?php echo esc_attr(get_user_meta($user->ID, 'mst_user_bonuses', true) ?: 0); ?>" 
                           class="regular-text" min="0">
                    <p class="description">Количество бонусов пользователя</p>
                </td>
            </tr>
            <tr>
                <th><label for="mst_user_status">Статус (рамка аватара)</label></th>
                <td>
                    <select name="mst_user_status" id="mst_user_status" class="regular-text">
                        <option value="bronze" <?php selected($current_status, 'bronze'); ?>>Бронзовый (#CD7F32)</option>
                        <option value="silver" <?php selected($current_status, 'silver'); ?>>Серебряный (#C0C0C0)</option>
                        <option value="gold" <?php selected($current_status, 'gold'); ?>>Золотой (#FFD700)</option>
                        <option value="guide" <?php selected($current_status, 'guide'); ?>>🟢 Гид (Зеленая рамка #00c896)</option>
                    </select>
                    <p class="description">Цвет рамки вокруг аватара пользователя</p>
                </td>
            </tr>
            <tr>
                <th><label for="mst_user_status_label">Название статуса</label></th>
                <td>
                    <input type="text" name="mst_user_status_label" id="mst_user_status_label" 
                           value="<?php echo esc_attr(get_user_meta($user->ID, 'mst_user_status_label', true) ?: 'Бронзовый статус'); ?>" 
                           class="regular-text">
                    <p class="description">Текст статуса (например: Гид, VIP, Проверенный)</p>
                </td>
            </tr>
        </table>
        <?php
    }
    
    public function save_user_meta_fields($user_id) {
        if (!current_user_can('edit_user', $user_id)) {
            return false;
        }
        
        update_user_meta($user_id, 'mst_user_bonuses', intval($_POST['mst_user_bonuses']));
        update_user_meta($user_id, 'mst_user_status', sanitize_text_field($_POST['mst_user_status']));
        update_user_meta($user_id, 'mst_user_status_label', sanitize_text_field($_POST['mst_user_status_label']));
    }
    
    public function handle_guide_template() {
        // Check for new rewrite rule query vars first
        $guide_id = get_query_var('mst_guide_id');
        if ($guide_id && get_query_var('mst_guide_page')) {
            // Show the guide profile directly
            $guide_id = intval($guide_id);
            
            // Validate guide exists
            $guide = get_userdata($guide_id);
            if (!$guide) {
                // Guide not found - redirect to home
                wp_redirect(home_url());
                exit;
            }
            
            // Display inline without redirect - this is the proper way
            $_GET['guide_id'] = $guide_id;
            get_header();
            echo '<div class="mst-guide-page-wrapper" style="max-width: 1200px; margin: 40px auto; padding: 0 20px;">';
            echo do_shortcode('[mst_guide_profile]');
            echo '</div>';
            get_footer();
            exit;
        }
        
        // Legacy support for old guide_id query var
        $guide_id = get_query_var('guide_id');
        
        if ($guide_id) {
            $guide_id = intval($guide_id);
            
            // Validate guide exists
            $guide = get_userdata($guide_id);
            if (!$guide) {
                // Guide not found - redirect to home
                wp_redirect(home_url());
                exit;
            }
            
            // Set up the guide_id in GET for compatibility with existing shortcode
            $_GET['guide_id'] = $guide_id;
            
            // Display inline without redirect
            get_header();
            echo '<div class="mst-guide-page-wrapper" style="max-width: 1200px; margin: 40px auto; padding: 0 20px;">';
            echo do_shortcode('[mst_guide_profile]');
            echo '</div>';
            get_footer();
            exit;
        }
    }
    
    public function render_admin_page() {
        $settings = get_option('mst_lk_settings', []);
        
        if (isset($_POST['mst_lk_save_settings']) && check_admin_referer('mst_lk_settings', 'mst_lk_nonce')) {
            $tabs = [];
            foreach (['orders', 'bookings', 'messages', 'affiliate', 'wishlist'] as $tab) {
                $tabs[$tab] = [
                    'icon' => sanitize_text_field($_POST['tab_icon_' . $tab] ?? '📦'),
                    'label' => sanitize_text_field($_POST['tab_label_' . $tab] ?? ''),
                    'enabled' => isset($_POST['tab_enabled_' . $tab])
                ];
            }
            
            $settings['tabs'] = $tabs;
            update_option('mst_lk_settings', $settings);
            echo '<div class="mst-save-notice">✅ Настройки сохранены!</div>';
        }
        
        include MST_LK_PLUGIN_DIR . 'templates/admin-page.php';
    }
    
    public function add_my_account_menu($items) {
        $new_items = [];
        foreach ($items as $key => $value) {
            if ($key === 'dashboard') {
                $new_items['mst-profile'] = __('Мой Профиль', 'mst-lk');
            }
            $new_items[$key] = $value;
        }
        return $new_items;
    }
    
    public function render_my_account_content() {
        $this->render_profile();
    }
    
    public function render_profile_shortcode($atts) {
        if (!is_user_logged_in()) {
            return '<p class="woocommerce-info">' . __('Пожалуйста, войдите в систему.', 'mst-lk') . '</p>';
        }
        
        ob_start();
        $this->render_profile();
        return ob_get_clean();
    }
    
    private function render_profile() {
        $user = wp_get_current_user();
        $settings = get_option('mst_lk_settings', []);
        
        if (empty($settings['tabs'])) {
            $settings['tabs'] = [
                'orders' => ['icon' => '📦', 'label' => 'Мои заказы', 'enabled' => true],
                'bookings' => ['icon' => '📅', 'label' => 'Бронирования', 'enabled' => true],
                'messages' => ['icon' => '💬', 'label' => 'Сообщения', 'enabled' => true],
                'affiliate' => ['icon' => '💰', 'label' => 'Реферальная программа', 'enabled' => true],
                'wishlist' => ['icon' => '❤️', 'label' => 'Избранное', 'enabled' => true]
            ];
        }
        
        include MST_LK_PLUGIN_DIR . 'templates/profile.php';
    }
}

add_action('plugins_loaded', function() {
    MST_LK::instance();
}, 10);