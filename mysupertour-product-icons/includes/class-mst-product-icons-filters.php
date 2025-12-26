<?php
/**
 * Адаптивные фильтры с гибкими настройками стилей
 * Author: Telegram @l1ghtsun
 * Author URI: https://t.me/l1ghtsun
 */
if (!defined('ABSPATH')) exit;

class MST_Product_Icons_Filters {
    private static $inst;
    private $brand_tax = 'brand';
    private $format_prefix = 'mst-format-';
    private $transport_prefix = 'mst-transport-';

    public static function instance() {
        return self::$inst ?: self::$inst = new self();
    }

    private function __construct() {
        add_shortcode('mst_filters', [$this, 'render_filters']);
        add_action('pre_get_posts', [$this, 'apply_filters_query']);
        add_action('init', [$this, 'register_attributes_taxonomy']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_filters_assets']);
        
        // Hide WooCommerce "no products" message when using Shop Grid with filters
        add_filter('woocommerce_no_products_found', [$this, 'hide_archive_no_products_message'], 10, 1);
    }

    public function enqueue_filters_assets() {
        if (!is_shop() && !is_product_category() && !is_product_taxonomy()) return;
        wp_enqueue_style('mst-filters-styles', MST_PI_URL . 'assets/css/mst-filters.css', [], MST_PI_VERSION);
        wp_enqueue_script('mst-filters-js', MST_PI_URL . 'assets/js/mst-filters.js', ['jquery'], MST_PI_VERSION, true);
    }

    public function register_attributes_taxonomy() {
        if (taxonomy_exists('product_attributes')) return;
        register_taxonomy('product_attributes', 'product', [
            'label' => 'Параметры',
            'labels' => [
                'name' => 'Параметры',
                'singular_name' => 'Параметр',
                'menu_name' => 'Параметры',
                'all_items' => 'Все параметры',
                'edit_item' => 'Редактировать параметр',
                'add_new_item' => 'Добавить параметр',
            ],
            'public' => true,
            'hierarchical' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'rewrite' => ['slug' => 'attribute'],
            'show_in_rest' => true,
        ]);
    }

    private function get_filter_config() {
        return get_option('mst_filters_config', [
            'format' => [
                'enabled' => true,
                'label' => 'Формат тура',
                'style' => 'checkbox',
                'multiple' => false,
                'order' => 1
            ],
            'transport' => [
                'enabled' => true,
                'label' => 'Способ передвижения',
                'style' => 'dropdown',
                'multiple' => true,
                'order' => 2
            ],
            'attributes' => [
                'enabled' => true,
                'label' => 'Параметры',
                'style' => 'checkbox',
                'multiple' => true,
                'order' => 3
            ]
        ]);
    }

    public function get_transports() {
        return get_option('mst_transports', []);
    }

    public function get_formats() {
        return get_option('mst_formats', []);
    }
	
    private function get_category_attributes() {
    $current_cat = get_queried_object();
    
    if (!$current_cat || !isset($current_cat->term_id) || $current_cat->taxonomy !== 'product_cat') {
        return get_terms(['taxonomy' => 'product_attributes', 'hide_empty' => false]);
    }
    
    $category_attrs = get_option('mst_category_attributes', []);
    
    if (isset($category_attrs[$current_cat->term_id]) && !empty($category_attrs[$current_cat->term_id])) {
        $attr_ids = $category_attrs[$current_cat->term_id];
        return get_terms([
            'taxonomy' => 'product_attributes',
            'hide_empty' => false,
            'include' => $attr_ids
        ]);
    }
    
    return get_terms(['taxonomy' => 'product_attributes', 'hide_empty' => false]);
}
    public function render_filters($atts = []) {
        $config = $this->get_filter_config();
        
        uasort($config, function($a, $b) {
            return ($a['order'] ?? 999) - ($b['order'] ?? 999);
        });
        
        $formats = $this->get_formats();
        $transports = $this->get_transports();
        $attributes = $this->get_category_attributes();
        
        global $wpdb;
        $price_range = $wpdb->get_row("
            SELECT MIN(CAST(meta_value AS DECIMAL(10,2))) as min_price, 
                   MAX(CAST(meta_value AS DECIMAL(10,2))) as max_price 
            FROM {$wpdb->postmeta} 
            WHERE meta_key = '_price' AND meta_value > 0
        ");
        
        $min_price = $price_range->min_price ?? 0;
        $max_price = $price_range->max_price ?? 10000;
        $current_min = isset($_GET['min_price']) ? floatval($_GET['min_price']) : $min_price;
        $current_max = isset($_GET['max_price']) ? floatval($_GET['max_price']) : $max_price;
        
        $current_format = [];
        if (isset($_GET['format'])) {
            $current_format = is_array($_GET['format']) ? array_map('sanitize_text_field', $_GET['format']) : [sanitize_text_field($_GET['format'])];
        }
        
        $current_transport = [];
        if (isset($_GET['transport'])) {
            $current_transport = is_array($_GET['transport']) ? array_map('sanitize_text_field', $_GET['transport']) : [sanitize_text_field($_GET['transport'])];
        }
        
        $current_attributes = [];
        if (isset($_GET['attributes'])) {
            $current_attributes = is_array($_GET['attributes']) ? array_map('intval', $_GET['attributes']) : [intval($_GET['attributes'])];
        }
        
        ob_start();
        ?>
        <div class="mst-filters-wrapper">
            <form class="mst-filters-form" method="get" action="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>">
                
                <div class="mst-filters-row mst-filters-main">
                    
                    <?php if($config['format']['enabled']): ?>
                    <div class="mst-filter-group mst-filter-format">
                        <div class="mst-filter-label"><?php echo esc_html($config['format']['label']); ?></div>
                        <?php $this->render_filter_by_style($config['format']['style'], 'format', $formats, $current_format, $config['format']['multiple']); ?>
                    </div>
                    <?php endif; ?>
                    
                    <div class="mst-filter-group mst-filter-price">
                        <div class="mst-filter-label">Цена</div>
                        <div class="mst-price-dropdown">
                            <button type="button" class="mst-price-toggle" id="mst-price-toggle">
                                <span id="mst-price-display-text"><?php echo number_format($current_min, 0, ',', ' '); ?> — <?php echo number_format($current_max, 0, ',', ' '); ?> €</span>
                                <span class="mst-arrow">▼</span>
                            </button>
                            <div class="mst-price-content" id="mst-price-content" style="display:none;">
                                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:20px;">
                                    <div>
                                        <label style="display:block;font-size:12px;color:#666;margin-bottom:6px;font-weight:600;">ОТ</label>
                                        <input type="number" id="mst-input-min" value="<?php echo $current_min; ?>" min="<?php echo $min_price; ?>" max="<?php echo $max_price; ?>" style="width:100%;padding:10px;border:2px solid #e0e0e0;border-radius:6px;font-size:15px;font-weight:600;">
                                    </div>
                                    <div>
                                        <label style="display:block;font-size:12px;color:#666;margin-bottom:6px;font-weight:600;">ДО</label>
                                        <input type="number" id="mst-input-max" value="<?php echo $current_max; ?>" min="<?php echo $min_price; ?>" max="<?php echo $max_price; ?>" style="width:100%;padding:10px;border:2px solid #e0e0e0;border-radius:6px;font-size:15px;font-weight:600;">
                                    </div>
                                </div>
                                <div style="position:relative;height:40px;margin:20px 0;">
                                    <div style="position:absolute;width:100%;height:5px;background:#e0e0e0;border-radius:5px;top:50%;transform:translateY(-50%);"></div>
                                    <div id="mst-track" style="position:absolute;height:5px;background:linear-gradient(90deg,#00c896,#00a87a);border-radius:5px;top:50%;transform:translateY(-50%);left:0%;width:100%;"></div>
                                    <input type="range" id="mst-slider-min" min="<?php echo $min_price; ?>" max="<?php echo $max_price; ?>" value="<?php echo $current_min; ?>" style="position:absolute;width:100%;top:50%;transform:translateY(-50%);-webkit-appearance:none;background:transparent;z-index:3;pointer-events:all;height:5px;">
									<input type="range" id="mst-slider-max" min="<?php echo $min_price; ?>" max="<?php echo $max_price; ?>" value="<?php echo $current_max; ?>" style="position:absolute;width:100%;top:50%;transform:translateY(-50%);-webkit-appearance:none;background:transparent;z-index:4;pointer-events:all;height:5px;">
                                </div>
                                <input type="hidden" name="min_price" id="mst-hidden-min" value="<?php echo $current_min; ?>">
                                <input type="hidden" name="max_price" id="mst-hidden-max" value="<?php echo $current_max; ?>">
                            </div>
                        </div>
                    </div>

                    <?php if($config['transport']['enabled']): ?>
                    <div class="mst-filter-group mst-filter-transport">
                        <div class="mst-filter-label"><?php echo esc_html($config['transport']['label']); ?></div>
                        <?php $this->render_filter_by_style($config['transport']['style'], 'transport', $transports, $current_transport, $config['transport']['multiple']); ?>
                    </div>
                    <?php endif; ?>

                </div>

                <?php if($config['attributes']['enabled'] && !empty($attributes) && !is_wp_error($attributes)): ?>
                <div class="mst-filters-row mst-filters-attributes">
                    <div class="mst-filter-group mst-filter-attributes-full">
                        <div class="mst-filter-label"><?php echo esc_html($config['attributes']['label']); ?></div>
                        <?php $this->render_attributes_horizontal($attributes, $current_attributes); ?>
                    </div>
                </div>
                <?php endif; ?>

                <div class="mst-filter-actions">
                    <button type="submit" class="mst-btn mst-btn-apply">НАЙТИ</button>
                    <button type="button" class="mst-btn mst-btn-reset">СБРОС</button>
                </div>
            </form>
        </div>

        <style>
        .mst-price-dropdown{position:relative;}
		.mst-price-toggle{
		width:100%;
		display:flex;
		align-items:center;
		justify-content:space-between;
		padding:12px 16px;
		background:#fff!important;
		color:#333!important;
		border:2px solid #e0e0e0;
		border-radius:8px;
		cursor:pointer;
		font-size:15px;
		transition:all 0.2s;
		outline:none;
		}
        .mst-price-content{position:absolute;top:100%;left:0;right:0;background:#fff;border:2px solid #00c896;border-top:none;border-radius:0 0 8px 8px;padding:20px;z-index:1000;box-shadow:0 4px 12px rgba(0,0,0,0.15);}
        .mst-price-toggle:hover{border-color:#00c896;}
        .mst-price-toggle.active{border-color:#00c896;border-radius:8px 8px 0 0;background:#fff!important;color:#333!important;}
        input[type="range"]{pointer-events:all!important;cursor:pointer!important;}
        input[type="range"]::-webkit-slider-thumb{-webkit-appearance:none;width:20px;height:20px;background:#fff;border-radius:50%;border:3px solid #00c896;box-shadow:0 2px 8px rgba(0,0,0,0.2);cursor:grab!important;pointer-events:all!important;}
        input[type="range"]::-moz-range-thumb{width:20px;height:20px;background:#fff;border-radius:50%;border:3px solid #00c896;box-shadow:0 2px 8px rgba(0,0,0,0.2);cursor:grab!important;pointer-events:all!important;}
		input[type="range"]:active::-webkit-slider-thumb{cursor:grabbing!important;}
		input[type="range"]:active::-moz-range-thumb{cursor:grabbing!important;}
		input[type="range"]::-webkit-slider-runnable-track{
		background:transparent;
		height:5px;
		}
		input[type="range"]::-moz-range-track{
		background:transparent;
		height:5px;
		}
		#mst-slider-min{z-index:3!important;}
		#mst-slider-max{z-index:4!important;}
        </style>
        <?php
        return ob_get_clean();
    }

    private function render_filter_by_style($style, $name, $options, $current, $multiple = false) {
        $input_type = $multiple ? 'checkbox' : 'radio';
        $name_attr = $multiple ? $name . '[]' : $name;
        
        switch ($style) {
            case 'dropdown':
                $this->render_custom_dropdown($name_attr, $options, $current, $multiple);
                break;
            case 'checkbox':
                $this->render_checkboxes($name_attr, $options, $current);
                break;
            case 'radio':
                $this->render_radios($name, $options, $current);
                break;
            case 'chips':
                $this->render_chips($name_attr, $options, $current, $input_type);
                break;
        }
    }

    private function render_custom_dropdown($name, $options, $current, $multiple) {
        $current_arr = is_array($current) ? $current : ($current ? [$current] : []);
        $selected_count = count($current_arr);
        
        if ($selected_count === 0) {
            $selected_text = 'Выберите';
        } elseif (!$multiple && !empty($current_arr[0]) && isset($options[$current_arr[0]])) {
            $selected_text = $options[$current_arr[0]]['icon'] . ' ' . $options[$current_arr[0]]['name'];
        } else {
            $selected_text = $selected_count . ' выбрано';
        }
        
        $dropdown_id = 'mst-dropdown-' . uniqid();
        ?>
        <div class="mst-custom-dropdown" data-name="<?php echo esc_attr($name); ?>" data-multiple="<?php echo $multiple ? 'true' : 'false'; ?>">
            <div class="mst-dropdown-toggle" data-dropdown="<?php echo $dropdown_id; ?>">
                <span class="mst-dropdown-text"><?php echo $selected_text; ?></span>
                <span class="mst-dropdown-arrow">▼</span>
            </div>
            <div class="mst-dropdown-menu" id="<?php echo $dropdown_id; ?>">
                <?php foreach ($options as $value => $data): ?>
                    <?php $is_selected = in_array($value, $current_arr); ?>
                    <label class="mst-dropdown-item <?php echo $is_selected ? 'selected' : ''; ?>">
                        <input 
                            type="<?php echo $multiple ? 'checkbox' : 'radio'; ?>" 
                            name="<?php echo esc_attr($name); ?>" 
                            value="<?php echo esc_attr($value); ?>" 
                            <?php checked($is_selected); ?>
                            style="display: none;"
                        >
                        <span class="mst-item-icon"><?php echo $data['icon']; ?></span>
                        <span class="mst-item-text"><?php echo esc_html($data['name']); ?></span>
                        <?php if ($is_selected): ?>
                            <span class="mst-item-check">✓</span>
                        <?php endif; ?>
                    </label>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
    }

    private function render_checkboxes($name, $options, $current) {
        $current_arr = is_array($current) ? $current : ($current ? [$current] : []);
        ?>
        <div class="mst-checkbox-list">
            <?php foreach ($options as $value => $data): ?>
                <label class="mst-checkbox-label <?php echo in_array($value, $current_arr) ? 'active' : ''; ?>">
                    <input type="checkbox" name="<?php echo esc_attr($name); ?>" value="<?php echo esc_attr($value); ?>" <?php checked(in_array($value, $current_arr)); ?>>
                    <span class="checkbox-icon"><?php echo $data['icon']; ?></span>
                    <span class="checkbox-text"><?php echo esc_html($data['name']); ?></span>
                </label>
            <?php endforeach; ?>
        </div>
        <?php
    }

    private function render_radios($name, $options, $current) {
        $current_arr = is_array($current) ? $current : ($current ? [$current] : []);
        $current_val = !empty($current_arr[0]) ? $current_arr[0] : '';
        ?>
        <div class="mst-radio-list">
            <?php foreach ($options as $value => $data): ?>
                <label class="mst-radio-label <?php echo $current_val === $value ? 'active' : ''; ?>">
                    <input type="radio" name="<?php echo esc_attr($name); ?>" value="<?php echo esc_attr($value); ?>" <?php checked($current_val, $value); ?>>
                    <span class="radio-icon"><?php echo $data['icon']; ?></span>
                    <span class="radio-text"><?php echo esc_html($data['name']); ?></span>
                </label>
            <?php endforeach; ?>
        </div>
        <?php
    }

    private function render_chips($name, $options, $current, $input_type) {
        $current_arr = is_array($current) ? $current : ($current ? [$current] : []);
        ?>
        <div class="mst-chips-container">
            <?php foreach ($options as $value => $data): ?>
                <?php $is_active = in_array($value, $current_arr); ?>
                <label class="mst-chip <?php echo $is_active ? 'active' : ''; ?>">
                    <input type="<?php echo $input_type; ?>" name="<?php echo esc_attr($name); ?>" value="<?php echo esc_attr($value); ?>" <?php checked($is_active); ?>>
                    <span class="chip-icon"><?php echo $data['icon']; ?></span>
                    <span class="chip-text"><?php echo esc_html($data['name']); ?></span>
                </label>
            <?php endforeach; ?>
        </div>
        <?php
    }

    private function render_attributes_horizontal($attributes, $current) {
        ?>
        <div class="mst-attributes-horizontal">
            <?php foreach ($attributes as $attr): ?>
                <?php 
                $icon = get_term_meta($attr->term_id, 'attribute_icon', true) ?: '🏷️';
                $is_active = in_array($attr->term_id, $current);
                ?>
                <label class="mst-attr-chip <?php echo $is_active ? 'active' : ''; ?>">
                    <input type="checkbox" name="attributes[]" value="<?php echo esc_attr($attr->term_id); ?>" <?php checked($is_active); ?>>
                    <span class="attr-icon"><?php echo $icon; ?></span>
                    <span class="attr-text"><?php echo esc_html($attr->name); ?></span>
                </label>
            <?php endforeach; ?>
        </div>
        <?php
    }

    public function apply_filters_query($query) {
        if (is_admin() || !$query->is_main_query()) return;
        
        // Only proceed if we have filter parameters
        if (!isset($_GET['format']) && !isset($_GET['transport']) && !isset($_GET['attributes']) && !isset($_GET['min_price']) && !isset($_GET['max_price'])) {
            return;
        }

        $meta_query = [];
        $tax_query = [];

        // FIXED: Use WooCommerce taxonomy attributes without pa_ prefix
        // Format filter - use tour-type taxonomy
        if (!empty($_GET['format'])) {
            $formats = is_array($_GET['format']) ? array_map('sanitize_text_field', $_GET['format']) : [sanitize_text_field($_GET['format'])];
            $tax_query[] = [
                'taxonomy' => 'tour-type',
                'field' => 'slug',
                'terms' => $formats,
                'operator' => 'IN'
            ];
        }

        // Transport filter - use transport taxonomy
        if (!empty($_GET['transport'])) {
            $transports = is_array($_GET['transport']) ? array_map('sanitize_text_field', $_GET['transport']) : [sanitize_text_field($_GET['transport'])];
            $tax_query[] = [
                'taxonomy' => 'transport',
                'field' => 'slug',
                'terms' => $transports,
                'operator' => 'IN'
            ];
        }

        // Attributes filter - use product_attributes taxonomy
        if (!empty($_GET['attributes'])) {
            $attributes = is_array($_GET['attributes']) ? array_map('intval', $_GET['attributes']) : [intval($_GET['attributes'])];
            $tax_query[] = [
                'taxonomy' => 'product_attributes',
                'field' => 'term_id',
                'terms' => $attributes,
                'operator' => 'IN'
            ];
        }

        // Duration filter - use duration taxonomy if provided
        if (!empty($_GET['duration'])) {
            $durations = is_array($_GET['duration']) ? array_map('sanitize_text_field', $_GET['duration']) : [sanitize_text_field($_GET['duration'])];
            $tax_query[] = [
                'taxonomy' => 'duration',
                'field' => 'slug',
                'terms' => $durations,
                'operator' => 'IN'
            ];
        }

        // Price filter - still uses meta_query as prices are stored in post meta
        if (!empty($_GET['min_price']) || !empty($_GET['max_price'])) {
            $min = !empty($_GET['min_price']) ? floatval($_GET['min_price']) : 0;
            $max = !empty($_GET['max_price']) ? floatval($_GET['max_price']) : 999999;
            $meta_query[] = [
                'key' => '_price',
                'value' => [$min, $max],
                'compare' => 'BETWEEN',
                'type' => 'NUMERIC'
            ];
        }

        if (!empty($meta_query)) {
            $meta_query['relation'] = 'AND';
            $query->set('meta_query', $meta_query);
        }
        
        if (!empty($tax_query)) {
            $tax_query['relation'] = 'AND';
            $query->set('tax_query', $tax_query);
        }
    }
    
    public function hide_archive_no_products_message($message) {
        // Hide "no products" from archive when filters are active
        $has_filters = isset($_GET['format']) || isset($_GET['transport']) || isset($_GET['attributes']) || isset($_GET['min_price']) || isset($_GET['max_price']);
        
        if ($has_filters && (is_shop() || is_product_category() || is_product_taxonomy())) {
            // Return empty string to hide the default archive message
            // Shop Grid widget should handle its own "no results" display
            return '';
        }
        
        return $message;
    }
}