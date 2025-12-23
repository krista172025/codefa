<?php
/**
 * Core: метабокс, сбор мета, вывод плашек, подключение стилей/скриптов.
 * Author: Telegram @l1ghtsun
 * Author URI: https://t.me/l1ghtsun
 */
if ( ! defined('ABSPATH') ) exit;

class MST_Product_Icons {

    private static $inst;
    private $meta_map = [];

    public static function instance() {
        return self::$inst ?: self::$inst = new self();
    }

    private function __construct() {
		add_action('rest_api_init', [$this, 'register_rest_api']);
        add_action('add_meta_boxes',        [ $this, 'add_meta_box' ]);
        add_action('save_post_product',     [ $this, 'save_meta' ], 10, 2);
        add_action('wp',                    [ $this, 'collect_query_products_meta' ]);
        add_action('wp_enqueue_scripts',    [ $this, 'enqueue_assets' ], 30);
        add_action('wp_footer',             [ $this, 'print_inline_json' ], 5);
        add_action('admin_enqueue_scripts', [ $this, 'admin_assets' ]);
    }

    /* =============== Метабокс =============== */
    public function add_meta_box() {
        add_meta_box(
            'mst_pi_meta',
            'Tour info',
            [ $this, 'render_meta_box' ],
            'product',
            'side',
            'default'
        );
    }

    public function render_meta_box( $post ) {
        wp_nonce_field('mst_pi_save', 'mst_pi_nonce');

        $format    = get_post_meta($post->ID, '_mst_pi_format', true) ?: '';
        $duration  = get_post_meta($post->ID, '_mst_pi_duration', true) ?: '';
        $transport = get_post_meta($post->ID, '_mst_pi_transport', true) ?: '';
        $time_icon_id      = (int) get_post_meta($post->ID, '_mst_pi_time_icon', true);
        $transport_icon_id = (int) get_post_meta($post->ID, '_mst_pi_transport_icon', true);

        $time_icon_url      = $time_icon_id      ? wp_get_attachment_image_url($time_icon_id, 'thumbnail') : '';
        $transport_icon_url = $transport_icon_id ? wp_get_attachment_image_url($transport_icon_id, 'thumbnail') : '';
        ?>
        <style>
            .mst-pi-field{margin:8px 0 14px;}
            .mst-pi-field label{font-weight:600;display:block;margin-bottom:4px;}
            .mst-pi-upload-wrap{display:flex;gap:8px;align-items:flex-start;}
            .mst-pi-preview-box{width:52px;height:52px;border:1px solid #ddd;background:#f6f6f6;border-radius:6px;display:flex;align-items:center;justify-content:center;overflow:hidden;}
            .mst-pi-preview-box img{max-width:100%;max-height:100%;display:block;}
            .mst-pi-buttons{display:flex;flex-direction:column;gap:6px;}
            #mst-pi-save-meta{margin-top:4px;width:100%;}
        </style>

                <div class="mst-pi-field">
            <label for="mst_pi_format">Формат</label>
            <select name="mst_pi_format" id="mst_pi_format" style="width:100%;">
                <option value="">—</option>
                <?php 
                $formats = get_option('mst_formats', [
                    'group' => ['name' => 'Групповая', 'icon' => '👥'],
                    'individual' => ['name' => 'Индивидуальная', 'icon' => '⭐']
                ]);
                foreach($formats as $slug => $data): ?>
                    <option value="<?php echo esc_attr($slug); ?>" <?php selected($format, $slug); ?>>
                        <?php echo esc_html($data['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mst-pi-field">
            <label for="mst_pi_duration">Время (напр. 2:00)</label>
            <input type="text" name="mst_pi_duration" id="mst_pi_duration" value="<?php echo esc_attr($duration);?>" style="width:100%;" placeholder="2:00 часа">
        </div>

        <div class="mst-pi-field">
            <label for="mst_pi_transport">Способ передвижения</label>
            <select name="mst_pi_transport" id="mst_pi_transport" style="width:100%;">
                <option value="">—</option>
                <?php 
                $transports = get_option('mst_transports', [
                    'walk' => ['name' => 'Пешком', 'icon' => '🚶‍♂️'],
                    'car' => ['name' => 'Авто', 'icon' => '🚗'],
                    'combined' => ['name' => 'Комбинированный', 'icon' => '🔁']
                ]);
                foreach($transports as $slug => $data): ?>
                    <option value="<?php echo esc_attr($slug); ?>" <?php selected($transport, $slug); ?>>
                        <?php echo $data['icon'] . ' ' . esc_html($data['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mst-pi-field">
            <label>Иконка времени</label>
            <div class="mst-pi-upload-wrap">
                <div class="mst-pi-preview-box" data-role="preview-time">
                    <?php if($time_icon_url) echo '<img src="'.esc_url($time_icon_url).'" alt="">'; ?>
                </div>
                <div class="mst-pi-buttons">
                    <input type="hidden" name="mst_pi_time_icon" id="mst_pi_time_icon" value="<?php echo $time_icon_id ?: ''; ?>">
                    <button type="button" class="button mst-pi-btn-upload" data-target="mst_pi_time_icon" data-preview="preview-time">Загрузить / Выбрать</button>
                    <button type="button" class="button mst-pi-btn-remove" data-target="mst_pi_time_icon" data-preview="preview-time">Удалить</button>
                </div>
            </div>
        </div>

        <div class="mst-pi-field">
            <label>Иконка транспорта</label>
            <div class="mst-pi-upload-wrap">
                <div class="mst-pi-preview-box" data-role="preview-transport">
                    <?php if($transport_icon_url) echo '<img src="'.esc_url($transport_icon_url).'" alt="">'; ?>
                </div>
                <div class="mst-pi-buttons">
                    <input type="hidden" name="mst_pi_transport_icon" id="mst_pi_transport_icon" value="<?php echo $transport_icon_id ?: ''; ?>">
                    <button type="button" class="button mst-pi-btn-upload" data-target="mst_pi_transport_icon" data-preview="preview-transport">Загрузить / Выбрать</button>
                    <button type="button" class="button mst-pi-btn-remove" data-target="mst_pi_transport_icon" data-preview="preview-transport">Удалить</button>
                </div>
            </div>
        </div>

        <button type="button" class="button button-primary" id="mst-pi-save-meta">Сохранить (обновить товар)</button>
        <p style="font-size:11px;color:#666;line-height:1.4;margin-top:6px;">
            Формат → метка (product_tag). Способ передвижения → бренд. Плашки появятся автоматически.
        </p>
        <?php
    }

    public function save_meta( $post_id, $post ) {
        if ( ! isset($_POST['mst_pi_nonce']) || ! wp_verify_nonce($_POST['mst_pi_nonce'],'mst_pi_save') ) return;
        if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) return;
        if ( $post->post_type !== 'product' ) return;

        $map = [
            '_mst_pi_format'          => 'mst_pi_format',
            '_mst_pi_duration'        => 'mst_pi_duration',
            '_mst_pi_transport'       => 'mst_pi_transport',
            '_mst_pi_time_icon'       => 'mst_pi_time_icon',
            '_mst_pi_transport_icon'  => 'mst_pi_transport_icon',
        ];
        foreach($map as $meta=>$field){
            if(!isset($_POST[$field])) { delete_post_meta($post_id,$meta); continue; }
            $val = sanitize_text_field($_POST[$field]);
            if($meta==='_mst_pi_time_icon' || $meta==='_mst_pi_transport_icon'){
                $val = (int)$val;
                if($val>0) update_post_meta($post_id,$meta,$val); else delete_post_meta($post_id,$meta);
            } else {
                if($val==='') delete_post_meta($post_id,$meta); else update_post_meta($post_id,$meta,$val);
            }
        }
    }

    /* =============== Сбор мета для витрины =============== */
    public function collect_query_products_meta() {
        global $wp_query;
        if ( empty($wp_query) || empty($wp_query->posts) ) return;
        foreach($wp_query->posts as $p){
            if($p->post_type !== 'product') continue;
            $id = $p->ID;
            $format    = get_post_meta($id,'_mst_pi_format',true);
            $duration  = get_post_meta($id,'_mst_pi_duration',true);
            $transport = get_post_meta($id,'_mst_pi_transport',true);
            $time_id   = (int)get_post_meta($id,'_mst_pi_time_icon',true);
            $tr_id     = (int)get_post_meta($id,'_mst_pi_transport_icon',true);
           $formats = get_option('mst_formats', []);
            $transports = get_option('mst_transports', []);

            $format_data = isset($formats[$format]) ? $formats[$format] : null;
            $transport_data = isset($transports[$transport]) ? $transports[$transport] : null;

            $meta = [
    'id'            => $id,
    'format'        => $format,
    'format_name'   => $format_data ? $format_data['name'] : '',  // ✅ ДОБАВИЛИ
    'format_icon'   => $format_data ? $format_data['icon'] : '',  // ✅ ДОБАВИЛИ
    'duration'      => $duration,
    'transport'     => $transport,
    'transport_name' => $transport_data ? $transport_data['name'] : '',  // ✅ ДОБАВИЛИ
    'transport_icon_emoji' => $transport_data ? $transport_data['icon'] : '',  // ✅ ДОБАВИЛИ
    'time_icon'     => $time_id ? wp_get_attachment_url($time_id) : '',
    'transport_icon'=> $tr_id ? wp_get_attachment_url($tr_id) : ''  // ✅ ЗАГРУЖЕННАЯ ИКОНКА
            ];
            if($meta['format'] || $meta['duration'] || $meta['transport'] || $meta['time_icon'] || $meta['transport_icon']){
                $this->meta_map[$id] = $meta;
            }
        }
    }

    /* =============== Assets =============== */
    public function enqueue_assets() {
        wp_enqueue_style('mst-pi-styles', MST_PI_URL.'assets/css/mst-icons.css', [], MST_PI_VERSION);

        wp_enqueue_script('mst-pi-front', MST_PI_URL.'assets/js/mst-icons.js', [], MST_PI_VERSION, true);
        wp_localize_script('mst-pi-front','MST_PI_DATA',[
            'meta'=>$this->meta_map,
            'version'=>MST_PI_VERSION
        ]);
    }

    public function print_inline_json() {
        $json = wp_json_encode($this->meta_map);
        echo '<script id="mst-pi-json" type="application/json">'.$json.'</script>';
    }

    public function admin_assets( $hook ) {
        if ( ! in_array($hook, ['post.php','post-new.php'], true) ) return;
        $screen = get_current_screen();
        if ( ! $screen || $screen->post_type !== 'product' ) return;
        wp_enqueue_media();
        wp_enqueue_script('mst-pi-admin', MST_PI_URL.'assets/js/mst-admin.js', ['jquery'], MST_PI_VERSION, true);
    }
	/**
 * REST API endpoint для получения данных товара по ID
 */
public function register_rest_api() {
    register_rest_route('mst/v1', '/product-meta/(?P<id>\d+)', [
        'methods' => 'GET',
        'callback' => [$this, 'get_product_meta_rest'],
        'permission_callback' => '__return_true',
        'args' => [
            'id' => [
                'required' => true,
                'validate_callback' => function($param) {
                    return is_numeric($param);
                }
            ]
        ]
    ]);
}

public function get_product_meta_rest($request) {
    $product_id = (int) $request['id'];
    
    if (!$product_id) {
        return new WP_Error('no_id', 'ID товара не указан', ['status' => 400]);
    }
    
    // ✅ Загружаем форматы и транспорт из Hub
    $formats = get_option('mst_formats', [
        'group' => ['name' => 'Групповая', 'icon' => '👥'],
        'individual' => ['name' => 'Индивидуальная', 'icon' => '⭐']
    ]);
    $transports = get_option('mst_transports', [
        'walk' => ['name' => 'Пешком', 'icon' => '🚶‍♂️'],
        'car' => ['name' => 'Авто', 'icon' => '🚗'],
        'combined' => ['name' => 'Комбинированный', 'icon' => '🔁']
    ]);
    
    $format    = get_post_meta($product_id, '_mst_pi_format', true);
    $duration  = get_post_meta($product_id, '_mst_pi_duration', true);
    $transport = get_post_meta($product_id, '_mst_pi_transport', true);
    $time_id   = (int) get_post_meta($product_id, '_mst_pi_time_icon', true);
    $tr_id     = (int) get_post_meta($product_id, '_mst_pi_transport_icon', true);
    
    // ✅ Получаем полные данные
    $format_data = isset($formats[$format]) ? $formats[$format] : null;
    $transport_data = isset($transports[$transport]) ? $transports[$transport] : null;
    
    $meta = [
        'id'                  => $product_id,
        'format'              => $format ?: '',
        'format_name'         => $format_data ? $format_data['name'] : '',
        'format_icon'         => $format_data ? $format_data['icon'] : '',
        'duration'            => $duration ?: '',
        'transport'           => $transport ?: '',
        'transport_name'      => $transport_data ? $transport_data['name'] : '',
        'transport_icon_emoji'=> $transport_data ? $transport_data['icon'] : '',
        'time_icon'           => $time_id ? wp_get_attachment_url($time_id) : '',
        'transport_icon'      => $tr_id ? wp_get_attachment_url($tr_id) : ''
    ];
    
    return rest_ensure_response($meta);
 }
}