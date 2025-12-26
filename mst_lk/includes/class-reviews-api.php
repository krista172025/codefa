<?php
/**
 * MST Reviews API
 * Live reviews system for MySuperTour
 */

if (!defined('ABSPATH')) exit;

class MST_Reviews_API {
    
    public static function init() {
        add_action('wp_ajax_mst_submit_review', [self::class, 'submit_review']);
        add_action('wp_ajax_mst_get_reviews', [self::class, 'get_reviews']);
        add_action('wp_ajax_nopriv_mst_get_reviews', [self::class, 'get_reviews']);
    }
    
    /**
     * Submit a review
     */
    public static function submit_review() {
        check_ajax_referer('mst_review_nonce', 'nonce');
        
        if (!is_user_logged_in()) {
            wp_send_json_error(['message' => __('Необходимо авторизоваться', 'mst-lk')]);
        }
        
        $product_id = intval($_POST['product_id']);
        $rating = intval($_POST['rating']);
        $comment = sanitize_textarea_field($_POST['comment']);
        $guide_id = intval($_POST['guide_id'] ?? 0);
        
        // Validate rating
        if ($rating < 1 || $rating > 5) {
            wp_send_json_error(['message' => __('Некорректный рейтинг', 'mst-lk')]);
        }
        
        // Check if user bought this product - include on-hold status
        $user_id = get_current_user_id();
        
        // Get user orders with different statuses
        $orders = wc_get_orders([
            'customer_id' => $user_id,
            'limit' => -1,
        ]);
        
        $has_purchased = false;
        $order_status = null;
        
        foreach ($orders as $order) {
            foreach ($order->get_items() as $item) {
                if ($item->get_product_id() == $product_id) {
                    $order_status = $order->get_status();
                    // Allow reviews for completed, processing, and on-hold orders
                    if (in_array($order_status, ['completed', 'processing', 'on-hold'])) {
                        $has_purchased = true;
                        break 2;
                    }
                }
            }
        }
        
        if (!$has_purchased) {
            // Provide specific messages based on order status
            if ($order_status === 'pending') {
                wp_send_json_error(['message' => __('⏳ Заказ ожидает оплаты. Вы сможете оставить отзыв после оплаты.', 'mst-lk')]);
            } elseif ($order_status === 'cancelled' || $order_status === 'failed') {
                wp_send_json_error(['message' => __('❌ Этот заказ был отменен. Вы не можете оставить отзыв.', 'mst-lk')]);
            } else {
                wp_send_json_error(['message' => __('❌ Вы не покупали этот тур', 'mst-lk')]);
            }
        }
        
        // Create WooCommerce review
        $comment_id = wp_insert_comment([
            'comment_post_ID' => $product_id,
            'comment_author' => wp_get_current_user()->display_name,
            'comment_author_email' => wp_get_current_user()->user_email,
            'comment_content' => $comment,
            'comment_type' => 'review',
            'comment_approved' => 1,
            'user_id' => $user_id,
        ]);
        
        if ($comment_id) {
            // Add rating
            update_comment_meta($comment_id, 'rating', $rating);
            
            // If guide is specified - add link
            if ($guide_id) {
                update_comment_meta($comment_id, 'mst_guide_id', $guide_id);
            }
            
            // Update product rating
            self::update_product_rating($product_id);
            
            // Update guide rating
            if ($guide_id) {
                self::update_guide_rating($guide_id);
            }
            
            wp_send_json_success([
                'message' => __('Отзыв успешно добавлен', 'mst-lk'),
                'review_id' => $comment_id
            ]);
        }
        
        wp_send_json_error(['message' => __('Ошибка при добавлении отзыва', 'mst-lk')]);
    }
    
    /**
     * Get reviews for Elementor widgets
     */
    public static function get_reviews() {
        $type = sanitize_text_field($_GET['type'] ?? 'all');
        $guide_id = intval($_GET['guide_id'] ?? 0);
        $product_id = intval($_GET['product_id'] ?? 0);
        $limit = intval($_GET['limit'] ?? 10);
        
        $args = [
            'type' => 'review',
            'status' => 'approve',
            'number' => $limit,
            'orderby' => 'comment_date',
            'order' => 'DESC',
        ];
        
        if ($product_id) {
            $args['post_id'] = $product_id;
        }
        
        if ($guide_id) {
            $args['meta_query'] = [
                ['key' => 'mst_guide_id', 'value' => $guide_id]
            ];
        }
        
        $reviews = get_comments($args);
        $result = [];
        
        foreach ($reviews as $review) {
            $result[] = [
                'id' => $review->comment_ID,
                'author' => $review->comment_author,
                'avatar' => get_avatar_url($review->comment_author_email, ['size' => 80]),
                'rating' => intval(get_comment_meta($review->comment_ID, 'rating', true)) ?: 5,
                'content' => $review->comment_content,
                'date' => human_time_diff(strtotime($review->comment_date)) . ' ' . __('назад', 'mst-lk'),
                'product_id' => $review->comment_post_ID,
                'product_title' => get_the_title($review->comment_post_ID),
            ];
        }
        
        wp_send_json_success(['reviews' => $result]);
    }
    
    /**
     * Update product average rating
     */
    private static function update_product_rating($product_id) {
        if (!function_exists('wc_get_product')) {
            return;
        }
        
        $product = wc_get_product($product_id);
        if ($product) {
            // Get all approved reviews
            $comments = get_comments([
                'post_id' => $product_id,
                'type' => 'review',
                'status' => 'approve',
            ]);
            
            if (count($comments) > 0) {
                $total = 0;
                foreach ($comments as $comment) {
                    $rating = intval(get_comment_meta($comment->comment_ID, 'rating', true));
                    $total += $rating ?: 5;
                }
                $average = $total / count($comments);
                
                update_post_meta($product_id, '_wc_average_rating', round($average, 2));
                update_post_meta($product_id, '_wc_review_count', count($comments));
                
                // Clear product cache for immediate visibility
                wc_delete_product_transients($product_id);
            }
        }
    }
    
    /**
     * Update guide average rating
     */
    private static function update_guide_rating($guide_id) {
        $reviews = get_comments([
            'type' => 'review',
            'status' => 'approve',
            'meta_query' => [
                ['key' => 'mst_guide_id', 'value' => $guide_id]
            ]
        ]);
        
        if ($reviews) {
            $total = 0;
            foreach ($reviews as $r) {
                $rating = intval(get_comment_meta($r->comment_ID, 'rating', true));
                $total += $rating ?: 5;
            }
            $avg = $total / count($reviews);
            update_user_meta($guide_id, 'mst_guide_rating', round($avg, 1));
            update_user_meta($guide_id, 'mst_guide_reviews_count', count($reviews));
        }
    }
}

// Initialize
MST_Reviews_API::init();
