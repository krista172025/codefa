<?php
/**
 * Привязка параметров к городам
 * Author: Telegram @l1ghtsun
 */
if(!defined('ABSPATH')) exit;

if(isset($_POST['mst_save_category_attrs']) && check_admin_referer('mst_category_attrs', 'mst_cat_nonce')){
    $category_id = intval($_POST['category_id']);
    $selected_attrs = isset($_POST['attributes']) ? array_map('intval', $_POST['attributes']) : [];
    
    $all_settings = get_option('mst_category_attributes', []);
    $all_settings[$category_id] = $selected_attrs;
    update_option('mst_category_attributes', $all_settings);
    
    echo '<div class="mst-save-notice">✅ Параметры сохранены!</div>';
}

// ✅ ТОЛЬКО РОДИТЕЛЬСКИЕ КАТЕГОРИИ (ГОРОДА)
$categories = get_terms([
    'taxonomy' => 'product_cat',
    'hide_empty' => false,
    'parent' => 0  // Только родительские!
]);

$attributes = get_terms(['taxonomy' => 'product_attributes', 'hide_empty' => false]);
$settings = get_option('mst_category_attributes', []);
$selected_category = isset($_GET['category']) ? intval($_GET['category']) : 0;
?>
<div class="wrap mst-hub-wrap">
    <div class="mst-hub-header">
        <h1 class="mst-hub-title">🗺️ Города и параметры</h1>
    </div>
    
    <div class="mst-admin-layout">
        <div class="mst-panel" style="grid-column:1/-1;">
            
            <h2><span class="mst-section-icon">🌍</span> Выберите город</h2>
            
            <!-- ✅ КНОПКИ ВМЕСТО СЕЛЕКТА -->
            <div style="display:flex;flex-wrap:wrap;gap:12px;margin-bottom:30px;">
                <?php foreach($categories as $cat): ?>
                    <?php $is_active = ($selected_category === $cat->term_id); ?>
                    <a href="?page=mysupertour-category-attributes&category=<?php echo $cat->term_id; ?>" 
                       style="display:inline-flex;align-items:center;gap:8px;padding:12px 24px;border-radius:24px;text-decoration:none;font-weight:600;font-size:15px;transition:all 0.2s;<?php echo $is_active ? 'background:linear-gradient(135deg,#00c896 0%,#00a87a 100%);color:#fff;box-shadow:0 4px 12px rgba(0,200,150,0.3);' : 'background:#f5f5f5;color:#666;border:2px solid #e0e0e0;'; ?>">
                        <span style="font-size:20px;">🏙️</span>
                        <span><?php echo esc_html($cat->name); ?></span>
                    </a>
                <?php endforeach; ?>
            </div>
            
            <?php if($selected_category > 0): ?>
                <?php $current_cat = get_term($selected_category); ?>
                
                <h3 style="margin:30px 0 20px;font-size:20px;color:#333;">📍 Параметры для: <strong style="color:#00c896;"><?php echo esc_html($current_cat->name); ?></strong></h3>
                
                <form method="post" action="">
                    <?php wp_nonce_field('mst_category_attrs', 'mst_cat_nonce'); ?>
                    <input type="hidden" name="category_id" value="<?php echo $selected_category; ?>">
                    
                    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:15px;margin-bottom:30px;">
                        <?php 
                        $current_attrs = isset($settings[$selected_category]) ? $settings[$selected_category] : [];
                        foreach($attributes as $attr): 
                            $icon = get_term_meta($attr->term_id, 'attribute_icon', true) ?: '🏷️';
                            $is_checked = in_array($attr->term_id, $current_attrs);
                        ?>
                            <label style="display:flex;align-items:center;gap:10px;padding:15px;border:2px solid <?php echo $is_checked ? '#00c896' : '#e0e0e0'; ?>;border-radius:12px;cursor:pointer;background:<?php echo $is_checked ? '#e8f5f1' : '#fff'; ?>;transition:all 0.2s;">
                                <input type="checkbox" name="attributes[]" value="<?php echo $attr->term_id; ?>" <?php checked($is_checked); ?> style="width:20px;height:20px;">
                                <span style="font-size:24px;"><?php echo $icon; ?></span>
                                <span style="font-weight:600;"><?php echo esc_html($attr->name); ?></span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                    
                    <button type="submit" name="mst_save_category_attrs" class="mst-btn mst-btn-primary" style="padding:14px 32px;font-size:16px;">💾 Сохранить</button>
                </form>
                
            <?php else: ?>
                <div class="mst-empty-state" style="text-align:center;padding:60px 20px;">
                    <div class="mst-empty-icon" style="font-size:80px;margin-bottom:20px;">🗺️</div>
                    <p style="font-size:18px;color:#666;">Выберите город кнопками выше</p>
                </div>
            <?php endif; ?>
            
            <hr style="margin:40px 0;border:none;border-top:2px solid #f0f0f0;">
            
            <h3 style="font-size:20px;margin-bottom:20px;">📋 Настроенные города</h3>
            <?php if(empty($settings)): ?>
                <p style="color:#999;">Нет настроек</p>
            <?php else: ?>
                <div style="display:flex;flex-direction:column;gap:15px;">
                    <?php foreach($settings as $cat_id => $attr_ids): ?>
                        <?php 
                        $cat = get_term($cat_id);
                        if(!$cat || is_wp_error($cat)) continue;
                        ?>
                        <div style="padding:20px;background:#f9f9f9;border-radius:12px;">
                            <h4 style="margin:0 0 10px;font-size:16px;">📍 <?php echo esc_html($cat->name); ?></h4>
                            <div style="display:flex;flex-wrap:wrap;gap:8px;">
                                <?php foreach($attr_ids as $attr_id): ?>
                                    <?php 
                                    $attr = get_term($attr_id);
                                    if(!$attr || is_wp_error($attr)) continue;
                                    $icon = get_term_meta($attr_id, 'attribute_icon', true) ?: '🏷️';
                                    ?>
                                    <span style="display:inline-flex;align-items:center;gap:6px;padding:6px 12px;background:#fff;border-radius:20px;font-size:13px;">
                                        <?php echo $icon; ?> <?php echo esc_html($attr->name); ?>
                                    </span>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
a[href*="mysupertour-category-attributes"]:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(0,0,0,0.15) !important;
}
label:has(input[type="checkbox"]):hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}
</style>