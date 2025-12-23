<?php
/**
 * Управление параметрами товаров
 * Author: Telegram @l1ghtsun
 */
if(!defined('ABSPATH')) exit;

// Обработка добавления параметра
if(isset($_POST['mst_add_attribute']) && check_admin_referer('mst_attributes', 'mst_attr_nonce')){
    $name = sanitize_text_field($_POST['attribute_name']);
    $icon = sanitize_text_field($_POST['attribute_icon']);
    
    $term = wp_insert_term($name, 'product_attributes');
    
    if(!is_wp_error($term)){
        update_term_meta($term['term_id'], 'attribute_icon', $icon);
        echo '<div class="mst-save-notice">✅ Параметр добавлен!</div>';
    } else {
        echo '<div class="notice notice-error"><p>Ошибка: ' . $term->get_error_message() . '</p></div>';
    }
}

// Обработка удаления параметра
if(isset($_GET['delete_attr']) && check_admin_referer('mst_delete_attr_' . $_GET['delete_attr'], '_wpnonce')){
    $term_id = intval($_GET['delete_attr']);
    $result = wp_delete_term($term_id, 'product_attributes');
    
    if(!is_wp_error($result)){
        wp_redirect(admin_url('admin.php?page=mysupertour-attributes-hub'));
        exit;
    }
}

// Обработка редактирования параметра
if(isset($_POST['mst_edit_attribute']) && check_admin_referer('mst_edit_attr_' . $_POST['attr_id'], '_wpnonce')){
    $term_id = intval($_POST['attr_id']);
    $name = sanitize_text_field($_POST['attribute_name']);
    $icon = sanitize_text_field($_POST['attribute_icon']);
    
    $result = wp_update_term($term_id, 'product_attributes', ['name' => $name]);
    
    if(!is_wp_error($result)){
        update_term_meta($term_id, 'attribute_icon', $icon);
        wp_redirect(admin_url('admin.php?page=mysupertour-attributes-hub'));
        exit;
    }
}

$attributes = get_terms([
    'taxonomy' => 'product_attributes',
    'hide_empty' => false
]);

$is_editing = isset($_GET['edit_attr']) ? intval($_GET['edit_attr']) : 0;
?>
<div class="wrap mst-hub-wrap">
    <div class="mst-hub-header">
        <h1 class="mst-hub-title">🎯 Управление параметрами</h1>
    </div>
    
    <div class="mst-admin-layout">
        <div class="mst-panel" style="grid-column:1/-1;">
            
            <h2><span class="mst-section-icon">➕</span> Добавить параметр</h2>
            <form method="post" action="" class="mst-compact-form">
                <?php wp_nonce_field('mst_attributes', 'mst_attr_nonce'); ?>
                <div class="mst-form-group">
                    <label class="mst-form-label">Название</label>
                    <input type="text" name="attribute_name" class="mst-form-control" placeholder="Детские" required>
                </div>
                <div class="mst-form-group">
                    <label class="mst-form-label">Эмодзи-иконка</label>
                    <input type="text" name="attribute_icon" class="mst-form-control" placeholder="👶" maxlength="4" required>
                </div>
                <button type="submit" name="mst_add_attribute" class="mst-btn mst-btn-primary">➕ Добавить параметр</button>
            </form>
            
            <hr style="margin:40px 0;">
            
            <h3 style="margin:0 0 20px;">📋 Текущие параметры (<?php echo count($attributes); ?>)</h3>
            
            <?php if(empty($attributes) || is_wp_error($attributes)): ?>
                <div class="mst-empty-state">
                    <div class="mst-empty-icon">🏷️</div>
                    <p>Параметры не добавлены</p>
                </div>
            <?php else: ?>
                <div style="display:flex;flex-direction:column;gap:10px;">
                    <?php foreach($attributes as $attr): ?>
                        <?php 
                        $icon = get_term_meta($attr->term_id, 'attribute_icon', true) ?: '🏷️';
                        $editing = ($is_editing === $attr->term_id);
                        ?>
                        <div class="mst-param-item">
                            <?php if($editing): ?>
                                <form method="post" action="" style="width:100%;display:flex;gap:12px;align-items:flex-start;">
                                    <?php wp_nonce_field('mst_edit_attr_' . $attr->term_id, '_wpnonce'); ?>
                                    <input type="hidden" name="attr_id" value="<?php echo $attr->term_id; ?>">
                                    <div style="flex:1;display:grid;gap:8px;">
                                        <input type="text" name="attribute_name" class="mst-form-control" value="<?php echo esc_attr($attr->name); ?>" required>
                                        <input type="text" name="attribute_icon" class="mst-form-control" value="<?php echo esc_attr($icon); ?>" maxlength="4" required placeholder="🏷️">
                                    </div>
                                    <div style="display:flex;gap:6px;">
                                        <button type="submit" name="mst_edit_attribute" class="mst-btn mst-btn-primary" style="padding:10px 18px;">💾</button>
                                        <a href="<?php echo admin_url('admin.php?page=mysupertour-attributes-hub'); ?>" class="mst-btn mst-btn-secondary" style="padding:10px 18px;">❌</a>
                                    </div>
                                </form>
                            <?php else: ?>
                                <div class="mst-param-info">
                                    <span class="mst-param-icon"><?php echo $icon; ?></span>
                                    <span class="mst-param-name"><?php echo esc_html($attr->name); ?></span>
                                    <span style="color:#999;font-size:13px;margin-left:10px;">(ID: <?php echo $attr->term_id; ?>)</span>
                                </div>
                                <div style="display:flex;gap:8px;">
                                    <a href="<?php echo esc_url(add_query_arg(['page' => 'mysupertour-attributes-hub', 'edit_attr' => $attr->term_id], admin_url('admin.php'))); ?>" 
                                       class="mst-btn mst-btn-secondary" style="padding:8px 14px;font-size:13px;">✏️</a>
                                    <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=mysupertour-attributes-hub&delete_attr=' . $attr->term_id), 'mst_delete_attr_' . $attr->term_id)); ?>" 
                                       class="mst-btn mst-btn-danger" onclick="return confirm('Удалить этот параметр?');">🗑️</a>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            
            <hr style="margin:40px 0;">
            
            <div style="background:#e8f5f1;padding:20px;border-radius:12px;">
                <h4 style="margin:0 0 10px;">💡 Подсказка</h4>
                <p style="margin:0;font-size:14px;line-height:1.6;">
                    Параметры используются для фильтрации товаров. Например: "Детские", "Музейные", "Необычные".<br>
                    После создания параметров, перейдите в <strong>🗺️ Города и параметры</strong>, чтобы привязать их к городам.
                </p>
            </div>
            
            <div style="margin-top:20px;padding:16px;background:#fff3cd;border-radius:12px;border-left:4px solid #ffc107;">
                <strong>🔗 Быстрые ссылки:</strong>
                <ul style="margin:10px 0 0;padding-left:20px;">
                    <li><a href="<?php echo admin_url('admin.php?page=mysupertour-category-attributes'); ?>">Привязать параметры к городам</a></li>
                    <li><a href="<?php echo admin_url('edit-tags.php?taxonomy=product_attributes&post_type=product'); ?>">Открыть в стандартной админке WP</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>