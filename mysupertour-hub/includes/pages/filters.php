<?php
/**
 * Filters Page
 * Author: Telegram @l1ghtsun
 * Author URI: https://t.me/l1ghtsun
 */
if(!defined('ABSPATH')) exit;

// Обработка добавления формата
if(isset($_POST['mst_add_format']) && check_admin_referer('mst_formats', 'mst_formats_nonce')){
    MST_Hub_Formats::add_format($_POST['format_name'], $_POST['format_icon']);
    echo '<div class="mst-save-notice">✅ Формат добавлен!</div>';
}

// Обработка удаления формата
if(isset($_GET['delete_format']) && check_admin_referer('mst_delete_format_' . $_GET['delete_format'], '_wpnonce')){
    MST_Hub_Formats::delete_format($_GET['delete_format']);
    wp_redirect(admin_url('admin.php?page=mysupertour-filters-hub'));
    exit;
}

// Обработка редактирования формата
if(isset($_POST['mst_edit_format']) && check_admin_referer('mst_edit_format_' . $_POST['format_slug'], '_wpnonce')){
    MST_Hub_Formats::edit_format(
        sanitize_text_field($_POST['format_slug']),
        sanitize_text_field($_POST['format_name']),
        sanitize_text_field($_POST['format_icon'])
    );
    wp_redirect(admin_url('admin.php?page=mysupertour-filters-hub'));
    exit;
}

// Обработка добавления транспорта
if(isset($_POST['mst_add_transport']) && check_admin_referer('mst_transports', 'mst_transports_nonce')){
    MST_Hub_Transports::add_transport($_POST['transport_name'], $_POST['transport_icon']);
    echo '<div class="mst-save-notice">✅ Транспорт добавлен!</div>';
}

// Обработка удаления транспорта
if(isset($_GET['delete_transport']) && check_admin_referer('mst_delete_transport_' . $_GET['delete_transport'], '_wpnonce')){
    MST_Hub_Transports::delete_transport($_GET['delete_transport']);
    wp_redirect(admin_url('admin.php?page=mysupertour-filters-hub'));
    exit;
}

// Обработка редактирования транспорта
if(isset($_POST['mst_edit_transport']) && check_admin_referer('mst_edit_transport_' . $_POST['transport_slug'], '_wpnonce')){
    MST_Hub_Transports::edit_transport(
        sanitize_text_field($_POST['transport_slug']),
        sanitize_text_field($_POST['transport_name']),
        sanitize_text_field($_POST['transport_icon'])
    );
    wp_redirect(admin_url('admin.php?page=mysupertour-filters-hub'));
    exit;
}

// Сохранение настроек фильтров
if(isset($_POST['mst_save_filters_config']) && check_admin_referer('mst_filters_config', 'mst_filters_nonce')){
    $config = [
        'format' => [
            'enabled' => isset($_POST['format_enabled']),
            'label' => sanitize_text_field($_POST['format_label']),
            'style' => sanitize_text_field($_POST['format_style']),
            'multiple' => isset($_POST['format_multiple']),
            'order' => intval($_POST['format_order'])
        ],
        'price' => [
            'enabled' => isset($_POST['price_enabled']),
            'label' => sanitize_text_field($_POST['price_label']),
            'style' => sanitize_text_field($_POST['price_style']),
            'order' => intval($_POST['price_order'])
        ],
        'transport' => [
            'enabled' => isset($_POST['transport_enabled']),
            'label' => sanitize_text_field($_POST['transport_label']),
            'style' => sanitize_text_field($_POST['transport_style']),
            'multiple' => isset($_POST['transport_multiple']),
            'order' => intval($_POST['transport_order'])
        ],
        'attributes' => [
            'enabled' => isset($_POST['attributes_enabled']),
            'label' => sanitize_text_field($_POST['attributes_label']),
            'style' => sanitize_text_field($_POST['attributes_style']),
            'multiple' => isset($_POST['attributes_multiple']),
            'order' => intval($_POST['attributes_order'])
        ]
    ];
    update_option('mst_filters_config', $config);
    echo '<div class="mst-save-notice">✅ Настройки фильтров сохранены!</div>';
}

$config = get_option('mst_filters_config', [
    'format' => ['enabled' => true, 'label' => 'Формат тура', 'style' => 'radio', 'multiple' => false, 'order' => 1],
    'price' => ['enabled' => true, 'label' => 'Цена', 'style' => 'slider', 'order' => 2],
    'transport' => ['enabled' => true, 'label' => 'Способ передвижения', 'style' => 'dropdown', 'multiple' => true, 'order' => 3],
    'attributes' => ['enabled' => true, 'label' => 'Параметры', 'style' => 'chips', 'multiple' => true, 'order' => 4]
]);

$formats = MST_Hub_Formats::get_all();
$transports = MST_Hub_Transports::get_all();

if(empty($transports)){
    $transports = [
        'walk' => ['name' => 'Пешком', 'icon' => '🚶‍♂️', 'code' => 'walk'],
        'car' => ['name' => 'Авто', 'icon' => '🚗', 'code' => 'car'],
        'combined' => ['name' => 'Комбинированный', 'icon' => '🔁', 'code' => 'combined']
    ];
    update_option('mst_transports', $transports);
}

$styles = [
    'dropdown' => 'Выпадающий список',
    'checkbox' => 'Чекбоксы',
    'radio' => 'Радиокнопки',
    'chips' => 'Чипы-кнопки'
];
?>

<div class="wrap mst-hub-wrap">
    <div class="mst-hub-header"><h1 class="mst-hub-title">⚙️ Настройки Фильтров</h1></div>
    
    <div style="margin-bottom:30px;">
        
        <!-- АККОРДЕОН: ФОРМАТЫ -->
        <div class="mst-accordion active">
            <div class="mst-accordion-header">
                <div class="mst-accordion-title"><span>📋</span><span>Управление форматами (<?php echo count($formats); ?>)</span></div>
                <span class="mst-accordion-arrow">▼</span>
            </div>
            <div class="mst-accordion-content">
                <form method="post" action="" class="mst-compact-form">
                    <?php wp_nonce_field('mst_formats', 'mst_formats_nonce'); ?>
                    <div class="mst-form-group">
                        <label class="mst-form-label">Название формата</label>
                        <input type="text" name="format_name" class="mst-form-control" placeholder="Групповая" required>
                    </div>
                    <div class="mst-form-group">
                        <label class="mst-form-label">Эмодзи-иконка</label>
                        <input type="text" name="format_icon" class="mst-form-control" placeholder="👥" maxlength="4" required>
                    </div>
                    <button type="submit" name="mst_add_format" class="mst-btn mst-btn-primary">➕ Добавить формат</button>
                </form>
                
                <div style="margin-top:20px;">
                    <?php if(empty($formats)): ?>
                        <p style="color:#999;text-align:center;padding:20px;">Нет форматов</p>
                    <?php else: ?>
                        <?php foreach($formats as $slug => $data): ?>
                            <?php 
                            // ✅ ИСПРАВЛЕНИЕ: используем code (латинский slug) или генерируем новый
                            $latin_slug = isset($data['code']) ? $data['code'] : sanitize_title($data['name']);
                            $is_editing = isset($_GET['edit_format']) && (
                                $_GET['edit_format'] === $latin_slug || 
                                urldecode($_GET['edit_format']) === $slug ||
                                $_GET['edit_format'] === $slug
                            );
                            ?>
                            <div class="mst-param-item">
                                <?php if($is_editing): ?>
                                    <form method="post" action="" style="width:100%;display:flex;gap:12px;align-items:flex-start;">
                                        <?php wp_nonce_field('mst_edit_format_' . $latin_slug, '_wpnonce'); ?>
                                        <input type="hidden" name="format_slug" value="<?php echo esc_attr($latin_slug); ?>">
                                        <div style="flex:1;display:grid;gap:8px;">
                                            <input type="text" name="format_name" class="mst-form-control" value="<?php echo esc_attr($data['name']); ?>" required>
                                            <input type="text" name="format_icon" class="mst-form-control" value="<?php echo esc_attr($data['icon']); ?>" maxlength="4" required placeholder="👥">
                                        </div>
                                        <div style="display:flex;gap:6px;">
                                            <button type="submit" name="mst_edit_format" class="mst-btn mst-btn-primary" style="padding:10px 18px;">💾</button>
                                            <a href="<?php echo admin_url('admin.php?page=mysupertour-filters-hub'); ?>" class="mst-btn mst-btn-secondary" style="padding:10px 18px;">❌</a>
                                        </div>
                                    </form>
                                <?php else: ?>
                                    <div class="mst-param-info">
                                        <span class="mst-param-icon"><?php echo $data['icon']; ?></span>
                                        <span class="mst-param-name"><?php echo esc_html($data['name']); ?></span>
                                    </div>
                                    <div style="display:flex;gap:8px;">
                                        <a href="<?php echo esc_url(add_query_arg(['page' => 'mysupertour-filters-hub', 'edit_format' => $latin_slug], admin_url('admin.php'))); ?>" 
                                           class="mst-btn mst-btn-secondary" style="padding:8px 14px;font-size:13px;">✏️</a>
                                        <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=mysupertour-filters-hub&delete_format=' . urlencode($latin_slug)), 'mst_delete_format_' . $latin_slug)); ?>"
                                           class="mst-btn mst-btn-danger" onclick="return confirm('Удалить этот формат?');">🗑️</a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- АККОРДЕОН: ТРАНСПОРТ -->
        <div class="mst-accordion active">
            <div class="mst-accordion-header">
                <div class="mst-accordion-title"><span>🚗</span><span>Управление транспортом (<?php echo count($transports); ?>)</span></div>
                <span class="mst-accordion-arrow">▼</span>
            </div>
            <div class="mst-accordion-content">
                <form method="post" action="" class="mst-compact-form">
                    <?php wp_nonce_field('mst_transports', 'mst_transports_nonce'); ?>
                    <div class="mst-form-group">
                        <label class="mst-form-label">Название транспорта</label>
                        <input type="text" name="transport_name" class="mst-form-control" placeholder="Пешком" required>
                    </div>
                    <div class="mst-form-group">
                        <label class="mst-form-label">Эмодзи-иконка</label>
                        <input type="text" name="transport_icon" class="mst-form-control" placeholder="🚶‍♂️" maxlength="10" required>
                    </div>
                    <button type="submit" name="mst_add_transport" class="mst-btn mst-btn-primary">➕ Добавить транспорт</button>
                </form>
                
                <div style="margin-top:20px;">
                    <?php if(empty($transports)): ?>
                        <p style="color:#999;text-align:center;padding:20px;">Нет транспорта</p>
                    <?php else: ?>
                        <?php foreach($transports as $slug => $data): ?>
                            <?php 
                            // ✅ ИСПРАВЛЕНИЕ: используем code (латинский slug) или генерируем новый
                            $latin_slug = isset($data['code']) ? $data['code'] : sanitize_title($data['name']);
                            $is_editing = isset($_GET['edit_transport']) && (
                                $_GET['edit_transport'] === $latin_slug || 
                                urldecode($_GET['edit_transport']) === $slug ||
                                $_GET['edit_transport'] === $slug
                            );
                            ?>
                            <div class="mst-param-item">
                                <?php if($is_editing): ?>
                                    <form method="post" action="" style="width:100%;display:flex;gap:12px;align-items:flex-start;">
                                        <?php wp_nonce_field('mst_edit_transport_' . $latin_slug, '_wpnonce'); ?>
                                        <input type="hidden" name="transport_slug" value="<?php echo esc_attr($latin_slug); ?>">
                                        <div style="flex:1;display:grid;gap:8px;">
                                            <input type="text" name="transport_name" class="mst-form-control" value="<?php echo esc_attr($data['name']); ?>" required>
                                            <input type="text" name="transport_icon" class="mst-form-control" value="<?php echo esc_attr($data['icon']); ?>" maxlength="10" required placeholder="🚶‍♂️">
                                        </div>
                                        <div style="display:flex;gap:6px;">
                                            <button type="submit" name="mst_edit_transport" class="mst-btn mst-btn-primary" style="padding:10px 18px;">💾</button>
                                            <a href="<?php echo admin_url('admin.php?page=mysupertour-filters-hub'); ?>" class="mst-btn mst-btn-secondary" style="padding:10px 18px;">❌</a>
                                        </div>
                                    </form>
                                <?php else: ?>
                                    <div class="mst-param-info">
                                        <span class="mst-param-icon"><?php echo $data['icon']; ?></span>
                                        <span class="mst-param-name"><?php echo esc_html($data['name']); ?></span>
                                    </div>
                                    <div style="display:flex;gap:8px;">
                                        <a href="<?php echo esc_url(add_query_arg(['page' => 'mysupertour-filters-hub', 'edit_transport' => $latin_slug], admin_url('admin.php'))); ?>" 
                                           class="mst-btn mst-btn-secondary" style="padding:8px 14px;font-size:13px;">✏️</a>
                                        <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=mysupertour-filters-hub&delete_transport=' . urlencode($latin_slug)), 'mst_delete_transport_' . $latin_slug)); ?>"
                                           class="mst-btn mst-btn-danger" onclick="return confirm('Удалить этот транспорт?');">🗑️</a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
    </div>
    
    <!-- КОНФИГУРАЦИЯ ФИЛЬТРОВ -->
    <div class="mst-admin-layout">
        <div class="mst-panel" style="grid-column:1/-1;">
            <h2><span class="mst-section-icon">🎛️</span> Конфигурация фильтров</h2>
            <form method="post" action="">
                <?php wp_nonce_field('mst_filters_config', 'mst_filters_nonce'); ?>
                
                <!-- ФОРМАТ ТУРА -->
                <div class="mst-filter-config-block">
                    <h3 style="display:flex;align-items:center;gap:10px;margin-bottom:20px;"><span style="font-size:24px;">📋</span>Формат тура</h3>
                    <div class="mst-form-group">
                        <label style="display:flex;align-items:center;gap:10px;">
                            <input type="checkbox" name="format_enabled" value="1" <?php checked($config['format']['enabled']); ?> style="width:20px;height:20px;">
                            <span style="font-weight:600;">Включить фильтр</span>
                        </label>
                    </div>
                    <div class="mst-form-group">
                        <label class="mst-form-label">Название фильтра</label>
                        <input type="text" name="format_label" class="mst-form-control" value="<?php echo esc_attr($config['format']['label']); ?>" required>
                    </div>
                    <div class="mst-form-group">
                        <label class="mst-form-label">Стиль отображения</label>
                        <select name="format_style" class="mst-form-control">
                            <?php foreach($styles as $value => $label): ?>
                                <option value="<?php echo esc_attr($value); ?>" <?php selected($config['format']['style'], $value); ?>><?php echo esc_html($label); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mst-form-group">
                        <label style="display:flex;align-items:center;gap:10px;">
                            <input type="checkbox" name="format_multiple" value="1" <?php checked($config['format']['multiple']); ?> style="width:20px;height:20px;">
                            <span style="font-weight:600;">Множественный выбор</span>
                        </label>
                    </div>
                    <div class="mst-form-group">
                        <label class="mst-form-label">Порядок отображения</label>
                        <input type="number" name="format_order" class="mst-form-control" value="<?php echo intval($config['format']['order']); ?>" min="1" max="10">
                    </div>
                </div>
                
                <hr style="margin:30px 0;border:none;border-top:2px solid #f0f0f0;">
                
                <!-- СПОСОБ ПЕРЕДВИЖЕНИЯ -->
                <div class="mst-filter-config-block">
                    <h3 style="display:flex;align-items:center;gap:10px;margin-bottom:20px;"><span style="font-size:24px;">🚗</span>Способ передвижения</h3>
                    <div class="mst-form-group">
                        <label style="display:flex;align-items:center;gap:10px;">
                            <input type="checkbox" name="transport_enabled" value="1" <?php checked($config['transport']['enabled']); ?> style="width:20px;height:20px;">
                            <span style="font-weight:600;">Включить фильтр</span>
                        </label>
                    </div>
                    <div class="mst-form-group">
                        <label class="mst-form-label">Название фильтра</label>
                        <input type="text" name="transport_label" class="mst-form-control" value="<?php echo esc_attr($config['transport']['label']); ?>" required>
                    </div>
                    <div class="mst-form-group">
                        <label class="mst-form-label">Стиль отображения</label>
                        <select name="transport_style" class="mst-form-control">
                            <?php foreach($styles as $value => $label): ?>
                                <option value="<?php echo esc_attr($value); ?>" <?php selected($config['transport']['style'], $value); ?>><?php echo esc_html($label); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mst-form-group">
                        <label style="display:flex;align-items:center;gap:10px;">
                            <input type="checkbox" name="transport_multiple" value="1" <?php checked($config['transport']['multiple']); ?> style="width:20px;height:20px;">
                            <span style="font-weight:600;">Множественный выбор</span>
                        </label>
                    </div>
                    <div class="mst-form-group">
                        <label class="mst-form-label">Порядок отображения</label>
                        <input type="number" name="transport_order" class="mst-form-control" value="<?php echo intval($config['transport']['order']); ?>" min="1" max="10">
                    </div>
                </div>
                
                <hr style="margin:30px 0;border:none;border-top:2px solid #f0f0f0;">
                
                <!-- ЦЕНА -->
                <div class="mst-filter-config-block">
                    <h3 style="display:flex;align-items:center;gap:10px;margin-bottom:20px;"><span style="font-size:24px;">💰</span>Цена</h3>
                    <div class="mst-form-group">
                        <label style="display:flex;align-items:center;gap:10px;">
                            <input type="checkbox" name="price_enabled" value="1" <?php checked($config['price']['enabled'] ?? true); ?> style="width:20px;height:20px;">
                            <span style="font-weight:600;">Включить фильтр</span>
                        </label>
                    </div>
                    <div class="mst-form-group">
                        <label class="mst-form-label">Название фильтра</label>
                        <input type="text" name="price_label" class="mst-form-control" value="<?php echo esc_attr($config['price']['label'] ?? 'Цена'); ?>" required>
                    </div>
                    <div class="mst-form-group">
                        <label class="mst-form-label">Стиль отображения</label>
                        <select name="price_style" class="mst-form-control">
                            <option value="slider" <?php selected($config['price']['style'] ?? 'slider', 'slider'); ?>>Слайдер с диапазоном</option>
                            <option value="input" <?php selected($config['price']['style'] ?? 'slider', 'input'); ?>>Два поля ввода</option>
                        </select>
                    </div>
                    <div class="mst-form-group">
                        <label class="mst-form-label">Порядок отображения</label>
                        <input type="number" name="price_order" class="mst-form-control" value="<?php echo intval($config['price']['order'] ?? 2); ?>" min="1" max="10">
                    </div>
                </div>
                
                <hr style="margin:30px 0;border:none;border-top:2px solid #f0f0f0;">
                
                <!-- ПАРАМЕТРЫ -->
                <div class="mst-filter-config-block">
                    <h3 style="display:flex;align-items:center;gap:10px;margin-bottom:20px;"><span style="font-size:24px;">🏷️</span>Параметры</h3>
                    <div class="mst-form-group">
                        <label style="display:flex;align-items:center;gap:10px;">
                            <input type="checkbox" name="attributes_enabled" value="1" <?php checked($config['attributes']['enabled']); ?> style="width:20px;height:20px;">
                            <span style="font-weight:600;">Включить фильтр</span>
                        </label>
                    </div>
                    <div class="mst-form-group">
                        <label class="mst-form-label">Название фильтра</label>
                        <input type="text" name="attributes_label" class="mst-form-control" value="<?php echo esc_attr($config['attributes']['label']); ?>" required>
                    </div>
                    <div class="mst-form-group">
                        <label class="mst-form-label">Стиль отображения</label>
                        <select name="attributes_style" class="mst-form-control">
                            <?php foreach($styles as $value => $label): ?>
                                <option value="<?php echo esc_attr($value); ?>" <?php selected($config['attributes']['style'], $value); ?>><?php echo esc_html($label); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mst-form-group">
                        <label style="display:flex;align-items:center;gap:10px;">
                            <input type="checkbox" name="attributes_multiple" value="1" <?php checked($config['attributes']['multiple']); ?> style="width:20px;height:20px;">
                            <span style="font-weight:600;">Множественный выбор</span>
                        </label>
                    </div>
                    <div class="mst-form-group">
                        <label class="mst-form-label">Порядок отображения</label>
                        <input type="number" name="attributes_order" class="mst-form-control" value="<?php echo intval($config['attributes']['order']); ?>" min="1" max="10">
                    </div>
                </div>
                
                <button type="submit" name="mst_save_filters_config" class="mst-btn mst-btn-primary" style="width:100%;margin-top:30px;">💾 Сохранить настройки</button>
            </form>
            
            <div style="margin-top:24px;padding:16px;background:#e8f5f1;border-radius:12px;">
                <strong>📝 Шорткод для вставки:</strong><br>
                <code style="background:#fff;padding:8px 12px;border-radius:6px;display:inline-block;margin-top:8px;">[mst_filters]</code>
                <p style="font-size:13px;color:#666;margin-top:12px;">Вставьте этот шорткод на страницу магазина или в шаблон темы.</p>
            </div>
            
            <hr style="margin:30px 0;">
            
            <div class="mst-filters-preview-box">
                <h3 style="margin:0 0 16px;font-size:18px;font-weight:700;display:flex;align-items:center;gap:10px;"><span>🎨</span> Превью фильтров</h3>
                <?php echo do_shortcode('[mst_filters]'); ?>
            </div>
        </div>
    </div>
</div>