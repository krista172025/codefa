<?php
/**
 * Plugin Name: MySuperTour Hub
 * Description: Единая панель управления для Search/Icons/Filters
 * Version: 2.0.3
 * Author: Telegram @l1ghtsun
 * Author URI: https://t.me/l1ghtsun
 */

if(!defined('ABSPATH')) exit;

require_once plugin_dir_path(__FILE__) . 'includes/class-hub-core.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-hub-sync.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-hub-formats.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-hub-transports.php';

class MySuperTour_Hub {
    private static $inst;
    
    public static function instance(){return self::$inst ?: self::$inst = new self();}
    
    private function __construct(){
        add_action('admin_menu', [$this, 'create_menu'], 5);
        add_action('admin_menu', [$this, 'remove_duplicate_menus'], 999);
        add_action('admin_enqueue_scripts', [$this, 'admin_assets']);
        add_action('wp_head', [$this, 'output_icon_positioning_css'], 999);
        add_action('admin_init', [$this, 'handle_settings_save']);
        add_action('admin_init', [$this, 'handle_old_tags_cleanup']);
		add_action('admin_bar_menu', [$this, 'remove_latepoint_from_admin_bar'], 999);
    }
    
    public function output_icon_positioning_css(){
        if(!is_shop() && !is_product_category() && !is_product_taxonomy()) return;
        $settings = get_option('mst_icon_positioning', ['type'=>'absolute','top'=>'10','left'=>'25','right'=>'','bottom'=>'','size'=>'32','radius'=>'50']);
        ?>
        <style id="mst-icon-positioning">
        .products .product .mst-pi-badge,ul.products li.product .mst-pi-badge {
            position: <?php echo esc_attr($settings['type']); ?> !important;
            <?php if($settings['right']): ?>right: <?php echo esc_attr($settings['right']); ?>px !important;left: auto !important;
            <?php else: ?>left: <?php echo esc_attr($settings['left']); ?>px !important;<?php endif; ?>
            <?php if($settings['bottom']): ?>bottom: <?php echo esc_attr($settings['bottom']); ?>px !important;top: auto !important;
            <?php else: ?>top: <?php echo esc_attr($settings['top']); ?>px !important;<?php endif; ?>
            z-index: 9999 !important;
        }
        </style>
        <?php
    }
    
    public function admin_assets($hook){
        if(strpos($hook, 'mysupertour') === false) return;
        wp_enqueue_style('mst-hub-admin', plugin_dir_url(__FILE__) . 'assets/css/admin.css', [], '2.0.3');
        wp_enqueue_script('mst-hub-admin', plugin_dir_url(__FILE__) . 'assets/js/admin.js', ['jquery'], '2.0.3', true);
    }
    
    public function create_menu(){
        add_menu_page('MySuperTour','MySuperTour','manage_options','mysupertour-hub',[$this,'render_dashboard'],'dashicons-admin-site-alt3',30);
        add_submenu_page('mysupertour-hub','Dashboard','🏠 Dashboard','manage_options','mysupertour-hub',[$this,'render_dashboard']);
        if(defined('MSTS_VERSION')) add_submenu_page('mysupertour-hub','Поиск','🔍 Поиск','manage_options','mysupertour-search-hub',[$this,'render_search']);
        if(defined('MST_PI_VERSION')){
            add_submenu_page('mysupertour-hub','Иконки','🎨 Иконки','manage_options','mysupertour-icons-hub',[$this,'render_icons']);
            add_submenu_page('mysupertour-hub','Фильтры','⚙️ Фильтры','manage_options','mysupertour-filters-hub',[$this,'render_filters']);
            add_submenu_page('mysupertour-hub','Параметры','🎯 Параметры','manage_options','mysupertour-attributes-hub',[$this,'render_attributes']);
            add_submenu_page('mysupertour-hub','Города и параметры','🗺️ Города','manage_options','mysupertour-category-attributes',[$this,'render_category_attributes']);
        }
    }
	
	public function remove_latepoint_from_admin_bar($wp_admin_bar) {
    $wp_admin_bar->remove_node('latepoint');
	}
    
    public function remove_duplicate_menus(){remove_menu_page('mysupertour-search-settings');}
    
    public function handle_settings_save(){
        if(isset($_POST['mst_save_icon_settings']) && check_admin_referer('mst_icon_settings','mst_icon_nonce')){
            $new = [
                'type' => sanitize_text_field($_POST['mst_icon_positioning']['type'] ?? 'absolute'),
                'top' => intval($_POST['mst_icon_positioning']['top'] ?? 10),
                'left' => intval($_POST['mst_icon_positioning']['left'] ?? 10),
                'right' => intval($_POST['mst_icon_positioning']['right'] ?? 0),
                'bottom' => intval($_POST['mst_icon_positioning']['bottom'] ?? 0),
                'size' => intval($_POST['mst_icon_positioning']['size'] ?? 32),
                'radius' => intval($_POST['mst_icon_positioning']['radius'] ?? 50)
            ];
            update_option('mst_icon_positioning', $new);
            add_settings_error('mst_messages', 'mst_message', 'Настройки сохранены!', 'success');
        }
    }
    
    public function render_dashboard(){require_once plugin_dir_path(__FILE__) . 'includes/pages/dashboard.php';}
    public function render_search(){
        $this->sync_hub_to_search();
        if(class_exists('MSTS_Settings')) MSTS_Settings::instance()->render();
    }
    public function render_icons(){require_once plugin_dir_path(__FILE__) . 'includes/pages/icons.php';}
    public function render_filters(){require_once plugin_dir_path(__FILE__) . 'includes/pages/filters.php';}
    public function render_attributes(){require_once plugin_dir_path(__FILE__) . 'includes/pages/attributes.php';}
    public function render_category_attributes(){require_once plugin_dir_path(__FILE__) . 'includes/pages/category-attributes.php';}
    
    private function sync_hub_to_search(){
        $formats = get_option('mst_formats', []);
        $transports = get_option('mst_transports', []);
        $search_settings = get_option('mysupertour_search_settings', []);
        
        $format_names = [];
        foreach($formats as $slug => $data){$format_names[] = mb_strtolower($data['name']);}
        $transport_names = [];
        foreach($transports as $slug => $data){$transport_names[] = mb_strtolower($data['name']);}
        
        if(!isset($search_settings['exclude_city_slugs'])){$search_settings['exclude_city_slugs'] = '';}
        $current_excludes = array_filter(array_map('trim', explode(',', $search_settings['exclude_city_slugs'])));
        $new_excludes = array_unique(array_merge($current_excludes, $format_names, $transport_names));
        $search_settings['exclude_city_slugs'] = implode(',', $new_excludes);
        update_option('mysupertour_search_settings', $search_settings);
    }
    
    /**
     * Handle cleanup of old mst-format-* and mst-transport-* tags
     */
    public function handle_old_tags_cleanup() {
        if (!isset($_GET['mst_cleanup_old_tags'])) return;
        if (!wp_verify_nonce(wp_unslash($_GET['_wpnonce'] ?? ''), 'mst_cleanup')) return;
        if (!current_user_can('manage_options')) return;
        
        $deleted_tags = 0;
        $deleted_brands = 0;
        
        // Remove old mst-format-* product tags
        $format_tags = get_terms([
            'taxonomy' => 'product_tag',
            'hide_empty' => false,
            'number' => 100,
        ]);
        
        if (!is_wp_error($format_tags)) {
            foreach ($format_tags as $tag) {
                if (strpos($tag->slug, 'mst-format-') === 0) {
                    wp_delete_term($tag->term_id, 'product_tag');
                    $deleted_tags++;
                }
            }
        }
        
        // Remove old mst-transport-* brands
        if (taxonomy_exists('brand')) {
            $brands = get_terms([
                'taxonomy' => 'brand',
                'hide_empty' => false,
                'number' => 100,
            ]);
            
            if (!is_wp_error($brands)) {
                foreach ($brands as $brand) {
                    if (strpos($brand->slug, 'mst-transport-') === 0) {
                        wp_delete_term($brand->term_id, 'brand');
                        $deleted_brands++;
                    }
                }
            }
        }
        
        wp_redirect(add_query_arg([
            'page' => 'mysupertour-hub',
            'cleaned' => 1,
            'deleted_tags' => $deleted_tags,
            'deleted_brands' => $deleted_brands,
        ], admin_url('admin.php')));
        exit;
    }
}

add_action('plugins_loaded', function(){MySuperTour_Hub::instance();}, 1);