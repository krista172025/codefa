<?php
/**
 * Formats Handler
 * Author: Telegram @l1ghtsun
 */
if(!defined('ABSPATH')) exit;

class MST_Hub_Formats {
    public static function add_format($name, $icon){
        $formats = get_option('mst_formats', []);
        $slug = sanitize_title($name); // ВСЕГДА латиница!
        $formats[$slug] = [
            'name' => sanitize_text_field($name),
            'icon' => sanitize_text_field($icon),
            'code' => $slug
        ];
        update_option('mst_formats', $formats);
        return true;
    }
    
    public static function edit_format($old_slug, $new_name, $new_icon){
        $formats = get_option('mst_formats', []);
        $new_slug = sanitize_title($new_name);
        
        if(isset($formats[$old_slug])){
            // Если slug изменился, обновляем мета-поля у всех товаров
            if($old_slug !== $new_slug){
                global $wpdb;
                $wpdb->query($wpdb->prepare(
                    "UPDATE {$wpdb->postmeta} SET meta_value = %s WHERE meta_key = '_mst_pi_format' AND meta_value = %s",
                    $new_slug, $old_slug
                ));
            }
            
            unset($formats[$old_slug]);
            $formats[$new_slug] = [
                'name' => sanitize_text_field($new_name),
                'icon' => sanitize_text_field($new_icon),
                'code' => $new_slug
            ];
            update_option('mst_formats', $formats);
            return true;
        }
        return false;
    }
    
    public static function delete_format($slug){
        $formats = get_option('mst_formats', []);
        if(isset($formats[$slug])){
            unset($formats[$slug]);
            update_option('mst_formats', $formats);
            return true;
        }
        return false;
    }
    
    public static function get_all(){
        return get_option('mst_formats', []);
    }
}