<?php
/**
 * Core Helper Functions
 * Author: Telegram @l1ghtsun
 * Author URI: https://t.me/l1ghtsun
 */
if(!defined('ABSPATH')) exit;

class MST_Hub_Core {
    public static function get_products_with_icons($per_page = 10, $paged = 1){
        $offset = ($paged - 1) * $per_page;
        $query = new WP_Query([
            'post_type' => 'product','posts_per_page' => $per_page,'offset' => $offset,
            'meta_query' => ['relation' => 'OR',
                ['key' => '_mst_pi_format', 'compare' => 'EXISTS'],
                ['key' => '_mst_pi_transport', 'compare' => 'EXISTS'],
                ['key' => '_mst_pi_duration', 'compare' => 'EXISTS']
            ]
        ]);
        
        $products = [];
        if($query->have_posts()){
            while($query->have_posts()){
                $query->the_post();
                $id = get_the_ID();
                $format = get_post_meta($id, '_mst_pi_format', true);
                $transport = get_post_meta($id, '_mst_pi_transport', true);
                $duration = get_post_meta($id, '_mst_pi_duration', true);
                
                if($format || $transport || $duration){
                    $products[] = [
                        'id' => $id,'title' => get_the_title(),'thumbnail' => get_the_post_thumbnail($id, 'thumbnail'),
                        'format' => $format,'transport' => $transport,'duration' => $duration,'edit_url' => get_edit_post_link($id)
                    ];
                }
            }
            wp_reset_postdata();
        }
        return $products;
    }
    
    public static function count_products_with_icons(){
        $query = new WP_Query([
            'post_type' => 'product','posts_per_page' => -1,'fields' => 'ids',
            'meta_query' => ['relation' => 'OR',
                ['key' => '_mst_pi_format', 'compare' => 'EXISTS'],
                ['key' => '_mst_pi_transport', 'compare' => 'EXISTS'],
                ['key' => '_mst_pi_duration', 'compare' => 'EXISTS']
            ]
        ]);
        return $query->found_posts;
    }
}