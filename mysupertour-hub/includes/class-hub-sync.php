<?php
/**
 * Sync Handler - синхронизация форматов и транспорта с товарами
 * Author: Telegram @l1ghtsun
 * Author URI: https://t.me/l1ghtsun
 */
if(!defined('ABSPATH')) exit;

class MST_Hub_Sync {
    
    /**
     * Синхронизация всех товаров
     */
    public static function sync_all_products() {
        $query = new WP_Query([
            'post_type' => 'product',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'fields' => 'ids'
        ]);
        
        $count = 0;
        foreach($query->posts as $product_id) {
            $format = get_post_meta($product_id, '_mst_pi_format', true);
            $transport = get_post_meta($product_id, '_mst_pi_transport', true);
            
            if($format || $transport) {
                if(class_exists('MST_Product_Icons_Sync')) {
                    $sync = MST_Product_Icons_Sync::instance();
                    $sync->sync_on_save($product_id, get_post($product_id));
                    $count++;
                }
            }
        }
        
        return $count;
    }
    
    /**
     * Миграция URL-encoded форматов
     */
    public static function migrate_format_slugs() {
        global $wpdb;
        
        $results = $wpdb->get_results("
            SELECT post_id, meta_value 
            FROM {$wpdb->postmeta} 
            WHERE meta_key = '_mst_pi_format'
        ");
        
        $formats = get_option('mst_formats', []);
        $fixed = 0;
        
        foreach($results as $row) {
            $old_value = $row->meta_value;
            
            if (strpos($old_value, '%') !== false) {
                $old_value = urldecode($old_value);
            }
            
            $new_slug = '';
            foreach($formats as $slug => $data) {
                if (mb_strtolower($data['name']) === mb_strtolower($old_value)) {
                    $new_slug = $slug;
                    break;
                }
            }
            
            if (empty($new_slug)) {
                $new_slug = sanitize_title($old_value);
            }
            
            if ($new_slug !== $row->meta_value) {
                update_post_meta($row->post_id, '_mst_pi_format', $new_slug);
                $fixed++;
            }
        }
        
        return $fixed;
    }
}