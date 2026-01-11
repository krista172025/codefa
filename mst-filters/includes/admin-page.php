<?php
/**
 * MST Filters Admin Page
 * v3.0.0 - With user analytics (IP, device, browser), attribute icons, WooCommerce tags sync
 */

if (!defined('ABSPATH')) exit;

$settings = get_option('mst_filters_settings', [
    'hide_shop_page' => 0,
    'url_structure' => '/{city}/{type}/',
    'default_domain' => '',
]);

$category_settings = get_option('mst_filters_category_settings', []);
$city_settings = get_option('mst_filters_city_settings', []);
$filter_visibility = get_option('mst_filters_visibility', []);
$attribute_icons = get_option('mst_filters_attribute_icons', []);
$analytics_summary = get_option('mst_filters_analytics_summary', [
    'total_searches' => 0,
    'searches_today' => 0,
    'popular_tour_types' => [],
    'popular_transports' => [],
    'popular_tags' => [],
    'popular_categories' => [],
    'avg_results' => 0,
]);

// User analytics
$user_analytics = get_option('mst_filters_user_analytics', [
    'visitors' => [],
    'top_products' => [],
]);

// Получаем все категории товаров
$product_categories = get_terms([
    'taxonomy' => 'product_cat',
    'hide_empty' => false,
    'parent' => 0,
]);

// Получаем типы товаров (подкатегории)
$product_types = get_terms([
    'taxonomy' => 'product_cat',
    'hide_empty' => false,
    'exclude' => [get_option('default_product_cat')],
]);

// Получаем атрибуты
$transport_terms = get_terms(['taxonomy' => 'pa_transport', 'hide_empty' => false]);
$tour_type_terms = get_terms(['taxonomy' => 'pa_tour-type', 'hide_empty' => false]);
$tags = get_terms(['taxonomy' => 'product_tag', 'hide_empty' => false]);

// Получаем все категории для группировки
$all_categories = get_terms(['taxonomy' => 'product_cat', 'hide_empty' => false]);

// Available icons for selection
$available_icons = [
    '🚗' => 'Автомобиль',
    '🚶' => 'Пешком',
    '🚌' => 'Автобус',
    '🚲' => 'Велосипед',
    '🛵' => 'Мотоцикл',
    '🚕' => 'Такси',
    '🚇' => 'Метро',
    '🚂' => 'Поезд',
    '⛵' => 'Лодка',
    '🚁' => 'Вертолет',
    '✈️' => 'Самолет',
    '🔄' => 'Комбо',
    '👥' => 'Группа',
    '👤' => 'Индивидуальный',
    '👨‍👩‍👧‍👦' => 'Семейный',
    '💑' => 'Романтический',
    '🏛' => 'Культурный',
    '🎭' => 'Театр',
    '🎨' => 'Искусство',
    '🏰' => 'Замок',
    '⛪' => 'Церковь',
    '🏖️' => 'Пляж',
    '🏔️' => 'Горы',
    '🌳' => 'Природа',
    '🍷' => 'Вино',
    '🍽️' => 'Еда',
    '🎉' => 'Развлечения',
    '📸' => 'Фото',
    '🌙' => 'Ночной',
    '☀️' => 'Дневной',
    '⭐' => 'Премиум',
    '💎' => 'Люкс',
    '💰' => 'Бюджетный',
    '🎁' => 'Подарок',
    '🏷️' => 'Метка',
];

settings_errors('mst_filters');
?>

<div class="wrap mst-filters-admin">
    <h1>
        <span class="dashicons dashicons-filter" style="margin-right: 10px;"></span>
        MST Filters - Настройки магазина
    </h1>
    
    <div class="mst-admin-tabs">
        <button class="mst-tab active" data-tab="general">🏠 Основные</button>
        <button class="mst-tab" data-tab="cities">🏙️ Города</button>
        <button class="mst-tab" data-tab="categories">📁 Категории</button>
        <button class="mst-tab" data-tab="filter-visibility">🎚️ Видимость фильтров</button>
        <button class="mst-tab" data-tab="filters">🔍 Атрибуты</button>
        <button class="mst-tab" data-tab="icons">🎨 Иконки</button>
        <button class="mst-tab" data-tab="urls">🔗 URL структура</button>
        <button class="mst-tab" data-tab="analytics">📊 Аналитика</button>
        <button class="mst-tab" data-tab="users">👥 Пользователи</button>
    </div>
    
    <form method="post" action="">
        <?php wp_nonce_field('mst_filters_settings', 'mst_filters_nonce'); ?>
        
        <!-- ОСНОВНЫЕ НАСТРОЙКИ -->
        <div class="mst-tab-content active" data-tab="general">
            <div class="mst-admin-card">
                <h2>🛒 Настройки магазина</h2>
                
                <table class="form-table">
                    <tr>
                        <th>Скрыть страницу /shop/</th>
                        <td>
                            <label class="mst-toggle">
                                <input type="checkbox" name="hide_shop_page" value="1" <?php checked(!empty($settings['hide_shop_page'])); ?>>
                                <span class="mst-toggle-slider"></span>
                            </label>
                            <p class="description">Скрывает стандартную страницу магазина WooCommerce и редиректит /shop/... URL на чистые URL без /shop/.</p>
                        </td>
                    </tr>
                    <tr>
                        <th>Домен по умолчанию</th>
                        <td>
                            <input type="text" name="default_domain" value="<?php echo esc_attr($settings['default_domain'] ?? ''); ?>" class="regular-text" placeholder="example.com">
                            <p class="description">Основной домен для генерации ссылок (опционально).</p>
                        </td>
                    </tr>
                </table>
            </div>
            
            <div class="mst-admin-card">
                <h2>📈 Быстрая статистика</h2>
                <div class="mst-stats-grid">
                    <div class="mst-stat-box">
                        <span class="mst-stat-number"><?php echo count($product_categories); ?></span>
                        <span class="mst-stat-label">Городов</span>
                    </div>
                    <div class="mst-stat-box">
                        <span class="mst-stat-number"><?php echo wp_count_posts('product')->publish; ?></span>
                        <span class="mst-stat-label">Товаров</span>
                    </div>
                    <div class="mst-stat-box">
                        <span class="mst-stat-number"><?php echo count($transport_terms); ?></span>
                        <span class="mst-stat-label">Транспортов</span>
                    </div>
                    <div class="mst-stat-box">
                        <span class="mst-stat-number"><?php echo count($tour_type_terms); ?></span>
                        <span class="mst-stat-label">Форматов туров</span>
                    </div>
                    <div class="mst-stat-box">
                        <span class="mst-stat-number"><?php echo count($tags); ?></span>
                        <span class="mst-stat-label">Рубрик (меток)</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- ГОРОДА -->
        <div class="mst-tab-content" data-tab="cities">
            <div class="mst-admin-card">
                <h2>🏙️ Настройка городов (родительские категории)</h2>
                <p class="description">Здесь настраиваются города - это родительские категории товаров WooCommerce. Каждый город будет доступен по своему URL.</p>
                
                <table class="wp-list-table widefat fixed striped mst-cities-table">
                    <thead>
                        <tr>
                            <th width="50">✓</th>
                            <th>Город</th>
                            <th>URL слаг</th>
                            <th>Категория по умолчанию</th>
                            <th>Товаров</th>
                            <th>Подкатегорий</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($product_categories) && !is_wp_error($product_categories)): ?>
                            <?php foreach ($product_categories as $category): 
                                $city_data = $city_settings[$category->term_id] ?? [];
                                $children = get_term_children($category->term_id, 'product_cat');
                            ?>
                            <tr>
                                <td>
                                    <input type="checkbox" 
                                           name="city_settings[<?php echo $category->term_id; ?>][enabled]" 
                                           value="1" 
                                           <?php checked(!empty($city_data['enabled'])); ?>>
                                </td>
                                <td>
                                    <strong><?php echo esc_html($category->name); ?></strong>
                                    <div class="row-actions">
                                        <span class="view">
                                            <a href="<?php echo get_term_link($category); ?>" target="_blank">Просмотр</a> |
                                        </span>
                                        <span class="edit">
                                            <a href="<?php echo admin_url('term.php?taxonomy=product_cat&tag_ID=' . $category->term_id); ?>">Редактировать</a>
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <input type="text" 
                                           name="city_settings[<?php echo $category->term_id; ?>][url_slug]" 
                                           value="<?php echo esc_attr($city_data['url_slug'] ?? $category->slug); ?>" 
                                           class="regular-text"
                                           placeholder="<?php echo esc_attr($category->slug); ?>">
                                </td>
                                <td>
                                    <select name="city_settings[<?php echo $category->term_id; ?>][default_category]">
                                        <option value="">— Выберите —</option>
                                        <?php 
                                        $subcats = get_terms(['taxonomy' => 'product_cat', 'parent' => $category->term_id, 'hide_empty' => false]);
                                        foreach ($subcats as $subcat): ?>
                                            <option value="<?php echo esc_attr($subcat->slug); ?>" <?php selected($city_data['default_category'] ?? '', $subcat->slug); ?>>
                                                <?php echo esc_html($subcat->name); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                                <td><?php echo $category->count; ?></td>
                                <td><?php echo count($children); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" style="text-align: center; padding: 40px;">
                                    <p style="color: #666;">Нет родительских категорий. Создайте категории товаров в WooCommerce → Товары → Категории.</p>
                                    <a href="<?php echo admin_url('edit-tags.php?taxonomy=product_cat&post_type=product'); ?>" class="button button-primary">Создать категорию</a>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
                
                <div class="mst-url-preview">
                    <h4>🔗 Примеры URL:</h4>
                    <code><?php echo home_url('/paris/ekskursii/'); ?></code><br>
                    <code><?php echo home_url('/paris/kvartiri/'); ?></code><br>
                    <code><?php echo home_url('/paris/bilety/'); ?></code>
                </div>
            </div>
        </div>
        
        <!-- КАТЕГОРИИ -->
        <div class="mst-tab-content" data-tab="categories">
            <div class="mst-admin-card">
                <h2>📁 Настройка категорий (типов товаров)</h2>
                <p class="description">Подкатегории внутри городов - типы услуг: экскурсии, квартиры, билеты и т.д.</p>
                
                <table class="wp-list-table widefat fixed striped">
                    <thead>
                        <tr>
                            <th width="50">✓</th>
                            <th>Категория</th>
                            <th>Родитель</th>
                            <th>URL слаг</th>
                            <th>Разрешенные домены</th>
                            <th>Лимит товаров</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        if (!empty($all_categories) && !is_wp_error($all_categories)): 
                            foreach ($all_categories as $category): 
                                if ($category->parent == 0) continue;
                                $cat_data = $category_settings[$category->term_id] ?? [];
                                $parent = get_term($category->parent, 'product_cat');
                        ?>
                            <tr>
                                <td>
                                    <input type="checkbox" 
                                           name="category_settings[<?php echo $category->term_id; ?>][enabled]" 
                                           value="1" 
                                           <?php checked(!empty($cat_data['enabled'])); ?>>
                                </td>
                                <td>
                                    <strong><?php echo esc_html($category->name); ?></strong>
                                    <span class="mst-badge"><?php echo $category->count; ?> товаров</span>
                                </td>
                                <td><?php echo $parent ? esc_html($parent->name) : '—'; ?></td>
                                <td>
                                    <input type="text" 
                                           name="category_settings[<?php echo $category->term_id; ?>][url_slug]" 
                                           value="<?php echo esc_attr($cat_data['url_slug'] ?? $category->slug); ?>" 
                                           class="regular-text"
                                           placeholder="<?php echo esc_attr($category->slug); ?>">
                                </td>
                                <td>
                                    <textarea name="category_settings[<?php echo $category->term_id; ?>][allowed_domains]" 
                                              rows="2" 
                                              style="width: 100%;" 
                                              placeholder="Один домен на строку"><?php 
                                        echo esc_textarea(implode("\n", $cat_data['allowed_domains'] ?? [])); 
                                    ?></textarea>
                                </td>
                                <td>
                                    <input type="number" 
                                           name="category_settings[<?php echo $category->term_id; ?>][product_limit]" 
                                           value="<?php echo esc_attr($cat_data['product_limit'] ?? 0); ?>" 
                                           min="0"
                                           style="width: 80px;"
                                           placeholder="0 = все">
                                </td>
                            </tr>
                        <?php 
                            endforeach;
                        endif; 
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- ВИДИМОСТЬ ФИЛЬТРОВ ПО КАТЕГОРИЯМ -->
        <div class="mst-tab-content" data-tab="filter-visibility">
            <div class="mst-admin-card">
                <h2>🎚️ Видимость фильтров по категориям</h2>
                <p class="description">Настройте какие фильтры показывать для каждой категории и города. Оставьте пустым для показа всех.</p>
                
                <?php if (!empty($all_categories) && !is_wp_error($all_categories)): ?>
                    <?php 
                    // Group by parent
                    $categories_by_parent = [];
                    foreach ($all_categories as $category) {
                        if ($category->parent == 0) {
                            $categories_by_parent[$category->term_id] = [
                                'parent' => $category,
                                'children' => []
                            ];
                        }
                    }
                    foreach ($all_categories as $category) {
                        if ($category->parent > 0 && isset($categories_by_parent[$category->parent])) {
                            $categories_by_parent[$category->parent]['children'][] = $category;
                        }
                    }
                    ?>
                    
                    <?php foreach ($categories_by_parent as $city_id => $data): 
                        $city = $data['parent'];
                        $vis = $filter_visibility[$city->term_id] ?? [];
                    ?>
                    <div class="mst-filter-visibility-group">
                        <h3>🏙️ <?php echo esc_html($city->name); ?> (город)</h3>
                        
                        <div class="mst-visibility-row">
                            <div class="mst-visibility-toggles">
                                <label>
                                    <input type="checkbox" name="filter_visibility[<?php echo $city->term_id; ?>][show_tour_type]" value="1" <?php checked(!isset($vis['show_tour_type']) || $vis['show_tour_type']); ?>>
                                    👥 Формат тура
                                </label>
                                <label>
                                    <input type="checkbox" name="filter_visibility[<?php echo $city->term_id; ?>][show_transport]" value="1" <?php checked(!isset($vis['show_transport']) || $vis['show_transport']); ?>>
                                    🚗 Транспорт
                                </label>
                                <label>
                                    <input type="checkbox" name="filter_visibility[<?php echo $city->term_id; ?>][show_price]" value="1" <?php checked(!isset($vis['show_price']) || $vis['show_price']); ?>>
                                    💰 Цена
                                </label>
                                <label>
                                    <input type="checkbox" name="filter_visibility[<?php echo $city->term_id; ?>][show_tags]" value="1" <?php checked(!isset($vis['show_tags']) || $vis['show_tags']); ?>>
                                    🏷️ Рубрики
                                </label>
                            </div>
                            
                            <div class="mst-visibility-selects">
                                <div class="mst-select-group">
                                    <label>Разрешенные форматы тура:</label>
                                    <div class="mst-terms-box" role="group" aria-label="Разрешенные форматы тура">
                                        <?php foreach ($tour_type_terms as $term): 
                                            $icon = $attribute_icons['tour_type'][$term->slug] ?? '👥';
                                        ?>
                                            <label class="mst-term-item">
                                                <input type="checkbox" name="filter_visibility[<?php echo $city->term_id; ?>][allowed_tour_types][]" value="<?php echo esc_attr($term->slug); ?>" <?php checked(in_array($term->slug, $vis['allowed_tour_types'] ?? [])); ?>>
                                                <span><?php echo $icon; ?> <?php echo esc_html($term->name); ?></span>
                                            </label>
                                        <?php endforeach; ?>
                                    </div>
                                    <small>Оставьте пустым для показа всех</small>
                                </div>
                                
                                <div class="mst-select-group">
                                    <label>Разрешенный транспорт:</label>
                                    <div class="mst-terms-box" role="group" aria-label="Разрешенный транспорт">
                                        <?php foreach ($transport_terms as $term): 
                                            $icon = $attribute_icons['transport'][$term->slug] ?? '🚗';
                                        ?>
                                            <label class="mst-term-item">
                                                <input type="checkbox" name="filter_visibility[<?php echo $city->term_id; ?>][allowed_transports][]" value="<?php echo esc_attr($term->slug); ?>" <?php checked(in_array($term->slug, $vis['allowed_transports'] ?? [])); ?>>
                                                <span><?php echo $icon; ?> <?php echo esc_html($term->name); ?></span>
                                            </label>
                                        <?php endforeach; ?>
                                    </div>
                                    <small>Оставьте пустым для показа всех</small>
                                </div>
                                
                                <div class="mst-select-group">
                                    <label>Разрешенные рубрики:</label>
                                    <div class="mst-terms-box" role="group" aria-label="Разрешенные рубрики">
                                        <?php foreach ($tags as $tag): 
                                            $icon = $attribute_icons['tags'][$tag->slug] ?? '🏷️';
                                        ?>
                                            <label class="mst-term-item">
                                                <input type="checkbox" name="filter_visibility[<?php echo $city->term_id; ?>][allowed_tags][]" value="<?php echo esc_attr($tag->slug); ?>" <?php checked(in_array($tag->slug, $vis['allowed_tags'] ?? [])); ?>>
                                                <span><?php echo $icon; ?> <?php echo esc_html($tag->name); ?></span>
                                            </label>
                                        <?php endforeach; ?>
                                    </div>
                                    <small>Оставьте пустым для показа всех</small>
                                </div>
                            </div>
                        </div>
                        
                        <!-- ДОЧЕРНИЕ КАТЕГОРИИ -->
                        <?php foreach ($data['children'] as $child): 
                            $child_vis = $filter_visibility[$child->term_id] ?? [];
                        ?>
                        <div class="mst-filter-visibility-child">
                            <h4>↳ <?php echo esc_html($child->name); ?></h4>
                            
                            <div class="mst-visibility-row">
                                <div class="mst-visibility-toggles">
                                    <label>
                                        <input type="checkbox" name="filter_visibility[<?php echo $child->term_id; ?>][show_tour_type]" value="1" <?php checked(!isset($child_vis['show_tour_type']) || $child_vis['show_tour_type']); ?>>
                                        👥 Формат
                                    </label>
                                    <label>
                                        <input type="checkbox" name="filter_visibility[<?php echo $child->term_id; ?>][show_transport]" value="1" <?php checked(!isset($child_vis['show_transport']) || $child_vis['show_transport']); ?>>
                                        🚗 Транспорт
                                    </label>
                                    <label>
                                        <input type="checkbox" name="filter_visibility[<?php echo $child->term_id; ?>][show_price]" value="1" <?php checked(!isset($child_vis['show_price']) || $child_vis['show_price']); ?>>
                                        💰 Цена
                                    </label>
                                    <label>
                                        <input type="checkbox" name="filter_visibility[<?php echo $child->term_id; ?>][show_tags]" value="1" <?php checked(!isset($child_vis['show_tags']) || $child_vis['show_tags']); ?>>
                                        🏷️ Рубрики
                                    </label>
                                </div>
                                
                                <div class="mst-visibility-selects mst-visibility-selects-child">
                                    <div class="mst-select-group">
                                        <label>Разрешенные форматы тура:</label>
                                        <div class="mst-terms-box" role="group">
                                            <?php foreach ($tour_type_terms as $term): 
                                                $icon = $attribute_icons['tour_type'][$term->slug] ?? '👥';
                                            ?>
                                                <label class="mst-term-item">
                                                    <input type="checkbox" name="filter_visibility[<?php echo $child->term_id; ?>][allowed_tour_types][]" value="<?php echo esc_attr($term->slug); ?>" <?php checked(in_array($term->slug, $child_vis['allowed_tour_types'] ?? [])); ?>>
                                                    <span><?php echo $icon; ?> <?php echo esc_html($term->name); ?></span>
                                                </label>
                                            <?php endforeach; ?>
                                        </div>
                                        <small>Пусто = наследует от родителя</small>
                                    </div>
                                    
                                    <div class="mst-select-group">
                                        <label>Разрешенный транспорт:</label>
                                        <div class="mst-terms-box" role="group">
                                            <?php foreach ($transport_terms as $term): 
                                                $icon = $attribute_icons['transport'][$term->slug] ?? '🚗';
                                            ?>
                                                <label class="mst-term-item">
                                                    <input type="checkbox" name="filter_visibility[<?php echo $child->term_id; ?>][allowed_transports][]" value="<?php echo esc_attr($term->slug); ?>" <?php checked(in_array($term->slug, $child_vis['allowed_transports'] ?? [])); ?>>
                                                    <span><?php echo $icon; ?> <?php echo esc_html($term->name); ?></span>
                                                </label>
                                            <?php endforeach; ?>
                                        </div>
                                        <small>Пусто = наследует от родителя</small>
                                    </div>
                                    
                                    <div class="mst-select-group">
                                        <label>Разрешенные рубрики:</label>
                                        <div class="mst-terms-box" role="group">
                                            <?php foreach ($tags as $tag): 
                                                $icon = $attribute_icons['tags'][$tag->slug] ?? '🏷️';
                                            ?>
                                                <label class="mst-term-item">
                                                    <input type="checkbox" name="filter_visibility[<?php echo $child->term_id; ?>][allowed_tags][]" value="<?php echo esc_attr($tag->slug); ?>" <?php checked(in_array($tag->slug, $child_vis['allowed_tags'] ?? [])); ?>>
                                                    <span><?php echo $icon; ?> <?php echo esc_html($tag->name); ?></span>
                                                </label>
                                            <?php endforeach; ?>
                                        </div>
                                        <small>Пусто = наследует от родителя</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- АТРИБУТЫ (ФИЛЬТРЫ) -->
        <div class="mst-tab-content" data-tab="filters">
            <div class="mst-admin-card">
                <h2>🔍 Атрибуты товаров</h2>
                <p class="description">Обзор атрибутов WooCommerce, используемых для фильтрации.</p>
                
                <h3>🚗 Способы передвижения (pa_transport)</h3>
                <table class="wp-list-table widefat fixed striped">
                    <thead>
                        <tr>
                            <th width="60">Иконка</th>
                            <th>Название</th>
                            <th>Слаг</th>
                            <th>Товаров</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($transport_terms) && !is_wp_error($transport_terms)): ?>
                            <?php foreach ($transport_terms as $term): 
                                $icon = $attribute_icons['transport'][$term->slug] ?? '🚗';
                            ?>
                            <tr>
                                <td style="font-size: 20px; text-align: center;"><?php echo $icon; ?></td>
                                <td><strong><?php echo esc_html($term->name); ?></strong></td>
                                <td><code><?php echo esc_html($term->slug); ?></code></td>
                                <td><?php echo $term->count; ?></td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="4">Нет атрибутов транспорта</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
                
                <h3 style="margin-top: 30px;">👥 Форматы тура (pa_tour-type)</h3>
                <table class="wp-list-table widefat fixed striped">
                    <thead>
                        <tr>
                            <th width="60">Иконка</th>
                            <th>Название</th>
                            <th>Слаг</th>
                            <th>Товаров</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($tour_type_terms) && !is_wp_error($tour_type_terms)): ?>
                            <?php foreach ($tour_type_terms as $term): 
                                $icon = $attribute_icons['tour_type'][$term->slug] ?? '👥';
                            ?>
                            <tr>
                                <td style="font-size: 20px; text-align: center;"><?php echo $icon; ?></td>
                                <td><strong><?php echo esc_html($term->name); ?></strong></td>
                                <td><code><?php echo esc_html($term->slug); ?></code></td>
                                <td><?php echo $term->count; ?></td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="4">Нет атрибутов формата тура</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
                
                <h3 style="margin-top: 30px;">🏷️ Рубрики (product_tag)</h3>
                <table class="wp-list-table widefat fixed striped">
                    <thead>
                        <tr>
                            <th width="60">Иконка</th>
                            <th>Название</th>
                            <th>Слаг</th>
                            <th>Товаров</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($tags) && !is_wp_error($tags)): ?>
                            <?php foreach ($tags as $tag): 
                                $icon = $attribute_icons['tags'][$tag->slug] ?? '🏷️';
                            ?>
                            <tr>
                                <td style="font-size: 20px; text-align: center;"><?php echo $icon; ?></td>
                                <td><strong><?php echo esc_html($tag->name); ?></strong></td>
                                <td><code><?php echo esc_html($tag->slug); ?></code></td>
                                <td><?php echo $tag->count; ?></td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="4">Нет рубрик. <a href="<?php echo admin_url('edit-tags.php?taxonomy=product_tag&post_type=product'); ?>">Добавить метки →</a></td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
                
                <p style="margin-top: 20px;">
                    <a href="<?php echo admin_url('edit.php?post_type=product&page=product_attributes'); ?>" class="button">Управление атрибутами →</a>
                    <a href="<?php echo admin_url('edit-tags.php?taxonomy=product_tag&post_type=product'); ?>" class="button">Управление рубриками →</a>
                </p>
            </div>
        </div>
        
        <!-- ИКОНКИ ДЛЯ АТРИБУТОВ -->
        <div class="mst-tab-content" data-tab="icons">
            <div class="mst-admin-card">
                <h2>🎨 Настройка иконок для атрибутов</h2>
                <p class="description">Выберите эмодзи или загрузите изображение через медиатеку WordPress/Elementor.</p>
                
                <!-- Способы передвижения -->
                <h3>🚗 Способы передвижения</h3>
                <div class="mst-icons-grid">
                    <?php if (!empty($transport_terms) && !is_wp_error($transport_terms)): ?>
                        <?php foreach ($transport_terms as $term): 
                            $current_icon = $attribute_icons['transport'][$term->slug] ?? '🚗';
                            $current_image = $attribute_icons['transport_images'][$term->slug] ?? '';
                            $icon_type = !empty($current_image) ? 'image' : 'emoji';
                        ?>
                        <div class="mst-icon-item" data-term="<?php echo esc_attr($term->slug); ?>" data-type="transport">
                            <label>
                                <strong><?php echo esc_html($term->name); ?></strong>
                                <div class="mst-icon-selector">
                                    <!-- Тип иконки -->
                                    <div class="mst-icon-type-switcher">
                                        <label>
                                            <input type="radio" name="icon_type_transport_<?php echo esc_attr($term->slug); ?>" value="emoji" <?php checked($icon_type, 'emoji'); ?> class="mst-icon-type-radio">
                                            Эмодзи
                                        </label>
                                        <label>
                                            <input type="radio" name="icon_type_transport_<?php echo esc_attr($term->slug); ?>" value="image" <?php checked($icon_type, 'image'); ?> class="mst-icon-type-radio">
                                            Изображение
                                        </label>
                                    </div>
                                    
                                    <!-- Эмодзи выбор -->
                                    <div class="mst-icon-emoji-wrap" style="<?php echo $icon_type === 'image' ? 'display:none;' : ''; ?>">
                                        <span class="mst-current-icon"><?php echo $current_icon; ?></span>
                                        <select name="attribute_icons[transport][<?php echo esc_attr($term->slug); ?>]" class="mst-icon-select">
                                            <?php foreach ($available_icons as $icon => $label): ?>
                                                <option value="<?php echo esc_attr($icon); ?>" <?php selected($current_icon, $icon); ?>><?php echo $icon; ?> <?php echo esc_html($label); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    
                                    <!-- Изображение выбор -->
                                    <div class="mst-icon-image-wrap" style="<?php echo $icon_type === 'emoji' ? 'display:none;' : ''; ?>">
                                        <div class="mst-image-preview">
                                            <?php if ($current_image): ?>
                                                <img src="<?php echo esc_url($current_image); ?>" alt="">
                                            <?php else: ?>
                                                <span class="mst-no-image">Нет изображения</span>
                                            <?php endif; ?>
                                        </div>
                                        <input type="hidden" name="attribute_icons[transport_images][<?php echo esc_attr($term->slug); ?>]" value="<?php echo esc_url($current_image); ?>" class="mst-image-url-input">
                                        <button type="button" class="button mst-upload-image-btn">📁 Выбрать</button>
                                        <button type="button" class="button mst-remove-image-btn" style="<?php echo empty($current_image) ? 'display:none;' : ''; ?>">✕</button>
                                    </div>
                                </div>
                            </label>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>Нет атрибутов транспорта</p>
                    <?php endif; ?>
                </div>
                
                <!-- Форматы тура -->
                <h3 style="margin-top: 30px;">👥 Форматы тура</h3>
                <div class="mst-icons-grid">
                    <?php if (!empty($tour_type_terms) && !is_wp_error($tour_type_terms)): ?>
                        <?php foreach ($tour_type_terms as $term): 
                            $current_icon = $attribute_icons['tour_type'][$term->slug] ?? '👥';
                            $current_image = $attribute_icons['tour_type_images'][$term->slug] ?? '';
                            $icon_type = !empty($current_image) ? 'image' : 'emoji';
                        ?>
                        <div class="mst-icon-item" data-term="<?php echo esc_attr($term->slug); ?>" data-type="tour_type">
                            <label>
                                <strong><?php echo esc_html($term->name); ?></strong>
                                <div class="mst-icon-selector">
                                    <div class="mst-icon-type-switcher">
                                        <label>
                                            <input type="radio" name="icon_type_tour_type_<?php echo esc_attr($term->slug); ?>" value="emoji" <?php checked($icon_type, 'emoji'); ?> class="mst-icon-type-radio">
                                            Эмодзи
                                        </label>
                                        <label>
                                            <input type="radio" name="icon_type_tour_type_<?php echo esc_attr($term->slug); ?>" value="image" <?php checked($icon_type, 'image'); ?> class="mst-icon-type-radio">
                                            Изображение
                                        </label>
                                    </div>
                                    
                                    <div class="mst-icon-emoji-wrap" style="<?php echo $icon_type === 'image' ? 'display:none;' : ''; ?>">
                                        <span class="mst-current-icon"><?php echo $current_icon; ?></span>
                                        <select name="attribute_icons[tour_type][<?php echo esc_attr($term->slug); ?>]" class="mst-icon-select">
                                            <?php foreach ($available_icons as $icon => $label): ?>
                                                <option value="<?php echo esc_attr($icon); ?>" <?php selected($current_icon, $icon); ?>><?php echo $icon; ?> <?php echo esc_html($label); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    
                                    <div class="mst-icon-image-wrap" style="<?php echo $icon_type === 'emoji' ? 'display:none;' : ''; ?>">
                                        <div class="mst-image-preview">
                                            <?php if ($current_image): ?>
                                                <img src="<?php echo esc_url($current_image); ?>" alt="">
                                            <?php else: ?>
                                                <span class="mst-no-image">Нет изображения</span>
                                            <?php endif; ?>
                                        </div>
                                        <input type="hidden" name="attribute_icons[tour_type_images][<?php echo esc_attr($term->slug); ?>]" value="<?php echo esc_url($current_image); ?>" class="mst-image-url-input">
                                        <button type="button" class="button mst-upload-image-btn">📁 Выбрать</button>
                                        <button type="button" class="button mst-remove-image-btn" style="<?php echo empty($current_image) ? 'display:none;' : ''; ?>">✕</button>
                                    </div>
                                </div>
                            </label>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>Нет атрибутов формата тура</p>
                    <?php endif; ?>
                </div>
                
                <!-- Рубрики -->
                <h3 style="margin-top: 30px;">🏷️ Рубрики (метки товаров)</h3>
                <div class="mst-icons-grid">
                    <?php if (!empty($tags) && !is_wp_error($tags)): ?>
                        <?php foreach ($tags as $tag): 
                            $current_icon = $attribute_icons['tags'][$tag->slug] ?? '🏷️';
                            $current_image = $attribute_icons['tags_images'][$tag->slug] ?? '';
                            $icon_type = !empty($current_image) ? 'image' : 'emoji';
                        ?>
                        <div class="mst-icon-item" data-term="<?php echo esc_attr($tag->slug); ?>" data-type="tags">
                            <label>
                                <strong><?php echo esc_html($tag->name); ?></strong>
                                <div class="mst-icon-selector">
                                    <div class="mst-icon-type-switcher">
                                        <label>
                                            <input type="radio" name="icon_type_tags_<?php echo esc_attr($tag->slug); ?>" value="emoji" <?php checked($icon_type, 'emoji'); ?> class="mst-icon-type-radio">
                                            Эмодзи
                                        </label>
                                        <label>
                                            <input type="radio" name="icon_type_tags_<?php echo esc_attr($tag->slug); ?>" value="image" <?php checked($icon_type, 'image'); ?> class="mst-icon-type-radio">
                                            Изображение
                                        </label>
                                    </div>
                                    
                                    <div class="mst-icon-emoji-wrap" style="<?php echo $icon_type === 'image' ? 'display:none;' : ''; ?>">
                                        <span class="mst-current-icon"><?php echo $current_icon; ?></span>
                                        <select name="attribute_icons[tags][<?php echo esc_attr($tag->slug); ?>]" class="mst-icon-select">
                                            <?php foreach ($available_icons as $icon => $label): ?>
                                                <option value="<?php echo esc_attr($icon); ?>" <?php selected($current_icon, $icon); ?>><?php echo $icon; ?> <?php echo esc_html($label); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    
                                    <div class="mst-icon-image-wrap" style="<?php echo $icon_type === 'emoji' ? 'display:none;' : ''; ?>">
                                        <div class="mst-image-preview">
                                            <?php if ($current_image): ?>
                                                <img src="<?php echo esc_url($current_image); ?>" alt="">
                                            <?php else: ?>
                                                <span class="mst-no-image">Нет изображения</span>
                                            <?php endif; ?>
                                        </div>
                                        <input type="hidden" name="attribute_icons[tags_images][<?php echo esc_attr($tag->slug); ?>]" value="<?php echo esc_url($current_image); ?>" class="mst-image-url-input">
                                        <button type="button" class="button mst-upload-image-btn">📁 Выбрать</button>
                                        <button type="button" class="button mst-remove-image-btn" style="<?php echo empty($current_image) ? 'display:none;' : ''; ?>">✕</button>
                                    </div>
                                </div>
                            </label>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>Нет рубрик. <a href="<?php echo admin_url('edit-tags.php?taxonomy=product_tag&post_type=product'); ?>">Добавить метки →</a></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- URL СТРУКТУРА -->
        <div class="mst-tab-content" data-tab="urls">
            <div class="mst-admin-card">
                <h2>🔗 Структура URL</h2>
                
                <table class="form-table">
                    <tr>
                        <th>Шаблон URL</th>
                        <td>
                            <input type="text" name="url_structure" value="<?php echo esc_attr($settings['url_structure'] ?? '/{city}/{type}/'); ?>" class="regular-text">
                            <p class="description">
                                Доступные переменные: <code>{city}</code>, <code>{type}</code><br>
                                Пример: <code>/{city}/{type}/</code> → <code>/paris/ekskursii/</code>
                            </p>
                        </td>
                    </tr>
                </table>
                
                <h3>Примеры сгенерированных URL:</h3>
                <div class="mst-url-examples">
                    <?php 
                    if (!empty($product_categories) && !is_wp_error($product_categories)):
                        foreach ($product_categories as $city):
                            $city_slug = $city_settings[$city->term_id]['url_slug'] ?? $city->slug;
                            $subcats = get_terms(['taxonomy' => 'product_cat', 'parent' => $city->term_id, 'hide_empty' => false, 'number' => 3]);
                            
                            if (!empty($subcats) && !is_wp_error($subcats)):
                                foreach ($subcats as $subcat):
                                    $cat_slug = $category_settings[$subcat->term_id]['url_slug'] ?? $subcat->slug;
                    ?>
                        <div class="mst-url-example">
                            <span class="mst-url-label"><?php echo esc_html($city->name); ?> → <?php echo esc_html($subcat->name); ?>:</span>
                            <code><?php echo home_url('/' . $city_slug . '/' . $cat_slug . '/'); ?></code>
                        </div>
                    <?php 
                                endforeach;
                            endif;
                        endforeach;
                    endif;
                    ?>
                </div>
                
                <div class="mst-notice mst-notice-info" style="margin-top: 20px;">
                    <p><strong>💡 Совет:</strong> После изменения настроек URL нажмите "Сохранить изменения", а затем перейдите в Настройки → Постоянные ссылки и нажмите "Сохранить" для обновления правил перезаписи.</p>
                </div>
            </div>
        </div>
        
        <!-- АНАЛИТИКА -->
        <div class="mst-tab-content" data-tab="analytics">
            <div class="mst-admin-card">
                <h2>📊 Аналитика фильтров</h2>
                
                <div class="mst-stats-grid">
                    <div class="mst-stat-box">
                        <span class="mst-stat-number"><?php echo intval($analytics_summary['total_searches'] ?? 0); ?></span>
                        <span class="mst-stat-label">Всего поисков</span>
                    </div>
                    <div class="mst-stat-box">
                        <span class="mst-stat-number"><?php echo intval($analytics_summary['searches_today'] ?? 0); ?></span>
                        <span class="mst-stat-label">Сегодня</span>
                    </div>
                    <div class="mst-stat-box">
                        <span class="mst-stat-number"><?php echo floatval($analytics_summary['avg_results'] ?? 0); ?></span>
                        <span class="mst-stat-label">Среднее кол-во результатов</span>
                    </div>
                </div>
                
                <h3 style="margin-top: 30px;">🔥 Популярные форматы туров</h3>
                <?php 
                $popular_tour_types = $analytics_summary['popular_tour_types'] ?? [];
                arsort($popular_tour_types);
                $popular_tour_types = array_slice($popular_tour_types, 0, 5, true);
                ?>
                <?php if (!empty($popular_tour_types)): ?>
                <table class="wp-list-table widefat fixed striped">
                    <thead><tr><th>Формат</th><th>Запросов</th></tr></thead>
                    <tbody>
                        <?php foreach ($popular_tour_types as $slug => $count): 
                            $icon = $attribute_icons['tour_type'][$slug] ?? '👥';
                        ?>
                        <tr>
                            <td><?php echo $icon; ?> <?php echo esc_html($slug); ?></td>
                            <td><?php echo intval($count); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php else: ?>
                <p style="color: #666;">Пока нет данных</p>
                <?php endif; ?>
                
                <h3 style="margin-top: 30px;">🚗 Популярный транспорт</h3>
                <?php 
                $popular_transports = $analytics_summary['popular_transports'] ?? [];
                arsort($popular_transports);
                $popular_transports = array_slice($popular_transports, 0, 5, true);
                ?>
                <?php if (!empty($popular_transports)): ?>
                <table class="wp-list-table widefat fixed striped">
                    <thead><tr><th>Транспорт</th><th>Запросов</th></tr></thead>
                    <tbody>
                        <?php foreach ($popular_transports as $slug => $count): 
                            $icon = $attribute_icons['transport'][$slug] ?? '🚗';
                        ?>
                        <tr>
                            <td><?php echo $icon; ?> <?php echo esc_html($slug); ?></td>
                            <td><?php echo intval($count); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php else: ?>
                <p style="color: #666;">Пока нет данных</p>
                <?php endif; ?>
                
                <h3 style="margin-top: 30px;">📍 Популярные категории</h3>
                <?php 
                $popular_categories = $analytics_summary['popular_categories'] ?? [];
                arsort($popular_categories);
                $popular_categories = array_slice($popular_categories, 0, 5, true);
                ?>
                <?php if (!empty($popular_categories)): ?>
                <table class="wp-list-table widefat fixed striped">
                    <thead><tr><th>Категория</th><th>Запросов</th></tr></thead>
                    <tbody>
                        <?php foreach ($popular_categories as $slug => $count): ?>
                        <tr>
                            <td><?php echo esc_html($slug); ?></td>
                            <td><?php echo intval($count); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php else: ?>
                <p style="color: #666;">Пока нет данных</p>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- ПОЛЬЗОВАТЕЛИ -->
        <div class="mst-tab-content" data-tab="users">
            <div class="mst-admin-card">
                <h2>👥 Аналитика пользователей</h2>
                <p class="description">Информация о посетителях сайта: IP, устройства, браузеры и поведение.</p>
                
                <div class="mst-stats-grid">
                    <div class="mst-stat-box">
                        <span class="mst-stat-number"><?php echo count($user_analytics['visitors'] ?? []); ?></span>
                        <span class="mst-stat-label">Уникальных посетителей</span>
                    </div>
                    <div class="mst-stat-box">
                        <span class="mst-stat-number">
                            <?php 
                            $logged_in = 0;
                            foreach (($user_analytics['visitors'] ?? []) as $v) {
                                if (!empty($v['user_id'])) $logged_in++;
                            }
                            echo $logged_in;
                            ?>
                        </span>
                        <span class="mst-stat-label">Авторизованных</span>
                    </div>
                    <div class="mst-stat-box">
                        <span class="mst-stat-number">
                            <?php 
                            $mobile = 0;
                            foreach (($user_analytics['visitors'] ?? []) as $v) {
                                if (stripos($v['device'] ?? '', 'Mobile') !== false) $mobile++;
                            }
                            echo $mobile;
                            ?>
                        </span>
                        <span class="mst-stat-label">С мобильных</span>
                    </div>
                </div>
                
                <h3 style="margin-top: 30px;">📱 Последние посетители</h3>
                <?php 
                $visitors = array_slice(array_reverse($user_analytics['visitors'] ?? []), 0, 20);
                ?>
                <?php if (!empty($visitors)): ?>
                <table class="wp-list-table widefat fixed striped">
                    <thead>
                        <tr>
                            <th width="140">Время</th>
                            <th width="130">IP</th>
                            <th width="80">Тип</th>
                            <th>Устройство</th>
                            <th>Браузер</th>
                            <th>Пользователь</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($visitors as $visitor): ?>
                        <tr>
                            <td><?php echo esc_html($visitor['time'] ?? '—'); ?></td>
                            <td><code><?php echo esc_html($visitor['ip'] ?? '—'); ?></code></td>
                            <td>
                                <?php 
                                $device_type = $visitor['device_type'] ?? 'Unknown';
                                $device_icon = '💻';
                                if ($device_type === 'Mobile') $device_icon = '📱';
                                elseif ($device_type === 'Tablet') $device_icon = '📲';
                                echo $device_icon . ' ' . esc_html($device_type);
                                ?>
                            </td>
                            <td><?php echo esc_html($visitor['device'] ?? '—'); ?></td>
                            <td><?php echo esc_html($visitor['browser'] ?? '—'); ?></td>
                            <td>
                                <?php if (!empty($visitor['user_id'])): 
                                    $user = get_user_by('id', $visitor['user_id']);
                                ?>
                                    <a href="<?php echo admin_url('user-edit.php?user_id=' . $visitor['user_id']); ?>">
                                        <?php echo esc_html($user ? $user->display_name : 'User #' . $visitor['user_id']); ?>
                                    </a>
                                <?php else: ?>
                                    <span style="color: #999;">Гость</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php else: ?>
                <p style="color: #666;">Пока нет данных о посетителях. Данные начнут собираться после использования фильтров.</p>
                <?php endif; ?>
                
                <h3 style="margin-top: 30px;">📈 Топ товаров по посещаемости</h3>
                <?php 
                $top_products = $user_analytics['top_products'] ?? [];
                arsort($top_products);
                $top_products = array_slice($top_products, 0, 10, true);
                ?>
                <?php if (!empty($top_products)): ?>
                <table class="wp-list-table widefat fixed striped">
                    <thead>
                        <tr>
                            <th>Товар</th>
                            <th width="120">Просмотров</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($top_products as $product_id => $views): 
                            $product = wc_get_product($product_id);
                        ?>
                        <tr>
                            <td>
                                <?php if ($product): ?>
                                    <a href="<?php echo get_edit_post_link($product_id); ?>">
                                        <?php echo esc_html($product->get_name()); ?>
                                    </a>
                                <?php else: ?>
                                    <span style="color: #999;">Товар #<?php echo $product_id; ?> (удален)</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo intval($views); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php else: ?>
                <p style="color: #666;">Пока нет данных о просмотрах товаров.</p>
                <?php endif; ?>
                
                <div style="margin-top: 20px;">
                    <button type="submit" name="mst_reset_user_analytics" class="button" onclick="return confirm('Вы уверены? Это очистит все данные о посетителях.');">🗑️ Очистить данные пользователей</button>
                </div>
            </div>
        </div>
        
        <p class="submit">
            <input type="submit" name="mst_filters_save" class="button button-primary button-hero" value="💾 Сохранить изменения">
        </p>
    </form>
</div>

<script>
jQuery(document).ready(function($) {
    // Tabs
    $('.mst-tab').on('click', function() {
        var tab = $(this).data('tab');
        
        $('.mst-tab').removeClass('active');
        $(this).addClass('active');
        
        $('.mst-tab-content').removeClass('active');
        $('.mst-tab-content[data-tab="' + tab + '"]').addClass('active');
    });
    
    // Icon selector preview update
    $('.mst-icon-select').on('change', function() {
        var icon = $(this).val();
        $(this).closest('.mst-icon-selector').find('.mst-current-icon').text(icon);
    });
});
</script>
