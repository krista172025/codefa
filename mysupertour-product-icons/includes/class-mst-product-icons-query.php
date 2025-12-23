<?php
/**
 * Query фильтрация + шорткод фильтров с ЧЕКБОКСАМИ
 * Author: Telegram @l1ghtsun
 * Author URI: https://t.me/l1ghtsun
 */
if ( ! defined('ABSPATH') ) exit;

class MST_Product_Icons_Query {

    private static $inst;
    private $brand_tax = 'brand';
    private $format_prefix = 'mst-format-';
    private $transport_prefix = 'mst-transport-';

    public static function instance() {
        return self::$inst ?: self::$inst = new self();
    }

    private function __construct() {
    add_action('pre_get_posts', [ $this, 'apply_filters_to_query' ]);
    add_shortcode('mst_filters', [ $this, 'shortcode_filters' ]);
    // ОТКЛЮЧЕНО: add_action('woocommerce_before_shop_loop', [$this, 'auto_display_filters'], 15);
}

    public function apply_filters_to_query( $query ) {
        if ( is_admin() || ! $query->is_main_query() ) return;
        if ( ! ( $query->is_post_type_archive('product') || $query->is_tax() || $query->is_search() ) ) return;

        $tax_query  = [];
        $meta_query = [];

        if( isset($_GET['format']) && $_GET['format'] !== '' ){
            $format_code = sanitize_text_field($_GET['format']);
            $tax_query[] = [
                'taxonomy'=>'product_tag',
                'field'=>'slug',
                'terms'=>[ $this->format_prefix.$format_code ]
            ];
        }

        if( taxonomy_exists($this->brand_tax) && isset($_GET['transport']) && $_GET['transport'] !== '' ){
            $transport_code = sanitize_text_field($_GET['transport']);
            $tax_query[] = [
                'taxonomy'=>$this->brand_tax,
                'field'=>'slug',
                'terms'=>[ $this->transport_prefix.$transport_code ]
            ];
        }

        $min = isset($_GET['min_price']) ? floatval($_GET['min_price']) : null;
        $max = isset($_GET['max_price']) ? floatval($_GET['max_price']) : null;

        if( $min !== null ){
            $meta_query[] = [
                'key'=>'_price',
                'value'=>$min,
                'compare'=>'>=',
                'type'=>'DECIMAL(10,2)'
            ];
        }
        if( $max !== null && $max > 0 ){
            $meta_query[] = [
                'key'=>'_price',
                'value'=>$max,
                'compare'=>'<=',
                'type'=>'DECIMAL(10,2)'
            ];
        }

        if($tax_query){
            $existing = $query->get('tax_query');
            if(is_array($existing)) $tax_query = array_merge($existing,$tax_query);
            $query->set('tax_query',$tax_query);
        }
        if($meta_query){
            $existing_mq = $query->get('meta_query');
            if(is_array($existing_mq)) $meta_query = array_merge($existing_mq,$meta_query);
            $query->set('meta_query',$meta_query);
        }
    }

    public function auto_display_filters() {
        if(!is_shop() && !is_product_category() && !is_product_taxonomy()) return;
        echo $this->shortcode_filters();
    }

    public function shortcode_filters() {
        // ✅ Берём форматы и транспорт из Hub
        $formats_data = get_option('mst_formats', [
            'group' => ['name' => 'Групповая', 'icon' => '👥'],
            'individual' => ['name' => 'Индивидуальная', 'icon' => '⭐']
        ]);
        
        $formats = [];
        foreach($formats_data as $slug => $data) {
            $formats[] = ['code' => $slug, 'name' => $data['name'], 'icon' => $data['icon']];
        }
        
        $transports_data = get_option('mst_transports', [
            'walk' => ['name' => 'Пешком', 'icon' => '🚶‍♂️'],
            'car' => ['name' => 'Авто', 'icon' => '🚗'],
            'combined' => ['name' => 'Комбинированный', 'icon' => '🔁']
        ]);
        
        $transports = [];
        foreach($transports_data as $slug => $data) {
            $transports[] = ['code' => $slug, 'name' => $data['name'], 'icon' => $data['icon']];
        }
        
        $current_format = isset($_GET['format']) ? sanitize_text_field($_GET['format']) : '';
        $current_transport = isset($_GET['transport']) ? sanitize_text_field($_GET['transport']) : '';
        
        ob_start();
        ?>
        <div class="mst-filters-checkbox-wrapper" style="background:#fff;padding:20px;border-radius:12px;margin-bottom:30px;box-shadow:0 2px 12px rgba(0,0,0,0.06);">
 
        ob_start();
        ?>
        <div class="mst-filters-checkbox-wrapper" style="background:#fff;padding:20px;border-radius:12px;margin-bottom:30px;box-shadow:0 2px 12px rgba(0,0,0,0.06);">
            <h3 style="margin:0 0 20px;font-size:18px;color:#1d1d1f;">Фильтры</h3>
            
            <div class="mst-filter-section" style="margin-bottom:20px;">
                <h4 style="margin:0 0 12px;font-size:14px;color:#666;font-weight:600;">📅 Формат проведения</h4>
                <div class="mst-checkbox-list">
                    <?php foreach($formats as $f): ?>
                        <label style="display:flex;align-items:center;gap:10px;padding:10px;border-radius:8px;cursor:pointer;transition:background 0.2s;" class="mst-filter-label">
                            <input type="checkbox" name="format" value="<?php echo esc_attr($f['code']); ?>" <?php checked($current_format, $f['code']); ?> style="width:18px;height:18px;">
                            <span style="font-size:16px;"><?php echo $f['icon']; ?></span>
                            <span style="flex:1;font-size:14px;"><?php echo esc_html($f['name']); ?></span>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <div class="mst-filter-section" style="margin-bottom:20px;">
                <h4 style="margin:0 0 12px;font-size:14px;color:#666;font-weight:600;">🚶 Способ передвижения</h4>
                <div class="mst-checkbox-list">
                    <?php foreach($transports as $t): ?>
                        <label style="display:flex;align-items:center;gap:10px;padding:10px;border-radius:8px;cursor:pointer;transition:background 0.2s;" class="mst-filter-label">
                            <input type="checkbox" name="transport" value="<?php echo esc_attr($t['code']); ?>" <?php checked($current_transport, $t['code']); ?> style="width:18px;height:18px;">
                            <span style="font-size:16px;"><?php echo $t['icon']; ?></span>
                            <span style="flex:1;font-size:14px;"><?php echo esc_html($t['name']); ?></span>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <div class="mst-filter-buttons" style="display:flex;gap:10px;padding-top:15px;border-top:1px solid #e0e0e0;">
                <button type="button" class="mst-filter-reset" style="flex:1;padding:12px 20px;background:#f5f5f5;color:#333;border:none;border-radius:8px;font-weight:600;cursor:pointer;">Сброс</button>
                <button type="button" class="mst-filter-apply" style="flex:1;padding:12px 20px;background:#00c896;color:#fff;border:none;border-radius:8px;font-weight:600;cursor:pointer;">Применить</button>
            </div>
        </div>
        
        <style>
        .mst-filter-label:hover {
            background: #f5f5f5;
        }
        .mst-filter-apply:hover {
            background: #00b386;
        }
        .mst-filter-reset:hover {
            background: #e0e0e0;
        }
        </style>
        
        <script>
        (function(){
            const apply = document.querySelector('.mst-filter-apply');
            const reset = document.querySelector('.mst-filter-reset');
            
            if(apply){
                apply.addEventListener('click', function(){
                    const url = new URL(window.location.href);
                    const formatCb = document.querySelector('input[name="format"]:checked');
                    const transportCb = document.querySelector('input[name="transport"]:checked');
                    
                    if(formatCb) url.searchParams.set('format', formatCb.value);
                    else url.searchParams.delete('format');
                    
                    if(transportCb) url.searchParams.set('transport', transportCb.value);
                    else url.searchParams.delete('transport');
                    
                    window.location.href = url.toString();
                });
            }
            
            if(reset){
                reset.addEventListener('click', function(){
                    document.querySelectorAll('.mst-checkbox-list input').forEach(cb => cb.checked = false);
                    const url = new URL(window.location.href);
                    url.searchParams.delete('format');
                    url.searchParams.delete('transport');
                    window.location.href = url.toString();
                });
            }
        })();
        </script>
        <?php
        return ob_get_clean();
    }
}