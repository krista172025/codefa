<?php
/**
 * Plugin Name: MySuperTour - Auth + LK Unified
 * Description: Объединённый Elementor виджет: Авторизация + Личный Кабинет + Система Гидов
 * Version: 4.0.2
 * Author: Telegram @l1ghtsun
 * Author URI: https://t.me/l1ghtsun
 * Text Domain: mst-auth-lk
 * Requires: Elementor, WooCommerce
 * 
 * ИСПРАВЛЕННАЯ ВЕРСИЯ v4.0.2:
 * 1. Fixed guide metabox - теперь корректно сохраняется и показывается выбранный гид
 * 2. Fixed reviews with photo upload
 * 3. Improved guide selection in metabox
 * 4. Auto-link reviews to guides
 */

if (!defined('ABSPATH')) exit;

define('MST_AUTH_LK_VERSION', '4.0.2');
define('MST_AUTH_LK_DIR', plugin_dir_path(__FILE__));
define('MST_AUTH_LK_URL', plugin_dir_url(__FILE__));

/**
 * Главный класс плагина
 */
class MST_Auth_LK_Unified {
    
    private static $instance = null;
    
    public static function instance() {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        // Инициализация
        add_action('plugins_loaded', [$this, 'init']);
        
        // Регистрация виджетов Elementor
        add_action('elementor/widgets/register', [$this, 'register_widgets']);
        
        // Регистрация категории виджетов
        add_action('elementor/elements/categories_registered', [$this, 'register_category']);
        
        // Подключение ассетов
        add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
        
        // AJAX обработчики
        $this->register_ajax_handlers();
        
        // Регистрация эндпоинтов
        add_action('init', [$this, 'register_endpoints']);
        add_filter('query_vars', [$this, 'add_query_vars']);
        add_action('template_redirect', [$this, 'handle_guide_template']);
        
        // OAuth handlers
        add_action('template_redirect', [$this, 'handle_oauth_redirect']);
        
        // Шорткоды
        add_shortcode('mst_auth_lk', [$this, 'render_auth_lk_shortcode']);
        add_shortcode('mst_guide_profile', [$this, 'render_guide_profile']);
        add_shortcode('mst_guides_list', [$this, 'render_guides_list']);
        
        // Админка
        add_action('admin_menu', [$this, 'add_admin_menu'], 20);
        add_action('show_user_profile', [$this, 'add_user_meta_fields']);
        add_action('edit_user_profile', [$this, 'add_user_meta_fields']);
        add_action('personal_options_update', [$this, 'save_user_meta_fields']);
        add_action('edit_user_profile_update', [$this, 'save_user_meta_fields']);
        
        // WooCommerce интеграция
        add_filter('woocommerce_account_menu_items', [$this, 'add_woo_menu_item'], 40);
        add_action('woocommerce_account_mst-profile_endpoint', [$this, 'render_woo_account_content']);
        
        // Metabox для гидов - ИСПРАВЛЕНО: добавлен приоритет
        add_action('add_meta_boxes', [$this, 'add_guide_metabox'], 10, 2);
        add_action('save_post_product', [$this, 'save_guide_meta'], 10, 3);
        
        // Hook для автоматической привязки отзывов при завершении заказа
        add_action('woocommerce_order_status_completed', [$this, 'enable_review_for_order']);
        
        // LatePoint integration для отзывов
        add_action('latepoint_booking_completed', [$this, 'enable_review_for_latepoint_booking']);
        
        // REST API для гидов
        add_action('rest_api_init', [$this, 'register_rest_routes']);
        
        // Активация
        register_activation_hook(__FILE__, [$this, 'activate']);
    }
    
    public function init() {
        load_plugin_textdomain('mst-auth-lk', false, dirname(plugin_basename(__FILE__)) . '/languages/');
    }
    
    public function activate() {
        $default_settings = [
            'tabs' => [
                'orders' => ['icon' => '📦', 'label' => __('Мои заказы', 'mst-auth-lk'), 'enabled' => true],
                'bookings' => ['icon' => '📅', 'label' => __('Бронирования', 'mst-auth-lk'), 'enabled' => true],
                'affiliate' => ['icon' => '💰', 'label' => __('Реферальная программа', 'mst-auth-lk'), 'enabled' => true],
                'wishlist' => ['icon' => '❤️', 'label' => __('Избранное', 'mst-auth-lk'), 'enabled' => true],
                'reviews' => ['icon' => '⭐', 'label' => __('Мои отзывы', 'mst-auth-lk'), 'enabled' => true],
            ]
        ];
        
        if (!get_option('mst_auth_lk_settings')) {
            update_option('mst_auth_lk_settings', $default_settings);
        }
        
        // Default OTP settings
        if (!get_option('mst_otp_settings')) {
            update_option('mst_otp_settings', [
                'enabled' => true,
                'method' => 'email',
                'code_length' => 6,
                'expiry_minutes' => 10,
                'max_attempts' => 5,
                'debug_mode' => false,
                'sms_provider' => 'none',
            ]);
        }
        
        $this->register_endpoints();
        flush_rewrite_rules();
    }
    
    public function register_category($elements_manager) {
        $elements_manager->add_category('mysupertour', [
            'title' => __('MySuperTour', 'mst-auth-lk'),
            'icon' => 'fa fa-user',
        ]);
    }
    
    public function register_widgets($widgets_manager) {
        // Auth + LK Widget
        require_once MST_AUTH_LK_DIR . 'widgets/class-mst-auth-lk-widget.php';
        $widgets_manager->register(new MST_Auth_LK_Widget());
        
        // Guide Profile Section Widget
        require_once MST_AUTH_LK_DIR . 'widgets/class-mst-guide-profile-section-widget.php';
        $widgets_manager->register(new \MySuperTourElementor\Widgets\Guide_Profile_Section());
    }
    
    public function enqueue_assets() {
        // CSS
        wp_enqueue_style('mst-auth-lk-style', MST_AUTH_LK_URL . 'assets/css/style.css', [], MST_AUTH_LK_VERSION);
        
        // JS
        wp_enqueue_script('mst-auth-lk-script', MST_AUTH_LK_URL . 'assets/js/script.js', ['jquery'], MST_AUTH_LK_VERSION, true);
        
        // IMask для телефона
        wp_enqueue_script('imask', 'https://cdn.jsdelivr.net/npm/imask@7.1.3/dist/imask.min.js', [], '7.1.3', true);
        
        // Локализация
        wp_localize_script('mst-auth-lk-script', 'mstAuthLK', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('mst_auth_lk_nonce'),
            'auth_nonce' => wp_create_nonce('mst_auth_nonce'),
            'shop_url' => home_url('/shop'),
            'is_logged_in' => is_user_logged_in(),
        ]);
    }
    
    // ==================== AJAX HANDLERS ====================
    
    private function register_ajax_handlers() {
        // Auth handlers
        add_action('wp_ajax_nopriv_mst_auth_login', [$this, 'ajax_login']);
        add_action('wp_ajax_mst_auth_login', [$this, 'ajax_login']);
        add_action('wp_ajax_nopriv_mst_auth_register', [$this, 'ajax_register']);
        add_action('wp_ajax_mst_auth_register', [$this, 'ajax_register']);
        add_action('wp_ajax_nopriv_mst_auth_forgot', [$this, 'ajax_forgot_password']);
        add_action('wp_ajax_mst_auth_forgot', [$this, 'ajax_forgot_password']);
        
        // OTP handlers
        add_action('wp_ajax_nopriv_mst_auth_verify_otp', [$this, 'ajax_verify_otp']);
        add_action('wp_ajax_mst_auth_verify_otp', [$this, 'ajax_verify_otp']);
        add_action('wp_ajax_nopriv_mst_auth_resend_otp', [$this, 'ajax_resend_otp']);
        add_action('wp_ajax_mst_auth_resend_otp', [$this, 'ajax_resend_otp']);
        
        // LK handlers
        add_action('wp_ajax_mst_lk_get_order_details', [$this, 'ajax_get_order_details']);
        add_action('wp_ajax_mst_lk_get_ticket', [$this, 'ajax_get_ticket']);
        add_action('wp_ajax_mst_lk_update_avatar', [$this, 'ajax_update_avatar']);
        add_action('wp_ajax_mst_lk_update_profile', [$this, 'ajax_update_profile']);
        add_action('wp_ajax_mst_lk_get_latepoint_booking', [$this, 'ajax_get_latepoint_booking']);
        add_action('wp_ajax_mst_lk_remove_from_wishlist', [$this, 'ajax_remove_from_wishlist']);
        add_action('wp_ajax_mst_lk_submit_review', [$this, 'ajax_submit_review']);
        add_action('wp_ajax_mst_lk_download_gift', [$this, 'ajax_download_gift']);
        add_action('wp_ajax_mst_lk_get_product_guide', [$this, 'ajax_get_product_guide']);
        add_action('wp_ajax_mst_lk_toggle_otp', [$this, 'ajax_toggle_otp']);
        add_action('wp_ajax_mst_lk_clear_trusted_ips', [$this, 'ajax_clear_trusted_ips']);
        add_action('wp_ajax_mst_lk_get_pending_reviews', [$this, 'ajax_get_pending_reviews']);
    }
    
    // --- AUTH AJAX ---
    
    public function ajax_login() {
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'mst_auth_nonce')) {
            wp_send_json_error(__('Ошибка безопасности. Обновите страницу.', 'mst-auth-lk'));
        }
        
        $email = isset($_POST['email']) ? sanitize_email($_POST['email']) : '';
        $password = isset($_POST['password']) ? $_POST['password'] : '';
        $redirect = !empty($_POST['redirect']) ? esc_url_raw($_POST['redirect']) : home_url('/auth/');
        
        if (empty($email) || empty($password)) {
            wp_send_json_error(__('Заполните все поля', 'mst-auth-lk'));
        }
        
        if (!is_email($email)) {
            wp_send_json_error(__('Некорректный email', 'mst-auth-lk'));
        }
        
        $user = wp_authenticate($email, $password);
        
        if (is_wp_error($user)) {
            $error_code = $user->get_error_code();
            if ($error_code === 'invalid_email' || $error_code === 'invalid_username') {
                wp_send_json_error(__('Пользователь с таким email не найден', 'mst-auth-lk'));
            } elseif ($error_code === 'incorrect_password') {
                wp_send_json_error(__('Неверный пароль', 'mst-auth-lk'));
            } else {
                wp_send_json_error(__('Ошибка входа', 'mst-auth-lk'));
            }
        }
        
        // Проверяем нужна ли OTP верификация
        if ($this->requires_otp($user->ID)) {
            // Генерируем и отправляем OTP код
            $otp_result = $this->generate_and_send_otp($user);
            
            if (is_wp_error($otp_result)) {
                wp_send_json_error($otp_result->get_error_message());
            }
            
            wp_send_json_success([
                'require_otp' => true,
                'message' => __('Код подтверждения отправлен на ваш email', 'mst-auth-lk')
            ]);
        }
        
        // Сохраняем IP пользователя
        $this->save_trusted_ip($user->ID);
        
        wp_set_current_user($user->ID);
        wp_set_auth_cookie($user->ID, true);
        do_action('wp_login', $user->user_login, $user);
        
        wp_send_json_success([
            'redirect' => $redirect,
            'message' => __('Успешный вход!', 'mst-auth-lk')
        ]);
    }
    
    /**
     * Проверяет нужна ли OTP верификация для пользователя
     */
    private function requires_otp($user_id) {
        // Проверяем настройки OTP
        $otp_settings = get_option('mst_otp_settings', []);
        if (empty($otp_settings['enabled'])) {
            return false;
        }
        
        // Проверяем отключил ли пользователь OTP
        $otp_disabled = get_user_meta($user_id, 'mst_otp_disabled', true);
        if ($otp_disabled) {
            return false;
        }
        
        // Получаем текущий IP
        $current_ip = $this->get_client_ip();
        
        // Получаем доверенные IP пользователя
        $trusted_ips = get_user_meta($user_id, 'mst_trusted_ips', true);
        if (!is_array($trusted_ips)) {
            $trusted_ips = [];
        }
        
        // Если IP уже доверенный - OTP не нужен
        if (in_array($current_ip, $trusted_ips)) {
            return false;
        }
        
        // Новый IP - требуем OTP
        return true;
    }
    
    /**
     * Получить IP клиента
     */
    private function get_client_ip() {
        $ip_keys = ['HTTP_CF_CONNECTING_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_REAL_IP', 'REMOTE_ADDR'];
        foreach ($ip_keys as $key) {
            if (!empty($_SERVER[$key])) {
                $ip = explode(',', $_SERVER[$key])[0];
                return trim($ip);
            }
        }
        return '0.0.0.0';
    }
    
    /**
     * Сохранить доверенный IP
     */
    private function save_trusted_ip($user_id) {
        $current_ip = $this->get_client_ip();
        $trusted_ips = get_user_meta($user_id, 'mst_trusted_ips', true);
        
        if (!is_array($trusted_ips)) {
            $trusted_ips = [];
        }
        
        if (!in_array($current_ip, $trusted_ips)) {
            $trusted_ips[] = $current_ip;
            // Храним максимум 10 IP
            if (count($trusted_ips) > 10) {
                array_shift($trusted_ips);
            }
            update_user_meta($user_id, 'mst_trusted_ips', $trusted_ips);
        }
    }
    
    /**
     * Генерация и отправка OTP кода
     */
    private function generate_and_send_otp($user) {
        $otp_settings = get_option('mst_otp_settings', []);
        $code_length = intval($otp_settings['code_length'] ?? 6);
        $expiry_minutes = intval($otp_settings['expiry_minutes'] ?? 10);
        
        // Генерируем код
        $code = '';
        for ($i = 0; $i < $code_length; $i++) {
            $code .= mt_rand(0, 9);
        }
        
        // Сохраняем в базу
        $otp_data = [
            'code' => wp_hash($code),
            'expires' => time() + ($expiry_minutes * 60),
            'attempts' => 0,
            'user_id' => $user->ID
        ];
        
        set_transient('mst_otp_' . $user->ID, $otp_data, $expiry_minutes * 60);
        
        // Логируем если включен debug mode
        if (!empty($otp_settings['debug_mode'])) {
            error_log("MST OTP DEBUG: Code for user {$user->ID}: {$code}");
        }
        
        // Отправляем код
        $method = $otp_settings['method'] ?? 'email';
        
        if ($method === 'email') {
            $subject = sprintf(__('Код подтверждения для %s', 'mst-auth-lk'), get_bloginfo('name'));
            $message = sprintf(
                __("Здравствуйте, %s!\n\nВаш код подтверждения: %s\n\nКод действителен %d минут.\n\nЕсли вы не запрашивали код, проигнорируйте это сообщение.\n\nС уважением,\n%s", 'mst-auth-lk'),
                $user->display_name,
                $code,
                $expiry_minutes,
                get_bloginfo('name')
            );
            
            $sent = wp_mail($user->user_email, $subject, $message);
            
            if (!$sent) {
                return new WP_Error('otp_send_failed', __('Ошибка отправки кода', 'mst-auth-lk'));
            }
        }
        
        return true;
    }
    
    public function ajax_verify_otp() {
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'mst_auth_nonce')) {
            wp_send_json_error(__('Ошибка безопасности', 'mst-auth-lk'));
        }
        
        $email = sanitize_email($_POST['email'] ?? '');
        $code = sanitize_text_field($_POST['code'] ?? '');
        $redirect = esc_url_raw($_POST['redirect'] ?? home_url('/auth/'));
        
        $user = get_user_by('email', $email);
        if (!$user) {
            wp_send_json_error(__('Пользователь не найден', 'mst-auth-lk'));
        }
        
        $otp_data = get_transient('mst_otp_' . $user->ID);
        
        if (!$otp_data) {
            wp_send_json_error(__('Код истек. Запросите новый', 'mst-auth-lk'));
        }
        
        $otp_settings = get_option('mst_otp_settings', []);
        $max_attempts = intval($otp_settings['max_attempts'] ?? 5);
        
        if ($otp_data['attempts'] >= $max_attempts) {
            delete_transient('mst_otp_' . $user->ID);
            wp_send_json_error(__('Превышено количество попыток', 'mst-auth-lk'));
        }
        
        if (time() > $otp_data['expires']) {
            delete_transient('mst_otp_' . $user->ID);
            wp_send_json_error(__('Код истек', 'mst-auth-lk'));
        }
        
        if (!wp_check_password($code, $otp_data['code'])) {
            $otp_data['attempts']++;
            set_transient('mst_otp_' . $user->ID, $otp_data, $otp_settings['expiry_minutes'] * 60);
            wp_send_json_error(__('Неверный код', 'mst-auth-lk'));
        }
        
        // Код верный - удаляем и авторизуем
        delete_transient('mst_otp_' . $user->ID);
        $this->save_trusted_ip($user->ID);
        
        wp_set_current_user($user->ID);
        wp_set_auth_cookie($user->ID, true);
        do_action('wp_login', $user->user_login, $user);
        
        wp_send_json_success([
            'redirect' => $redirect,
            'message' => __('Успешный вход!', 'mst-auth-lk')
        ]);
    }
    
    public function ajax_resend_otp() {
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'mst_auth_nonce')) {
            wp_send_json_error(__('Ошибка безопасности', 'mst-auth-lk'));
        }
        
        $email = sanitize_email($_POST['email'] ?? '');
        $user = get_user_by('email', $email);
        
        if (!$user) {
            wp_send_json_error(__('Пользователь не найден', 'mst-auth-lk'));
        }
        
        $result = $this->generate_and_send_otp($user);
        
        if (is_wp_error($result)) {
            wp_send_json_error($result->get_error_message());
        }
        
        wp_send_json_success(['message' => __('Новый код отправлен', 'mst-auth-lk')]);
    }
    
    public function ajax_register() {
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'mst_auth_nonce')) {
            wp_send_json_error(__('Ошибка безопасности', 'mst-auth-lk'));
        }
        
        $email = sanitize_email($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $name = sanitize_text_field($_POST['name'] ?? '');
        $redirect = esc_url_raw($_POST['redirect'] ?? home_url('/auth/'));
        
        if (empty($email) || empty($password)) {
            wp_send_json_error(__('Заполните все обязательные поля', 'mst-auth-lk'));
        }
        
        if (!is_email($email)) {
            wp_send_json_error(__('Некорректный email', 'mst-auth-lk'));
        }
        
        if (email_exists($email)) {
            wp_send_json_error(__('Этот email уже зарегистрирован', 'mst-auth-lk'));
        }
        
        if (strlen($password) < 6) {
            wp_send_json_error(__('Пароль должен быть не менее 6 символов', 'mst-auth-lk'));
        }
        
        $user_id = wp_create_user($email, $password, $email);
        
        if (is_wp_error($user_id)) {
            wp_send_json_error($user_id->get_error_message());
        }
        
        wp_update_user([
            'ID' => $user_id,
            'display_name' => $name ?: $email,
            'first_name' => $name,
        ]);
        
        $user = new WP_User($user_id);
        $user->set_role('customer');
        
        // Сохраняем IP как доверенный
        $this->save_trusted_ip($user_id);
        
        // Авторизуем
        wp_set_current_user($user_id);
        wp_set_auth_cookie($user_id, true);
        do_action('wp_login', $user->user_login, $user);
        
        wp_send_json_success([
            'redirect' => $redirect,
            'message' => __('Регистрация успешна!', 'mst-auth-lk')
        ]);
    }
    
    public function ajax_forgot_password() {
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'mst_auth_nonce')) {
            wp_send_json_error(__('Ошибка безопасности', 'mst-auth-lk'));
        }
        
        $email = sanitize_email($_POST['email'] ?? '');
        
        if (empty($email) || !is_email($email)) {
            wp_send_json_error(__('Введите корректный email', 'mst-auth-lk'));
        }
        
        $user = get_user_by('email', $email);
        
        if (!$user) {
            // Не сообщаем что пользователь не найден (безопасность)
            wp_send_json_success(['message' => __('Если email найден, инструкции отправлены', 'mst-auth-lk')]);
        }
        
        $reset_key = get_password_reset_key($user);
        
        if (is_wp_error($reset_key)) {
            wp_send_json_error(__('Ошибка генерации ключа сброса', 'mst-auth-lk'));
        }
        
        $reset_url = network_site_url("wp-login.php?action=rp&key=$reset_key&login=" . rawurlencode($user->user_login), 'login');
        
        $subject = sprintf(__('Сброс пароля на %s', 'mst-auth-lk'), get_bloginfo('name'));
        $message = sprintf(
            __("Здравствуйте, %s!\n\nВы запросили сброс пароля.\n\nДля установки нового пароля перейдите по ссылке:\n%s\n\nЕсли вы не запрашивали сброс пароля, просто проигнорируйте это письмо.\n\nС уважением,\n%s", 'mst-auth-lk'),
            $user->display_name,
            $reset_url,
            get_bloginfo('name')
        );
        
        $sent = wp_mail($email, $subject, $message, ['Content-Type: text/plain; charset=UTF-8']);
        
        if (!$sent) {
            wp_send_json_error(__('Ошибка отправки email', 'mst-auth-lk'));
        }
        
        wp_send_json_success([
            'message' => __('Инструкции по сбросу пароля отправлены на ваш email!', 'mst-auth-lk')
        ]);
    }
    
    // --- LK AJAX ---
    
    public function ajax_get_order_details() {
        check_ajax_referer('mst_auth_lk_nonce', 'nonce');
        
        $order_id = intval($_POST['order_id']);
        $order = wc_get_order($order_id);
        
        if (!$order || $order->get_customer_id() !== get_current_user_id()) {
            wp_send_json_error(__('Заказ не найден', 'mst-auth-lk'));
        }
        
        ob_start();
        include MST_AUTH_LK_DIR . 'templates/order-modal.php';
        $html = ob_get_clean();
        
        wp_send_json_success(['html' => $html]);
    }
    
    public function ajax_get_ticket() {
        check_ajax_referer('mst_auth_lk_nonce', 'nonce');
        
        $order_id = intval($_POST['order_id']);
        $order = wc_get_order($order_id);
        
        if (!$order || $order->get_customer_id() !== get_current_user_id()) {
            wp_send_json_error(__('Заказ не найден', 'mst-auth-lk'));
        }
        
        ob_start();
        include MST_AUTH_LK_DIR . 'templates/order-ticket-modal.php';
        $html = ob_get_clean();
        
        wp_send_json_success(['html' => $html]);
    }
    
    public function ajax_update_avatar() {
        check_ajax_referer('mst_auth_lk_nonce', 'nonce');
        
        if (!isset($_FILES['avatar'])) {
            wp_send_json_error(__('Файл не загружен', 'mst-auth-lk'));
        }
        
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        require_once(ABSPATH . 'wp-admin/includes/media.php');
        
        $attachment_id = media_handle_upload('avatar', 0);
        
        if (is_wp_error($attachment_id)) {
            wp_send_json_error($attachment_id->get_error_message());
        }
        
        update_user_meta(get_current_user_id(), 'mst_lk_avatar', $attachment_id);
        
        wp_send_json_success(['url' => wp_get_attachment_url($attachment_id)]);
    }
    
    public function ajax_update_profile() {
        check_ajax_referer('mst_auth_lk_nonce', 'nonce');
        
        $user_id = get_current_user_id();
        
        update_user_meta($user_id, 'first_name', sanitize_text_field($_POST['first_name']));
        update_user_meta($user_id, 'last_name', sanitize_text_field($_POST['last_name']));
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
        
        wp_send_json_success(__('Профиль обновлен', 'mst-auth-lk'));
    }
    
    public function ajax_get_latepoint_booking() {
        check_ajax_referer('mst_auth_lk_nonce', 'nonce');
        
        if (!class_exists('OsBookingModel')) {
            wp_send_json_error(__('LatePoint не установлен', 'mst-auth-lk'));
        }
        
        $booking_id = intval($_POST['booking_id']);
        $booking = new OsBookingModel($booking_id);
        
        if (!$booking->id) {
            wp_send_json_error(__('Бронирование не найдено', 'mst-auth-lk'));
        }
        
        ob_start();
        include MST_AUTH_LK_DIR . 'templates/latepoint-modal.php';
        $html = ob_get_clean();
        
        wp_send_json_success(['html' => $html]);
    }
    
    public function ajax_remove_from_wishlist() {
        check_ajax_referer('mst_auth_lk_nonce', 'nonce');
        
        $product_id = intval($_POST['product_id']);
        $user_id = get_current_user_id();
        
        if (!$user_id) {
            wp_send_json_error(__('Необходима авторизация', 'mst-auth-lk'));
        }
        
        $wishlist_data = get_user_meta($user_id, 'xstore_wishlist_ids_0', true);
        
        if (!$wishlist_data) {
            wp_send_json_error(__('Wishlist пуст', 'mst-auth-lk'));
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
        
        wp_send_json_success(['message' => __('Товар удален из избранного', 'mst-auth-lk')]);
    }
    
    /**
     * AJAX: Отправка отзыва с поддержкой фото
     */
    public function ajax_submit_review() {
        check_ajax_referer('mst_auth_lk_nonce', 'nonce');
        
        if (!is_user_logged_in()) {
            wp_send_json_error(__('Необходимо авторизоваться', 'mst-auth-lk'));
        }
        
        $product_id = intval($_POST['product_id'] ?? 0);
        $order_id = intval($_POST['order_id'] ?? 0);
        $guide_id = intval($_POST['guide_id'] ?? 0);
        $rating = intval($_POST['rating'] ?? 5);
        $comment = sanitize_textarea_field($_POST['comment'] ?? '');
        
        $user_id = get_current_user_id();
        $user = get_userdata($user_id);
        
        if ($rating < 1 || $rating > 5) {
            wp_send_json_error(__('Некорректный рейтинг', 'mst-auth-lk'));
        }
        
        if (empty($comment)) {
            wp_send_json_error(__('Напишите текст отзыва', 'mst-auth-lk'));
        }
        
        // Если guide_id не передан, пробуем получить из продукта
        if (!$guide_id && $product_id) {
            $guide_id = intval(get_post_meta($product_id, '_mst_guide_id', true));
        }
        
        $comment_id = wp_insert_comment([
            'comment_post_ID' => $product_id,
            'comment_author' => $user->display_name,
            'comment_author_email' => $user->user_email,
            'comment_content' => $comment,
            'comment_type' => 'review',
            'comment_approved' => 0, // На модерацию
            'user_id' => $user_id,
        ]);
        
        if ($comment_id) {
            // Сохраняем рейтинг
            update_comment_meta($comment_id, 'rating', $rating);
            
            // Сохраняем связь с гидом для отображения в reviews-section и review-carousel
            if ($guide_id) {
                update_comment_meta($comment_id, 'mst_guide_id', $guide_id);
                
                // Обновляем счетчик отзывов гида
                $guide_reviews_count = intval(get_user_meta($guide_id, 'mst_guide_reviews_count', true));
                update_user_meta($guide_id, 'mst_guide_reviews_count', $guide_reviews_count + 1);
                
                // Пересчитываем средний рейтинг гида
                $this->recalculate_guide_rating($guide_id);
            }
            
            // Сохраняем связь с заказом
            if ($order_id) {
                update_comment_meta($comment_id, 'mst_order_id', $order_id);
            }
            
            // Получаем город пользователя если есть
            $user_city = get_user_meta($user_id, 'billing_city', true);
            if ($user_city) {
                update_comment_meta($comment_id, 'mst_user_city', $user_city);
            }
            
            // Обработка загруженных фото
            if (!empty($_FILES['review_photos'])) {
                require_once(ABSPATH . 'wp-admin/includes/image.php');
                require_once(ABSPATH . 'wp-admin/includes/file.php');
                require_once(ABSPATH . 'wp-admin/includes/media.php');
                
                $photo_ids = [];
                $files = $_FILES['review_photos'];
                
                // Поддержка множественной загрузки
                if (is_array($files['name'])) {
                    for ($i = 0; $i < count($files['name']); $i++) {
                        if ($files['error'][$i] === UPLOAD_ERR_OK) {
                            $_FILES['review_photo'] = [
                                'name' => $files['name'][$i],
                                'type' => $files['type'][$i],
                                'tmp_name' => $files['tmp_name'][$i],
                                'error' => $files['error'][$i],
                                'size' => $files['size'][$i],
                            ];
                            
                            $attachment_id = media_handle_upload('review_photo', 0);
                            
                            if (!is_wp_error($attachment_id)) {
                                $photo_ids[] = $attachment_id;
                            }
                        }
                    }
                }
                
                if (!empty($photo_ids)) {
                    update_comment_meta($comment_id, 'mst_review_photos', $photo_ids);
                }
            }
            
            // Отмечаем что отзыв оставлен для этого заказа
            if ($order_id) {
                $order = wc_get_order($order_id);
                if ($order) {
                    $reviewed_products = $order->get_meta('_mst_reviewed_products') ?: [];
                    $reviewed_products[] = $product_id;
                    $order->update_meta_data('_mst_reviewed_products', array_unique($reviewed_products));
                    $order->save();
                }
            }
            
            wp_send_json_success(['message' => __('Отзыв отправлен на модерацию', 'mst-auth-lk')]);
        }
        
        wp_send_json_error(__('Ошибка добавления отзыва', 'mst-auth-lk'));
    }
    
    /**
     * Пересчет среднего рейтинга гида
     */
    private function recalculate_guide_rating($guide_id) {
        global $wpdb;
        
        // Получаем все одобренные отзывы для этого гида
        $reviews = $wpdb->get_results($wpdb->prepare(
            "SELECT cm.meta_value as rating
             FROM {$wpdb->comments} c
             JOIN {$wpdb->commentmeta} cm ON c.comment_ID = cm.comment_id AND cm.meta_key = 'rating'
             JOIN {$wpdb->commentmeta} cm2 ON c.comment_ID = cm2.comment_id AND cm2.meta_key = 'mst_guide_id' AND cm2.meta_value = %d
             WHERE c.comment_approved = 1 AND c.comment_type = 'review'",
            $guide_id
        ));
        
        if (!empty($reviews)) {
            $total = 0;
            foreach ($reviews as $review) {
                $total += intval($review->rating);
            }
            $avg = round($total / count($reviews), 1);
            update_user_meta($guide_id, 'mst_guide_rating', $avg);
        }
    }
    
    /**
     * AJAX: Получить информацию о гиде для продукта
     */
    public function ajax_get_product_guide() {
        check_ajax_referer('mst_auth_lk_nonce', 'nonce');
        
        $product_id = intval($_POST['product_id'] ?? 0);
        
        if (!$product_id) {
            wp_send_json_success(['guide' => null]);
        }
        
        $guide_id = get_post_meta($product_id, '_mst_guide_id', true);
        
        if (!$guide_id || $guide_id == '0') {
            wp_send_json_success(['guide' => null]);
        }
        
        $guide = get_userdata($guide_id);
        
        if (!$guide) {
            wp_send_json_success(['guide' => null]);
        }
        
        $custom_avatar = get_user_meta($guide_id, 'mst_lk_avatar', true);
        $avatar_url = $custom_avatar ? wp_get_attachment_url($custom_avatar) : get_avatar_url($guide_id, ['size' => 80]);
        
        wp_send_json_success([
            'guide' => [
                'id' => $guide_id,
                'name' => $guide->display_name,
                'avatar' => $avatar_url,
                'rating' => get_user_meta($guide_id, 'mst_guide_rating', true) ?: '5.0',
            ]
        ]);
    }
    
    /**
     * AJAX: Получить список заказов для отзывов
     */
    public function ajax_get_pending_reviews() {
        check_ajax_referer('mst_auth_lk_nonce', 'nonce');
        
        if (!is_user_logged_in()) {
            wp_send_json_error(__('Необходимо авторизоваться', 'mst-auth-lk'));
        }
        
        $user_id = get_current_user_id();
        
        // Получаем завершенные заказы пользователя
        $orders = wc_get_orders([
            'customer_id' => $user_id,
            'status' => 'completed',
            'limit' => 20,
            'orderby' => 'date',
            'order' => 'DESC',
        ]);
        
        $pending_reviews = [];
        
        foreach ($orders as $order) {
            $reviewed_products = $order->get_meta('_mst_reviewed_products') ?: [];
            
            foreach ($order->get_items() as $item) {
                $product_id = $item->get_product_id();
                
                // Пропускаем если уже оставлен отзыв
                if (in_array($product_id, $reviewed_products)) {
                    continue;
                }
                
                $product = wc_get_product($product_id);
                if (!$product) continue;
                
                $guide_id = get_post_meta($product_id, '_mst_guide_id', true);
                $guide_data = null;
                
                if ($guide_id) {
                    $guide = get_userdata($guide_id);
                    if ($guide) {
                        $custom_avatar = get_user_meta($guide_id, 'mst_lk_avatar', true);
                        $guide_data = [
                            'id' => $guide_id,
                            'name' => $guide->display_name,
                            'avatar' => $custom_avatar ? wp_get_attachment_url($custom_avatar) : get_avatar_url($guide_id, ['size' => 60]),
                        ];
                    }
                }
                
                $pending_reviews[] = [
                    'order_id' => $order->get_id(),
                    'product_id' => $product_id,
                    'product_name' => $product->get_name(),
                    'product_image' => wp_get_attachment_url($product->get_image_id()),
                    'order_date' => $order->get_date_completed() ? $order->get_date_completed()->format('d.m.Y') : '',
                    'guide' => $guide_data,
                ];
            }
        }
        
        wp_send_json_success(['reviews' => $pending_reviews]);
    }
    
    public function ajax_toggle_otp() {
        check_ajax_referer('mst_auth_lk_nonce', 'nonce');
        
        $user_id = get_current_user_id();
        $enabled = !empty($_POST['enabled']);
        
        update_user_meta($user_id, 'mst_otp_disabled', $enabled ? '' : '1');
        
        wp_send_json_success(['message' => __('Настройки сохранены', 'mst-auth-lk')]);
    }
    
    public function ajax_clear_trusted_ips() {
        check_ajax_referer('mst_auth_lk_nonce', 'nonce');
        
        $user_id = get_current_user_id();
        delete_user_meta($user_id, 'mst_trusted_ips');
        
        wp_send_json_success(['message' => __('Доверенные устройства сброшены', 'mst-auth-lk')]);
    }
    
    public function ajax_download_gift() {
        check_ajax_referer('mst_auth_lk_nonce', 'nonce');
        // TODO: Implement gift download
        wp_send_json_error(__('Функция в разработке', 'mst-auth-lk'));
    }
    
    // ==================== ENDPOINTS ====================
    
    public function register_endpoints() {
        add_rewrite_rule('^guide/([0-9]+)/?$', 'index.php?mst_guide_id=$matches[1]', 'top');
        add_rewrite_endpoint('mst-profile', EP_ROOT | EP_PAGES);
    }
    
    public function add_query_vars($vars) {
        $vars[] = 'mst_guide_id';
        return $vars;
    }
    
    public function handle_guide_template() {
        $guide_id = get_query_var('mst_guide_id');
        if ($guide_id) {
            $_GET['guide_id'] = intval($guide_id);
        }
    }
    
    // ==================== OAUTH ====================
    
    public function handle_oauth_redirect() {
        if (!isset($_GET['mst_oauth']) && !isset($_GET['mst_oauth_callback'])) {
            return;
        }
        
        $provider = sanitize_text_field($_GET['mst_oauth'] ?? $_GET['mst_oauth_callback']);
        $redirect = isset($_GET['redirect']) ? esc_url_raw(urldecode($_GET['redirect'])) : home_url('/auth/');
        
        $oauth_settings = get_option('mst_oauth_settings', []);
        
        switch ($provider) {
            case 'google':
                $this->handle_google_oauth($oauth_settings, $redirect);
                break;
            case 'vk':
                $this->handle_vk_oauth($oauth_settings, $redirect);
                break;
            case 'yandex':
                $this->handle_yandex_oauth($oauth_settings, $redirect);
                break;
        }
    }
    
    private function handle_google_oauth($settings, $redirect) {
        // OAuth implementation (unchanged)
    }
    
    private function handle_vk_oauth($settings, $redirect) {
        // OAuth implementation (unchanged)
    }
    
    private function handle_yandex_oauth($settings, $redirect) {
        // OAuth implementation (unchanged)
    }
    
    private function process_oauth_user($email, $name, $provider, $redirect) {
        $email = sanitize_email($email);
        $user = get_user_by('email', $email);
        
        if (!$user) {
            $password = wp_generate_password(16, true, true);
            $user_id = wp_create_user($email, $password, $email);
            
            if (is_wp_error($user_id)) {
                wp_redirect(add_query_arg('error', 'registration_failed', home_url('/auth/')));
                exit;
            }
            
            wp_update_user([
                'ID' => $user_id,
                'display_name' => sanitize_text_field($name) ?: $email,
                'first_name' => sanitize_text_field($name),
            ]);
            
            $user = new WP_User($user_id);
            $user->set_role('customer');
            
            update_user_meta($user_id, 'mst_oauth_provider', $provider);
        }
        
        $this->save_trusted_ip($user->ID);
        
        wp_set_current_user($user->ID);
        wp_set_auth_cookie($user->ID, true);
        do_action('wp_login', $user->user_login, $user);
        
        wp_safe_redirect($redirect);
        exit;
    }
    
    // ==================== REST API ====================
    
    public function register_rest_routes() {
        register_rest_route('mst/v1', '/guides/(?P<ids>[0-9,]+)', [
            'methods' => 'GET',
            'callback' => [$this, 'rest_get_guides'],
            'permission_callback' => '__return_true'
        ]);
    }
    
    public function rest_get_guides($request) {
        $ids = explode(',', $request['ids']);
        $result = [];
        
        $status_colors = [
            'bronze' => '#CD7F32',
            'silver' => '#C0C0C0',
            'gold' => '#FFD700',
            'guide' => '#9952E0'
        ];
        
        foreach ($ids as $product_id) {
            $product_id = intval($product_id);
            $guide_id = get_post_meta($product_id, '_mst_guide_id', true);
            
            if (!$guide_id || $guide_id == '0') continue;
            
            $guide = get_userdata($guide_id);
            if (!$guide) continue;
            
            $custom_avatar = get_user_meta($guide_id, 'mst_lk_avatar', true);
            $avatar_url = $custom_avatar ? wp_get_attachment_url($custom_avatar) : get_avatar_url($guide_id, ['size' => 80]);
            
            $user_status = get_user_meta($guide_id, 'mst_user_status', true) ?: 'guide';
            
            $result[$product_id] = [
                'name' => $guide->display_name,
                'avatar' => $avatar_url,
                'rating' => get_user_meta($guide_id, 'mst_guide_rating', true) ?: '5.0',
                'reviews' => get_user_meta($guide_id, 'mst_guide_reviews_count', true) ?: '0',
                'border' => $status_colors[$user_status] ?? '#9952E0',
                'url' => home_url('/guide/' . $guide_id)
            ];
        }
        
        return $result;
    }
    
    // ==================== ADMIN ====================
    
    public function add_admin_menu() {
        add_submenu_page(
            'mysupertour-hub',
            __('Auth + ЛК', 'mst-auth-lk'),
            '👤 Auth + ЛК',
            'manage_options',
            'mst-auth-lk-settings',
            [$this, 'render_admin_page']
        );
    }
    
    public function render_admin_page() {
        include MST_AUTH_LK_DIR . 'templates/admin-page.php';
    }
    
    public function add_user_meta_fields($user) {
        $current_status = get_user_meta($user->ID, 'mst_user_status', true) ?: 'bronze';
        ?>
        <h3><?php _e('MySuperTour - Личный Кабинет', 'mst-auth-lk'); ?></h3>
        <table class="form-table">
            <tr>
                <th><label for="mst_user_bonuses"><?php _e('Бонусы', 'mst-auth-lk'); ?></label></th>
                <td>
                    <input type="number" name="mst_user_bonuses" value="<?php echo esc_attr(get_user_meta($user->ID, 'mst_user_bonuses', true) ?: 0); ?>" class="regular-text" min="0">
                </td>
            </tr>
            <tr>
                <th><label for="mst_user_status"><?php _e('Статус', 'mst-auth-lk'); ?></label></th>
                <td>
                    <select name="mst_user_status" class="regular-text">
                        <option value="bronze" <?php selected($current_status, 'bronze'); ?>><?php _e('Бронзовый', 'mst-auth-lk'); ?></option>
                        <option value="silver" <?php selected($current_status, 'silver'); ?>><?php _e('Серебряный', 'mst-auth-lk'); ?></option>
                        <option value="gold" <?php selected($current_status, 'gold'); ?>><?php _e('Золотой', 'mst-auth-lk'); ?></option>
                        <option value="guide" <?php selected($current_status, 'guide'); ?>><?php _e('🟣 Гид', 'mst-auth-lk'); ?></option>
                    </select>
                </td>
            </tr>
            <tr>
                <th><label for="mst_user_status_label"><?php _e('Название статуса', 'mst-auth-lk'); ?></label></th>
                <td>
                    <input type="text" name="mst_user_status_label" value="<?php echo esc_attr(get_user_meta($user->ID, 'mst_user_status_label', true) ?: __('Бронзовый статус', 'mst-auth-lk')); ?>" class="regular-text">
                </td>
            </tr>
        </table>
        
        <h3><?php _e('Профиль гида', 'mst-auth-lk'); ?></h3>
        <table class="form-table">
            <tr>
                <th><label><?php _e('Рейтинг', 'mst-auth-lk'); ?></label></th>
                <td><input type="number" name="mst_guide_rating" value="<?php echo esc_attr(get_user_meta($user->ID, 'mst_guide_rating', true) ?: '5.0'); ?>" step="0.1" min="0" max="5" class="regular-text"></td>
            </tr>
            <tr>
                <th><label><?php _e('Кол-во отзывов', 'mst-auth-lk'); ?></label></th>
                <td><input type="number" name="mst_guide_reviews_count" value="<?php echo esc_attr(get_user_meta($user->ID, 'mst_guide_reviews_count', true) ?: '0'); ?>" min="0" class="regular-text"></td>
            </tr>
            <tr>
                <th><label><?php _e('О гиде', 'mst-auth-lk'); ?></label></th>
                <td><textarea name="mst_guide_experience" class="large-text" rows="4"><?php echo esc_textarea(get_user_meta($user->ID, 'mst_guide_experience', true)); ?></textarea></td>
            </tr>
            <tr>
                <th><label><?php _e('Языки', 'mst-auth-lk'); ?></label></th>
                <td><input type="text" name="mst_guide_languages" value="<?php echo esc_attr(get_user_meta($user->ID, 'mst_guide_languages', true)); ?>" class="regular-text"></td>
            </tr>
            <tr>
                <th><label><?php _e('Специализация', 'mst-auth-lk'); ?></label></th>
                <td><input type="text" name="mst_guide_specialization" value="<?php echo esc_attr(get_user_meta($user->ID, 'mst_guide_specialization', true)); ?>" class="regular-text"></td>
            </tr>
            <tr>
                <th><label><?php _e('Город', 'mst-auth-lk'); ?></label></th>
                <td><input type="text" name="mst_guide_city" value="<?php echo esc_attr(get_user_meta($user->ID, 'mst_guide_city', true)); ?>" class="regular-text"></td>
            </tr>
            <tr>
                <th><label><?php _e('Опыт (лет)', 'mst-auth-lk'); ?></label></th>
                <td><input type="number" name="mst_guide_experience_years" value="<?php echo esc_attr(get_user_meta($user->ID, 'mst_guide_experience_years', true) ?: '0'); ?>" min="0" class="regular-text"></td>
            </tr>
            <tr>
                <th><label><?php _e('Туров проведено', 'mst-auth-lk'); ?></label></th>
                <td><input type="number" name="mst_guide_tours_count" value="<?php echo esc_attr(get_user_meta($user->ID, 'mst_guide_tours_count', true) ?: '0'); ?>" min="0" class="regular-text"></td>
            </tr>
            <tr>
                <th><label><?php _e('Достижения', 'mst-auth-lk'); ?></label></th>
                <td><textarea name="mst_guide_achievements" class="large-text" rows="3"><?php echo esc_textarea(get_user_meta($user->ID, 'mst_guide_achievements', true)); ?></textarea></td>
            </tr>
            <tr>
                <th><label><?php _e('Верификация', 'mst-auth-lk'); ?></label></th>
                <td>
                    <label>
                        <input type="checkbox" name="mst_guide_verified" value="1" <?php checked(get_user_meta($user->ID, 'mst_guide_verified', true)); ?>>
                        <?php _e('Верифицированный гид', 'mst-auth-lk'); ?>
                    </label>
                </td>
            </tr>
        </table>
        <?php
    }
    
    public function save_user_meta_fields($user_id) {
        if (!current_user_can('edit_user', $user_id)) return;
        
        update_user_meta($user_id, 'mst_user_bonuses', intval($_POST['mst_user_bonuses'] ?? 0));
        update_user_meta($user_id, 'mst_user_status', sanitize_text_field($_POST['mst_user_status'] ?? 'bronze'));
        update_user_meta($user_id, 'mst_user_status_label', sanitize_text_field($_POST['mst_user_status_label'] ?? ''));
        update_user_meta($user_id, 'mst_guide_rating', sanitize_text_field($_POST['mst_guide_rating'] ?? '5.0'));
        update_user_meta($user_id, 'mst_guide_reviews_count', intval($_POST['mst_guide_reviews_count'] ?? 0));
        update_user_meta($user_id, 'mst_guide_experience', sanitize_textarea_field($_POST['mst_guide_experience'] ?? ''));
        update_user_meta($user_id, 'mst_guide_languages', sanitize_text_field($_POST['mst_guide_languages'] ?? ''));
        update_user_meta($user_id, 'mst_guide_specialization', sanitize_text_field($_POST['mst_guide_specialization'] ?? ''));
        update_user_meta($user_id, 'mst_guide_city', sanitize_text_field($_POST['mst_guide_city'] ?? ''));
        update_user_meta($user_id, 'mst_guide_experience_years', intval($_POST['mst_guide_experience_years'] ?? 0));
        update_user_meta($user_id, 'mst_guide_tours_count', intval($_POST['mst_guide_tours_count'] ?? 0));
        update_user_meta($user_id, 'mst_guide_achievements', sanitize_textarea_field($_POST['mst_guide_achievements'] ?? ''));
        update_user_meta($user_id, 'mst_guide_verified', !empty($_POST['mst_guide_verified']) ? 1 : 0);
    }
    
    // ==================== WOOCOMMERCE ====================
    
    public function add_woo_menu_item($items) {
        $new_items = [];
        foreach ($items as $key => $value) {
            $new_items[$key] = $value;
            if ($key === 'dashboard') {
                $new_items['mst-profile'] = __('Мой профиль', 'mst-auth-lk');
            }
        }
        return $new_items;
    }
    
    public function render_woo_account_content() {
        echo do_shortcode('[mst_auth_lk]');
    }
    
    /**
     * Включить возможность оставить отзыв после завершения заказа
     */
    public function enable_review_for_order($order_id) {
        $order = wc_get_order($order_id);
        if (!$order) return;
        
        $order->update_meta_data('_mst_can_review', 1);
        $order->update_meta_data('_mst_reviewed_products', []);
        $order->save();
    }
    
    /**
     * Включить возможность оставить отзыв после LatePoint бронирования
     */
    public function enable_review_for_latepoint_booking($booking_id) {
        if (!class_exists('OsBookingModel')) return;
        
        $booking = new OsBookingModel($booking_id);
        if (!$booking->id) return;
        
        // Сохраняем информацию о возможности отзыва
        update_post_meta($booking_id, '_mst_can_review', 1);
    }
    
    // ==================== GUIDE METABOX - ИСПРАВЛЕНО ====================
    
    public function add_guide_metabox($post_type, $post) {
        if ($post_type !== 'product') return;
        
        add_meta_box(
            'mst_product_guide', 
            '👨‍🎓 ' . __('Гид экскурсии', 'mst-auth-lk'), 
            [$this, 'render_guide_metabox'], 
            'product', 
            'side', 
            'high'
        );
    }
    
    public function render_guide_metabox($post) {
        // Получаем текущий выбранный гид
        $current_guide_id = get_post_meta($post->ID, '_mst_guide_id', true);
        
        // Получаем всех пользователей со статусом guide
        $guides = get_users([
            'meta_key' => 'mst_user_status', 
            'meta_value' => 'guide', 
            'orderby' => 'display_name', 
            'order' => 'ASC'
        ]);
        
        // Если нет гидов с меткой guide, показываем всех пользователей
        if (empty($guides)) {
            $guides = get_users([
                'role__in' => ['administrator', 'shop_manager', 'customer'],
                'orderby' => 'display_name', 
                'order' => 'ASC',
                'number' => 50
            ]);
        }
        
        wp_nonce_field('mst_save_guide_meta', 'mst_guide_meta_nonce');
        ?>
        <p>
            <label for="mst_guide_id"><strong><?php _e('Выберите гида:', 'mst-auth-lk'); ?></strong></label>
        </p>
        <select name="mst_guide_id" id="mst_guide_id" style="width:100%; padding:8px; margin-bottom:10px;">
            <option value="0">-- <?php _e('Без гида', 'mst-auth-lk'); ?> --</option>
            <?php foreach ($guides as $guide): 
                $guide_status = get_user_meta($guide->ID, 'mst_user_status', true);
                $is_verified_guide = ($guide_status === 'guide');
                $label = $guide->display_name;
                if ($is_verified_guide) {
                    $label .= ' ✓';
                }
            ?>
                <option value="<?php echo esc_attr($guide->ID); ?>" <?php selected($current_guide_id, $guide->ID); ?>>
                    <?php echo esc_html($label); ?> (ID: <?php echo $guide->ID; ?>)
                </option>
            <?php endforeach; ?>
        </select>
        
        <?php if ($current_guide_id && $current_guide_id != '0'): 
            $selected_guide = get_userdata($current_guide_id);
            if ($selected_guide):
                $custom_avatar = get_user_meta($current_guide_id, 'mst_lk_avatar', true);
                $avatar_url = $custom_avatar ? wp_get_attachment_url($custom_avatar) : get_avatar_url($current_guide_id, ['size' => 60]);
        ?>
        <div style="display:flex; align-items:center; gap:10px; margin-top:10px; padding:10px; background:#f0f0f1; border-radius:8px;">
            <img src="<?php echo esc_url($avatar_url); ?>" style="width:40px; height:40px; border-radius:50%; object-fit:cover;">
            <div>
                <strong><?php echo esc_html($selected_guide->display_name); ?></strong><br>
                <small style="color:#666;"><?php echo esc_html($selected_guide->user_email); ?></small>
            </div>
        </div>
        <?php endif; endif; ?>
        
        <p style="margin-top:10px;">
            <a href="<?php echo admin_url('users.php'); ?>" target="_blank" style="font-size:12px;">
                <?php _e('Управление пользователями →', 'mst-auth-lk'); ?>
            </a>
        </p>
        <?php
    }
    
    public function save_guide_meta($post_id, $post, $update) {
        // Проверяем nonce
        if (!isset($_POST['mst_guide_meta_nonce']) || !wp_verify_nonce($_POST['mst_guide_meta_nonce'], 'mst_save_guide_meta')) {
            return;
        }
        
        // Проверяем автосохранение
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        
        // Проверяем права
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
        
        // Сохраняем ID гида
        $guide_id = isset($_POST['mst_guide_id']) ? intval($_POST['mst_guide_id']) : 0;
        update_post_meta($post_id, '_mst_guide_id', $guide_id);
        
        // Логируем для отладки
        error_log("MST Guide Meta: Saved guide_id {$guide_id} for product {$post_id}");
    }
    
    // ==================== SHORTCODES ====================
    
    /**
     * Главный шорткод: показывает Auth или LK в зависимости от статуса
     */
    public function render_auth_lk_shortcode($atts) {
        $atts = shortcode_atts([
            'redirect' => home_url('/auth/'),
            'form_type' => 'both',
        ], $atts);
        
        if (is_user_logged_in()) {
            return $this->render_lk_content($atts);
        } else {
            return $this->render_auth_form($atts);
        }
    }
    
    private function render_auth_form($atts) {
        $uid = 'mst-auth-' . uniqid();
        $redirect = esc_url($atts['redirect']);
        $form_type = $atts['form_type'];
        
        ob_start();
        include MST_AUTH_LK_DIR . 'templates/auth-form.php';
        return ob_get_clean();
    }
    
    private function render_lk_content($atts) {
        $settings = get_option('mst_auth_lk_settings', []);
        $tabs = $settings['tabs'] ?? [];
        
        ob_start();
        include MST_AUTH_LK_DIR . 'templates/profile.php';
        return ob_get_clean();
    }
    
    public function render_guide_profile($atts) {
        $guide_id = isset($_GET['guide_id']) ? intval($_GET['guide_id']) : 0;
        
        if (!$guide_id) {
            return '<div style="text-align:center;padding:60px;"><h2>' . __('Гид не найден', 'mst-auth-lk') . '</h2></div>';
        }
        
        $guide = get_userdata($guide_id);
        if (!$guide) {
            return '<div style="text-align:center;padding:60px;"><h2>' . __('Гид не найден', 'mst-auth-lk') . '</h2></div>';
        }
        
        ob_start();
        include MST_AUTH_LK_DIR . 'templates/guide-profile.php';
        return ob_get_clean();
    }
    
    public function render_guides_list($atts) {
        $atts = shortcode_atts([
            'per_page' => 12,
            'columns' => 3,
        ], $atts);
        
        $guides = get_users([
            'meta_key' => 'mst_user_status',
            'meta_value' => 'guide',
            'number' => intval($atts['per_page']),
        ]);
        
        if (empty($guides)) {
            return '<div style="text-align:center;padding:60px;"><p>' . __('Гиды не найдены', 'mst-auth-lk') . '</p></div>';
        }
        
        ob_start();
        include MST_AUTH_LK_DIR . 'templates/guides-list.php';
        return ob_get_clean();
    }
}

// Инициализация
MST_Auth_LK_Unified::instance();
