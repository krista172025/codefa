<?php
/**
 * Plugin Name: MySuperTour - Auth + LK Unified
 * Description: Объединённый Elementor виджет: Авторизация + Личный Кабинет + Система Гидов
 * Version: 4.0.1
 * Author: Telegram @l1ghtsun
 * Author URI: https://t.me/l1ghtsun
 * Text Domain: mst-auth-lk
 * Requires: Elementor, WooCommerce
 * 
 * ИСПРАВЛЕННАЯ ВЕРСИЯ - Fixed:
 * 1. OTP verification with logging
 * 2. SMS provider support (Twilio, SMS.ru)
 * 3. Improved error handling
 * 4. Debug mode for OTP codes
 */

if (!defined('ABSPATH')) exit;

define('MST_AUTH_LK_VERSION', '4.0.1');
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
        
        // Metabox для гидов
        add_action('add_meta_boxes', [$this, 'add_guide_metabox']);
        add_action('save_post', [$this, 'save_guide_meta']);
        
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

        // Auth status (для обхода кэша гостя)
        add_action('wp_ajax_mst_auth_status', [$this, 'ajax_auth_status']);
        add_action('wp_ajax_nopriv_mst_auth_status', [$this, 'ajax_auth_status']);
        
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
        
        // Guide profile AJAX - загрузка отзывов
        add_action('wp_ajax_mst_load_more_guide_reviews', [$this, 'ajax_load_more_guide_reviews']);
        add_action('wp_ajax_nopriv_mst_load_more_guide_reviews', [$this, 'ajax_load_more_guide_reviews']);
        
        // Wishlist handlers for shop-grid (XStore format)
        add_action('wp_ajax_mst_add_wishlist', [$this, 'ajax_add_wishlist']);
        add_action('wp_ajax_nopriv_mst_add_wishlist', [$this, 'ajax_add_wishlist']);
        add_action('wp_ajax_mst_remove_wishlist', [$this, 'ajax_remove_wishlist']);
        add_action('wp_ajax_nopriv_mst_remove_wishlist', [$this, 'ajax_remove_wishlist']);
        add_action('wp_ajax_mst_check_wishlist', [$this, 'ajax_check_wishlist']);
        add_action('wp_ajax_nopriv_mst_check_wishlist', [$this, 'ajax_check_wishlist']);
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
     * AJAX: Статус авторизации (обходит кэш "гостя")
     */
    public function ajax_auth_status() {
        // Без nonce намеренно: на закэшированных страницах nonce часто "гостевой"
        $user_id = get_current_user_id();

        wp_send_json_success([
            'is_logged_in' => $user_id > 0,
            'user_id' => $user_id > 0 ? $user_id : 0,
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
            // Храним максимум 10 последних IP
            $trusted_ips = array_slice($trusted_ips, -10);
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
        $method = $otp_settings['method'] ?? 'email';
        
        // Генерируем код
        $max = pow(10, $code_length) - 1;
        $otp_code = str_pad(mt_rand(0, $max), $code_length, '0', STR_PAD_LEFT);
        
        $otp_data = [
            'code' => $otp_code,
            'expires' => time() + ($expiry_minutes * 60),
            'attempts' => 0
        ];
        set_transient('mst_otp_' . $user->ID, $otp_data, $expiry_minutes * 60);
        
        // Отправляем код
        $sent = false;
        $send_method = 'email';
        
        if ($method === 'email' || $method === 'both') {
            $sent = $this->send_otp_email($user, $otp_code);
            $send_method = 'email';
        }
        
        if ($method === 'sms' || $method === 'both') {
            $phone = get_user_meta($user->ID, 'billing_phone', true);
            if ($phone) {
                $sms_sent = $this->send_otp_sms($phone, $otp_code);
                if ($sms_sent) {
                    $sent = true;
                    $send_method = $method === 'both' ? 'both' : 'sms';
                }
            }
        }
        
        // Логируем
        $this->log_otp_event($user, $otp_code, $send_method, 'sent');
        
        if (!$sent) {
            return new WP_Error('otp_send_failed', __('Не удалось отправить код подтверждения', 'mst-auth-lk'));
        }
        
        return true;
    }
    
    /**
     * Отправка OTP на email
     */
    private function send_otp_email($user, $otp_code) {
        $subject = sprintf(__('Код подтверждения для %s', 'mst-auth-lk'), get_bloginfo('name'));
        $message = sprintf(
            __("Здравствуйте, %s!\n\nВаш код подтверждения: %s\n\nКод действителен 10 минут.\n\nЕсли вы не запрашивали вход, проигнорируйте это письмо.\n\nС уважением,\n%s", 'mst-auth-lk'),
            $user->display_name,
            $otp_code,
            get_bloginfo('name')
        );
        
        return wp_mail($user->user_email, $subject, $message, ['Content-Type: text/plain; charset=UTF-8']);
    }
    
    /**
     * Отправка OTP по SMS
     */
    private function send_otp_sms($phone, $otp_code) {
        $otp_settings = get_option('mst_otp_settings', []);
        $provider = $otp_settings['sms_provider'] ?? 'none';
        
        if ($provider === 'none') {
            return false;
        }
        
        // Очищаем номер телефона
        $phone = preg_replace('/[^0-9+]/', '', $phone);
        if (substr($phone, 0, 1) === '8') {
            $phone = '+7' . substr($phone, 1);
        }
        if (substr($phone, 0, 1) !== '+') {
            $phone = '+' . $phone;
        }
        
        $message = sprintf(__('Ваш код подтверждения: %s', 'mst-auth-lk'), $otp_code);
        
        if ($provider === 'twilio') {
            return $this->send_sms_twilio($phone, $message, $otp_settings);
        }
        
        if ($provider === 'smsru') {
            return $this->send_sms_smsru($phone, $message, $otp_settings);
        }
        
        return false;
    }
    
    /**
     * Отправка SMS через Twilio
     */
    private function send_sms_twilio($phone, $message, $settings) {
        $sid = $settings['twilio_sid'] ?? '';
        $token = $settings['twilio_token'] ?? '';
        $from = $settings['twilio_phone'] ?? '';
        
        if (empty($sid) || empty($token) || empty($from)) {
            return false;
        }
        
        $url = "https://api.twilio.com/2010-04-01/Accounts/{$sid}/Messages.json";
        
        $response = wp_remote_post($url, [
            'headers' => [
                'Authorization' => 'Basic ' . base64_encode("{$sid}:{$token}"),
            ],
            'body' => [
                'From' => $from,
                'To' => $phone,
                'Body' => $message,
            ],
        ]);
        
        if (is_wp_error($response)) {
            error_log('MST OTP Twilio Error: ' . $response->get_error_message());
            return false;
        }
        
        $body = json_decode(wp_remote_retrieve_body($response), true);
        return !empty($body['sid']);
    }
    
    /**
     * Отправка SMS через SMS.ru
     */
    private function send_sms_smsru($phone, $message, $settings) {
        $api_key = $settings['smsru_api_key'] ?? '';
        $sender = $settings['smsru_sender'] ?? '';
        
        if (empty($api_key)) {
            return false;
        }
        
        $params = [
            'api_id' => $api_key,
            'to' => $phone,
            'msg' => $message,
            'json' => 1,
        ];
        
        if (!empty($sender)) {
            $params['from'] = $sender;
        }
        
        $response = wp_remote_get('https://sms.ru/sms/send?' . http_build_query($params));
        
        if (is_wp_error($response)) {
            error_log('MST OTP SMS.ru Error: ' . $response->get_error_message());
            return false;
        }
        
        $body = json_decode(wp_remote_retrieve_body($response), true);
        return !empty($body['status']) && $body['status'] === 'OK';
    }
    
    /**
     * Логирование OTP событий
     */
    private function log_otp_event($user, $code, $method, $status) {
        $otp_settings = get_option('mst_otp_settings', []);
        $logs = get_option('mst_otp_logs', []);
        
        $log_entry = [
            'date' => current_time('d.m.Y H:i:s'),
            'user_id' => $user->ID,
            'user_email' => $user->user_email,
            'ip' => $this->get_client_ip(),
            'method' => $method,
            'status' => $status,
        ];
        
        // В debug режиме сохраняем код
        if (!empty($otp_settings['debug_mode'])) {
            $log_entry['code'] = $code;
        }
        
        $logs[] = $log_entry;
        
        // Храним максимум 500 записей
        if (count($logs) > 500) {
            $logs = array_slice($logs, -500);
        }
        
        update_option('mst_otp_logs', $logs);
    }
    
    /**
     * AJAX: Проверка OTP кода
     */
    public function ajax_verify_otp() {
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'mst_auth_nonce')) {
            wp_send_json_error(__('Ошибка безопасности', 'mst-auth-lk'));
        }
        
        $email = sanitize_email($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $otp_code = sanitize_text_field($_POST['otp_code'] ?? '');
        $redirect = esc_url_raw($_POST['redirect'] ?? home_url('/auth/'));
        $remember_device = !empty($_POST['remember_device']);
        
        if (empty($email) || empty($otp_code)) {
            wp_send_json_error(__('Введите код подтверждения', 'mst-auth-lk'));
        }
        
        $user = get_user_by('email', $email);
        if (!$user) {
            wp_send_json_error(__('Пользователь не найден', 'mst-auth-lk'));
        }
        
        // Проверяем OTP
        $otp_data = get_transient('mst_otp_' . $user->ID);
        
        if (!$otp_data) {
            $this->log_otp_event($user, '', 'verify', 'expired');
            wp_send_json_error(__('Код истек. Запросите новый', 'mst-auth-lk'));
        }
        
        $otp_settings = get_option('mst_otp_settings', []);
        $max_attempts = intval($otp_settings['max_attempts'] ?? 5);
        
        if ($otp_data['attempts'] >= $max_attempts) {
            delete_transient('mst_otp_' . $user->ID);
            $this->log_otp_event($user, '', 'verify', 'failed');
            wp_send_json_error(__('Слишком много попыток. Запросите новый код', 'mst-auth-lk'));
        }
        
        if ($otp_code !== $otp_data['code']) {
            $otp_data['attempts']++;
            set_transient('mst_otp_' . $user->ID, $otp_data, 600);
            $this->log_otp_event($user, $otp_code, 'verify', 'failed');
            wp_send_json_error(__('Неверный код', 'mst-auth-lk'));
        }
        
        // OTP верный - удаляем
        delete_transient('mst_otp_' . $user->ID);
        $this->log_otp_event($user, $otp_code, 'verify', 'success');
        
        // Сохраняем IP как доверенный если выбрано
        if ($remember_device) {
            $this->save_trusted_ip($user->ID);
        }
        
        // Авторизуем пользователя
        wp_set_current_user($user->ID);
        wp_set_auth_cookie($user->ID, true);
        do_action('wp_login', $user->user_login, $user);
        
        wp_send_json_success([
            'redirect' => $redirect,
            'message' => __('Успешный вход!', 'mst-auth-lk')
        ]);
    }
    
    /**
     * AJAX: Повторная отправка OTP
     */
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
        
        wp_send_json_success(['message' => __('Код отправлен', 'mst-auth-lk')]);
    }
    
    /**
     * AJAX: Включить/выключить OTP для пользователя
     */
    public function ajax_toggle_otp() {
        check_ajax_referer('mst_auth_lk_nonce', 'nonce');
        
        if (!is_user_logged_in()) {
            wp_send_json_error(__('Необходима авторизация', 'mst-auth-lk'));
        }
        
        $user_id = get_current_user_id();
        $enabled = !empty($_POST['enabled']);
        
        if ($enabled) {
            delete_user_meta($user_id, 'mst_otp_disabled');
        } else {
            update_user_meta($user_id, 'mst_otp_disabled', 1);
        }
        
        wp_send_json_success([
            'enabled' => $enabled,
            'message' => $enabled 
                ? __('Двухфакторная аутентификация включена', 'mst-auth-lk') 
                : __('Двухфакторная аутентификация отключена', 'mst-auth-lk')
        ]);
    }
    
    public function ajax_register() {
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'mst_auth_nonce')) {
            wp_send_json_error(__('Ошибка безопасности. Обновите страницу.', 'mst-auth-lk'));
        }
        
        $display_name = isset($_POST['display_name']) ? sanitize_text_field($_POST['display_name']) : '';
        $email = isset($_POST['email']) ? sanitize_email($_POST['email']) : '';
        $password = isset($_POST['password']) ? $_POST['password'] : '';
        $redirect = !empty($_POST['redirect']) ? esc_url_raw($_POST['redirect']) : home_url('/auth/');
        
        if (empty($display_name)) {
            wp_send_json_error(__('Введите ваше имя', 'mst-auth-lk'));
        }
        
        if (empty($email) || !is_email($email)) {
            wp_send_json_error(__('Некорректный email', 'mst-auth-lk'));
        }
        
        if (strlen($password) < 6) {
            wp_send_json_error(__('Пароль должен быть минимум 6 символов', 'mst-auth-lk'));
        }
        
        if (email_exists($email)) {
            wp_send_json_error(__('Пользователь с таким email уже существует', 'mst-auth-lk'));
        }
        
        $user_id = wp_create_user($email, $password, $email);
        
        if (is_wp_error($user_id)) {
            wp_send_json_error(__('Ошибка регистрации', 'mst-auth-lk'));
        }
        
        wp_update_user([
            'ID' => $user_id,
            'display_name' => $display_name,
            'first_name' => $display_name,
            'nickname' => $display_name
        ]);
        
        $user = new WP_User($user_id);
        $user->set_role('customer');
        
        wp_set_current_user($user_id);
        wp_set_auth_cookie($user_id, true);
        do_action('wp_login', $email, $user);
        
        wp_send_json_success([
            'redirect' => $redirect,
            'message' => __('Регистрация успешна!', 'mst-auth-lk')
        ]);
    }
    
    public function ajax_forgot_password() {
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'mst_auth_nonce')) {
            wp_send_json_error(__('Ошибка безопасности. Обновите страницу.', 'mst-auth-lk'));
        }
        
        $email = isset($_POST['email']) ? sanitize_email($_POST['email']) : '';
        
        if (empty($email) || !is_email($email)) {
            wp_send_json_error(__('Некорректный email', 'mst-auth-lk'));
        }
        
        $user = get_user_by('email', $email);
        
        if (!$user) {
            wp_send_json_error(__('Пользователь с таким email не найден', 'mst-auth-lk'));
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
     * AJAX: Добавить товар в wishlist (XStore format)
     * Called from shop-grid.js
     */
    public function ajax_add_wishlist() {
        // Проверяем nonce - используем mst_shop_grid_nonce из shop-grid.php
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'mst_shop_grid_nonce')) {
            wp_send_json_error(['message' => __('Ошибка безопасности', 'mst-auth-lk')]);
        }
        
        if (!is_user_logged_in()) {
            wp_send_json_error(['message' => __('Необходима авторизация', 'mst-auth-lk')]);
        }
        
        $product_id = intval($_POST['product_id'] ?? 0);
        $user_id = get_current_user_id();
        
        if (!$product_id) {
            wp_send_json_error(['message' => __('Некорректный product_id', 'mst-auth-lk')]);
        }
        
        // XStore wishlist format: xstore_wishlist_ids_0
        $wishlist_data = get_user_meta($user_id, 'xstore_wishlist_ids_0', true);
        $items = [];
        
        if ($wishlist_data) {
            $items = explode('|', $wishlist_data);
        }
        
        // Проверяем, не добавлен ли уже
        foreach ($items as $item) {
            $decoded = json_decode($item, true);
            if ($decoded && isset($decoded['id']) && intval($decoded['id']) === $product_id) {
                wp_send_json_success(['message' => __('Товар уже в избранном', 'mst-auth-lk'), 'already_exists' => true]);
            }
        }
        
        // Добавляем новый элемент в формате XStore: {"id":"123"}
        $new_item = json_encode(['id' => (string) $product_id]);
        $items[] = $new_item;
        
        update_user_meta($user_id, 'xstore_wishlist_ids_0', implode('|', $items));
        
        // Обновляем мета для XStore wishlist count
        update_user_meta($user_id, 'xstore_wishlist_u', time());
        
        wp_send_json_success(['message' => __('Товар добавлен в избранное', 'mst-auth-lk')]);
    }
    
    /**
     * AJAX: Удалить товар из wishlist (XStore format)
     * Called from shop-grid.js
     */
    public function ajax_remove_wishlist() {
        // Проверяем nonce - используем mst_shop_grid_nonce из shop-grid.php
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'mst_shop_grid_nonce')) {
            wp_send_json_error(['message' => __('Ошибка безопасности', 'mst-auth-lk')]);
        }
        
        if (!is_user_logged_in()) {
            wp_send_json_error(['message' => __('Необходима авторизация', 'mst-auth-lk')]);
        }
        
        $product_id = intval($_POST['product_id'] ?? 0);
        $user_id = get_current_user_id();
        
        if (!$product_id) {
            wp_send_json_error(['message' => __('Некорректный product_id', 'mst-auth-lk')]);
        }
        
        $wishlist_data = get_user_meta($user_id, 'xstore_wishlist_ids_0', true);
        
        if (!$wishlist_data) {
            wp_send_json_success(['message' => __('Wishlist пуст', 'mst-auth-lk')]);
        }
        
        $items = explode('|', $wishlist_data);
        $new_items = [];
        
        foreach ($items as $item) {
            $decoded = json_decode($item, true);
            if ($decoded && isset($decoded['id']) && intval($decoded['id']) !== $product_id) {
                $new_items[] = $item;
            }
        }
        
        if (empty($new_items)) {
            delete_user_meta($user_id, 'xstore_wishlist_ids_0');
            delete_user_meta($user_id, 'xstore_wishlist_u');
        } else {
            update_user_meta($user_id, 'xstore_wishlist_ids_0', implode('|', $new_items));
            update_user_meta($user_id, 'xstore_wishlist_u', time());
        }
        
        wp_send_json_success(['message' => __('Товар удален из избранного', 'mst-auth-lk')]);
    }
    
    /**
     * AJAX: Проверить статус wishlist для списка product_ids
     * Called from shop-grid.js on page load
     */
    public function ajax_check_wishlist() {
        // Проверяем nonce - используем mst_shop_grid_nonce из shop-grid.php
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'mst_shop_grid_nonce')) {
            wp_send_json_error(['message' => __('Ошибка безопасности', 'mst-auth-lk')]);
        }
        
        if (!is_user_logged_in()) {
            wp_send_json_success([]);
        }
        
        $product_ids = isset($_POST['product_ids']) ? (array) $_POST['product_ids'] : [];
        $user_id = get_current_user_id();
        
        $wishlist_data = get_user_meta($user_id, 'xstore_wishlist_ids_0', true);
        
        if (!$wishlist_data) {
            wp_send_json_success([]);
        }
        
        $items = explode('|', $wishlist_data);
        $in_wishlist = [];
        
        foreach ($items as $item) {
            $decoded = json_decode($item, true);
            if ($decoded && isset($decoded['id'])) {
                $item_id = intval($decoded['id']);
                if (in_array($item_id, array_map('intval', $product_ids))) {
                    $in_wishlist[] = $item_id;
                }
            }
        }
        
        wp_send_json_success($in_wishlist);
    }
    
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
        
        if (!$guide_id) {
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
    
    public function ajax_download_gift() {
        check_ajax_referer('mst_auth_lk_nonce', 'nonce');
        
        $order_id = intval($_POST['order_id'] ?? 0);
        $order = wc_get_order($order_id);
        
        if (!$order || $order->get_customer_id() != get_current_user_id()) {
            wp_send_json_error(__('Заказ не найден', 'mst-auth-lk'));
        }
        
        foreach ($order->get_items() as $item) {
            $gift_id = get_post_meta($item->get_product_id(), '_mst_gift_file', true);
            if ($gift_id) {
                $gift_url = wp_get_attachment_url($gift_id);
                if ($gift_url) {
                    wp_send_json_success(['url' => $gift_url, 'filename' => basename($gift_url)]);
                }
            }
        }
        
        wp_send_json_error(__('Подарок не найден', 'mst-auth-lk'));
    }
    
    /**
     * AJAX: Сброс доверенных IP
     */
    public function ajax_clear_trusted_ips() {
        check_ajax_referer('mst_auth_lk_nonce', 'nonce');
        
        if (!is_user_logged_in()) {
            wp_send_json_error(__('Необходима авторизация', 'mst-auth-lk'));
        }
        
        delete_user_meta(get_current_user_id(), 'mst_trusted_ips');
        wp_send_json_success(['message' => __('Доверенные устройства сброшены', 'mst-auth-lk')]);
    }
    
    /**
     * AJAX: Загрузка дополнительных отзывов гида
     * FIXED: Использует mst_guide_id в commentmeta (как и начальная загрузка в guide-profile.php)
     */
    public function ajax_load_more_guide_reviews() {
        check_ajax_referer('mst_auth_lk_nonce', 'nonce');
        
        $guide_id = intval($_POST['guide_id'] ?? 0);
        $page = intval($_POST['page'] ?? 1);
        $per_page = intval($_POST['per_page'] ?? 9);
        
        if (!$guide_id) {
            wp_send_json_error(__('Гид не найден', 'mst-auth-lk'));
        }
        
        global $wpdb;
        $offset = ($page - 1) * $per_page;
        
        // Получаем РЕАЛЬНЫЕ отзывы для гида (через mst_guide_id в commentmeta - как и начальная загрузка)
        $reviews = $wpdb->get_results($wpdb->prepare(
            "SELECT c.*, 
                    cm.meta_value as rating, 
                    cm2.meta_value as user_city, 
                    cm4.meta_value as review_photos,
                    cm5.meta_value as author_avatar_id
             FROM {$wpdb->comments} c
             JOIN {$wpdb->commentmeta} cm ON c.comment_ID = cm.comment_id AND cm.meta_key = 'rating'
             JOIN {$wpdb->commentmeta} cm3 ON c.comment_ID = cm3.comment_id AND cm3.meta_key = 'mst_guide_id' AND cm3.meta_value = %d
             LEFT JOIN {$wpdb->commentmeta} cm2 ON c.comment_ID = cm2.comment_id AND cm2.meta_key = 'mst_user_city'
             LEFT JOIN {$wpdb->commentmeta} cm4 ON c.comment_ID = cm4.comment_id AND cm4.meta_key = 'mst_review_photos'
             LEFT JOIN {$wpdb->commentmeta} cm5 ON c.comment_ID = cm5.comment_id AND cm5.meta_key = 'mst_author_avatar_id'
             WHERE c.comment_approved = 1 AND c.comment_type = 'review'
             ORDER BY c.comment_date DESC
             LIMIT %d OFFSET %d",
            $guide_id,
            $per_page,
            $offset
        ));
        
        // Общее количество реальных отзывов
        $total_real = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(DISTINCT c.comment_ID)
             FROM {$wpdb->comments} c
             JOIN {$wpdb->commentmeta} cm3 ON c.comment_ID = cm3.comment_id AND cm3.meta_key = 'mst_guide_id' AND cm3.meta_value = %d
             WHERE c.comment_approved = 1 AND c.comment_type = 'review'",
            $guide_id
        ));
        
        // Получаем фейковые отзывы
        $fake_reviews = get_user_meta($guide_id, 'mst_guide_fake_reviews', true);
        if (!is_array($fake_reviews)) $fake_reviews = [];
        
        $total = intval($total_real) + count($fake_reviews);
        
        $html = '';
        $new_photos = [];
        $global_photo_index = ($page - 1) * $per_page * 3; // Примерный индекс для lightbox
        
        // Обрабатываем реальные отзывы
        foreach ($reviews as $r) {
            $product_id = $r->comment_post_ID;
            $product = wc_get_product($product_id);
            $tour_title = $product ? $product->get_name() : '';
            
            // Получаем город
            $review_city = '';
            if ($product) {
                $pa_city = $product->get_attribute('pa_city');
                if (!empty($pa_city)) {
                    $review_city = $pa_city;
                }
            }
            if (empty($review_city) && !empty($r->user_city)) {
                $review_city = $r->user_city;
            }
            
            // Получаем аватар автора
            $author_avatar_url = '';
            if (!empty($r->author_avatar_id)) {
                $author_avatar_url = wp_get_attachment_image_url($r->author_avatar_id, 'thumbnail');
            }
            if (empty($author_avatar_url) && $r->user_id) {
                $user_avatar_id = get_user_meta($r->user_id, 'mst_lk_avatar', true);
                if ($user_avatar_id) {
                    $author_avatar_url = wp_get_attachment_image_url($user_avatar_id, 'thumbnail');
                }
            }
            if (empty($author_avatar_url)) {
                $author_avatar_url = get_avatar_url($r->comment_author_email, ['size' => 80]);
            }
            
            $photos = !empty($r->review_photos) ? maybe_unserialize($r->review_photos) : [];
            $rating = intval($r->rating);
            $date_formatted = date_i18n('d F Y', strtotime($r->comment_date));
            $initials = mb_strtoupper(mb_substr($r->comment_author, 0, 2));
            
            // Добавляем фото для лайтбокса
            foreach ($photos as $photo_id) {
                $photo_url = is_numeric($photo_id) ? wp_get_attachment_image_url($photo_id, 'large') : $photo_id;
                if ($photo_url) {
                    $new_photos[] = [
                        'url' => $photo_url,
                        'author' => $r->comment_author,
                        'avatar' => $author_avatar_url,
                        'rating' => $rating,
                        'date' => $date_formatted,
                        'city' => $review_city,
                        'tour' => $tour_title,
                        'text' => wp_trim_words($r->comment_content, 80)
                    ];
                }
            }
            
            // Генерируем HTML карточки отзыва (точно как в guide-profile.php)
            $html .= '<div class="mst-guide-testimonial-card">';
            $html .= '<div class="mst-guide-testimonial-header">';
            $html .= '<div class="mst-guide-testimonial-author">';
            $html .= '<div class="mst-guide-testimonial-avatar">';
            if (!empty($author_avatar_url)) {
                $html .= '<img src="' . esc_url($author_avatar_url) . '" alt="">';
            } else {
                $html .= esc_html($initials);
            }
            $html .= '</div>';
            $html .= '<div class="mst-guide-testimonial-author-info">';
            $html .= '<span class="mst-guide-testimonial-name">' . esc_html($r->comment_author) . '</span>';
            $html .= '<span class="mst-guide-testimonial-date">' . esc_html($date_formatted) . '</span>';
            if ($review_city) {
                $html .= '<span class="mst-guide-testimonial-meta">' . esc_html($review_city) . '</span>';
            }
            $html .= '</div>';
            $html .= '</div>';
            $html .= '<div class="mst-guide-testimonial-rating">';
            for ($i = 0; $i < 5; $i++) {
                $html .= '<span class="star">' . ($i < $rating ? '★' : '☆') . '</span>';
            }
            $html .= '</div>';
            $html .= '</div>';
            
            if ($tour_title) {
                $html .= '<div class="mst-guide-testimonial-tour-title">' . esc_html($tour_title) . '</div>';
            }
            
            $html .= '<p class="mst-guide-testimonial-text">' . esc_html($r->comment_content) . '</p>';
            
            if (!empty($photos)) {
                $html .= '<div class="mst-guide-testimonial-photos">';
                $shown_photos = array_slice($photos, 0, 3);
                $extra_count = count($photos) - 3;
                foreach ($shown_photos as $photo_id) {
                    $photo_url = is_numeric($photo_id) ? wp_get_attachment_image_url($photo_id, 'thumbnail') : $photo_id;
                    if ($photo_url) {
                        $html .= '<div class="mst-guide-testimonial-photo" data-lightbox-index="' . $global_photo_index . '">';
                        $html .= '<img src="' . esc_url($photo_url) . '" alt="">';
                        $html .= '</div>';
                        $global_photo_index++;
                    }
                }
                if ($extra_count > 0) {
                    $html .= '<div class="mst-guide-testimonial-photo-more" data-lightbox-index="' . ($global_photo_index - 1) . '">+' . $extra_count . '</div>';
                }
                $html .= '</div>';
            }
            $html .= '</div>';
        }
        
        // Если реальных отзывов меньше чем per_page, добавляем фейковые
        $reviews_shown = count($reviews);
        if ($reviews_shown < $per_page && !empty($fake_reviews)) {
            $fake_offset = max(0, $offset - intval($total_real));
            $fake_needed = $per_page - $reviews_shown;
            $fake_to_add = array_slice($fake_reviews, $fake_offset, $fake_needed);
            
            foreach ($fake_to_add as $fake) {
                $fake_avatar_url = '';
                if (!empty($fake['author_avatar_id'])) {
                    $fake_avatar_url = wp_get_attachment_image_url($fake['author_avatar_id'], 'thumbnail');
                }
                $fake_photos = [];
                if (!empty($fake['photos']) && is_array($fake['photos'])) {
                    $fake_photos = $fake['photos'];
                    foreach ($fake_photos as $photo_id) {
                        $photo_url = is_numeric($photo_id) ? wp_get_attachment_image_url($photo_id, 'large') : $photo_id;
                        if ($photo_url) {
                            $new_photos[] = [
                                'url' => $photo_url,
                                'author' => $fake['author_name'] ?? 'Гость',
                                'avatar' => $fake_avatar_url,
                                'rating' => intval($fake['rating'] ?? 5),
                                'date' => $fake['date'] ?? date('d F Y'),
                                'city' => $fake['city'] ?? '',
                                'tour' => $fake['tour_title'] ?? '',
                                'text' => wp_trim_words($fake['text'] ?? '', 80)
                            ];
                        }
                    }
                }
                
                $fake_rating = intval($fake['rating'] ?? 5);
                $fake_date = $fake['date'] ?? date('d F Y');
                $fake_initials = mb_strtoupper(mb_substr($fake['author_name'] ?? 'Г', 0, 2));
                
                $html .= '<div class="mst-guide-testimonial-card">';
                $html .= '<div class="mst-guide-testimonial-header">';
                $html .= '<div class="mst-guide-testimonial-author">';
                $html .= '<div class="mst-guide-testimonial-avatar">';
                if (!empty($fake_avatar_url)) {
                    $html .= '<img src="' . esc_url($fake_avatar_url) . '" alt="">';
                } else {
                    $html .= esc_html($fake_initials);
                }
                $html .= '</div>';
                $html .= '<div class="mst-guide-testimonial-author-info">';
                $html .= '<span class="mst-guide-testimonial-name">' . esc_html($fake['author_name'] ?? 'Гость') . '</span>';
                $html .= '<span class="mst-guide-testimonial-date">' . esc_html($fake_date) . '</span>';
                if (!empty($fake['city'])) {
                    $html .= '<span class="mst-guide-testimonial-meta">' . esc_html($fake['city']) . '</span>';
                }
                $html .= '</div>';
                $html .= '</div>';
                $html .= '<div class="mst-guide-testimonial-rating">';
                for ($i = 0; $i < 5; $i++) {
                    $html .= '<span class="star">' . ($i < $fake_rating ? '★' : '☆') . '</span>';
                }
                $html .= '</div>';
                $html .= '</div>';
                
                if (!empty($fake['tour_title'])) {
                    $html .= '<div class="mst-guide-testimonial-tour-title">' . esc_html($fake['tour_title']) . '</div>';
                }
                
                $html .= '<p class="mst-guide-testimonial-text">' . esc_html($fake['text'] ?? '') . '</p>';
                
                if (!empty($fake_photos)) {
                    $html .= '<div class="mst-guide-testimonial-photos">';
                    $shown_photos = array_slice($fake_photos, 0, 3);
                    $extra_count = count($fake_photos) - 3;
                    foreach ($shown_photos as $photo_id) {
                        $photo_url = is_numeric($photo_id) ? wp_get_attachment_image_url($photo_id, 'thumbnail') : $photo_id;
                        if ($photo_url) {
                            $html .= '<div class="mst-guide-testimonial-photo" data-lightbox-index="' . $global_photo_index . '">';
                            $html .= '<img src="' . esc_url($photo_url) . '" alt="">';
                            $html .= '</div>';
                            $global_photo_index++;
                        }
                    }
                    if ($extra_count > 0) {
                        $html .= '<div class="mst-guide-testimonial-photo-more" data-lightbox-index="' . ($global_photo_index - 1) . '">+' . $extra_count . '</div>';
                    }
                    $html .= '</div>';
                }
                $html .= '</div>';
            }
        }
        
        $has_more = ($page * $per_page) < $total;
        
        wp_send_json_success([
            'html' => $html,
            'has_more' => $has_more,
            'photos' => $new_photos
        ]);
    }
    
    // ==================== ENDPOINTS ====================
    
    public function register_endpoints() {
        add_rewrite_endpoint('mst-profile', EP_ROOT | EP_PAGES);
        add_rewrite_rule('^gid/([0-9]+)/?$', 'index.php?mst_guide_page=1&mst_guide_id=$matches[1]', 'top');
        add_rewrite_rule('^guide/([0-9]+)/?$', 'index.php?mst_guide_page=1&mst_guide_id=$matches[1]', 'top');
        add_rewrite_tag('%guide_id%', '([0-9]+)');
    }
    
    public function add_query_vars($vars) {
        $vars[] = 'mst_guide_page';
        $vars[] = 'mst_guide_id';
        $vars[] = 'guide_id';
        return $vars;
    }
    
    public function handle_guide_template() {
        $guide_id = get_query_var('mst_guide_id') ?: get_query_var('guide_id');
        
        if (isset($_GET['guide_id']) || !$guide_id) {
            return;
        }
        
        $guide_id = intval($guide_id);
        $guide = get_userdata($guide_id);
        
        if (!$guide) {
            wp_redirect(home_url());
            exit;
        }
        
        global $wpdb;
        $page = $wpdb->get_var($wpdb->prepare(
            "SELECT ID FROM {$wpdb->posts} WHERE post_type = 'page' AND post_status = 'publish' AND post_content LIKE %s LIMIT 1",
            '%[mst_guide_profile%'
        ));
        
        if ($page) {
            wp_safe_redirect(add_query_arg('guide_id', $guide_id, get_permalink($page)));
            exit;
        }
        
        $_GET['guide_id'] = $guide_id;
        get_header();
        echo '<div style="max-width:1200px;margin:40px auto;padding:0 20px;">' . do_shortcode('[mst_guide_profile]') . '</div>';
        get_footer();
        exit;
    }
    
    /**
     * Обработчик OAuth редиректов
     */
    public function handle_oauth_redirect() {
        if (!isset($_GET['mst_oauth'])) {
            return;
        }
        
        $provider = sanitize_text_field($_GET['mst_oauth']);
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
    
    /**
     * Google OAuth
     */
    private function handle_google_oauth($settings, $redirect) {
        $client_id = $settings['google_client_id'] ?? '';
        $client_secret = $settings['google_client_secret'] ?? '';
        
        if (empty($client_id) || empty($client_secret)) {
            wp_redirect(add_query_arg('error', 'oauth_not_configured', home_url('/auth/')));
            exit;
        }
        
        $callback_url = home_url('/?mst_oauth_callback=google');
        
        // Если есть code - обрабатываем callback
        if (isset($_GET['mst_oauth_callback']) && $_GET['mst_oauth_callback'] === 'google' && isset($_GET['code'])) {
            $code = sanitize_text_field($_GET['code']);
            
            // Получаем токен
            $token_response = wp_remote_post('https://oauth2.googleapis.com/token', [
                'body' => [
                    'code' => $code,
                    'client_id' => $client_id,
                    'client_secret' => $client_secret,
                    'redirect_uri' => $callback_url,
                    'grant_type' => 'authorization_code'
                ]
            ]);
            
            if (is_wp_error($token_response)) {
                wp_redirect(add_query_arg('error', 'oauth_failed', home_url('/auth/')));
                exit;
            }
            
            $token_data = json_decode(wp_remote_retrieve_body($token_response), true);
            
            if (empty($token_data['access_token'])) {
                wp_redirect(add_query_arg('error', 'oauth_failed', home_url('/auth/')));
                exit;
            }
            
            // Получаем данные пользователя
            $user_response = wp_remote_get('https://www.googleapis.com/oauth2/v2/userinfo', [
                'headers' => ['Authorization' => 'Bearer ' . $token_data['access_token']]
            ]);
            
            $user_data = json_decode(wp_remote_retrieve_body($user_response), true);
            
            if (empty($user_data['email'])) {
                wp_redirect(add_query_arg('error', 'oauth_no_email', home_url('/auth/')));
                exit;
            }
            
            $this->process_oauth_user($user_data['email'], $user_data['name'] ?? '', 'google', $redirect);
            return;
        }
        
        // Редирект на Google
        $auth_url = 'https://accounts.google.com/o/oauth2/v2/auth?' . http_build_query([
            'client_id' => $client_id,
            'redirect_uri' => $callback_url,
            'response_type' => 'code',
            'scope' => 'email profile',
            'access_type' => 'offline',
            'state' => wp_create_nonce('mst_oauth_google')
        ]);
        
        wp_redirect($auth_url);
        exit;
    }
    
    /**
     * VK OAuth
     */
    private function handle_vk_oauth($settings, $redirect) {
        $client_id = $settings['vk_client_id'] ?? '';
        $client_secret = $settings['vk_client_secret'] ?? '';
        
        if (empty($client_id) || empty($client_secret)) {
            wp_redirect(add_query_arg('error', 'oauth_not_configured', home_url('/auth/')));
            exit;
        }
        
        $callback_url = home_url('/?mst_oauth_callback=vk');
        
        if (isset($_GET['mst_oauth_callback']) && $_GET['mst_oauth_callback'] === 'vk' && isset($_GET['code'])) {
            $code = sanitize_text_field($_GET['code']);
            
            $token_response = wp_remote_get('https://oauth.vk.com/access_token?' . http_build_query([
                'client_id' => $client_id,
                'client_secret' => $client_secret,
                'redirect_uri' => $callback_url,
                'code' => $code
            ]));
            
            $token_data = json_decode(wp_remote_retrieve_body($token_response), true);
            
            if (empty($token_data['access_token']) || empty($token_data['email'])) {
                wp_redirect(add_query_arg('error', 'oauth_failed', home_url('/auth/')));
                exit;
            }
            
            // Получаем имя пользователя
            $user_response = wp_remote_get('https://api.vk.com/method/users.get?' . http_build_query([
                'access_token' => $token_data['access_token'],
                'v' => '5.131',
                'fields' => 'first_name,last_name'
            ]));
            
            $user_data = json_decode(wp_remote_retrieve_body($user_response), true);
            $name = '';
            if (!empty($user_data['response'][0])) {
                $name = $user_data['response'][0]['first_name'] . ' ' . $user_data['response'][0]['last_name'];
            }
            
            $this->process_oauth_user($token_data['email'], $name, 'vk', $redirect);
            return;
        }
        
        $auth_url = 'https://oauth.vk.com/authorize?' . http_build_query([
            'client_id' => $client_id,
            'redirect_uri' => $callback_url,
            'display' => 'page',
            'scope' => 'email',
            'response_type' => 'code',
            'v' => '5.131'
        ]);
        
        wp_redirect($auth_url);
        exit;
    }
    
    /**
     * Yandex OAuth
     */
    private function handle_yandex_oauth($settings, $redirect) {
        $client_id = $settings['yandex_client_id'] ?? '';
        $client_secret = $settings['yandex_client_secret'] ?? '';
        
        if (empty($client_id) || empty($client_secret)) {
            wp_redirect(add_query_arg('error', 'oauth_not_configured', home_url('/auth/')));
            exit;
        }
        
        $callback_url = home_url('/?mst_oauth_callback=yandex');
        
        if (isset($_GET['mst_oauth_callback']) && $_GET['mst_oauth_callback'] === 'yandex' && isset($_GET['code'])) {
            $code = sanitize_text_field($_GET['code']);
            
            $token_response = wp_remote_post('https://oauth.yandex.ru/token', [
                'body' => [
                    'grant_type' => 'authorization_code',
                    'code' => $code,
                    'client_id' => $client_id,
                    'client_secret' => $client_secret
                ]
            ]);
            
            $token_data = json_decode(wp_remote_retrieve_body($token_response), true);
            
            if (empty($token_data['access_token'])) {
                wp_redirect(add_query_arg('error', 'oauth_failed', home_url('/auth/')));
                exit;
            }
            
            $user_response = wp_remote_get('https://login.yandex.ru/info', [
                'headers' => ['Authorization' => 'OAuth ' . $token_data['access_token']]
            ]);
            
            $user_data = json_decode(wp_remote_retrieve_body($user_response), true);
            
            if (empty($user_data['default_email'])) {
                wp_redirect(add_query_arg('error', 'oauth_no_email', home_url('/auth/')));
                exit;
            }
            
            $name = $user_data['display_name'] ?? ($user_data['real_name'] ?? '');
            $this->process_oauth_user($user_data['default_email'], $name, 'yandex', $redirect);
            return;
        }
        
        $auth_url = 'https://oauth.yandex.ru/authorize?' . http_build_query([
            'response_type' => 'code',
            'client_id' => $client_id
        ]);
        
        wp_redirect($auth_url);
        exit;
    }
    
    /**
     * Обработка OAuth пользователя - создание или вход
     */
    private function process_oauth_user($email, $name, $provider, $redirect) {
        $email = sanitize_email($email);
        $user = get_user_by('email', $email);
        
        if (!$user) {
            // Создаем нового пользователя
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
            
            // Отмечаем как OAuth пользователя
            update_user_meta($user_id, 'mst_oauth_provider', $provider);
        }
        
        // Сохраняем IP как доверенный (OAuth пользователи не требуют OTP)
        $this->save_trusted_ip($user->ID);
        
        // Авторизуем
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
            
            if (!$guide_id) continue;
            
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
    
    // ==================== GUIDE METABOX ====================
    
    public function add_guide_metabox() {
        add_meta_box('mst_product_guide', '👨‍🎓 ' . __('Гид экскурсии', 'mst-auth-lk'), [$this, 'render_guide_metabox'], 'product', 'side', 'default');
    }
    
    public function render_guide_metabox($post) {
        $guide_id = get_post_meta($post->ID, '_mst_guide_id', true);
        $guides = get_users(['meta_key' => 'mst_user_status', 'meta_value' => 'guide', 'orderby' => 'display_name', 'order' => 'ASC']);
        wp_nonce_field('mst_save_guide', 'mst_guide_nonce');
        ?>
        <select name="mst_guide_id" style="width:100%;padding:8px;">
            <option value="">-- <?php _e('Без гида', 'mst-auth-lk'); ?> --</option>
            <?php foreach ($guides as $guide): ?>
                <option value="<?php echo $guide->ID; ?>" <?php selected($guide_id, $guide->ID); ?>><?php echo esc_html($guide->display_name); ?></option>
            <?php endforeach; ?>
        </select>
        <?php
    }
    
    public function save_guide_meta($post_id) {
        if (!isset($_POST['mst_guide_nonce']) || !wp_verify_nonce($_POST['mst_guide_nonce'], 'mst_save_guide')) return;
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
        if (!current_user_can('edit_post', $post_id)) return;
        
        update_post_meta($post_id, '_mst_guide_id', intval($_POST['mst_guide_id'] ?? 0));
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
            // Показываем личный кабинет
            return $this->render_lk_content($atts);
        } else {
            // Показываем форму авторизации
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
