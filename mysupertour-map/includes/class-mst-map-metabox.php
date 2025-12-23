<?php
/**
 * Metabox для координат в карточке товара
 * 
 * @package MySuperTour_Map
 * @author Telegram @l1ghtsun
 * @link https://t.me/l1ghtsun
 */

if(!defined('ABSPATH')) exit;

class MST_Map_Metabox {
    private static $instance = null;
    
    public static function instance() {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        add_action('add_meta_boxes', [$this, 'add_metabox']);
        add_action('save_post_product', [$this, 'save_metabox']);
    }
    
    public function add_metabox() {
        add_meta_box(
            'mst_map_coordinates',
            '🗺️ Координаты на карте',
            [$this, 'render_metabox'],
            'product',
            'side',
            'default'
        );
    }
    
    public function render_metabox($post) {
        wp_nonce_field('mst_map_metabox', 'mst_map_metabox_nonce');
        
        $lat = get_post_meta($post->ID, '_mst_map_lat', true);
        $lng = get_post_meta($post->ID, '_mst_map_lng', true);
        $city = get_post_meta($post->ID, '_mst_map_city', true);
        
        ?>
        <div class="mst-map-metabox">
            <p>
                <label style="display:block;font-weight:600;margin-bottom:5px;">Широта (Latitude)</label>
                <input type="text" name="mst_map_lat" value="<?php echo esc_attr($lat); ?>" 
                       class="widefat" placeholder="48.8584" step="any">
            </p>
            
            <p>
                <label style="display:block;font-weight:600;margin-bottom:5px;">Долгота (Longitude)</label>
                <input type="text" name="mst_map_lng" value="<?php echo esc_attr($lng); ?>" 
                       class="widefat" placeholder="2.2945" step="any">
            </p>
            
            <p>
                <label style="display:block;font-weight:600;margin-bottom:5px;">Город</label>
                <input type="text" name="mst_map_city" value="<?php echo esc_attr($city); ?>" 
                       class="widefat" placeholder="Париж">
            </p>
            
            <?php if ($lat && $lng): ?>
            <p style="margin-top:15px;">
                <a href="https://www.google.com/maps?q=<?php echo $lat; ?>,<?php echo $lng; ?>" 
                   target="_blank" class="button button-secondary" style="width:100%;">
                    🌍 Открыть в Google Maps
                </a>
            </p>
            <?php endif; ?>
            
            <p style="font-size:12px;color:#666;margin-top:10px;">
                💡 <strong>Подсказка:</strong> Чтобы найти координаты, откройте Google Maps, 
                кликните правой кнопкой на нужное место и выберите координаты.
            </p>
        </div>
        
        <style>
        .mst-map-metabox input[type="text"] {
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .mst-map-metabox input[type="text"]:focus {
            border-color: #00c896;
            outline: none;
            box-shadow: 0 0 0 1px #00c896;
        }
        </style>
        <?php
    }
    
    public function save_metabox($post_id) {
        if (!isset($_POST['mst_map_metabox_nonce'])) return;
        if (!wp_verify_nonce($_POST['mst_map_metabox_nonce'], 'mst_map_metabox')) return;
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
        if (!current_user_can('edit_post', $post_id)) return;
        
        if (isset($_POST['mst_map_lat'])) {
            update_post_meta($post_id, '_mst_map_lat', sanitize_text_field($_POST['mst_map_lat']));
        }
        
        if (isset($_POST['mst_map_lng'])) {
            update_post_meta($post_id, '_mst_map_lng', sanitize_text_field($_POST['mst_map_lng']));
        }
        
        if (isset($_POST['mst_map_city'])) {
            update_post_meta($post_id, '_mst_map_city', sanitize_text_field($_POST['mst_map_city']));
        }
    }
}