<?php
/**
 * Transports Handler
 * Author: Telegram @l1ghtsun
 */
if(!defined('ABSPATH')) exit;

class MST_Hub_Transports {
    public static function add_transport($name, $icon){
        $transports = get_option('mst_transports', []);
        $slug = sanitize_title($name); // ВСЕГДА латиница!
        $transports[$slug] = [
            'name' => sanitize_text_field($name),
            'icon' => sanitize_text_field($icon),
            'code' => $slug
        ];
        update_option('mst_transports', $transports);
        return true;
    }
    
    public static function edit_transport($old_slug, $new_name, $new_icon){
        $transports = get_option('mst_transports', []);
        $new_slug = sanitize_title($new_name);
        
        if(isset($transports[$old_slug])){
            if($old_slug !== $new_slug){
                global $wpdb;
                $wpdb->query($wpdb->prepare(
                    "UPDATE {$wpdb->postmeta} SET meta_value = %s WHERE meta_key = '_mst_pi_transport' AND meta_value = %s",
                    $new_slug, $old_slug
                ));
            }
            
            unset($transports[$old_slug]);
            $transports[$new_slug] = [
                'name' => sanitize_text_field($new_name),
                'icon' => sanitize_text_field($new_icon),
                'code' => $new_slug
            ];
            update_option('mst_transports', $transports);
            return true;
        }
        return false;
    }
    
    public static function delete_transport($slug){
        $transports = get_option('mst_transports', []);
        if(isset($transports[$slug])){
            unset($transports[$slug]);
            update_option('mst_transports', $transports);
            return true;
        }
        return false;
    }
    
    public static function get_all(){
        return get_option('mst_transports', []);
    }
}