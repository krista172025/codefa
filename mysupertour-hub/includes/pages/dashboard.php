<?php
/**
 * Dashboard Page
 * Author: Telegram @l1ghtsun
 */
if(!defined('ABSPATH')) exit;

$products_count = wp_count_posts('product')->publish ?? 0;
$attributes_count = wp_count_terms(['taxonomy' => 'product_attributes', 'hide_empty' => false]);

if(isset($_POST['mst_sync_all_products']) && check_admin_referer('mst_sync_products', 'mst_sync_nonce')){
    $synced = MST_Hub_Sync::sync_all_products();
    echo '<div class="notice notice-success"><p>✅ Синхронизировано товаров: ' . $synced . '</p></div>';
}

if(isset($_POST['mst_migrate_formats']) && check_admin_referer('mst_migrate', 'mst_migrate_nonce')){
    $fixed = MST_Hub_Sync::migrate_format_slugs();
    echo '<div class="notice notice-success"><p>✅ Исправлено форматов: ' . $fixed . '</p></div>';
}

// ✅ МИГРАЦИЯ ЛАТИНСКИХ SLUG
if(isset($_POST['mst_fix_slugs']) && check_admin_referer('mst_fix_slugs', 'mst_fix_slugs_nonce')){
    $formats = get_option('mst_formats', []);
    $transports = get_option('mst_transports', []);
    
    $fixed_formats = 0;
    $new_formats = [];
    foreach($formats as $old_key => $data){
        $latin_slug = sanitize_title($data['name']);
        $new_formats[$latin_slug] = [
            'name' => $data['name'],
            'icon' => $data['icon'],
            'code' => $latin_slug
        ];
        
        // Обновляем товары
        global $wpdb;
        $wpdb->query($wpdb->prepare(
            "UPDATE {$wpdb->postmeta} SET meta_value = %s WHERE meta_key = '_mst_pi_format' AND meta_value = %s",
            $latin_slug, $old_key
        ));
        $fixed_formats++;
    }
    update_option('mst_formats', $new_formats);
    
    $fixed_transports = 0;
    $new_transports = [];
    foreach($transports as $old_key => $data){
        $latin_slug = sanitize_title($data['name']);
        $new_transports[$latin_slug] = [
            'name' => $data['name'],
            'icon' => $data['icon'],
            'code' => $latin_slug
        ];
        
        // Обновляем товары
        $wpdb->query($wpdb->prepare(
            "UPDATE {$wpdb->postmeta} SET meta_value = %s WHERE meta_key = '_mst_pi_transport' AND meta_value = %s",
            $latin_slug, $old_key
        ));
        $fixed_transports++;
    }
    update_option('mst_transports', $new_transports);
    
    echo '<div class="notice notice-success"><p>✅ Исправлено форматов: ' . $fixed_formats . ', транспорта: ' . $fixed_transports . '</p></div>';
}
?>
<div class="wrap mst-hub-wrap">
    <div class="mst-hub-header">
        <h1 class="mst-hub-title">🚀 MySuperTour Hub <span class="mst-version-badge">v2.0.3</span></h1>
    </div>
    
    <!-- МИГРАЦИЯ SLUG -->
    <div style="background:#fff;padding:20px;border-radius:12px;margin-bottom:20px;box-shadow:0 2px 8px rgba(0,0,0,0.1);border-left:4px solid #ff6b6b;">
        <h3 style="margin:0 0 10px;color:#ff6b6b;">⚠️ Миграция на латинские slug</h3>
        <p style="color:#666;margin:0 0 15px;">Если форматы/транспорт не редактируются - нажмите эту кнопку ОДИН РАЗ!</p>
        <form method="post" action="">
            <?php wp_nonce_field('mst_fix_slugs', 'mst_fix_slugs_nonce'); ?>
            <button type="submit" name="mst_fix_slugs" class="mst-btn mst-btn-primary" style="background:linear-gradient(135deg,#ff6b6b 0%,#ee5a52 100%);" onclick="return confirm('Вы уверены? Это обновит все форматы и транспорт!');">🔧 Исправить латинские slug</button>
        </form>
    </div>
    
    <div style="background:#fff;padding:20px;border-radius:12px;margin-bottom:20px;box-shadow:0 2px 8px rgba(0,0,0,0.1);">
        <h3 style="margin:0 0 10px;">🔄 Синхронизация товаров</h3>
        <p style="color:#666;margin:0 0 15px;">Пересохранить все товары и обновить форматы/транспорт из Hub.</p>
        <form method="post" action="">
            <?php wp_nonce_field('mst_sync_products', 'mst_sync_nonce'); ?>
            <button type="submit" name="mst_sync_all_products" class="mst-btn mst-btn-primary">🔄 Синхронизировать все товары</button>
        </form>
    </div>
    
    <div style="background:#fff;padding:20px;border-radius:12px;margin-bottom:20px;box-shadow:0 2px 8px rgba(0,0,0,0.1);">
        <h3 style="margin:0 0 10px;">🔧 Миграция форматов</h3>
        <p style="color:#666;margin:0 0 15px;">Исправить старые URL-encoded значения форматов.</p>
        <form method="post" action="">
            <?php wp_nonce_field('mst_migrate', 'mst_migrate_nonce'); ?>
            <button type="submit" name="mst_migrate_formats" class="mst-btn mst-btn-primary">🔧 Мигрировать форматы</button>
        </form>
    </div>
    
    <div class="mst-stats-grid">
        <div class="mst-stat-card"><div class="mst-stat-value"><?php echo $products_count; ?></div><div class="mst-stat-label">Товаров</div></div>
        <div class="mst-stat-card"><div class="mst-stat-value"><?php echo $attributes_count; ?></div><div class="mst-stat-label">Параметров</div></div>
        <div class="mst-stat-card"><div class="mst-stat-value"><?php echo defined('MST_MAP_VERSION') ? 4 : 3; ?></div><div class="mst-stat-label">Модуля</div></div>
    </div>
    
    <div class="mst-dashboard-grid">
        <div class="mst-card">
            <div class="mst-card-icon">🔍</div>
            <h2>Поиск</h2>
            <p>AJAX поиск с подсказками, городами и товарами</p>
            <span class="mst-badge-active">✓ Активен</span>
            <div class="mst-card-actions">
                <a href="<?php echo admin_url('admin.php?page=mysupertour-search-hub'); ?>" class="mst-btn mst-btn-primary">Настроить</a>
            </div>
        </div>
        
        <div class="mst-card">
            <div class="mst-card-icon">🎨</div>
            <h2>Иконки</h2>
            <p>Позиционирование иконок на карточках товаров</p>
            <span class="mst-badge-active">✓ Активен</span>
            <div class="mst-card-actions">
                <a href="<?php echo admin_url('admin.php?page=mysupertour-icons-hub'); ?>" class="mst-btn mst-btn-primary">Настроить</a>
            </div>
        </div>
        
        <div class="mst-card">
            <div class="mst-card-icon">⚙️</div>
            <h2>Фильтры</h2>
            <p>Горизонтальные фильтры с чекбоксами</p>
            <span class="mst-badge-active">✓ Активен</span>
            <div class="mst-card-actions">
                <a href="<?php echo admin_url('admin.php?page=mysupertour-filters-hub'); ?>" class="mst-btn mst-btn-primary">Настроить</a>
            </div>
        </div>
        
        <div class="mst-card">
            <div class="mst-card-icon">🎯</div>
            <h2>Параметры</h2>
            <p>Управление параметрами товаров (детские, музейные и т.д.)</p>
            <span class="mst-badge-active">✓ Активен</span>
            <div class="mst-card-actions">
                <a href="<?php echo admin_url('admin.php?page=mysupertour-attributes-hub'); ?>" class="mst-btn mst-btn-primary">Управлять</a>
                <a href="<?php echo admin_url('edit-tags.php?taxonomy=product_attributes&post_type=product'); ?>" class="mst-btn mst-btn-secondary">WP Админка</a>
            </div>
        </div>
        
        <?php if(defined('MST_MAP_VERSION')): ?>
        <div class="mst-card">
            <div class="mst-card-icon">🗺️</div>
            <h2>Карта экскурсий</h2>
            <p>Интерактивная карта как у Airbnb с точками экскурсий</p>
            <span class="mst-badge-active">✓ Активен</span>
            <div class="mst-card-actions">
                <a href="<?php echo admin_url('admin.php?page=mysupertour-map'); ?>" class="mst-btn mst-btn-primary">Настроить</a>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>