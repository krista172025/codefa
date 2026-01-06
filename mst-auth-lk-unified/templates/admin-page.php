<?php
/**
 * Admin Page Template - OAuth + OTP + Guides + Profile Settings + Fake Reviews
 * UPDATED v4.1: Added Guide Profile Display Settings tab
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

// Handle profile settings save
if (isset($_POST['mst_save_profile_settings']) && wp_verify_nonce($_POST['mst_profile_settings_nonce'], 'mst_save_profile_settings')) {
    $profile_settings = [
        'card_width'       => intval($_POST['card_width'] ?? 380),
        'card_padding'     => intval($_POST['card_padding'] ?? 20),
        'avatar_size'      => intval($_POST['avatar_size'] ?? 60),
        'title_size'       => intval($_POST['title_size'] ?? 16),
        'text_size'        => intval($_POST['text_size'] ?? 14),
        'reviews_per_page' => intval($_POST['reviews_per_page'] ?? 9),
        'lightbox_image_width' => intval($_POST['lightbox_image_width'] ?? 70),
    ];
    update_option('mst_guide_profile_settings', $profile_settings);
    $profile_settings_saved = true;
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

// Handle fake reviews
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

        // Upload review photos
        $review_photos = [];
        if (!empty($_FILES['fake_review_photos']['tmp_name'][0])) {
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

// Handle fake review delete
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
$profile_settings = get_option('mst_guide_profile_settings', [
    'card_width'       => 380,
    'card_padding'     => 20,
    'avatar_size'      => 60,
    'title_size'       => 16,
    'text_size'        => 14,
    'reviews_per_page' => 9,
    'lightbox_image_width' => 70,
]);

// Get guides
$guides = get_users([
    'meta_key'   => 'mst_user_status',
    'meta_value' => 'guide',
]);

// Active tab
$active_tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : 'oauth';
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
        .mst-form-group input, .mst-form-group select, .mst-form-group textarea { padding: 12px 14px; border: 1px solid #e5e7eb; border-radius: 10px; font-size: 14px; transition: all .2s; }
        .mst-form-group input:focus, .mst-form-group select:focus, .mst-form-group textarea:focus { outline: none; border-color: #9952E0; box-shadow: 0 0 0 3px rgba(153,82,224,0.1); }
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
        .mst-tabs { display:flex; gap:8px; margin-bottom:24px; border-bottom:2px solid #e5e7eb; flex-wrap:wrap; }
        .mst-tab { padding:12px 20px; font-size:14px; font-weight:500; color:#6b7280; text-decoration:none; border-radius:8px 8px 0 0; transition:all .2s; margin-bottom:-2px; }
        .mst-tab:hover { color:#9952E0; background:#f9fafb; }
        .mst-tab.active { color:#9952E0; background:#fff; border-bottom:2px solid #9952E0; }
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
        .mst-fake-review-card { background:#f9fafb; border-radius:12px; padding:16px; margin-bottom:16px; }
        .mst-fake-review-header { display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:12px; }
        .mst-fake-review-author { display:flex; align-items:center; gap:12px; }
        .mst-fake-review-avatar { width:44px; height:44px; border-radius:50%; background:linear-gradient(135deg,#9952E0,#fbd603); display:flex; align-items:center; justify-content:center; color:#fff; font-weight:600; font-size:16px; overflow:hidden; }
        .mst-fake-review-avatar img { width:100%; height:100%; object-fit:cover; }
        .mst-fake-review-meta { font-size:13px; color:#6b7280; }
        .mst-fake-review-stars { color:#fbbf24; font-size:18px; }
        .mst-fake-review-text { color:#4b5563; line-height:1.6; font-size:14px; margin-bottom:12px; }
        .mst-fake-review-footer { display:flex; justify-content:space-between; align-items:center; padding-top:12px; border-top:1px solid #e5e7eb; }
        .mst-fake-review-photos { display:flex; gap:8px; flex-wrap:wrap; margin-top:12px; }
        .mst-fake-review-photo { width:60px; height:60px; border-radius:8px; overflow:hidden; }
        .mst-fake-review-photo img { width:100%; height:100%; object-fit:cover; }
        .mst-file-upload-wrapper { display:flex; flex-direction:column; gap:8px; }
        .mst-file-upload-label { display:inline-flex; align-items:center; gap:8px; padding:10px 16px; background:#fff; border:1.5px dashed #d1d5db; border-radius:10px; cursor:pointer; font-size:14px; color:#6b7280; transition:all .2s; }
        .mst-file-upload-label:hover { border-color:#9952E0; color:#9952E0; }
        .mst-file-preview { display:flex; gap:8px; flex-wrap:wrap; }
        .mst-file-preview-item { width:60px; height:60px; border-radius:8px; overflow:hidden; border:2px solid #e5e7eb; }
        .mst-file-preview-item img { width:100%; height:100%; object-fit:cover; }
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
        .mst-preview-box { background:#1a1a2e; border-radius:12px; padding:20px; margin-top:16px; }
        .mst-preview-card { background:linear-gradient(135deg,rgba(255,255,255,0.9),rgba(255,255,255,0.7)); border-radius:16px; padding:16px; display:flex; gap:12px; }
        .mst-preview-avatar { width:48px; height:48px; border-radius:50%; background:linear-gradient(135deg,#9952E0,#fbd603); }
        .mst-preview-content { flex:1; }
        .mst-preview-name { font-weight:600; color:#1a1a2e; margin-bottom:4px; }
        .mst-preview-text { color:#4b5563; font-size:14px; }
    </style>

    <div class="mst-admin-header">
        <h1>MySuperTour Auth + LK</h1>
        <span class="badge">v4.1</span>
    </div>

    <?php if (!empty($success) || !empty($guide_saved) || !empty($guide_added) || !empty($guide_removed) || !empty($logs_cleared) || !empty($fake_review_added) || !empty($fake_review_deleted) || !empty($profile_settings_saved)): ?>
        <div class="mst-notice mst-notice-success">✅ <?php _e('Настройки успешно сохранены!', 'mst-auth-lk'); ?></div>
    <?php endif; ?>

    <!-- TABS -->
    <div class="mst-tabs">
        <a href="?page=mst-auth-lk-settings&tab=oauth" class="mst-tab <?php echo $active_tab === 'oauth' ? 'active' : ''; ?>">🔐 OAuth + OTP</a>
        <a href="?page=mst-auth-lk-settings&tab=guides" class="mst-tab <?php echo $active_tab === 'guides' ? 'active' : ''; ?>">👥 <?php _e('Гиды', 'mst-auth-lk'); ?></a>
        <a href="?page=mst-auth-lk-settings&tab=profile_settings" class="mst-tab <?php echo $active_tab === 'profile_settings' ? 'active' : ''; ?>">🎨 <?php _e('Настройки профиля', 'mst-auth-lk'); ?></a>
        <a href="?page=mst-auth-lk-settings&tab=fake_reviews" class="mst-tab <?php echo $active_tab === 'fake_reviews' ? 'active' : ''; ?>">⭐ <?php _e('Фейк-отзывы', 'mst-auth-lk'); ?></a>
        <a href="?page=mst-auth-lk-settings&tab=logs" class="mst-tab <?php echo $active_tab === 'logs' ? 'active' : ''; ?>">📋 <?php _e('Логи', 'mst-auth-lk'); ?></a>
    </div>

    <?php if ($active_tab === 'profile_settings'): ?>
        <!-- Profile Display Settings -->
        <div class="mst-card">
            <div class="mst-card-header">
                <div class="icon" style="background:#f0e6fa;">🎨</div>
                <h2><?php _e('Настройки отображения профиля гида', 'mst-auth-lk'); ?></h2>
            </div>

            <form method="post">
                <?php wp_nonce_field('mst_save_profile_settings', 'mst_profile_settings_nonce'); ?>

                <div class="mst-provider-section">
                    <h3 style="margin:0 0 16px; font-size:16px; font-weight:600;">📐 <?php _e('Размеры карточки отзыва', 'mst-auth-lk'); ?></h3>

                    <div class="mst-form-row">
                        <div class="mst-form-group">
                            <label><?php _e('Отступ внутри карточки (px)', 'mst-auth-lk'); ?></label>
                            <input type="number" name="card_padding" value="<?php echo esc_attr($profile_settings['card_padding'] ?? 20); ?>" min="10" max="40">
                        </div>
                        <div class="mst-form-group">
                            <label><?php _e('Размер аватара автора (px)', 'mst-auth-lk'); ?></label>
                            <input type="number" name="avatar_size" value="<?php echo esc_attr($profile_settings['avatar_size'] ?? 60); ?>" min="40" max="100">
                        </div>
                    </div>

                    <div class="mst-form-row">
                        <div class="mst-form-group">
                            <label><?php _e('Размер заголовка (px)', 'mst-auth-lk'); ?></label>
                            <input type="number" name="title_size" value="<?php echo esc_attr($profile_settings['title_size'] ?? 16); ?>" min="14" max="24">
                        </div>
                        <div class="mst-form-group">
                            <label><?php _e('Размер текста отзыва (px)', 'mst-auth-lk'); ?></label>
                            <input type="number" name="text_size" value="<?php echo esc_attr($profile_settings['text_size'] ?? 14); ?>" min="12" max="20">
                        </div>
                    </div>
                </div>

                <div class="mst-provider-section">
                    <h3 style="margin:0 0 16px; font-size:16px; font-weight:600;">📷 <?php _e('Лайтбокс фотографий', 'mst-auth-lk'); ?></h3>

                    <div class="mst-form-row">
                        <div class="mst-form-group">
                            <label><?php _e('Ширина изображения (%)', 'mst-auth-lk'); ?></label>
                            <input type="number" name="lightbox_image_width" value="<?php echo esc_attr($profile_settings['lightbox_image_width'] ?? 70); ?>" min="50" max="85">
                            <p class="mst-help-text"><?php _e('Оставшееся место займёт панель с информацией', 'mst-auth-lk'); ?></p>
                        </div>
                        <div class="mst-form-group">
                            <label><?php _e('Отзывов на странице', 'mst-auth-lk'); ?></label>
                            <input type="number" name="reviews_per_page" value="<?php echo esc_attr($profile_settings['reviews_per_page'] ?? 9); ?>" min="3" max="30">
                        </div>
                    </div>
                </div>

                <div class="mst-provider-section">
                    <h3 style="margin:0 0 16px; font-size:16px; font-weight:600;">👁️ <?php _e('Предпросмотр карточки', 'mst-auth-lk'); ?></h3>
                    
                    <div class="mst-preview-box">
                        <div class="mst-preview-card" style="padding: <?php echo intval($profile_settings['card_padding'] ?? 20); ?>px;">
                            <div class="mst-preview-avatar" style="width: <?php echo intval($profile_settings['avatar_size'] ?? 60); ?>px; height: <?php echo intval($profile_settings['avatar_size'] ?? 60); ?>px;"></div>
                            <div class="mst-preview-content">
                                <div class="mst-preview-name" style="font-size: <?php echo intval($profile_settings['title_size'] ?? 16); ?>px;">Анна С. ★★★★★</div>
                                <div class="mst-preview-text" style="font-size: <?php echo intval($profile_settings['text_size'] ?? 14); ?>px;">Спасибо за честность! Все было именно так, как обещали. Отличный гид!</div>
                            </div>
                        </div>
                    </div>
                </div>

                <button type="submit" name="mst_save_profile_settings" class="mst-btn">💾 <?php _e('Сохранить настройки', 'mst-auth-lk'); ?></button>
            </form>
        </div>

    <?php elseif ($active_tab === 'oauth'): ?>
        <!-- OAuth Settings -->
        <form method="post">
            <?php wp_nonce_field('mst_save_oauth_settings', 'mst_oauth_nonce'); ?>
            
            <div class="mst-card">
                <div class="mst-card-header">
                    <div class="icon" style="background:#f0e6fa;">🔐</div>
                    <h2>OAuth провайдеры</h2>
                </div>
                <div class="mst-provider-section">
                    <h3><?php _e('Google', 'mst-auth-lk'); ?></h3>
                    <div class="mst-form-row">
                        <div class="mst-form-group">
                            <label><?php _e('Client ID', 'mst-auth-lk'); ?></label>
                            <input type="text" name="google_client_id" value="<?php echo esc_attr($oauth_settings['google_client_id'] ?? ''); ?>">
                        </div>
                        <div class="mst-form-group">
                            <label><?php _e('Client Secret', 'mst-auth-lk'); ?></label>
                            <input type="text" name="google_client_secret" value="<?php echo esc_attr($oauth_settings['google_client_secret'] ?? ''); ?>">
                        </div>
                    </div>
                    <label>
                        <input type="checkbox" name="google_enabled" <?php checked($oauth_enabled['google']); ?>>
                        <?php _e('Включить', 'mst-auth-lk'); ?>
                    </label>
                </div>
                <div class="mst-provider-section">
                    <h3><?php _e('VK', 'mst-auth-lk'); ?></h3>
                    <div class="mst-form-row">
                        <div class="mst-form-group">
                            <label><?php _e('Client ID', 'mst-auth-lk'); ?></label>
                            <input type="text" name="vk_client_id" value="<?php echo esc_attr($oauth_settings['vk_client_id'] ?? ''); ?>">
                        </div>
                        <div class="mst-form-group">
                            <label><?php _e('Client Secret', 'mst-auth-lk'); ?></label>
                            <input type="text" name="vk_client_secret" value="<?php echo esc_attr($oauth_settings['vk_client_secret'] ?? ''); ?>">
                        </div>
                    </div>
                    <label>
                        <input type="checkbox" name="vk_enabled" <?php checked($oauth_enabled['vk']); ?>>
                        <?php _e('Включить', 'mst-auth-lk'); ?>
                    </label>
                </div>
                <div class="mst-provider-section">
                    <h3><?php _e('Yandex', 'mst-auth-lk'); ?></h3>
                    <div class="mst-form-row">
                        <div class="mst-form-group">
                            <label><?php _e('Client ID', 'mst-auth-lk'); ?></label>
                            <input type="text" name="yandex_client_id" value="<?php echo esc_attr($oauth_settings['yandex_client_id'] ?? ''); ?>">
                        </div>
                        <div class="mst-form-group">
                            <label><?php _e('Client Secret', 'mst-auth-lk'); ?></label>
                            <input type="text" name="yandex_client_secret" value="<?php echo esc_attr($oauth_settings['yandex_client_secret'] ?? ''); ?>">
                        </div>
                    </div>
                    <label>
                        <input type="checkbox" name="yandex_enabled" <?php checked($oauth_enabled['yandex']); ?>>
                        <?php _e('Включить', 'mst-auth-lk'); ?>
                    </label>
                </div>
                <p style="color:#6b7280;">OAuth настройки сохранены в основном файле.</p>
            </div>

            <button type="submit" name="mst_oauth_save" class="mst-btn">💾 Сохранить настройки</button>
        </form>

    <?php elseif ($active_tab === 'guides'): ?>
        <!-- Guides Management -->
        <?php if ($edit_guide): ?>
            <!-- Edit Guide Form -->
            <div class="mst-card">
                <div class="mst-card-header">
                    <div class="icon" style="background:#f0e6fa;">✏️</div>
                    <h2><?php _e('Редактирование профиля гида', 'mst-auth-lk'); ?>: <?php echo esc_html($edit_guide->display_name); ?></h2>
                </div>
                <p><a href="?page=mst-auth-lk-settings&tab=guides" class="mst-btn mst-btn-secondary">← <?php _e('Назад к списку', 'mst-auth-lk'); ?></a></p>
            </div>
        <?php else: ?>
            <!-- Guides List -->
            <div class="mst-card">
                <div class="mst-card-header">
                    <div class="icon" style="background:#f0e6fa;">👥</div>
                    <h2><?php _e('Управление гидами', 'mst-auth-lk'); ?></h2>
                </div>

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
                                <td>
                                    <?php if ($g_verified): ?>
                                        <span class="mst-guide-badge verified">✅ <?php _e('Верифицирован', 'mst-auth-lk'); ?></span>
                                    <?php else: ?>
                                        <span class="mst-guide-badge pending">⏳ <?php _e('Не верифицирован', 'mst-auth-lk'); ?></span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="mst-guide-actions">
                                        <a href="?page=mst-auth-lk-settings&tab=guides&edit_guide=<?php echo $g->ID; ?>" class="mst-btn mst-btn-sm mst-btn-secondary">✏️</a>
                                        <a href="<?php echo add_query_arg('guide_id', $g->ID, home_url('/guide/')); ?>" class="mst-btn mst-btn-sm mst-btn-secondary" target="_blank">👁️</a>
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
        <!-- Fake Reviews -->
        <div class="mst-card">
            <div class="mst-card-header">
                <div class="icon" style="background:#fef3c7;">⭐</div>
                <h2><?php _e('Управление фейк-отзывами гидов', 'mst-auth-lk'); ?></h2>
            </div>
            <p style="color:#6b7280;"><?php _e('Добавляйте отзывы для демонстрации на профилях гидов', 'mst-auth-lk'); ?></p>
        </div>

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
                <p style="color:#6b7280;">Последние <?php echo count($otp_logs); ?> записей</p>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>
