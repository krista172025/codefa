<?php
/**
 * MST Auth AJAX Handlers
 */

if (! defined('ABSPATH')) exit;

// Login handler
add_action('wp_ajax_nopriv_mst_auth_login', 'mst_auth_login_handler');
function mst_auth_login_handler() {
     if (! wp_verify_nonce($_POST['nonce'], 'mst_auth_nonce')) {
        wp_send_json_error('Ошибка безопасности');
    }
    
    check_ajax_referer('mst_auth_nonce', 'nonce');
    
    $email = sanitize_email($_POST['email']);
    $password = $_POST['password'];
    $redirect = !empty($_POST['redirect']) ? esc_url_raw($_POST['redirect']) : home_url('/auth/');
    
    if (empty($email) || empty($password)) {
        wp_send_json_error('Заполните все поля');
    }
    
    $user = wp_authenticate($email, $password);
    
    if (is_wp_error($user)) {
        wp_send_json_error('Неверный email или пароль');
    }
    
    wp_set_current_user($user->ID);
    wp_set_auth_cookie($user->ID, true);
    
    wp_send_json_success(['redirect' => $redirect, 'message' => 'Успешный вход']);
}

// Register handler  
add_action('wp_ajax_nopriv_mst_auth_register', 'mst_auth_register_handler');
add_action('wp_ajax_mst_auth_register', 'mst_auth_register_handler');
function mst_auth_register_handler() {
    if (!wp_verify_nonce($_POST['nonce'], 'mst_auth_nonce')) {
        wp_send_json_error('Ошибка безопасности');
    }
    check_ajax_referer('mst_auth_nonce', 'nonce');
    
    $display_name = sanitize_text_field($_POST['display_name']);
    $email = sanitize_email($_POST['email']);
    $password = $_POST['password'];
    $redirect = !empty($_POST['redirect']) ? esc_url_raw($_POST['redirect']) : home_url('/auth/');
    
    if (empty($display_name) || empty($email) || empty($password)) {
        wp_send_json_error('Заполните все поля');
    }
    
    if (!is_email($email)) {
        wp_send_json_error('Некорректный email');
    }
    
    if (email_exists($email)) {
        wp_send_json_error('Этот email уже зарегистрирован');
    }
    
    if (strlen($password) < 8) {
        wp_send_json_error('Пароль должен быть минимум 8 символов');
    }
    
    $user_id = wp_create_user($email, $password, $email);
    
    if (is_wp_error($user_id)) {
        wp_send_json_error($user_id->get_error_message());
    }
    
    wp_update_user([
        'ID' => $user_id,
        'display_name' => $display_name,
        'first_name' => $display_name
    ]);
    
    // Auto-login
    wp_set_current_user($user_id);
    wp_set_auth_cookie($user_id, true);
    
    wp_send_json_success(['redirect' => $redirect, 'message' => 'Регистрация успешна']);
}