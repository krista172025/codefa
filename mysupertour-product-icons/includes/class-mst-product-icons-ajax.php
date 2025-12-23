<?php
/**
 * AJAX/REST: Получение HTML товаров без перезагрузки.
 * Author: @l1ghtsun (Telegram: https://t.me/l1ghtsun)
 */
if ( ! defined('ABSPATH') ) exit;

class MST_Product_Icons_Ajax {

    private static $inst;
    private $brand_tax        = 'brand';
    private $format_prefix    = 'mst-format-';
    private $transport_prefix = 'mst-transport-';
    private $allowed_params   = ['format','transport','min_price','max_price','paged'];

    public static function instance(){
        return self::$inst ?: self::$inst = new self();
    }

    private function __construct(){
        add_action('wp_enqueue_scripts', [ $this, 'enqueue' ]);
        add_action('wp_ajax_mst_pi_products',       [ $this, 'handle' ]);
        add_action('wp_ajax_nopriv_mst_pi_products',[ $this, 'handle' ]);
        add_action('rest_api_init', function(){
            register_rest_route('mst/v1','/products',[
                'methods'=>'GET',
                'callback'=>[ $this, 'rest_handle' ],
                'permission_callback'=>'__return_true'
            ]);
        });
    }

    public function enqueue(){
        if( is_post_type_archive('product') || is_tax(['product_cat','product_tag']) ){
            wp_enqueue_script(
                'mst-pi-ajax-filters',
                MST_PI_URL.'assets/js/mst-ajax-filters.js',
                ['jquery'],
                MST_PI_VERSION,
                true
            );
            wp_localize_script('mst-pi-ajax-filters','MST_PI_AJAX',[
                'url'   => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('mst_pi_ajax'),
                'rest'  => esc_url_raw( get_rest_url(null,'mst/v1/products') ),
            ]);
        }
    }

    public function handle(){
        check_ajax_referer('mst_pi_ajax','nonce');
        $params = [];
        foreach($this->allowed_params as $p){
            $params[$p] = isset($_REQUEST[$p]) ? sanitize_text_field($_REQUEST[$p]) : '';
        }
        wp_send_json( $this->build_response($params) );
    }

    public function rest_handle( WP_REST_Request $req ){
        return $this->build_response( $req->get_params() );
    }

    private function build_response(array $params){
        $format    = $params['format'] ?? '';
        $transport = $params['transport'] ?? '';
        $min_price = $params['min_price'] ?? '';
        $max_price = $params['max_price'] ?? '';
        $paged     = max(1, (int)($params['paged'] ?? 1));

        $tax_query  = [];
        $meta_query = [];

        if($format !== ''){
            $tax_query[] = [
                'taxonomy'=>'product_tag',
                'field'=>'slug',
                'terms'=>[ $this->format_prefix.$format ]
            ];
        }
        if($transport !== '' && taxonomy_exists($this->brand_tax)){
            $tax_query[] = [
                'taxonomy'=>$this->brand_tax,
                'field'=>'slug',
                'terms'=>[ $this->transport_prefix.$transport ]
            ];
        }
        if($min_price !== ''){
            $meta_query[] = [
                'key'=>'_price',
                'value'=>floatval($min_price),
                'compare'=>'>=',
                'type'=>'DECIMAL(10,2)'
            ];
        }
        if($max_price !== ''){
            $meta_query[] = [
                'key'=>'_price',
                'value'=>floatval($max_price),
                'compare'=>'<=',
                'type'=>'DECIMAL(10,2)'
            ];
        }

        // Fallback на стандартные значения колонок если функций нет
        if( function_exists('wc_get_default_products_per_row') ){
            $per_row = wc_get_default_products_per_row();
        } else {
            $per_row = 4;
        }
        if( function_exists('wc_get_default_columns') ){
            $columns = wc_get_default_columns();
        } else {
            $columns = 3;
        }

        $per_page = max(1, $per_row * $columns);

        $args = [
            'post_type'      => 'product',
            'post_status'    => 'publish',
            'posts_per_page' => $per_page,
            'paged'          => $paged,
            'tax_query'      => $tax_query ?: [],
            'meta_query'     => $meta_query ?: [],
        ];

        $count_args = $args;
        $count_args['fields'] = 'ids';
        $count_args['posts_per_page'] = -1;
        $count_args['paged'] = 1;

        $count_query = new WP_Query($count_args);
        $total = $count_query->found_posts;
        $pages = $total > 0 ? ceil($total / $per_page) : 1;

        $loop_query = new WP_Query($args);

        ob_start();
        if($loop_query->have_posts()){
            woocommerce_product_loop_start();
            while($loop_query->have_posts()){
                $loop_query->the_post();
                wc_get_template_part('content','product');
            }
            woocommerce_product_loop_end();

            if($pages > 1){
                echo '<nav class="mst-ajax-pagination">';
                for($i=1;$i<=$pages;$i++){
                    $cls = $i==$paged ? 'mst-page current' : 'mst-page';
                    echo '<button type="button" class="'.$cls.'" data-page="'.$i.'">'.$i.'</button>';
                }
                echo '</nav>';
            }
        } else {
            echo '<p class="mst-no-products">Товаров не найдено.</p>';
        }
        wp_reset_postdata();
        $html = ob_get_clean();

        return [
            'html'  => $html,
            'total' => $total,
            'pages' => $pages,
            'paged' => $paged,
            'state' => [
                'format'    => $format,
                'transport' => $transport,
                'min_price' => $min_price,
                'max_price' => $max_price
            ]
        ];
    }
}