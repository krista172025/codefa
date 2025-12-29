<?php
if (!defined('ABSPATH')) exit;

$woo_active = class_exists('WooCommerce');
$latepoint_active = class_exists('OsBookingController') || defined('LATEPOINT_VERSION');
$afwc_active = class_exists('AFWC') || function_exists('afwc_get_instance') || shortcode_exists('afwc_dashboard');
$wishlist_active = class_exists('XStore_Wishlist') || function_exists('etheme_get_option') || shortcode_exists('yith_wcwl_wishlist');

$default_tabs = [
    'orders' => ['icon' => '📦', 'label' => 'Мои заказы'],
    'bookings' => ['icon' => '📅', 'label' => 'Бронирования'],
    'affiliate' => ['icon' => '💰', 'label' => 'Реферальная программа'],
    'wishlist' => ['icon' => '❤️', 'label' => 'Избранное']
];

$tabs = $settings['tabs'] ?? $default_tabs;
?>

<div class="wrap mst-hub-wrap">
    <div class="mst-hub-header">
        <h1 class="mst-hub-title">
            👤 Настройки Личного Кабинета
            <span class="mst-version-badge">v<?php echo MST_LK_VERSION; ?></span>
        </h1>
    </div>
    
    <div class="mst-admin-layout">
        <div class="mst-panel">
            <h2><span class="mst-section-icon">🎨</span> Настройка табов</h2>
            
            <form method="post">
                <?php wp_nonce_field('mst_lk_settings', 'mst_lk_nonce'); ?>
                
                <?php foreach ($default_tabs as $key => $default): 
                    $tab = $tabs[$key] ?? $default;
                ?>
                <div class="mst-tab-editor">
                    <div class="mst-tab-header">
                        <label class="mst-tab-toggle">
                            <input type="checkbox" name="tab_enabled_<?php echo $key; ?>" value="1" <?php checked(!empty($tab['enabled']), true); ?>>
                            <strong><?php echo $default['label']; ?></strong>
                        </label>
                    </div>
                    
                    <div class="mst-tab-fields">
                        <div class="mst-form-row">
                            <div class="mst-form-group">
                                <label class="mst-form-label">Иконка (emoji)</label>
                                <input type="text" name="tab_icon_<?php echo $key; ?>" class="mst-form-control" value="<?php echo esc_attr($tab['icon']); ?>" maxlength="4">
                            </div>
                            
                            <div class="mst-form-group">
                                <label class="mst-form-label">Название</label>
                                <input type="text" name="tab_label_<?php echo $key; ?>" class="mst-form-control" value="<?php echo esc_attr($tab['label']); ?>">
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
                
                <button type="submit" name="mst_lk_save_settings" class="mst-btn mst-btn-primary" style="width:100%;margin-top:20px;">💾 Сохранить настройки</button>
            </form>
            
            <div style="margin-top:24px;padding:16px;background:#e8f5f1;border-radius:12px;">
                <strong>📝 Шорткод:</strong> <code style="background:#fff;padding:6px 12px;border-radius:6px;font-size:14px;">[mst_lk]</code>
                <p style="margin:8px 0 0;font-size:13px;color:#666;">Вставьте на любую страницу или используйте интеграцию с WooCommerce</p>
            </div>
        </div>
        
        <div class="mst-panel">
            <h2><span class="mst-section-icon">ℹ️</span> Статус плагинов</h2>
            
            <ul style="list-style:none;padding:0;margin:0;">
                <li style="padding:14px;background:<?php echo $woo_active ? '#e8f5f1' : '#fee'; ?>;border-radius:10px;margin-bottom:10px;display:flex;align-items:center;gap:10px;">
                    <span style="font-size:24px;"><?php echo $woo_active ? '✅' : '❌'; ?></span>
                    <div>
                        <strong>WooCommerce</strong>
                        <?php if (!$woo_active): ?>
                        <div style="font-size:12px;color:#c00;">Не установлен</div>
                        <?php endif; ?>
                    </div>
                </li>
                
                <li style="padding:14px;background:<?php echo $latepoint_active ? '#e8f5f1' : '#fee'; ?>;border-radius:10px;margin-bottom:10px;display:flex;align-items:center;gap:10px;">
                    <span style="font-size:24px;"><?php echo $latepoint_active ? '✅' : '❌'; ?></span>
                    <div>
                        <strong>LatePoint</strong>
                        <?php if (!$latepoint_active): ?>
                        <div style="font-size:12px;color:#c00;">Не установлен</div>
                        <?php endif; ?>
                    </div>
                </li>
                
                <li style="padding:14px;background:<?php echo $afwc_active ? '#e8f5f1' : '#fee'; ?>;border-radius:10px;margin-bottom:10px;display:flex;align-items:center;gap:10px;">
                    <span style="font-size:24px;"><?php echo $afwc_active ? '✅' : '❌'; ?></span>
                    <div>
                        <strong>Партнер для WooCommerce</strong>
                        <?php if (!$afwc_active): ?>
                        <div style="font-size:12px;color:#c00;">Не установлен</div>
                        <?php else: ?>
                        <div style="font-size:12px;color:#9952E0;">Активен - использует [afwc_dashboard]</div>
                        <?php endif; ?>
                    </div>
                </li>
                
                <li style="padding:14px;background:<?php echo $wishlist_active ? '#e8f5f1' : '#fee'; ?>;border-radius:10px;display:flex;align-items:center;gap:10px;">
                    <span style="font-size:24px;"><?php echo $wishlist_active ? '✅' : '❌'; ?></span>
                    <div>
                        <strong>Wishlist (xStore/YITH)</strong>
                        <?php if (!$wishlist_active): ?>
                        <div style="font-size:12px;color:#c00;">Не найден</div>
                        <?php endif; ?>
                    </div>
                </li>
            </ul>
            
            <div style="margin-top:24px;padding:16px;background:#fff3cd;border-radius:12px;border-left:4px solid #ffd93d;">
                <strong style="display:flex;align-items:center;gap:8px;margin-bottom:8px;">
                    <span style="font-size:20px;">💡</span> Важно!
                </strong>
                <p style="font-size:13px;color:#666;margin:0;">
                    После изменений: <strong>Настройки → Постоянные ссылки → Сохранить</strong>
                </p>
            </div>
        </div>
    </div>
</div>

<style>
.mst-hub-wrap{background:linear-gradient(135deg,#f5f7fa,#e8ebef);padding:30px;min-height:100vh;margin:-10px -20px -20px}
.mst-hub-header{margin-bottom:30px}
.mst-hub-title{font-size:36px;font-weight:800;color:#1d1d1f;margin:0;display:flex;align-items:center;gap:12px}
.mst-version-badge{font-size:14px;background:linear-gradient(135deg,#9952E0,#00a87a);color:#fff;padding:4px 12px;border-radius:20px;font-weight:600}
.mst-admin-layout{display:grid;grid-template-columns:1fr 1fr;gap:30px}
.mst-panel{background:#fff;border-radius:20px;padding:30px;box-shadow:0 4px 24px rgba(0,0,0,.08)}
.mst-panel h2{margin-top:0;color:#1d1d1f;font-size:22px;border-bottom:3px solid #9952E0;padding-bottom:12px;display:flex;align-items:center;gap:10px}
.mst-section-icon{font-size:24px}
.mst-tab-editor{background:#f9f9f9;border-radius:12px;padding:16px;margin-bottom:16px}
.mst-tab-header{margin-bottom:12px}
.mst-tab-toggle{display:flex;align-items:center;gap:10px;cursor:pointer}
.mst-tab-toggle input{width:20px;height:20px}
.mst-tab-fields{display:grid;gap:12px}
.mst-form-row{display:grid;grid-template-columns:1fr 2fr;gap:12px}
.mst-form-group{display:flex;flex-direction:column}
.mst-form-label{font-size:13px;font-weight:600;color:#666;margin-bottom:4px}
.mst-form-control{padding:10px 12px;border:2px solid #e0e0e0;border-radius:8px;font-size:14px}
.mst-form-control:focus{border-color:#9952E0;outline:none}
.mst-btn{padding:12px 24px;border-radius:12px;font-size:14px;font-weight:600;border:none;cursor:pointer}
.mst-btn-primary{background:linear-gradient(135deg,#9952E0,#00a87a);color:#fff}
.mst-btn-primary:hover{transform:translateY(-2px);box-shadow:0 6px 20px rgba(0,200,150,.3)}
.mst-save-notice{background:linear-gradient(135deg,#9952E0,#00a87a);color:#fff;padding:16px 24px;border-radius:12px;margin-bottom:24px;font-weight:600}
@media (max-width:1400px){.mst-admin-layout{grid-template-columns:1fr}}
</style>