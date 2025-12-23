<?php
/**
 * Plugin Name: MySuperTour Map
 * Description: Карта экскурсий с товарами из WooCommerce + Яндекс Карты
 * Version: 1.1.0
 * Author: Telegram @l1ghtsun
 * Author URI: https://t.me/l1ghtsun
 */

if(!defined('ABSPATH')) exit;

define('MST_MAP_VERSION', '1.1.0');
define('MST_MAP_PATH', plugin_dir_path(__FILE__));
define('MST_MAP_URL', plugin_dir_url(__FILE__));

class MySuperTour_Map {
    private static $instance = null;
    
    public static function instance() {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        add_action('admin_menu', [$this, 'add_admin_menu']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
        add_shortcode('mst_map', [$this, 'render_map']);
        add_action('add_meta_boxes', [$this, 'add_coordinates_meta_box']);
        add_action('save_post_product', [$this, 'save_coordinates_meta'], 10, 1);
    }
    
    public function add_admin_menu() {
        add_submenu_page(
            'mysupertour-hub',
            'Карта',
            '🗺️ Карта',
            'manage_options',
            'mysupertour-map',
            [$this, 'render_admin_page']
        );
    }
    
    public function render_admin_page() {
        // Сохранение настроек
        if (isset($_POST['mst_save_map_settings']) && check_admin_referer('mst_map_settings', 'mst_map_nonce')) {
            $settings = [
                'api_key' => sanitize_text_field($_POST['yandex_api_key']),
                'center_lat' => sanitize_text_field($_POST['center_lat']),
                'center_lng' => sanitize_text_field($_POST['center_lng']),
                'zoom' => intval($_POST['zoom']),
                'map_type' => sanitize_text_field($_POST['map_type']),
                'marker_style' => sanitize_text_field($_POST['marker_style']),
                'marker_color' => sanitize_text_field($_POST['marker_color'])
            ];
            update_option('mst_map_settings', $settings);
            echo '<div class="notice notice-success"><p>✅ Настройки сохранены!</p></div>';
        }
        
        $settings = get_option('mst_map_settings', [
            'api_key' => '',
            'center_lat' => '48.8566',
            'center_lng' => '2.3522',
            'zoom' => '13',
            'map_type' => 'yandex',
            'marker_style' => 'price',
            'marker_color' => '#FF385C'
        ]);
        
        $products_count = wp_count_posts('product')->publish;
        $products_with_coords = $this->count_products_with_coords();
        ?>
        <div class="wrap">
            <h1>🗺️ Настройки Карты</h1>
            
            <div style="background:#fff;padding:20px;margin:20px 0;border-radius:8px;">
                <h2>⚙️ Основные настройки</h2>
                <form method="post">
                    <?php wp_nonce_field('mst_map_settings', 'mst_map_nonce'); ?>
                    
                    <table class="form-table">
                        <tr>
                            <th><label>Тип карты</label></th>
                            <td>
                                <select name="map_type" class="regular-text">
                                    <option value="yandex" <?php selected($settings['map_type'], 'yandex'); ?>>Яндекс Карты</option>
                                    <option value="google" <?php selected($settings['map_type'], 'google'); ?>>Google Maps</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th><label>API Ключ (Яндекс)</label></th>
                            <td>
                                <input type="text" name="yandex_api_key" class="regular-text" value="<?php echo esc_attr($settings['api_key']); ?>" placeholder="ваш-api-ключ">
                                <p class="description">
                                    Получите ключ на <a href="https://developer.tech.yandex.ru/" target="_blank">developer.tech.yandex.ru</a><br>
                                    <strong>Важно:</strong> Выберите API <strong>"JavaScript API и HTTP Геокодер"</strong>
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <th><label>Центр карты (широта)</label></th>
                            <td>
                                <input type="text" name="center_lat" class="regular-text" value="<?php echo esc_attr($settings['center_lat']); ?>">
                                <p class="description">Например: 48.8566 (для Парижа)</p>
                            </td>
                        </tr>
                        <tr>
                            <th><label>Центр карты (долгота)</label></th>
                            <td>
                                <input type="text" name="center_lng" class="regular-text" value="<?php echo esc_attr($settings['center_lng']); ?>">
                                <p class="description">Например: 2.3522 (для Парижа)</p>
                            </td>
                        </tr>
                        <tr>
                            <th><label>Масштаб (zoom)</label></th>
                            <td>
                                <input type="number" name="zoom" class="regular-text" value="<?php echo esc_attr($settings['zoom']); ?>" min="1" max="20">
                                <p class="description">От 1 (весь мир) до 20 (улица)</p>
                            </td>
                        </tr>
                    </table>
                    
                    <h3 style="margin-top:30px;padding-top:30px;border-top:2px solid #f0f0f0;">🎨 Настройки маркеров</h3>
                    
                    <table class="form-table">
                        <tr>
                            <th><label>Стиль маркеров</label></th>
                            <td>
                                <select name="marker_style" class="regular-text">
                                    <option value="price" <?php selected($settings['marker_style'], 'price'); ?>>Маркер с ценой 🔥</option>
                                    <option value="dot" <?php selected($settings['marker_style'], 'dot'); ?>>Красная точка</option>
                                    <option value="pin" <?php selected($settings['marker_style'], 'pin'); ?>>Пин</option>
                                </select>
                                <p class="description">Рекомендуем "Цена" - самый красивый стиль</p>
                            </td>
                        </tr>
                        <tr>
                            <th><label>Цвет акцента</label></th>
                            <td>
                                <input type="color" name="marker_color" value="<?php echo esc_attr($settings['marker_color']); ?>" 
                                       style="width:80px;height:40px;border:2px solid #ddd;border-radius:8px;cursor:pointer;">
                                <span style="margin-left:10px;color:#666;"><?php echo esc_html($settings['marker_color']); ?></span>
                                <p class="description">Цвет для цены и кнопок в балунах</p>
                            </td>
                        </tr>
                    </table>
                    
                    <p class="submit">
                        <button type="submit" name="mst_save_map_settings" class="button button-primary" style="padding:10px 30px;height:auto;">💾 Сохранить настройки</button>
                    </p>
                </form>
            </div>
            
            <div style="background:#fff;padding:20px;margin:20px 0;border-radius:8px;">
                <h2>📍 Товары на карте</h2>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin:20px 0;">
                    <div style="padding:20px;background:#f8f9fa;border-radius:8px;text-align:center;">
                        <div style="font-size:32px;font-weight:700;color:#222;"><?php echo $products_count; ?></div>
                        <div style="color:#666;margin-top:5px;">Всего товаров</div>
                    </div>
                    <div style="padding:20px;background:<?php echo $products_with_coords > 0 ? '#e8f5f1' : '#fff3cd'; ?>;border-radius:8px;text-align:center;">
                        <div style="font-size:32px;font-weight:700;color:<?php echo $products_with_coords > 0 ? '#00c896' : '#ffc107'; ?>;">
                            <?php echo $products_with_coords; ?>
                        </div>
                        <div style="color:#666;margin-top:5px;">С координатами</div>
                    </div>
                </div>
                
                <?php if ($products_with_coords === 0): ?>
                    <div style="padding:20px;background:#fff3cd;border-left:4px solid #ffc107;margin:20px 0;border-radius:4px;">
                        <strong style="display:block;margin-bottom:10px;">⚠️ Внимание!</strong>
                        У товаров нет координат. Откройте любой товар и добавьте координаты в боковой панели <strong>"📍 Координаты на карте"</strong>.
                    </div>
                <?php else: ?>
                    <div style="padding:20px;background:#e8f5f1;border-left:4px solid #00c896;margin:20px 0;border-radius:4px;">
                        <strong style="display:block;margin-bottom:10px;">✅ Отлично!</strong>
                        Товары с координатами будут автоматически отображаться на карте.
                    </div>
                <?php endif; ?>
                
                <div style="margin-top:20px;padding:15px;background:#f0f0f0;border-radius:8px;">
                    <strong>📝 Шорткод для вставки:</strong><br>
                    <code style="display:block;margin-top:10px;padding:10px;background:#fff;border-radius:6px;font-size:14px;">[mst_map]</code>
                    <p style="margin-top:10px;font-size:13px;color:#666;">Вставьте этот шорткод на любую страницу для отображения карты</p>
                </div>
            </div>
            
            <div style="background:#fff;padding:20px;margin:20px 0;border-radius:8px;">
                <h2>💡 Подсказки</h2>
                <ul style="line-height:1.8;">
                    <li><strong>Как добавить товар на карту?</strong><br>
                        Откройте товар → найдите блок "📍 Координаты на карте" справа → введите координаты → сохраните
                    </li>
                    <li><strong>Где взять координаты?</strong><br>
                        Откройте <a href="https://yandex.ru/maps/" target="_blank">Яндекс.Карты</a> → найдите место → клик правой кнопкой → "Что здесь?" → скопируйте координаты
                    </li>
                    <li><strong>Карта не показывает маркеры?</strong><br>
                        Проверьте что у товаров есть координаты и API ключ Яндекс активен
                    </li>
                </ul>
            </div>
        </div>
        
        <style>
        .form-table th {
            padding: 20px 10px 20px 0;
            font-weight: 600;
        }
        .form-table td {
            padding: 20px 10px;
        }
        </style>
        <?php
    }
    
    public function enqueue_assets() {
        $settings = get_option('mst_map_settings', [
            'map_type' => 'yandex',
            'api_key' => '',
            'marker_style' => 'price',
            'marker_color' => '#FF385C'
        ]);
        
        if ($settings['map_type'] === 'yandex' && !empty($settings['api_key'])) {
            wp_enqueue_script('yandex-maps', 'https://api-maps.yandex.ru/2.1/?apikey=' . $settings['api_key'] . '&lang=ru_RU', [], null, true);
        }
        
        wp_enqueue_style('mst-map', MST_MAP_URL . 'assets/css/map.css', [], MST_MAP_VERSION);
        wp_enqueue_script('mst-map', MST_MAP_URL . 'assets/js/map.js', ['jquery'], MST_MAP_VERSION, true);
        
        // Передаем данные товаров и настройки
        $products = $this->get_products_with_coords();
        wp_localize_script('mst-map', 'mstMap', [
            'products' => $products,
            'settings' => $settings
        ]);
    }
    
    private function get_products_with_coords() {
    $args = [
        'post_type' => 'product',
        'posts_per_page' => 100,
        'post_status' => 'publish'
    ];
    
    $query = new WP_Query($args);
    $products = [];
    
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $id = get_the_ID();
            $product = wc_get_product($id);
            
            $lat = get_post_meta($id, '_latitude', true);
            $lng = get_post_meta($id, '_longitude', true);
            
            if ($lat && $lng) {
                // Получаем ТОЛЬКО текущую цену
                $price = $product->get_price();
                $currency = get_woocommerce_currency_symbol();
                
                // Форматируем цену красиво
                $price_formatted = number_format((float)$price, 2, ',', ' ') . ' ' . $currency;
                
                $products[] = [
                    'id' => $id,
                    'title' => get_the_title(),
                    'price' => $price_formatted,
                    'image' => get_the_post_thumbnail_url($id, 'large') ?: MST_MAP_URL . 'assets/img/placeholder.jpg',
                    'link' => get_permalink($id),
                    'lat' => floatval($lat),
                    'lng' => floatval($lng)
                ];
            }
        }
        wp_reset_postdata();
    }
    
    return $products;
	}
    
    private function count_products_with_coords() {
        global $wpdb;
        $count = $wpdb->get_var("
            SELECT COUNT(DISTINCT p.ID) 
            FROM {$wpdb->posts} p
            INNER JOIN {$wpdb->postmeta} m1 ON p.ID = m1.post_id AND m1.meta_key = '_latitude'
            INNER JOIN {$wpdb->postmeta} m2 ON p.ID = m2.post_id AND m2.meta_key = '_longitude'
            WHERE p.post_type = 'product' 
            AND p.post_status = 'publish'
            AND m1.meta_value != ''
            AND m2.meta_value != ''
        ");
        return intval($count);
    }
    
    public function render_map($atts) {
        $atts = shortcode_atts([
            'height' => '600px'
        ], $atts);
        
        ob_start();
        ?>
        <div class="mst-map-wrapper" style="height: <?php echo esc_attr($atts['height']); ?>;">
            <div id="mst-map" style="width:100%;height:100%;"></div>
        </div>
        <?php
        return ob_get_clean();
    }
    
    /**
     * Добавляем поля координат в админку товара
     */
    public function add_coordinates_meta_box() {
        add_meta_box(
            'mst_coordinates',
            '📍 Координаты на карте',
            [$this, 'render_coordinates_meta_box'],
            'product',
            'side',
            'default'
        );
    }
    
    public function render_coordinates_meta_box($post) {
        $lat = get_post_meta($post->ID, '_latitude', true);
        $lng = get_post_meta($post->ID, '_longitude', true);
        ?>
        <div style="padding:10px 0;">
            <p>
                <label style="font-weight:600;display:block;margin-bottom:5px;">Широта (Latitude)</label>
                <input type="text" name="_latitude" value="<?php echo esc_attr($lat); ?>" 
                       placeholder="48.8566" style="width:100%;" 
                       pattern="[-+]?[0-9]*\.?[0-9]+">
                <small style="color:#666;">Например: 48.8566</small>
            </p>
            <p style="margin-top:15px;">
                <label style="font-weight:600;display:block;margin-bottom:5px;">Долгота (Longitude)</label>
                <input type="text" name="_longitude" value="<?php echo esc_attr($lng); ?>" 
                       placeholder="2.3522" style="width:100%;"
                       pattern="[-+]?[0-9]*\.?[0-9]+">
                <small style="color:#666;">Например: 2.3522</small>
            </p>
            <p style="margin-top:15px;padding:10px;background:#f0f0f0;border-radius:6px;">
                <small style="color:#666;">
                    💡 <a href="https://yandex.ru/maps/" target="_blank" style="font-weight:600;">Найти на Яндекс.Картах</a><br>
                    Клик правой кнопкой → "Что здесь?" → скопируйте координаты
                </small>
            </p>
        </div>
        <?php
    }
    
    public function save_coordinates_meta($post_id) {
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
        if (!current_user_can('edit_post', $post_id)) return;
        
        if (isset($_POST['_latitude'])) {
            update_post_meta($post_id, '_latitude', sanitize_text_field($_POST['_latitude']));
        }
        
        if (isset($_POST['_longitude'])) {
            update_post_meta($post_id, '_longitude', sanitize_text_field($_POST['_longitude']));
        }
    }
}

add_action('plugins_loaded', function() {
    MySuperTour_Map::instance();
}, 1);