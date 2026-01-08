<?php
/**
 * Admin Page Template - OAuth + OTP Settings + Logs + Guides Management + Fake Reviews
 * Author: Telegram @l1ghtsun
 */
if (!defined('ABSPATH')) exit;

// Handle form submission
if (isset($_POST['mst_oauth_save']) && wp_verify_nonce($_POST['mst_oauth_nonce'], 'mst_save_oauth_settings')) {

    // OAuth Settings
    $oauth_settings = [
        'google_client_id'     => sanitize_text_field($_POST['google_client_id'] ?? ''),
        'google_client_secret' => sanitize_text_field($_POST['google_client_secret'] ?? ''),
        'vk_client_id'         => sanitize_text_field($_POST['vk_client_id'] ?? ''),
        'vk_client_secret'     => sanitize_text_field($_POST['vk_client_secret'] ?? ''),
        'yandex_client_id'     => sanitize_text_field($_POST['yandex_client_id'] ?? ''),
        'yandex_client_secret' => sanitize_text_field($_POST['yandex_client_secret'] ?? ''),
    ];
    update_option('mst_oauth_settings', $oauth_settings);

    // OAuth Enabled
    $oauth_enabled = [
        'google' => !empty($_POST['google_enabled']),
        'vk'     => !empty($_POST['vk_enabled']),
        'yandex' => !empty($_POST['yandex_enabled']),
    ];
    update_option('mst_oauth_enabled', $oauth_enabled);

    // OTP Settings
    $otp_settings = [
        'enabled'         => !empty($_POST['otp_enabled']),
        'method'          => sanitize_text_field($_POST['otp_method'] ?? 'email'),
        'code_length'     => intval($_POST['otp_code_length'] ?? 6),
        'expiry_minutes'  => intval($_POST['otp_expiry_minutes'] ?? 10),
        'max_attempts'    => intval($_POST['otp_max_attempts'] ?? 5),
        'debug_mode'      => !empty($_POST['otp_debug_mode']),
        // SMS Provider Settings
        'sms_provider'    => sanitize_text_field($_POST['sms_provider'] ?? 'none'),
        'twilio_sid'      => sanitize_text_field($_POST['twilio_sid'] ?? ''),
        'twilio_token'    => sanitize_text_field($_POST['twilio_token'] ?? ''),
        'twilio_phone'    => sanitize_text_field($_POST['twilio_phone'] ?? ''),
        'smsru_api_key'   => sanitize_text_field($_POST['smsru_api_key'] ?? ''),
        'smsru_sender'    => sanitize_text_field($_POST['smsru_sender'] ?? ''),
    ];
    update_option('mst_otp_settings', $otp_settings);

    $success = true;
}

// Handle guide save
if (isset($_POST['mst_save_guide']) && wp_verify_nonce($_POST['mst_guide_nonce'], 'mst_save_guide_settings')) {
    $guide_user_id = intval($_POST['guide_user_id'] ?? 0);
    if ($guide_user_id) {
        update_user_meta($guide_user_id, 'mst_guide_city',              sanitize_text_field($_POST['guide_city'] ?? ''));
        update_user_meta($guide_user_id, 'mst_guide_bio',               sanitize_textarea_field($_POST['guide_bio'] ?? ''));
        update_user_meta($guide_user_id, 'mst_guide_languages',         sanitize_text_field($_POST['guide_languages'] ?? ''));
        update_user_meta($guide_user_id, 'mst_guide_specialization',    sanitize_text_field($_POST['guide_specialization'] ?? ''));
        update_user_meta($guide_user_id, 'mst_guide_experience_years',  intval($_POST['guide_experience_years'] ?? 0));
        update_user_meta($guide_user_id, 'mst_guide_tours_count',       intval($_POST['guide_tours_count'] ?? 0));
        update_user_meta($guide_user_id, 'mst_guide_rating',            sanitize_text_field($_POST['guide_rating'] ?? '5.0'));
        update_user_meta($guide_user_id, 'mst_guide_reviews_count',     intval($_POST['guide_reviews_count'] ?? 0));
        update_user_meta($guide_user_id, 'mst_guide_achievements',      sanitize_textarea_field($_POST['guide_achievements'] ?? ''));
        update_user_meta($guide_user_id, 'mst_guide_academic_title',    sanitize_text_field($_POST['guide_academic_title'] ?? ''));
        update_user_meta($guide_user_id, 'mst_guide_verified',          !empty($_POST['guide_verified']));
        update_user_meta($guide_user_id, 'mst_user_status',             sanitize_text_field($_POST['guide_status'] ?? 'guide'));

        $guide_saved = true;
    }
}

// Handle add new guide
if (isset($_POST['mst_add_guide']) && wp_verify_nonce($_POST['mst_guide_nonce'], 'mst_save_guide_settings')) {
    $user_id_to_add = intval($_POST['add_guide_user_id'] ?? 0);
    if ($user_id_to_add && get_userdata($user_id_to_add)) {
        update_user_meta($user_id_to_add, 'mst_user_status', 'guide');
        update_user_meta($user_id_to_add, 'mst_guide_verified', false);
        $guide_added = true;
    }
}

// Handle remove guide status
if (isset($_POST['mst_remove_guide']) && wp_verify_nonce($_POST['mst_guide_nonce'], 'mst_save_guide_settings')) {
    $guide_to_remove = intval($_POST['remove_guide_id'] ?? 0);
    if ($guide_to_remove) {
        delete_user_meta($guide_to_remove, 'mst_user_status');
        $guide_removed = true;
    }
}

// Handle log clear
if (isset($_POST['mst_clear_logs']) && wp_verify_nonce($_POST['mst_log_nonce'], 'mst_clear_otp_logs')) {
    delete_option('mst_otp_logs');
    $logs_cleared = true;
}

/**
 * ОБНОВЛЕНО: Fake reviews c загрузкой аватара и фото (из admin-pagenew.php)
 */
if (isset($_POST['mst_add_fake_review']) && wp_verify_nonce($_POST['mst_fake_review_nonce'], 'mst_save_fake_review')) {
    $guide_id = intval($_POST['fake_review_guide_id'] ?? 0);
    if ($guide_id) {
        // Upload author avatar
        $author_avatar_id = 0;
        if (!empty($_FILES['fake_review_avatar']['tmp_name'])) {
            require_once(ABSPATH . 'wp-admin/includes/file.php');
            require_once(ABSPATH . 'wp-admin/includes/image.php');
            require_once(ABSPATH . 'wp-admin/includes/media.php');

            $avatar_upload = wp_handle_upload($_FILES['fake_review_avatar'], ['test_form' => false]);
            if (!isset($avatar_upload['error'])) {
                $attachment = [
                    'post_mime_type' => $avatar_upload['type'],
                    'post_title'     => sanitize_file_name($_FILES['fake_review_avatar']['name']),
                    'post_status'    => 'inherit',
                ];
                $author_avatar_id = wp_insert_attachment($attachment, $avatar_upload['file']);
                if ($author_avatar_id) {
                    $attach_data = wp_generate_attachment_metadata($author_avatar_id, $avatar_upload['file']);
                    wp_update_attachment_metadata($author_avatar_id, $attach_data);
                }
            }
        }

        // Upload review photos (multiple)
        $review_photos = [];
        if (!empty($_FILES['fake_review_photos']['tmp_name'][0])) {
            require_once(ABSPATH . 'wp-admin/includes/file.php');
            require_once(ABSPATH . 'wp-admin/includes/image.php');
            require_once(ABSPATH . 'wp-admin/includes/media.php');

            foreach ($_FILES['fake_review_photos']['tmp_name'] as $key => $tmp_name) {
                if (!empty($tmp_name)) {
                    $file = [
                        'name'     => $_FILES['fake_review_photos']['name'][$key],
                        'type'     => $_FILES['fake_review_photos']['type'][$key],
                        'tmp_name' => $tmp_name,
                        'error'    => $_FILES['fake_review_photos']['error'][$key],
                        'size'     => $_FILES['fake_review_photos']['size'][$key],
                    ];
                    $photo_upload = wp_handle_upload($file, ['test_form' => false]);
                    if (!isset($photo_upload['error'])) {
                        $attachment = [
                            'post_mime_type' => $photo_upload['type'],
                            'post_title'     => sanitize_file_name($file['name']),
                            'post_status'    => 'inherit',
                        ];
                        $photo_id = wp_insert_attachment($attachment, $photo_upload['file']);
                        if ($photo_id) {
                            $attach_data = wp_generate_attachment_metadata($photo_id, $photo_upload['file']);
                            wp_update_attachment_metadata($photo_id, $attach_data);
                            $review_photos[] = $photo_id;
                        }
                    }
                }
            }
        }

        $fake_reviews   = get_user_meta($guide_id, 'mst_guide_fake_reviews', true) ?: [];
        $fake_reviews[] = [
            'id'               => uniqid('fr_'),
            'author_name'      => sanitize_text_field($_POST['fake_review_author'] ?? ''),
            'author_initials'  => sanitize_text_field($_POST['fake_review_initials'] ?? ''),
            'author_avatar_id' => $author_avatar_id,
            'rating'           => intval($_POST['fake_review_rating'] ?? 5),
            'city'             => sanitize_text_field($_POST['fake_review_city'] ?? ''),
            'tour_title'       => sanitize_text_field($_POST['fake_review_tour_title'] ?? ''),
            'text'             => sanitize_textarea_field($_POST['fake_review_text'] ?? ''),
            'date'             => sanitize_text_field($_POST['fake_review_date'] ?? date('d F Y')),
            'photos'           => $review_photos,
            'created_at'       => current_time('mysql'),
        ];
        update_user_meta($guide_id, 'mst_guide_fake_reviews', $fake_reviews);
        $fake_review_added = true;
    }
}

// Handle fake review delete (та же логика, что и была)
if (isset($_POST['mst_delete_fake_review']) && wp_verify_nonce($_POST['mst_fake_review_nonce'], 'mst_save_fake_review')) {
    $guide_id  = intval($_POST['delete_review_guide_id'] ?? 0);
    $review_id = sanitize_text_field($_POST['delete_review_id'] ?? '');
    if ($guide_id && $review_id) {
        $fake_reviews = get_user_meta($guide_id, 'mst_guide_fake_reviews', true) ?: [];
        $fake_reviews = array_filter($fake_reviews, function ($r) use ($review_id) {
            return ($r['id'] ?? '') !== $review_id;
        });
        update_user_meta($guide_id, 'mst_guide_fake_reviews', array_values($fake_reviews));
        $fake_review_deleted = true;
    }
}

$oauth_settings = get_option('mst_oauth_settings', []);
$oauth_enabled  = get_option('mst_oauth_enabled', ['google' => false, 'vk' => false, 'yandex' => false]);
$otp_settings   = get_option('mst_otp_settings', [
    'enabled'        => true,
    'method'         => 'email',
    'code_length'    => 6,
    'expiry_minutes' => 10,
    'max_attempts'   => 5,
    'debug_mode'     => false,
    'sms_provider'   => 'none',
]);
$otp_logs       = get_option('mst_otp_logs', []);

// Get guides
$guides = get_users([
    'meta_key'   => 'mst_user_status',
    'meta_value' => 'guide',
]);

// Active tab
$active_tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : 'oauth';

// Edit guide
$edit_guide_id = isset($_GET['edit_guide']) ? intval($_GET['edit_guide']) : 0;
$edit_guide    = $edit_guide_id ? get_userdata($edit_guide_id) : null;
?>

<div class="wrap mst-admin-wrap">
    <style>
        .mst-admin-wrap { max-width: 1100px; margin: 20px auto 20px 0; }
        .mst-admin-header { display: flex; align-items: center; gap: 16px; margin-bottom: 24px; }
        .mst-admin-header h1 { margin: 0; font-size: 28px; font-weight: 700; color: #1f2937; }
        .mst-admin-header .badge { background: #9952E0; color: #fff; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; }
        .mst-card { background: #fff; border-radius: 16px; box-shadow: 0 4px 24px rgba(0,0,0,0.06); padding: 24px; margin-bottom: 24px; }
        .mst-card-header { display: flex; align-items: center; gap: 12px; margin-bottom: 20px; padding-bottom: 16px; border-bottom: 1px solid #e5e7eb; }
        .mst-card-header h2 { margin: 0; font-size: 18px; font-weight: 600; color: #1f2937; }
        .mst-card-header .icon { width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; border-radius: 12px; font-size: 20px; }
        .mst-provider-section { display: flex; flex-direction: column; gap: 16px; padding: 20px; background: #f9fafb; border-radius: 12px; margin-bottom: 20px; }
        .mst-provider-section:last-child { margin-bottom: 0; }
        .mst-provider-header { display: flex; align-items: center; justify-content: space-between; }
        .mst-provider-info { display: flex; align-items: center; gap: 12px; }
        .mst-provider-icon { width: 48px; height: 48px; display: flex; align-items: center; justify-content: center; border-radius: 12px; background: #fff; box-shadow: 0 2px 8px rgba(0,0,0,0.08); }
        .mst-provider-icon svg { width: 28px; height: 28px; }
        .mst-provider-name { font-weight: 600; font-size: 16px; color: #1f2937; }
        .mst-provider-desc { font-size: 13px; color: #6b7280; }
        .mst-toggle { position: relative; display: inline-block; width: 52px; height: 28px; }
        .mst-toggle input { opacity: 0; width: 0; height: 0; }
        .mst-toggle-slider { position: absolute; cursor: pointer; inset: 0; background-color: #d1d5db; transition: .3s; border-radius: 28px; }
        .mst-toggle-slider:before { position: absolute; content: ""; height: 22px; width: 22px; left: 3px; bottom: 3px; background-color: white; transition: .3s; border-radius: 50%; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .mst-toggle input:checked + .mst-toggle-slider { background-color: #9952E0; }
        .mst-toggle input:checked + .mst-toggle-slider:before { transform: translateX(24px); }
        .mst-form-row { display: grid; grid-template-columns: repeat(2,1fr); gap: 16px; }
        .mst-form-group { display: flex; flex-direction: column; }
        .mst-form-group label { font-size: 14px; font-weight: 500; color: #374151; margin-bottom: 6px; }
        .mst-form-group input,
        .mst-form-group select,
        .mst-form-group textarea { padding: 12px 14px; border: 1px solid #e5e7eb; border-radius: 10px; font-size: 14px; transition: all .2s; }
        .mst-form-group input:focus,
        .mst-form-group select:focus,
        .mst-form-group textarea:focus { outline: none; border-color: #9952E0; box-shadow: 0 0 0 3px rgba(153,82,224,0.1); }
        .mst-form-group input:disabled { background: #f3f4f6; cursor: not-allowed; }
        .mst-form-group textarea { min-height: 100px; resize: vertical; }
        .mst-btn { display: inline-flex; align-items: center; gap: 8px; padding: 12px 24px; background: linear-gradient(135deg,#9952E0 0%,#7B3FC4 100%); color:#fff; border:none; border-radius:10px; font-size:15px; font-weight:600; cursor:pointer; transition:all .3s; text-decoration:none; }
        .mst-btn:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(153,82,224,0.3); color:#fff; }
        .mst-btn-secondary { background:#f3f4f6; color:#374151; }
        .mst-btn-secondary:hover { background:#e5e7eb; box-shadow:none; transform:none; color:#374151; }
        .mst-btn-danger { background: linear-gradient(135deg,#ef4444 0%,#dc2626 100%); }
        .mst-btn-danger:hover { box-shadow: 0 8px 24px rgba(239,68,68,0.3); }
        .mst-btn-sm { padding: 8px 16px; font-size: 13px; }
        .mst-notice { padding:16px; border-radius:10px; margin-bottom:20px; }
        .mst-notice-success { background:#d1fae5; color:#059669; border:1px solid #34d399; }
        .mst-help-text { font-size:12px; color:#6b7280; margin-top:4px; }
        .mst-callback-url { background:#f3f4f6; padding:10px 14px; border-radius:8px; font-family:monospace; font-size:13px; color:#374151; word-break:break-all; margin-top:8px; }
        .mst-tabs { display:flex; gap:8px; margin-bottom:24px; border-bottom:2px solid #e5e7eb; flex-wrap:wrap; }
        .mst-tab { padding:12px 20px; font-size:14px; font-weight:500; color:#6b7280; text-decoration:none; border-radius:8px 8px 0 0; transition:all .2s; margin-bottom:-2px; }
        .mst-tab:hover { color:#9952E0; background:#f9fafb; }
        .mst-tab.active { color:#9952E0; background:#fff; border-bottom:2px solid #9952E0; }
        .mst-log-table { width:100%; border-collapse:collapse; font-size:13px; }
        .mst-log-table th { background:#f9fafb; padding:12px; text-align:left; font-weight:600; color:#374151; border-bottom:1px solid #e5e7eb; }
        .mst-log-table td { padding:12px; border-bottom:1px solid #f3f4f6; }
        .mst-log-table tr:hover { background:#f9fafb; }
        .mst-log-status { padding:4px 10px; border-radius:20px; font-size:11px; font-weight:600; }
        .mst-log-status.success { background:#d1fae5; color:#059669; }
        .mst-log-status.failed { background:#fee2e2; color:#dc2626; }
        .mst-log-status.sent { background:#dbeafe; color:#2563eb; }
        .mst-log-status.expired { background:#fef3c7; color:#d97706; }
        .mst-empty-logs { text-align:center; padding:40px; color:#9ca3af; }
        .mst-code-display { font-family:monospace; background:#f3f4f6; padding:4px 8px; border-radius:4px; }

        /* Guides Table */
        .mst-guides-table { width:100%; border-collapse:collapse; }
        .mst-guides-table th { background:#f9fafb; padding:14px; text-align:left; font-weight:600; color:#374151; border-bottom:1px solid #e5e7eb; }
        .mst-guides-table td { padding:14px; border-bottom:1px solid #f3f4f6; vertical-align:middle; }
        .mst-guides-table tr:hover { background:#f9fafb; }
        .mst-guide-row-avatar { width:48px; height:48px; border-radius:50%; object-fit:cover; border:2px solid #9952E0; }
        .mst-guide-row-info { display:flex; flex-direction:column; }
        .mst-guide-row-name { font-weight:600; color:#1f2937; }
        .mst-guide-row-email { font-size:13px; color:#6b7280; }
        .mst-guide-badge { display:inline-flex; align-items:center; gap:4px; padding:4px 10px; border-radius:20px; font-size:12px; font-weight:500; }
        .mst-guide-badge.verified { background:#d1fae5; color:#059669; }
        .mst-guide-badge.pending { background:#fef3c7; color:#d97706; }
        .mst-guide-actions { display:flex; gap:8px; }
        .mst-add-guide-form { display:flex; gap:12px; align-items:flex-end; padding:20px; background:#f9fafb; border-radius:12px; margin-bottom:24px; }
        .mst-add-guide-form .mst-form-group { flex:1; margin:0; }

        /* Fake Reviews */
        .mst-fake-review-card { background:#f9fafb; border-radius:12px; padding:16px; margin-bottom:16px; }
        .mst-fake-review-header { display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:12px; }
        .mst-fake-review-author { display:flex; align-items:center; gap:12px; }
        .mst-fake-review-avatar { width:48px; height:48px; border-radius:50%; background:#9952E0; color:#fff; display:flex; align-items:center; justify-content:center; font-weight:600; font-size:14px; overflow:hidden; flex-shrink:0; }
        .mst-fake-review-avatar img { width:100%; height:100%; object-fit:cover; }
        .mst-fake-review-meta { font-size:13px; color:#6b7280; }
        .mst-fake-review-stars { color:#fbbf24; }
        .mst-fake-review-text { font-size:14px; line-height:1.6; color:#374151; }
        .mst-fake-review-photos { display:flex; gap:8px; margin:12px 0; flex-wrap:wrap; }
        .mst-fake-review-photo { width:60px; height:60px; border-radius:8px; overflow:hidden; }
        .mst-fake-review-photo img { width:100%; height:100%; object-fit:cover; }
        .mst-fake-review-footer { display:flex; justify-content:space-between; align-items:center; margin-top:12px; padding-top:12px; border-top:1px solid #e5e7eb; }

        /* File Upload Styling */
        .mst-file-upload-wrapper { display:flex; flex-direction:column; gap:8px; }
        .mst-file-upload-label { display:flex; align-items:center; justify-content:center; gap:8px; padding:16px; border:2px dashed #d1d5db; border-radius:10px; cursor:pointer; transition:all .2s; color:#6b7280; }
        .mst-file-upload-label:hover { border-color:#9952E0; color:#9952E0; background:rgba(153,82,224,0.05); }
        .mst-file-preview { display:flex; gap:8px; flex-wrap:wrap; margin-top:8px; }
        .mst-file-preview-item { position:relative; width:60px; height:60px; border-radius:8px; overflow:hidden; }
        .mst-file-preview-item img { width:100%; height:100%; object-fit:cover; }

        @media (max-width:768px) {
            .mst-form-row { grid-template-columns:1fr; }
            .mst-tabs { flex-wrap:wrap; }
            .mst-add-guide-form { flex-direction:column; }
        }
    </style>

    <div class="mst-admin-header">
        <h1>👤 Auth + ЛК Settings</h1>
        <span class="badge">v<?php echo defined('MST_AUTH_LK_VERSION') ? MST_AUTH_LK_VERSION : '4.0.0'; ?></span>
    </div>

    <?php if (!empty($success)): ?>
        <div class="mst-notice mst-notice-success">✅ Настройки успешно сохранены!</div>
    <?php endif; ?>

    <?php if (!empty($logs_cleared)): ?>
        <div class="mst-notice mst-notice-success">🗑️ Логи успешно очищены!</div>
    <?php endif; ?>

    <?php if (!empty($guide_saved)): ?>
        <div class="mst-notice mst-notice-success">✅ Профиль гида успешно обновлен!</div>
    <?php endif; ?>

    <?php if (!empty($guide_added)): ?>
        <div class="mst-notice mst-notice-success">✅ Пользователь добавлен как гид!</div>
    <?php endif; ?>

    <?php if (!empty($guide_removed)): ?>
        <div class="mst-notice mst-notice-success">🗑️ Статус гида удален!</div>
    <?php endif; ?>

    <?php if (!empty($fake_review_added)): ?>
        <div class="mst-notice mst-notice-success">✅ Фейк-отзыв успешно добавлен!</div>
    <?php endif; ?>

    <?php if (!empty($fake_review_deleted)): ?>
        <div class="mst-notice mst-notice-success">🗑️ Фейк-отзыв удален!</div>
    <?php endif; ?>

    <!-- Tabs -->
    <div class="mst-tabs">
        <a href="?page=mst-auth-lk-settings&tab=oauth" class="mst-tab <?php echo $active_tab === 'oauth' ? 'active' : ''; ?>">🔐 OAuth</a>
        <a href="?page=mst-auth-lk-settings&tab=otp" class="mst-tab <?php echo $active_tab === 'otp' ? 'active' : ''; ?>">📱 OTP</a>
        <a href="?page=mst-auth-lk-settings&tab=guides" class="mst-tab <?php echo $active_tab === 'guides' ? 'active' : ''; ?>">👥 Гиды (<?php echo count($guides); ?>)</a>
        <a href="?page=mst-auth-lk-settings&tab=fake_reviews" class="mst-tab <?php echo $active_tab === 'fake_reviews' ? 'active' : ''; ?>">⭐ Фейк-отзывы</a>
        <a href="?page=mst-auth-lk-settings&tab=logs" class="mst-tab <?php echo $active_tab === 'logs' ? 'active' : ''; ?>">📋 Логи (<?php echo count($otp_logs); ?>)</a>
    </div>

    <?php if ($active_tab === 'oauth'): ?>
        <!-- OAuth Settings (3 провайдера, как в старом файле) -->
        <form method="post">
            <?php wp_nonce_field('mst_save_oauth_settings', 'mst_oauth_nonce'); ?>

            <div class="mst-card">
                <div class="mst-card-header">
                    <div class="icon" style="background:#f0e6fa;">🔐</div>
                    <h2>OAuth Providers</h2>
                </div>

                <!-- Google -->
                <div class="mst-provider-section">
                    <div class="mst-provider-header">
                        <div class="mst-provider-info">
                            <div class="mst-provider-icon">
                                <svg viewBox="0 0 24 24">
                                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                                </svg>
                            </div>
                            <div>
                                <div class="mst-provider-name">Google</div>
                                <div class="mst-provider-desc">Sign in with Google account</div>
                            </div>
                        </div>
                        <label class="mst-toggle">
                            <input type="checkbox" name="google_enabled" value="1" <?php checked(!empty($oauth_enabled['google'])); ?>>
                            <span class="mst-toggle-slider"></span>
                        </label>
                    </div>

                    <div class="mst-form-row">
                        <div class="mst-form-group">
                            <label>Client ID</label>
                            <input type="text" name="google_client_id"
                                   value="<?php echo esc_attr($oauth_settings['google_client_id'] ?? ''); ?>"
                                   placeholder="xxx.apps.googleusercontent.com">
                        </div>
                        <div class="mst-form-group">
                            <label>Client Secret</label>
                            <input type="password" name="google_client_secret"
                                   value="<?php echo esc_attr($oauth_settings['google_client_secret'] ?? ''); ?>"
                                   placeholder="GOCSPX-xxxxxxxx">
                        </div>
                    </div>

                    <div>
                        <strong style="font-size:13px;">Callback URL:</strong>
                        <div class="mst-callback-url"><?php echo home_url('/?mst_oauth_callback=google'); ?></div>
                    </div>
                </div>

                <!-- VK -->
                <div class="mst-provider-section">
                    <div class="mst-provider-header">
                        <div class="mst-provider-info">
                            <div class="mst-provider-icon">
                                <svg viewBox="0 0 24 24" fill="#4C75A3">
                                    <path d="M15.684 0H8.316C1.592 0 0 1.592 0 8.316v7.368C0 22.408 1.592 24 8.316 24h7.368C22.408 24 24 22.408 24 15.684V8.316C24 1.592 22.408 0 15.684 0zm3.692 17.123h-1.744c-.66 0-.864-.523-2.052-1.727-1.032-1.008-1.488-1.143-1.744-1.143-.36 0-.456.096-.456.552v1.584c0 .396-.12.624-1.128.624-1.668 0-3.516-.996-4.812-2.868-1.956-2.76-2.496-4.836-2.496-5.268 0-.252.096-.492.552-.492h1.752c.408 0 .564.18.72.612.792 2.292 2.112 4.296 2.652 4.296.204 0 .3-.096.3-.612V9.123c-.072-1.2-.696-1.296-.696-1.728 0-.18.144-.36.384-.36h2.748c.324 0 .444.18.444.576v3.108c0 .324.144.444.24.444.204 0 .372-.12.744-.492 1.152-1.284 1.968-3.264 1.968-3.264.108-.228.3-.444.696-.444h1.752c.528 0 .648.276.528.66-.216 1.02-2.352 3.99-2.352 3.99-.18.3-.252.432 0 .768.18.252.792.768 1.2 1.236.756.864 1.32 1.584 1.476 2.076.156.48-.084.732-.564.732z"/>
                                </svg>
                            </div>
                            <div>
                                <div class="mst-provider-name">VK (ВКонтакте)</div>
                                <div class="mst-provider-desc">Вход через VK ID</div>
                            </div>
                        </div>
                        <label class="mst-toggle">
                            <input type="checkbox" name="vk_enabled" value="1" <?php checked(!empty($oauth_enabled['vk'])); ?>>
                            <span class="mst-toggle-slider"></span>
                        </label>
                    </div>

                    <div class="mst-form-row">
                        <div class="mst-form-group">
                            <label>App ID</label>
                            <input type="text" name="vk_client_id"
                                   value="<?php echo esc_attr($oauth_settings['vk_client_id'] ?? ''); ?>">
                        </div>
                        <div class="mst-form-group">
                            <label>Secure Key</label>
                            <input type="password" name="vk_client_secret"
                                   value="<?php echo esc_attr($oauth_settings['vk_client_secret'] ?? ''); ?>">
                        </div>
                    </div>

                    <div>
                        <strong style="font-size:13px;">Callback URL:</strong>
                        <div class="mst-callback-url"><?php echo home_url('/?mst_oauth_callback=vk'); ?></div>
                    </div>
                </div>

                <!-- Yandex -->
                <div class="mst-provider-section">
                    <div class="mst-provider-header">
                        <div class="mst-provider-info">
                            <div class="mst-provider-icon">
                                <svg viewBox="0 0 24 24">
                                    <path fill="#FC3F1D" d="M2.04 12c0-5.523 4.476-10 10-10 5.522 0 10 4.477 10 10s-4.478 10-10 10c-5.524 0-10-4.477-10-10zm6.627 6.325h2.008V6.625h-1.26c-2.334 0-3.566 1.256-3.566 3.067 0 1.515.602 2.402 1.848 3.319l-2.091 5.314h2.187l1.975-4.924.547.367c1.066.712 1.54 1.283 1.54 2.528v2.029h-.001zm-.16-8.437c0-1.022.596-1.643 1.52-1.643h.16v4.268l-.49-.327c-.802-.535-1.19-1.093-1.19-2.298z"/>
                                </svg>
                            </div>
                            <div>
                                <div class="mst-provider-name">Яндекс</div>
                                <div class="mst-provider-desc">Вход через Яндекс ID</div>
                            </div>
                        </div>
                        <label class="mst-toggle">
                            <input type="checkbox" name="yandex_enabled" value="1" <?php checked(!empty($oauth_enabled['yandex'])); ?>>
                            <span class="mst-toggle-slider"></span>
                        </label>
                    </div>

                    <div class="mst-form-row">
                        <div class="mst-form-group">
                            <label>Client ID</label>
                            <input type="text" name="yandex_client_id"
                                   value="<?php echo esc_attr($oauth_settings['yandex_client_id'] ?? ''); ?>">
                        </div>
                        <div class="mst-form-group">
                            <label>Client Secret</label>
                            <input type="password" name="yandex_client_secret"
                                   value="<?php echo esc_attr($oauth_settings['yandex_client_secret'] ?? ''); ?>">
                        </div>
                    </div>

                    <div>
                        <strong style="font-size:13px;">Callback URL:</strong>
                        <div class="mst-callback-url"><?php echo home_url('/?mst_oauth_callback=yandex'); ?></div>
                    </div>
                </div>
            </div>

            <button type="submit" name="mst_oauth_save" class="mst-btn">💾 Сохранить настройки</button>
        </form>

    <?php elseif ($active_tab === 'otp'): ?>
        <!-- OTP Settings -->
        <form method="post">
            <?php wp_nonce_field('mst_save_oauth_settings', 'mst_oauth_nonce'); ?>

            <div class="mst-card">
                <div class="mst-card-header">
                    <div class="icon" style="background:#dbeafe;">📱</div>
                    <h2>OTP Верификация</h2>
                </div>

                <!-- Main OTP Settings -->
                <div class="mst-provider-section">
                    <div class="mst-provider-header">
                        <div class="mst-provider-info">
                            <div class="mst-provider-icon" style="font-size:24px;">🔐</div>
                            <div>
                                <div class="mst-provider-name">Двухфакторная аутентификация</div>
                                <div class="mst-provider-desc">Требовать OTP код при входе с нового устройства</div>
                            </div>
                        </div>
                        <label class="mst-toggle">
                            <input type="checkbox" name="otp_enabled" value="1" <?php checked(!empty($otp_settings['enabled'])); ?>>
                            <span class="mst-toggle-slider"></span>
                        </label>
                    </div>

                    <div class="mst-form-row">
                        <div class="mst-form-group">
                            <label>Метод отправки OTP</label>
                            <select name="otp_method">
                                <option value="email" <?php selected($otp_settings['method'] ?? 'email', 'email'); ?>>📧 Email</option>
                                <option value="sms"   <?php selected($otp_settings['method'] ?? 'email', 'sms'); ?>>📱 SMS</option>
                                <option value="both"  <?php selected($otp_settings['method'] ?? 'email', 'both'); ?>>📧+📱 Email + SMS</option>
                            </select>
                        </div>
                        <div class="mst-form-group">
                            <label>Длина кода</label>
                            <select name="otp_code_length">
                                <option value="4" <?php selected($otp_settings['code_length'] ?? 6, 4); ?>>4 цифры</option>
                                <option value="6" <?php selected($otp_settings['code_length'] ?? 6, 6); ?>>6 цифр</option>
                                <option value="8" <?php selected($otp_settings['code_length'] ?? 6, 8); ?>>8 цифр</option>
                            </select>
                        </div>
                    </div>

                    <div class="mst-form-row">
                        <div class="mst-form-group">
                            <label>Срок действия кода (минуты)</label>
                            <input type="number" name="otp_expiry_minutes"
                                   value="<?php echo esc_attr($otp_settings['expiry_minutes'] ?? 10); ?>" min="1" max="60">
                        </div>
                        <div class="mst-form-group">
                            <label>Максимум попыток ввода</label>
                            <input type="number" name="otp_max_attempts"
                                   value="<?php echo esc_attr($otp_settings['max_attempts'] ?? 5); ?>" min="3" max="10">
                        </div>
                    </div>

                    <div class="mst-provider-header" style="margin-top:16px;">
                        <div class="mst-provider-info">
                            <div class="mst-provider-icon" style="font-size:24px;">🐛</div>
                            <div>
                                <div class="mst-provider-name">Debug режим</div>
                                <div class="mst-provider-desc">Показывать OTP код в логах (только для тестирования!)</div>
                            </div>
                        </div>
                        <label class="mst-toggle">
                            <input type="checkbox" name="otp_debug_mode" value="1" <?php checked(!empty($otp_settings['debug_mode'])); ?>>
                            <span class="mst-toggle-slider"></span>
                        </label>
                    </div>
                </div>

                <!-- SMS Provider Settings -->
                <div class="mst-provider-section">
                    <div class="mst-card-header" style="border:0; padding:0; margin-bottom:16px;">
                        <div class="icon" style="background:#fef3c7; font-size:16px;">📲</div>
                        <h2 style="font-size:16px;">SMS Провайдер</h2>
                    </div>

                    <div class="mst-form-group" style="margin-bottom:16px;">
                        <label>Провайдер SMS</label>
                        <select name="sms_provider" id="sms_provider" onchange="toggleSmsFields()">
                            <option value="none"   <?php selected($otp_settings['sms_provider'] ?? 'none', 'none'); ?>>Не выбран</option>
                            <option value="twilio" <?php selected($otp_settings['sms_provider'] ?? 'none', 'twilio'); ?>>Twilio</option>
                            <option value="smsru"  <?php selected($otp_settings['sms_provider'] ?? 'none', 'smsru'); ?>>SMS.ru</option>
                        </select>
                        <p class="mst-help-text">Для отправки SMS необходимо настроить провайдера</p>
                    </div>

                    <!-- Twilio Settings -->
                    <div id="twilio_fields" style="<?php echo ($otp_settings['sms_provider'] ?? 'none') !== 'twilio' ? 'display:none;' : ''; ?>">
                        <div class="mst-form-row">
                            <div class="mst-form-group">
                                <label>Account SID</label>
                                <input type="text" name="twilio_sid"
                                       value="<?php echo esc_attr($otp_settings['twilio_sid'] ?? ''); ?>" placeholder="ACxxxxxxxx">
                            </div>
                            <div class="mst-form-group">
                                <label>Auth Token</label>
                                <input type="password" name="twilio_token"
                                       value="<?php echo esc_attr($otp_settings['twilio_token'] ?? ''); ?>">
                            </div>
                        </div>
                        <div class="mst-form-group">
                            <label>Номер отправителя</label>
                            <input type="text" name="twilio_phone"
                                   value="<?php echo esc_attr($otp_settings['twilio_phone'] ?? ''); ?>" placeholder="+1234567890">
                            <p class="mst-help-text">Номер Twilio в формате +1234567890</p>
                        </div>
                    </div>

                    <!-- SMS.ru Settings -->
                    <div id="smsru_fields" style="<?php echo ($otp_settings['sms_provider'] ?? 'none') !== 'smsru' ? 'display:none;' : ''; ?>">
                        <div class="mst-form-row">
                            <div class="mst-form-group">
                                <label>API Key</label>
                                <input type="password" name="smsru_api_key"
                                       value="<?php echo esc_attr($otp_settings['smsru_api_key'] ?? ''); ?>">
                            </div>
                            <div class="mst-form-group">
                                <label>Имя отправителя</label>
                                <input type="text" name="smsru_sender"
                                       value="<?php echo esc_attr($otp_settings['smsru_sender'] ?? ''); ?>" placeholder="MySuperTour">
                                <p class="mst-help-text">Должно быть подтверждено в SMS.ru</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <button type="submit" name="mst_oauth_save" class="mst-btn">💾 Сохранить настройки</button>
        </form>

        <script>
            function toggleSmsFields() {
                var provider = document.getElementById('sms_provider').value;
                document.getElementById('twilio_fields').style.display = provider === 'twilio' ? 'block' : 'none';
                document.getElementById('smsru_fields').style.display  = provider === 'smsru'  ? 'block' : 'none';
            }
        </script>

    <?php elseif ($active_tab === 'guides'): ?>
        <!-- Guides Management -->
        <?php if ($edit_guide): ?>
            <!-- Edit Guide Form -->
            <div class="mst-card">
                <div class="mst-card-header">
                    <div class="icon" style="background:#f0e6fa;">✏️</div>
                    <h2><?php _e('Редактирование профиля гида', 'mst-auth-lk'); ?>: <?php echo esc_html($edit_guide->display_name); ?></h2>
                </div>

                <form method="post">
                    <?php wp_nonce_field('mst_save_guide_settings', 'mst_guide_nonce'); ?>
                    <input type="hidden" name="guide_user_id" value="<?php echo esc_attr($edit_guide_id); ?>">

                    <div class="mst-provider-section">
                        <div style="display:flex; align-items:center; gap:20px; margin-bottom:20px;">
                            <?php
                            $guide_avatar    = get_user_meta($edit_guide_id, 'mst_lk_avatar', true);
                            $guide_avatar_url = $guide_avatar ? wp_get_attachment_url($guide_avatar) : get_avatar_url($edit_guide_id, ['size' => 100]);
                            ?>
                            <img src="<?php echo esc_url($guide_avatar_url); ?>" alt="" class="mst-guide-row-avatar" style="width:80px; height:80px;">
                            <div>
                                <h3 style="margin:0 0 4px;"><?php echo esc_html($edit_guide->display_name); ?></h3>
                                <p style="margin:0; color:#6b7280; font-size:14px;"><?php echo esc_html($edit_guide->user_email); ?></p>
                                <p style="margin:4px 0 0; color:#9952E0; font-size:13px;">ID: <?php echo $edit_guide_id; ?></p>
                            </div>
                        </div>

                        <div class="mst-form-row">
                            <div class="mst-form-group">
                                <label><?php _e('Город', 'mst-auth-lk'); ?></label>
                                <input type="text" name="guide_city"
                                       value="<?php echo esc_attr(get_user_meta($edit_guide_id, 'mst_guide_city', true)); ?>"
                                       placeholder="Санкт-Петербург">
                            </div>
                            <div class="mst-form-group">
                                <label><?php _e('Опыт (лет)', 'mst-auth-lk'); ?></label>
                                <input type="number" name="guide_experience_years"
                                       value="<?php echo esc_attr(get_user_meta($edit_guide_id, 'mst_guide_experience_years', true) ?: 0); ?>"
                                       min="0" max="50">
                            </div>
                        </div>

                        <div class="mst-form-row">
                            <div class="mst-form-group">
                                <label><?php _e('Туров проведено', 'mst-auth-lk'); ?></label>
                                <input type="number" name="guide_tours_count"
                                       value="<?php echo esc_attr(get_user_meta($edit_guide_id, 'mst_guide_tours_count', true) ?: 0); ?>"
                                       min="0">
                            </div>
                            <div class="mst-form-group">
                                <label><?php _e('Рейтинг', 'mst-auth-lk'); ?></label>
                                <input type="text" name="guide_rating"
                                       value="<?php echo esc_attr(get_user_meta($edit_guide_id, 'mst_guide_rating', true) ?: '5.0'); ?>"
                                       placeholder="5.0">
                            </div>
                        </div>

                        <div class="mst-form-row">
                            <div class="mst-form-group">
                                <label><?php _e('Кол-во отзывов', 'mst-auth-lk'); ?></label>
                                <input type="number" name="guide_reviews_count"
                                       value="<?php echo esc_attr(get_user_meta($edit_guide_id, 'mst_guide_reviews_count', true) ?: 0); ?>"
                                       min="0">
                            </div>
                            <div class="mst-form-group">
                                <label><?php _e('Ученая степень', 'mst-auth-lk'); ?></label>
                                <input type="text" name="guide_academic_title"
                                       value="<?php echo esc_attr(get_user_meta($edit_guide_id, 'mst_guide_academic_title', true)); ?>"
                                       placeholder="к.и.н., PhD">
                            </div>
                        </div>

                        <div class="mst-form-group">
                            <label><?php _e('Языки', 'mst-auth-lk'); ?> <small style="color:#6b7280;">(через запятую)</small></label>
                            <input type="text" name="guide_languages"
                                   value="<?php echo esc_attr(get_user_meta($edit_guide_id, 'mst_guide_languages', true)); ?>"
                                   placeholder="Русский, Английский, Французский">
                        </div>

                        <div class="mst-form-group">
                            <label><?php _e('Специализация', 'mst-auth-lk'); ?> <small style="color:#6b7280;">(через запятую)</small></label>
                            <input type="text" name="guide_specialization"
                                   value="<?php echo esc_attr(get_user_meta($edit_guide_id, 'mst_guide_specialization', true)); ?>"
                                   placeholder="Исторические туры, Музеи, Архитектура">
                        </div>

                        <div class="mst-form-group">
                            <label><?php _e('О гиде (биография)', 'mst-auth-lk'); ?></label>
                            <textarea name="guide_bio" placeholder="Расскажите о себе..."><?php echo esc_textarea(get_user_meta($edit_guide_id, 'mst_guide_bio', true)); ?></textarea>
                        </div>

                        <div class="mst-form-group">
                            <label><?php _e('Достижения', 'mst-auth-lk'); ?> <small style="color:#6b7280;">(каждое с новой строки)</small></label>
                            <textarea name="guide_achievements" placeholder="Лучший гид 2023&#10;Сертифицированный историк&#10;500+ довольных туристов"><?php echo esc_textarea(get_user_meta($edit_guide_id, 'mst_guide_achievements', true)); ?></textarea>
                        </div>

                        <div class="mst-form-row">
                            <div class="mst-form-group">
                                <label><?php _e('Статус', 'mst-auth-lk'); ?></label>
                                <select name="guide_status">
                                    <option value="guide"  <?php selected(get_user_meta($edit_guide_id, 'mst_user_status', true), 'guide'); ?>><?php _e('Гид', 'mst-auth-lk'); ?></option>
                                    <option value="bronze" <?php selected(get_user_meta($edit_guide_id, 'mst_user_status', true), 'bronze'); ?>><?php _e('Бронза', 'mst-auth-lk'); ?></option>
                                    <option value="silver" <?php selected(get_user_meta($edit_guide_id, 'mst_user_status', true), 'silver'); ?>><?php _e('Серебро', 'mst-auth-lk'); ?></option>
                                    <option value="gold"   <?php selected(get_user_meta($edit_guide_id, 'mst_user_status', true), 'gold'); ?>><?php _e('Золото', 'mst-auth-lk'); ?></option>
                                </select>
                            </div>
                            <div class="mst-form-group">
                                <label style="display:flex; align-items:center; gap:8px; margin-top:28px;">
                                    <input type="checkbox" name="guide_verified" value="1" <?php checked(get_user_meta($edit_guide_id, 'mst_guide_verified', true)); ?>>
                                    ✅ <?php _e('Верифицированный гид', 'mst-auth-lk'); ?>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div style="display:flex; gap:12px;">
                        <button type="submit" name="mst_save_guide" class="mst-btn">💾 <?php _e('Сохранить', 'mst-auth-lk'); ?></button>
                        <a href="?page=mst-auth-lk-settings&tab=guides" class="mst-btn mst-btn-secondary">← <?php _e('Назад к списку', 'mst-auth-lk'); ?></a>
                    </div>
                </form>
            </div>
        <?php else: ?>
            <!-- Guides List -->
            <div class="mst-card">
                <div class="mst-card-header">
                    <div class="icon" style="background:#f0e6fa;">👥</div>
                    <h2><?php _e('Управление гидами', 'mst-auth-lk'); ?></h2>
                </div>

                <!-- Add New Guide -->
                <form method="post" class="mst-add-guide-form">
                    <?php wp_nonce_field('mst_save_guide_settings', 'mst_guide_nonce'); ?>
                    <div class="mst-form-group">
                        <label><?php _e('Добавить пользователя как гида', 'mst-auth-lk'); ?></label>
                        <select name="add_guide_user_id">
                            <option value=""><?php _e('Выберите пользователя...', 'mst-auth-lk'); ?></option>
                            <?php
                            $all_users = get_users(['exclude' => wp_list_pluck($guides, 'ID')]);
                            foreach ($all_users as $user): ?>
                                <option value="<?php echo $user->ID; ?>"><?php echo esc_html($user->display_name); ?> (<?php echo esc_html($user->user_email); ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" name="mst_add_guide" class="mst-btn mst-btn-sm">➕ <?php _e('Добавить гида', 'mst-auth-lk'); ?></button>
                </form>

                <?php if (empty($guides)): ?>
                    <div class="mst-empty-logs">
                        <div style="font-size:48px; margin-bottom:16px;">👥</div>
                        <p><?php _e('Гиды пока не добавлены', 'mst-auth-lk'); ?></p>
                    </div>
                <?php else: ?>
                    <table class="mst-guides-table">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th><?php _e('Гид', 'mst-auth-lk'); ?></th>
                            <th><?php _e('Город', 'mst-auth-lk'); ?></th>
                            <th><?php _e('Рейтинг', 'mst-auth-lk'); ?></th>
                            <th><?php _e('Туров', 'mst-auth-lk'); ?></th>
                            <th><?php _e('Статус', 'mst-auth-lk'); ?></th>
                            <th><?php _e('Действия', 'mst-auth-lk'); ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($guides as $g):
                            $g_avatar    = get_user_meta($g->ID, 'mst_lk_avatar', true);
                            $g_avatar_url = $g_avatar ? wp_get_attachment_url($g_avatar) : get_avatar_url($g->ID, ['size' => 48]);
                            $g_verified   = get_user_meta($g->ID, 'mst_guide_verified', true);
                            $g_city       = get_user_meta($g->ID, 'mst_guide_city', true) ?: '-';
                            $g_rating     = get_user_meta($g->ID, 'mst_guide_rating', true) ?: '5.0';
                            $g_tours      = get_user_meta($g->ID, 'mst_guide_tours_count', true) ?: '0';
                            ?>
                            <tr>
                                <td><code><?php echo $g->ID; ?></code></td>
                                <td>
                                    <div style="display:flex; align-items:center; gap:12px;">
                                        <img src="<?php echo esc_url($g_avatar_url); ?>" alt="" class="mst-guide-row-avatar">
                                        <div class="mst-guide-row-info">
                                            <span class="mst-guide-row-name"><?php echo esc_html($g->display_name); ?></span>
                                            <span class="mst-guide-row-email"><?php echo esc_html($g->user_email); ?></span>
                                        </div>
                                    </div>
                                </td>
                                <td><?php echo esc_html($g_city); ?></td>
                                <td>⭐ <?php echo esc_html($g_rating); ?></td>
                                <td><?php echo esc_html($g_tours); ?></td>
                                <td>
                                    <?php if ($g_verified): ?>
                                        <span class="mst-guide-badge verified">✅ <?php _e('Верифицирован', 'mst-auth-lk'); ?></span>
                                    <?php else: ?>
                                        <span class="mst-guide-badge pending">⏳ <?php _e('Не верифицирован', 'mst-auth-lk'); ?></span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="mst-guide-actions">
                                        <a href="?page=mst-auth-lk-settings&tab=guides&edit_guide=<?php echo $g->ID; ?>" class="mst-btn mst-btn-sm mst-btn-secondary">✏️ <?php _e('Изменить', 'mst-auth-lk'); ?></a>
                                        <a href="<?php echo add_query_arg('guide_id', $g->ID, home_url('/guide-profile/')); ?>" class="mst-btn mst-btn-sm mst-btn-secondary" target="_blank">👁️</a>
                                        <form method="post" style="display:inline;" onsubmit="return confirm('<?php _e('Удалить статус гида?', 'mst-auth-lk'); ?>');">
                                            <?php wp_nonce_field('mst_save_guide_settings', 'mst_guide_nonce'); ?>
                                            <input type="hidden" name="remove_guide_id" value="<?php echo $g->ID; ?>">
                                            <button type="submit" name="mst_remove_guide" class="mst-btn mst-btn-sm mst-btn-danger">🗑️</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        <?php endif; ?>

    <?php elseif ($active_tab === 'fake_reviews'): ?>
        <!-- Fake Reviews Management с загрузкой фото (обновлённый) -->
        <div class="mst-card">
            <div class="mst-card-header">
                <div class="icon" style="background:#fef3c7;">⭐</div>
                <h2><?php _e('Управление фейк-отзывами гидов', 'mst-auth-lk'); ?></h2>
            </div>

            <!-- Add New Fake Review -->
            <form method="post" enctype="multipart/form-data" class="mst-provider-section">
                <?php wp_nonce_field('mst_save_fake_review', 'mst_fake_review_nonce'); ?>

                <h3 style="margin:0 0 16px; font-size:16px; font-weight:600;">➕ <?php _e('Добавить новый отзыв', 'mst-auth-lk'); ?></h3>

                <div class="mst-form-row">
                    <div class="mst-form-group">
                        <label><?php _e('Выберите гида', 'mst-auth-lk'); ?> *</label>
                        <select name="fake_review_guide_id" required>
                            <option value=""><?php _e('Выберите гида...', 'mst-auth-lk'); ?></option>
                            <?php foreach ($guides as $g): ?>
                                <option value="<?php echo $g->ID; ?>"><?php echo esc_html($g->display_name); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mst-form-group">
                        <label><?php _e('Рейтинг', 'mst-auth-lk'); ?></label>
                        <select name="fake_review_rating">
                            <option value="5">⭐⭐⭐⭐⭐ (5)</option>
                            <option value="4">⭐⭐⭐⭐ (4)</option>
                            <option value="3">⭐⭐⭐ (3)</option>
                            <option value="2">⭐⭐ (2)</option>
                            <option value="1">⭐ (1)</option>
                        </select>
                    </div>
                </div>

                <div class="mst-form-row">
                    <div class="mst-form-group">
                        <label><?php _e('Имя автора', 'mst-auth-lk'); ?> *</label>
                        <input type="text" name="fake_review_author" placeholder="Анна С." required>
                    </div>
                    <div class="mst-form-group">
                        <label><?php _e('Инициалы', 'mst-auth-lk'); ?></label>
                        <input type="text" name="fake_review_initials" placeholder="АС" maxlength="3">
                    </div>
                </div>

                <!-- NEW: Author Avatar Upload -->
                <div class="mst-form-row">
                    <div class="mst-form-group">
                        <label><?php _e('Аватар автора', 'mst-auth-lk'); ?></label>
                        <div class="mst-file-upload-wrapper">
                            <label class="mst-file-upload-label" for="fake_review_avatar">
                                📷 <?php _e('Загрузить фото автора', 'mst-auth-lk'); ?>
                            </label>
                            <input type="file" name="fake_review_avatar" id="fake_review_avatar" accept="image/*" style="display:none;">
                            <div id="avatar-preview" class="mst-file-preview"></div>
                        </div>
                        <p class="mst-help-text"><?php _e('Если не загружено, будут показаны инициалы', 'mst-auth-lk'); ?></p>
                    </div>
                    <div class="mst-form-group">
                        <label><?php _e('Фотографии отзыва', 'mst-auth-lk'); ?></label>
                        <div class="mst-file-upload-wrapper">
                            <label class="mst-file-upload-label" for="fake_review_photos">
                                🖼️ <?php _e('Загрузить фото (до 5)', 'mst-auth-lk'); ?>
                            </label>
                            <input type="file" name="fake_review_photos[]" id="fake_review_photos" accept="image/*" multiple style="display:none;">
                            <div id="photos-preview" class="mst-file-preview"></div>
                        </div>
                    </div>
                </div>

                <div class="mst-form-row">
                    <div class="mst-form-group">
                        <label><?php _e('Город', 'mst-auth-lk'); ?></label>
                        <input type="text" name="fake_review_city" placeholder="Прага">
                    </div>
                    <div class="mst-form-group">
                        <label><?php _e('Название тура', 'mst-auth-lk'); ?></label>
                        <input type="text" name="fake_review_tour_title" placeholder="Историческая прогулка по старому городу">
                    </div>
                </div>

                <div class="mst-form-row">
                    <div class="mst-form-group">
                        <label><?php _e('Дата отзыва', 'mst-auth-lk'); ?></label>
                        <input type="text" name="fake_review_date" placeholder="15 ноября 2024" value="<?php echo date('d F Y'); ?>">
                    </div>
                </div>

                <div class="mst-form-group">
                    <label><?php _e('Текст отзыва', 'mst-auth-lk'); ?> *</label>
                    <textarea name="fake_review_text" placeholder="Спасибо за честность! Все было именно так, как обещали." required></textarea>
                </div>

                <button type="submit" name="mst_add_fake_review" class="mst-btn">➕ <?php _e('Добавить отзыв', 'mst-auth-lk'); ?></button>
            </form>

            <!-- Existing Fake Reviews by Guide -->
            <?php
            $has_any_reviews = false;
            foreach ($guides as $g):
                $fake_reviews = get_user_meta($g->ID, 'mst_guide_fake_reviews', true) ?: [];
                if (empty($fake_reviews)) {
                    continue;
                }
                $has_any_reviews = true;
                ?>
                <div style="margin-top:32px;">
                    <h3 style="margin:0 0 16px; font-size:16px; font-weight:600; display:flex; align-items:center; gap:8px;">
                        <?php
                        $g_avatar    = get_user_meta($g->ID, 'mst_lk_avatar', true);
                        $g_avatar_url = $g_avatar ? wp_get_attachment_url($g_avatar) : get_avatar_url($g->ID, ['size' => 32]);
                        ?>
                        <img src="<?php echo esc_url($g_avatar_url); ?>" alt="" style="width:32px; height:32px; border-radius:50%; object-fit:cover;">
                        <?php echo esc_html($g->display_name); ?>
                        <span style="font-weight:normal; color:#6b7280;">(<?php echo count($fake_reviews); ?> <?php _e('отзывов', 'mst-auth-lk'); ?>)</span>
                    </h3>

                    <?php foreach ($fake_reviews as $review):
                        $author_avatar_url = '';
                        if (!empty($review['author_avatar_id'])) {
                            $author_avatar_url = wp_get_attachment_image_url($review['author_avatar_id'], 'thumbnail');
                        }
                        ?>
                        <div class="mst-fake-review-card">
                            <div class="mst-fake-review-header">
                                <div class="mst-fake-review-author">
                                    <div class="mst-fake-review-avatar">
                                        <?php if ($author_avatar_url): ?>
                                            <img src="<?php echo esc_url($author_avatar_url); ?>" alt="">
                                        <?php else: ?>
                                            <?php echo esc_html($review['author_initials'] ?? mb_substr($review['author_name'] ?? 'U', 0, 2)); ?>
                                        <?php endif; ?>
                                    </div>
                                    <div>
                                        <strong><?php echo esc_html($review['author_name'] ?? __('Аноним', 'mst-auth-lk')); ?></strong>
                                        <div class="mst-fake-review-meta">
                                            <?php if (!empty($review['city'])): ?>
                                                <span style="color:#9952E0;"><?php echo esc_html($review['city']); ?></span> •
                                            <?php endif; ?>
                                            <?php echo esc_html($review['date'] ?? ''); ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="mst-fake-review-stars">
                                    <?php for ($i = 0; $i < intval($review['rating'] ?? 5); $i++): ?>★<?php endfor; ?>
                                </div>
                            </div>

                            <?php if (!empty($review['tour_title'])): ?>
                                <div style="font-weight:600; margin-bottom:8px;"><?php echo esc_html($review['tour_title']); ?></div>
                            <?php endif; ?>

                            <div class="mst-fake-review-text"><?php echo esc_html($review['text'] ?? ''); ?></div>

                            <!-- Display review photos -->
                            <?php if (!empty($review['photos']) && is_array($review['photos'])): ?>
                                <div class="mst-fake-review-photos">
                                    <?php foreach ($review['photos'] as $photo_id):
                                        $photo_url = wp_get_attachment_image_url($photo_id, 'thumbnail');
                                        if (!$photo_url) continue;
                                        ?>
                                        <div class="mst-fake-review-photo">
                                            <img src="<?php echo esc_url($photo_url); ?>" alt="">
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>

                            <div class="mst-fake-review-footer">
                                <small style="color:#9ca3af;">ID: <?php echo esc_html($review['id'] ?? ''); ?></small>
                                <form method="post" style="display:inline;" onsubmit="return confirm('<?php _e('Удалить этот отзыв?', 'mst-auth-lk'); ?>');">
                                    <?php wp_nonce_field('mst_save_fake_review', 'mst_fake_review_nonce'); ?>
                                    <input type="hidden" name="delete_review_guide_id" value="<?php echo $g->ID; ?>">
                                    <input type="hidden" name="delete_review_id" value="<?php echo esc_attr($review['id'] ?? ''); ?>">
                                    <button type="submit" name="mst_delete_fake_review" class="mst-btn mst-btn-sm mst-btn-danger">🗑️ <?php _e('Удалить', 'mst-auth-lk'); ?></button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>

            <?php if (!$has_any_reviews): ?>
                <div class="mst-empty-logs" style="margin-top:32px;">
                    <div style="font-size:48px; margin-bottom:16px;">⭐</div>
                    <p><?php _e('Фейк-отзывы пока не добавлены', 'mst-auth-lk'); ?></p>
                </div>
            <?php endif; ?>
        </div>

        <script>
            // Preview uploaded files (avatar + photos)
            document.addEventListener('DOMContentLoaded', function () {
                const avatarInput   = document.getElementById('fake_review_avatar');
                const avatarPreview = document.getElementById('avatar-preview');
                if (avatarInput) {
                    avatarInput.addEventListener('change', function () {
                        avatarPreview.innerHTML = '';
                        if (this.files[0]) {
                            const reader = new FileReader();
                            reader.onload = function (e) {
                                avatarPreview.innerHTML =
                                    '<div class="mst-file-preview-item"><img src="' + e.target.result + '" alt=""></div>';
                            };
                            reader.readAsDataURL(this.files[0]);
                        }
                    });
                }

                const photosInput   = document.getElementById('fake_review_photos');
                const photosPreview = document.getElementById('photos-preview');
                if (photosInput) {
                    photosInput.addEventListener('change', function () {
                        photosPreview.innerHTML = '';
                        const files = Array.from(this.files).slice(0, 5);
                        files.forEach(function (file) {
                            const reader = new FileReader();
                            reader.onload = function (e) {
                                const div = document.createElement('div');
                                div.className = 'mst-file-preview-item';
                                div.innerHTML = '<img src="' + e.target.result + '" alt="">';
                                photosPreview.appendChild(div);
                            };
                            reader.readAsDataURL(file);
                        });
                    });
                }
            });
        </script>

    <?php else: ?>
        <!-- OTP Logs -->
        <div class="mst-card">
            <div class="mst-card-header">
                <div class="icon" style="background:#fef3c7;">📋</div>
                <h2>Логи OTP верификации</h2>
            </div>

            <?php if (empty($otp_logs)): ?>
                <div class="mst-empty-logs">
                    <div style="font-size:48px; margin-bottom:16px;">📭</div>
                    <p>Логи пока пусты</p>
                </div>
            <?php else: ?>
                <table class="mst-log-table">
                    <thead>
                    <tr>
                        <th>Дата/Время</th>
                        <th>Пользователь</th>
                        <th>IP</th>
                        <th>Код</th>
                        <th>Метод</th>
                        <th>Статус</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $logs_reversed = array_reverse($otp_logs);
                    foreach (array_slice($logs_reversed, 0, 100) as $log): ?>
                        <tr>
                            <td><?php echo esc_html($log['date'] ?? '-'); ?></td>
                            <td>
                                <strong><?php echo esc_html($log['user_email'] ?? '-'); ?></strong>
                                <?php if (!empty($log['user_id'])): ?>
                                    <br><small style="color:#6b7280;">ID: <?php echo esc_html($log['user_id']); ?></small>
                                <?php endif; ?>
                            </td>
                            <td><code><?php echo esc_html($log['ip'] ?? '-'); ?></code></td>
                            <td>
                                <?php if (!empty($otp_settings['debug_mode']) && !empty($log['code'])): ?>
                                    <span class="mst-code-display"><?php echo esc_html($log['code']); ?></span>
                                <?php else: ?>
                                    <span style="color:#9ca3af;">******</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo esc_html($log['method'] ?? 'email'); ?></td>
                            <td>
                                <span class="mst-log-status <?php echo esc_attr($log['status'] ?? 'sent'); ?>">
                                    <?php
                                    $statuses = [
                                        'sent'    => '📤 Отправлен',
                                        'success' => '✅ Успешно',
                                        'failed'  => '❌ Неверный код',
                                        'expired' => '⏰ Истек',
                                    ];
                                    echo $statuses[$log['status'] ?? 'sent'] ?? $log['status'];
                                    ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>

                <div style="margin-top:24px; display:flex; justify-content:space-between; align-items:center;">
                    <p style="margin:0; color:#6b7280; font-size:13px;">
                        Показано последних 100 записей из <?php echo count($otp_logs); ?>
                    </p>
                    <form method="post" style="display:inline;">
                        <?php wp_nonce_field('mst_clear_otp_logs', 'mst_log_nonce'); ?>
                        <button type="submit" name="mst_clear_logs" class="mst-btn mst-btn-danger"
                                onclick="return confirm('Очистить все логи?')">🗑️ Очистить логи
                        </button>
                    </form>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>