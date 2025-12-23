<?php
/**
 * Синхронизация: формат -> product_tag, транспорт -> brand.
 * Author: @l1ghtsun (Telegram: https://t.me/l1ghtsun)
 */
if ( ! defined('ABSPATH') ) exit;

class MST_Product_Icons_Sync {

    private static $inst;

    private $brand_tax = 'brand';

   private function get_format_tags() {
    $formats = get_option('mst_formats', [
        'group' => ['name' => 'Групповая', 'icon' => '👥'],
        'individual' => ['name' => 'Индивидуальная', 'icon' => '⭐']
    ]);
    
    $tags = [];
    foreach($formats as $slug => $data) {
        $tags[$slug] = $data['name'];
    }
    return $tags;
  }

    private function get_transport_brands() {
    $transports = get_option('mst_transports', [
        'walk' => ['name' => 'Пешком', 'icon' => '🚶‍♂️'],
        'car' => ['name' => 'Авто', 'icon' => '🚗'],
        'combined' => ['name' => 'Комбинированный', 'icon' => '🔁']
    ]);
    
    $brands = [];
    foreach($transports as $slug => $data) {
        $brands[$slug] = $data['name'];
    }
    return $brands;
    }

    private $format_tag_prefix      = 'mst-format-';
    private $transport_brand_prefix = 'mst-transport-';

    public static function instance() {
        return self::$inst ?: self::$inst = new self();
    }

    private function __construct() {
        add_action('save_post_product', [ $this, 'sync_on_save' ], 50, 2);
        add_action('admin_init',        [ $this, 'maybe_bulk_sync' ]);
    }

    public function sync_on_save( $post_id, $post ) {
        if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) return;
        if ( $post->post_type !== 'product' ) return;

        $format    = get_post_meta($post_id,'_mst_pi_format',true);
        $transport = get_post_meta($post_id,'_mst_pi_transport',true);

        $this->sync_format_tag($post_id,$format);
        if ( taxonomy_exists($this->brand_tax) ) {
            $this->sync_transport_brand($post_id,$transport);
        }
    }

        private function sync_format_tag( $post_id, $format_code ) {
        $FORMAT_TAGS = $this->get_format_tags(); // ✅ Вызываем метод!
        
        $managed_slugs = [];
        foreach($FORMAT_TAGS as $code=>$label){
            $managed_slugs[] = $this->format_tag_prefix.$code;
        }
        $current = wp_get_post_terms($post_id,'product_tag',['fields'=>'all']);
        $keep = [];

        foreach($current as $t){
            if(in_array($t->slug,$managed_slugs,true)){
                $code_from_slug = substr($t->slug, strlen($this->format_tag_prefix));
                if($code_from_slug === $format_code){
                    $keep[] = $t->term_id;
                }
            } else {
                $keep[] = $t->term_id;
            }
        }

        if($format_code && isset($FORMAT_TAGS[$format_code])){
            $wanted_slug = $this->format_tag_prefix.$format_code;
            $wanted = get_term_by('slug',$wanted_slug,'product_tag');
            if(!$wanted){
                $res = wp_insert_term($FORMAT_TAGS[$format_code],'product_tag',['slug'=>$wanted_slug]);
                if(!is_wp_error($res)){
                    $keep[] = (int)$res['term_id'];
                }
            } else {
                $keep[] = $wanted->term_id;
            }
        }

        $keep = array_unique(array_filter($keep,'intval'));
        wp_set_post_terms($post_id,$keep,'product_tag');
    }

    private function sync_transport_brand( $post_id, $transport_code ) {
        $TRANSPORT_BRANDS = $this->get_transport_brands(); // ✅ Вызываем метод!
        
        $managed_slugs = [];
        foreach($TRANSPORT_BRANDS as $code=>$label){
            $managed_slugs[] = $this->transport_brand_prefix.$code;
        }
        $current = wp_get_object_terms($post_id,$this->brand_tax,['fields'=>'all']);
        $keep = [];

        foreach($current as $t){
            if(in_array($t->slug,$managed_slugs,true)){
                $code_from_slug = substr($t->slug, strlen($this->transport_brand_prefix));
                if($code_from_slug === $transport_code){
                    $keep[] = $t->term_id;
                }
            } else {
                $keep[] = $t->term_id;
            }
        }

        if($transport_code && isset($TRANSPORT_BRANDS[$transport_code])){
            $wanted_slug = $this->transport_brand_prefix.$transport_code;
            $wanted = get_term_by('slug',$wanted_slug,$this->brand_tax);
            if(!$wanted){
                $res = wp_insert_term($TRANSPORT_BRANDS[$transport_code],$this->brand_tax,['slug'=>$wanted_slug]);
                if(!is_wp_error($res)){
                    $keep[] = (int)$res['term_id'];
                }
            } else {
                $keep[] = $wanted->term_id;
            }
        }

        $keep = array_unique(array_filter($keep,'intval'));
        wp_set_object_terms($post_id,$keep,$this->brand_tax);
    }

    public function maybe_bulk_sync() {
        if ( empty($_GET['mst_sync_tags_brands']) ) return;
        if ( ! current_user_can('manage_woocommerce') ) return;

        $q = new WP_Query([
            'post_type'=>'product',
            'post_status'=>'any',
            'posts_per_page'=>-1,
            'fields'=>'ids'
        ]);
        foreach($q->posts as $pid){
            $this->sync_on_save($pid, get_post($pid));
        }
        wp_safe_redirect(remove_query_arg('mst_sync_tags_brands'));
        exit;
    }
}