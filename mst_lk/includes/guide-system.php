<?php
/**
 * Система гидов для товаров WooCommerce
 * Author: Telegram @l1ghtsun
 * Author URI: https://t.me/l1ghtsun
 */

if (!defined('ABSPATH')) exit;

class MST_Guide_System {
    
    public function __construct() {
        add_action('add_meta_boxes', [$this, 'add_guide_metabox']);
        add_action('save_post', [$this, 'save_guide_meta']);
        add_action('rest_api_init', [$this, 'register_rest_route']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
        add_shortcode('mst_guide_profile', [$this, 'render_guide_profile']);
        add_action('show_user_profile', [$this, 'add_guide_review_fields']);
        add_action('edit_user_profile', [$this, 'add_guide_review_fields']);
        add_action('personal_options_update', [$this, 'save_guide_review_fields']);
        add_action('edit_user_profile_update', [$this, 'save_guide_review_fields']);
    }
    
    public function enqueue_assets() {
        add_action('wp_head', [$this, 'add_guide_styles'], 999);
        add_action('wp_footer', [$this, 'add_guide_script'], 999);
    }
    
    public function register_rest_route() {
        register_rest_route('mst/v1', '/guides/(?P<ids>[0-9,]+)', [
            'methods' => 'GET',
            'callback' => [$this, 'get_guides_data'],
            'permission_callback' => '__return_true'
        ]);
    }
    
    public function get_guides_data($request) {
        $ids = explode(',', $request['ids']);
        $result = [];
        
        foreach ($ids as $product_id) {
            $product_id = intval($product_id);
            $guide_id = get_post_meta($product_id, '_mst_guide_id', true);
            
            if (!$guide_id) continue;
            
            $guide = get_userdata($guide_id);
            if (!$guide) continue;
            
            $custom_avatar = get_user_meta($guide_id, 'mst_lk_avatar', true);
            $avatar_url = $custom_avatar ? wp_get_attachment_url($custom_avatar) : get_avatar_url($guide_id, ['size' => 80]);
            
            $guide_rating = get_user_meta($guide_id, 'mst_guide_rating', true) ?: '5.0';
            $guide_reviews_count = get_user_meta($guide_id, 'mst_guide_reviews_count', true) ?: '0';
            
            $user_status = get_user_meta($guide_id, 'mst_user_status', true) ?: 'guide';
            $status_colors = [
                'bronze' => '#CD7F32',
                'silver' => '#C0C0C0', 
                'gold' => '#FFD700',
                'guide' => '#00c896'
            ];
            $border_color = $status_colors[$user_status] ?? '#00c896';
            
            $pages = get_posts([
                'post_type' => 'page',
                'posts_per_page' => 1,
                's' => '[mst_guide_profile]'
            ]);
            
            if (!empty($pages)) {
                $guide_url = add_query_arg('guide_id', $guide_id, get_permalink($pages[0]->ID));
            } else {
                $guide_url = '#';
            }
            
            $result[$product_id] = [
                'name' => $guide->display_name,
                'avatar' => $avatar_url,
                'rating' => $guide_rating,
                'reviews' => $guide_reviews_count,
                'border' => $border_color,
                'url' => $guide_url
            ];
        }
        
        return $result;
    }
    
    public function add_guide_styles() {
        ?>
        <style id="mst-guide-styles">
        .mst-guide-loop-card {
            margin-top: 12px !important;
            padding: 0 !important;
            background: #fff !important;
            border-radius: 12px !important;
            border: 1px solid #e8e8e8 !important;
            transition: all 0.3s ease !important;
            display: block !important;
            text-decoration: none !important;
            width: 100% !important;
            box-sizing: border-box !important;
            overflow: hidden !important;
        }
        
        .mst-guide-loop-card:hover {
            border-color: #00c896 !important;
            box-shadow: 0 4px 15px rgba(0, 200, 150, 0.15) !important;
            text-decoration: none !important;
            transform: translateY(-2px) !important;
        }
        
        .mst-guide-loop-inner {
            display: flex !important;
            align-items: center !important;
            gap: 12px !important;
            padding: 12px 14px !important;
        }
        
        .mst-guide-avatar-wrapper {
            flex-shrink: 0 !important;
            width: 44px !important;
            height: 44px !important;
        }
        
        .mst-guide-avatar-border {
            width: 44px !important;
            height: 44px !important;
            border-radius: 50% !important;
            padding: 2px !important;
            background: linear-gradient(135deg, var(--guide-border-color, #00c896) 0%, var(--guide-border-color, #00c896) 100%) !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            box-shadow: 0 3px 8px rgba(0, 200, 150, 0.25) !important;
        }
        
        .mst-guide-loop-avatar {
            width: 40px !important;
            height: 40px !important;
            border-radius: 50% !important;
            object-fit: cover !important;
            display: block !important;
            border: 2px solid #fff !important;
        }
        
        .mst-guide-loop-info {
            flex: 1 !important;
            min-width: 0 !important;
            display: flex !important;
            align-items: center !important;
            justify-content: space-between !important;
            gap: 10px !important;
        }
        
        .mst-guide-loop-name {
            font-size: 14px !important;
            font-weight: 600 !important;
            color: #232323 !important;
            margin: 0 !important;
            overflow: hidden !important;
            text-overflow: ellipsis !important;
            white-space: nowrap !important;
            flex: 1 !important;
        }
        
        .mst-guide-loop-card:hover .mst-guide-loop-name {
            color: #00c896 !important;
        }
        
        .mst-guide-loop-rating {
            display: flex !important;
            align-items: center !important;
            gap: 4px !important;
            font-size: 13px !important;
            white-space: nowrap !important;
        }
        
        .mst-rating-star {
            color: #ffa500 !important;
            font-size: 14px !important;
        }
        
        .mst-rating-value {
            font-weight: 700 !important;
            color: #232323 !important;
        }
        
        .mst-rating-count {
            color: #999 !important;
            font-size: 12px !important;
        }
        </style>
        <?php
    }
    
    public function add_guide_script() {
        ?>
        <script>
        (function() {
            'use strict';
            
            var processedProducts = new Set();
            var guidesCache = {};
            var pendingProducts = [];
            var fetchTimer = null;
            var isFetching = false;
            
            function createGuideCard(data) {
                var card = document.createElement('a');
                card.href = data.url;
                card.className = 'mst-guide-loop-card';
                card.onclick = function(e) { e.stopPropagation(); };
                card.innerHTML = 
                    '<div class="mst-guide-loop-inner">' +
                        '<div class="mst-guide-avatar-wrapper">' +
                            '<div class="mst-guide-avatar-border" style="--guide-border-color: ' + data.border + '">' +
                                '<img src="' + data.avatar + '" alt="' + data.name + '" class="mst-guide-loop-avatar" loading="lazy">' +
                            '</div>' +
                        '</div>' +
                        '<div class="mst-guide-loop-info">' +
                            '<div class="mst-guide-loop-name">' + data.name + '</div>' +
                            '<div class="mst-guide-loop-rating">' +
                                '<span class="mst-rating-star">★</span>' +
                                '<span class="mst-rating-value">' + data.rating + '</span>' +
                                '<span class="mst-rating-count">(' + data.reviews + ')</span>' +
                            '</div>' +
                        '</div>' +
                    '</div>';
                return card;
            }
            
            function batchFetch() {
                if (pendingProducts.length === 0 || isFetching) return;
                
                isFetching = true;
                
                var productIds = pendingProducts.map(function(p) { return p.id; });
                var uniqueIds = productIds.filter(function(id, index) { 
                    return productIds.indexOf(id) === index; 
                });
                
                pendingProducts = [];
                
                fetch('<?php echo rest_url('mst/v1/guides/'); ?>' + uniqueIds.join(','))
                    .then(function(response) { 
                        if (!response.ok) throw new Error('HTTP ' + response.status);
                        return response.json(); 
                    })
                    .then(function(data) {
                        Object.assign(guidesCache, data);
                        
                        var products = document.querySelectorAll('.etheme-product-grid-item');
                        products.forEach(function(product) {
                            var match = product.className.match(/post-(\d+)/);
                            if (!match) return;
                            
                            var productId = match[1];
                            if (processedProducts.has(productId)) return;
                            if (product.querySelector('.mst-guide-loop-card')) return;
                            
                            if (guidesCache[productId]) {
                                processedProducts.add(productId);
                                var target = product.querySelector('.etheme-product-grid-content');
                                if (target) {
                                    target.appendChild(createGuideCard(guidesCache[productId]));
                                }
                            }
                        });
                        
                        isFetching = false;
                    })
                    .catch(function(error) {
                        isFetching = false;
                    });
            }
            
            function processProduct(product) {
                var match = product.className.match(/post-(\d+)/);
                if (!match) return;
                
                var productId = match[1];
                
                if (processedProducts.has(productId)) return;
                if (product.querySelector('.mst-guide-loop-card')) return;
                
                if (guidesCache[productId]) {
                    processedProducts.add(productId);
                    var target = product.querySelector('.etheme-product-grid-content');
                    if (target) {
                        target.appendChild(createGuideCard(guidesCache[productId]));
                    }
                    return;
                }
                
                if (!pendingProducts.find(function(p) { return p.id === productId; })) {
                    pendingProducts.push({ id: productId });
                }
                
                clearTimeout(fetchTimer);
                fetchTimer = setTimeout(batchFetch, 200);
            }
            
            function scanAllProducts() {
                var products = document.querySelectorAll('.etheme-product-grid-item');
                products.forEach(processProduct);
            }
            
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', scanAllProducts);
            } else {
                scanAllProducts();
            }
            
            setTimeout(scanAllProducts, 300);
            
            var observer = new MutationObserver(function(mutations) {
                var needScan = false;
                mutations.forEach(function(mutation) {
                    mutation.addedNodes.forEach(function(node) {
                        if (node.nodeType === 1) {
                            if (node.classList && node.classList.contains('etheme-product-grid-item')) {
                                needScan = true;
                            }
                            if (node.querySelectorAll) {
                                var products = node.querySelectorAll('.etheme-product-grid-item');
                                if (products.length > 0) needScan = true;
                            }
                        }
                    });
                });
                if (needScan) setTimeout(scanAllProducts, 100);
            });
            
            var container = document.querySelector('body');
            if (container) {
                observer.observe(container, { childList: true, subtree: true });
            }
        })();
        </script>
        <?php
    }
    
    public function add_guide_metabox() {
        add_meta_box('mst_product_guide', '👨‍🎓 Гид экскурсии', [$this, 'render_guide_metabox'], 'product', 'side', 'default');
    }
    
    public function render_guide_metabox($post) {
        $guide_id = get_post_meta($post->ID, '_mst_guide_id', true);
        $guides = get_users(['meta_key' => 'mst_user_status', 'meta_value' => 'guide', 'orderby' => 'display_name', 'order' => 'ASC']);
        wp_nonce_field('mst_save_guide', 'mst_guide_nonce');
        echo '<select name="mst_guide_id" style="width: 100%; padding: 8px;"><option value="">-- Без гида --</option>';
        foreach ($guides as $guide) {
            $selected = ($guide_id == $guide->ID) ? 'selected' : '';
            echo '<option value="' . $guide->ID . '" ' . $selected . '>' . esc_html($guide->display_name) . '</option>';
        }
        echo '</select><p style="margin-top: 10px; font-size: 12px; color: #666;">Выберите гида для этой экскурсии</p>';
        if ($guide_id) {
            $guide = get_userdata($guide_id);
            if ($guide) echo '<p style="margin-top: 8px; color: #00c896; font-weight: 600;">✓ Гид: ' . esc_html($guide->display_name) . '</p>';
        }
    }
    
    public function save_guide_meta($post_id) {
        if (!isset($_POST['mst_guide_nonce']) || !wp_verify_nonce($_POST['mst_guide_nonce'], 'mst_save_guide')) return;
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
        if (!current_user_can('edit_post', $post_id)) return;
        $guide_id = isset($_POST['mst_guide_id']) ? intval($_POST['mst_guide_id']) : 0;
        update_post_meta($post_id, '_mst_guide_id', $guide_id);
    }
    
    public function add_guide_review_fields($user) {
        ?>
        <h3>📊 Статистика и профиль гида</h3>
        <table class="form-table">
            <tr>
                <th><label for="mst_guide_rating">Рейтинг гида</label></th>
                <td>
                    <input type="number" name="mst_guide_rating" id="mst_guide_rating" 
                           value="<?php echo esc_attr(get_user_meta($user->ID, 'mst_guide_rating', true) ?: '5.0'); ?>" 
                           class="regular-text" step="0.1" min="0" max="5">
                    <p class="description">Средний рейтинг (0.0 - 5.0)</p>
                </td>
            </tr>
            <tr>
                <th><label for="mst_guide_reviews_count">Количество отзывов</label></th>
                <td>
                    <input type="number" name="mst_guide_reviews_count" id="mst_guide_reviews_count" 
                           value="<?php echo esc_attr(get_user_meta($user->ID, 'mst_guide_reviews_count', true) ?: '1902'); ?>" 
                           class="regular-text" min="0">
                    <p class="description">Общее количество отзывов на гида</p>
                </td>
            </tr>
            <tr>
                <th><label for="mst_guide_experience">О гиде</label></th>
                <td>
                    <textarea name="mst_guide_experience" id="mst_guide_experience" 
                              class="large-text" rows="5"><?php echo esc_textarea(get_user_meta($user->ID, 'mst_guide_experience', true) ?: 'Профессиональный гид с 8-летним опытом. Специализируюсь на исторических турах по Санкт-Петербургу. Влюблена в архитектуру и историю своего города. Каждая экскурсия - это увлекательное путешествие во времени.'); ?></textarea>
                    <p class="description">Описание опыта и информация о гиде</p>
                </td>
            </tr>
            <tr>
                <th><label for="mst_guide_languages">Языки</label></th>
                <td>
                    <input type="text" name="mst_guide_languages" id="mst_guide_languages" 
                           value="<?php echo esc_attr(get_user_meta($user->ID, 'mst_guide_languages', true) ?: 'Русский, Английский, Французский'); ?>" 
                           class="regular-text">
                    <p class="description">Языки через запятую</p>
                </td>
            </tr>
            <tr>
                <th><label for="mst_guide_specialization">Специализация</label></th>
                <td>
                    <input type="text" name="mst_guide_specialization" id="mst_guide_specialization" 
                           value="<?php echo esc_attr(get_user_meta($user->ID, 'mst_guide_specialization', true) ?: 'Исторические туры, Музеи, Архитектура'); ?>" 
                           class="regular-text">
                    <p class="description">Специализация через запятую</p>
                </td>
            </tr>
            <tr>
                <th><label for="mst_guide_city">Город</label></th>
                <td>
                    <input type="text" name="mst_guide_city" id="mst_guide_city" 
                           value="<?php echo esc_attr(get_user_meta($user->ID, 'mst_guide_city', true) ?: 'Санкт-Петербург'); ?>" 
                           class="regular-text">
                    <p class="description">Город работы гида</p>
                </td>
            </tr>
            <tr>
                <th><label for="mst_guide_experience_years">Опыт (лет)</label></th>
                <td>
                    <input type="number" name="mst_guide_experience_years" id="mst_guide_experience_years" 
                           value="<?php echo esc_attr(get_user_meta($user->ID, 'mst_guide_experience_years', true) ?: '8'); ?>" 
                           class="regular-text" min="0">
                    <p class="description">Количество лет опыта работы гидом</p>
                </td>
            </tr>
            <tr>
                <th><label for="mst_guide_tours_count">Туров проведено</label></th>
                <td>
                    <input type="number" name="mst_guide_tours_count" id="mst_guide_tours_count" 
                           value="<?php echo esc_attr(get_user_meta($user->ID, 'mst_guide_tours_count', true) ?: '234'); ?>" 
                           class="regular-text" min="0">
                    <p class="description">Общее количество проведенных туров</p>
                </td>
            </tr>
            <tr>
                <th><label for="mst_guide_achievements">Достижения</label></th>
                <td>
                    <textarea name="mst_guide_achievements" id="mst_guide_achievements" 
                              class="large-text" rows="3"><?php echo esc_textarea(get_user_meta($user->ID, 'mst_guide_achievements', true) ?: "Лучший гид 2023 года\nСертифицированный историк\n500+ довольных туристов"); ?></textarea>
                    <p class="description">По одному достижению на строку</p>
                </td>
            </tr>
            <tr>
                <th><label for="mst_guide_testimonials">Отзывы туристов (JSON)</label></th>
                <td>
                    <textarea name="mst_guide_testimonials" id="mst_guide_testimonials" 
                              class="large-text" rows="10" style="font-family:monospace;font-size:12px;"><?php 
                    $testimonials = get_user_meta($user->ID, 'mst_guide_testimonials', true);
                    if (empty($testimonials)) {
                        $testimonials = [
                            ['author' => 'Анна С.', 'rating' => 5, 'text' => 'Невероятная экскурсия! Мария рассказала так много интересного об истории города. Рекомендую!', 'date' => '2 дня назад'],
                            ['author' => 'Джон М.', 'rating' => 5, 'text' => 'Best tour guide! Very knowledgeable and friendly.', 'date' => '1 неделю назад'],
                            ['author' => 'Елена К.', 'rating' => 5, 'text' => 'Профессионал своего дела. Время пролетело незаметно. Обязательно вернемся на другие туры!', 'date' => '2 недели назад']
                        ];
                    }
                    echo esc_textarea(json_encode($testimonials, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)); 
                    ?></textarea>
                    <p class="description">JSON массив с отзывами. Формат: [{"author": "Имя", "rating": 5, "text": "Текст отзыва", "date": "Дата"}]</p>
                </td>
            </tr>
        </table>
        <?php
    }
    
    public function save_guide_review_fields($user_id) {
        if (!current_user_can('edit_user', $user_id)) return false;
        
        update_user_meta($user_id, 'mst_guide_rating', sanitize_text_field($_POST['mst_guide_rating'] ?? '5.0'));
        update_user_meta($user_id, 'mst_guide_reviews_count', intval($_POST['mst_guide_reviews_count'] ?? 0));
        update_user_meta($user_id, 'mst_guide_experience', sanitize_textarea_field($_POST['mst_guide_experience'] ?? ''));
        update_user_meta($user_id, 'mst_guide_languages', sanitize_text_field($_POST['mst_guide_languages'] ?? ''));
        update_user_meta($user_id, 'mst_guide_specialization', sanitize_text_field($_POST['mst_guide_specialization'] ?? ''));
        update_user_meta($user_id, 'mst_guide_city', sanitize_text_field($_POST['mst_guide_city'] ?? ''));
        update_user_meta($user_id, 'mst_guide_experience_years', intval($_POST['mst_guide_experience_years'] ?? 0));
        update_user_meta($user_id, 'mst_guide_tours_count', intval($_POST['mst_guide_tours_count'] ?? 0));
        update_user_meta($user_id, 'mst_guide_achievements', sanitize_textarea_field($_POST['mst_guide_achievements'] ?? ''));
        
        // Save testimonials as JSON
        if (isset($_POST['mst_guide_testimonials'])) {
            $testimonials_json = stripslashes($_POST['mst_guide_testimonials']);
            $testimonials = json_decode($testimonials_json, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($testimonials)) {
                update_user_meta($user_id, 'mst_guide_testimonials', $testimonials);
            }
        }
    }
    
    public function render_guide_profile($atts) {
        $guide_id = isset($_GET['guide_id']) ? intval($_GET['guide_id']) : 0;
        
        if (!$guide_id) {
            return '<div style="text-align:center;padding:60px 20px;"><h2>Гид не найден</h2><p>Пожалуйста, выберите гида из каталога экскурсий.</p></div>';
        }
        
        $guide = get_userdata($guide_id);
        if (!$guide) {
            return '<div style="text-align:center;padding:60px 20px;"><h2>Гид не найден</h2><p>Пожалуйста, выберите гида из каталога экскурсий.</p></div>';
        }
        
        $custom_avatar = get_user_meta($guide_id, 'mst_lk_avatar', true);
        $avatar_url = $custom_avatar ? wp_get_attachment_url($custom_avatar) : get_avatar_url($guide_id, ['size' => 200]);
        
        $rating = get_user_meta($guide_id, 'mst_guide_rating', true) ?: '5.0';
        $reviews_count = get_user_meta($guide_id, 'mst_guide_reviews_count', true) ?: '0';
        $experience = get_user_meta($guide_id, 'mst_guide_experience', true) ?: '';
        $languages = get_user_meta($guide_id, 'mst_guide_languages', true) ?: '';
        $specialization = get_user_meta($guide_id, 'mst_guide_specialization', true) ?: '';
        $city = get_user_meta($guide_id, 'mst_guide_city', true) ?: '';
        $experience_years = get_user_meta($guide_id, 'mst_guide_experience_years', true) ?: '8';
        $tours_count = get_user_meta($guide_id, 'mst_guide_tours_count', true) ?: '234';
        $achievements = get_user_meta($guide_id, 'mst_guide_achievements', true) ?: '';
        $testimonials = get_user_meta($guide_id, 'mst_guide_testimonials', true) ?: '';
        
        $user_status = get_user_meta($guide_id, 'mst_user_status', true) ?: 'guide';
        $status_colors = [
            'bronze' => '#CD7F32',
            'silver' => '#C0C0C0', 
            'gold' => '#FFD700',
            'guide' => '#00c896'
        ];
        $border_color = $status_colors[$user_status] ?? '#00c896';
        
        $tours_args = [
            'post_type' => 'product',
            'posts_per_page' => 6,
            'meta_query' => [[
                'key' => '_mst_guide_id',
                'value' => $guide_id,
                'compare' => '='
            ]]
        ];
        $tours_query = new WP_Query($tours_args);
        
        ob_start();
        ?>
        <div class="mst-guide-profile" style="max-width:1200px;margin:0 auto;padding:20px;">
            
            <!-- Hero Section with Glass Liquid Style -->
            <div class="mst-guide-hero glass-liquid-hero">
                <div class="mst-guide-hero-content">
                    <div class="mst-guide-avatar-section">
                        <div class="mst-guide-avatar-wrapper hover-lift-gentle" style="background:<?php echo esc_attr($border_color); ?>;">
                            <div class="mst-guide-avatar-ring">
                                <img src="<?php echo esc_url($avatar_url); ?>" 
                                     alt="<?php echo esc_attr($guide->display_name); ?>" 
                                     class="mst-guide-avatar-img">
                            </div>
                        </div>
                    </div>
                    <div class="mst-guide-info">
                        <h1 class="mst-guide-name"><?php echo esc_html($guide->display_name); ?></h1>
                        <?php if ($city): ?>
                            <div class="mst-guide-location">
                                <span class="mst-icon mst-icon-location"></span>
                                <?php echo esc_html($city); ?>
                            </div>
                        <?php endif; ?>
                        <div class="mst-guide-rating">
                            <span class="mst-guide-rating-star">⭐</span>
                            <span class="mst-guide-rating-value"><?php echo esc_html($rating); ?></span>
                            <span class="mst-guide-rating-count">(<?php echo esc_html($reviews_count); ?> отзывов)</span>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="mst-guide-actions">
                            <a href="#tours" class="mst-btn-book-tour">
                                <span class="mst-icon mst-icon-calendar"></span>
                                Забронировать тур
                            </a>
                            <a href="#" class="mst-btn-icon-action" onclick="return false;" title="Добавить в избранное">
                                <span class="mst-icon mst-icon-heart"></span>
                            </a>
                            <a href="#" class="mst-btn-icon-action" onclick="return false;" title="Поделиться">
                                <span class="mst-icon mst-icon-share"></span>
                            </a>
                        </div>
                        
                        <div class="mst-guide-stats">
                            <div class="mst-guide-stat">
                                <span class="mst-guide-stat-label">Опыт</span>
                                <span class="mst-guide-stat-value"><?php echo esc_html($experience_years); ?> лет</span>
                            </div>
                            <div class="mst-guide-stat">
                                <span class="mst-guide-stat-label">Туров проведено</span>
                                <span class="mst-guide-stat-value"><?php echo esc_html($tours_count); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <?php if ($languages): ?>
                <div class="mst-guide-section">
                    <h3 class="mst-guide-section-title">
                        <span class="mst-icon mst-icon-language"></span>
                        Языки
                    </h3>
                    <div class="mst-guide-badges-container">
                        <?php foreach (explode(',', $languages) as $lang): ?>
                            <span class="badge-warm"><?php echo esc_html(trim($lang)); ?></span>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
            
            <?php if ($specialization): ?>
                <div class="mst-guide-section">
                    <h3 class="mst-guide-section-title">
                        <span class="mst-icon mst-icon-award"></span>
                        Специализация
                    </h3>
                    <div class="mst-guide-badges-container">
                        <?php foreach (explode(',', $specialization) as $spec): ?>
                            <span class="badge-trust"><?php echo esc_html(trim($spec)); ?></span>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
            
            <?php if ($experience): ?>
                <div class="mst-guide-section">
                    <h3 class="mst-guide-section-title">О гиде</h3>
                    <div class="mst-guide-description"><?php echo nl2br(esc_html($experience)); ?></div>
                </div>
            <?php endif; ?>
            
            <?php if ($achievements): ?>
                <div class="mst-guide-section">
                    <h3 class="mst-guide-section-title">🏆 Достижения</h3>
                    <ul style="list-style:none;padding:0;margin:0;">
                        <?php foreach (explode("\n", $achievements) as $achievement): 
                            if (trim($achievement)): ?>
                                <li style="padding:12px 0;border-bottom:1px solid #f0f0f0;font-size:16px;color:#555;">
                                    🎖️ <?php echo esc_html(trim($achievement)); ?>
                                </li>
                            <?php endif;
                        endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            
            <?php if ($tours_query->have_posts()): ?>
                <div class="mst-guide-section" id="tours">
                    <h2 class="mst-guide-section-title" style="font-size:30px;">Популярные туры</h2>
                    <div class="mst-guide-tours-grid">
                        <?php while ($tours_query->have_posts()): $tours_query->the_post();
                            $product = wc_get_product(get_the_ID());
                            $product_rating = $product->get_average_rating();
                            ?>
                            <div class="mst-tour-card glass-liquid hover-lift-gentle transition-smooth">
                                <a href="<?php the_permalink(); ?>" style="text-decoration:none;color:inherit;display:block;">
                                    <?php if (has_post_thumbnail()): ?>
                                        <div class="mst-tour-card-image">
                                            <?php the_post_thumbnail('medium', ['style' => 'width:100%;height:100%;object-fit:cover;']); ?>
                                        </div>
                                    <?php endif; ?>
                                    <div class="mst-tour-card-content">
                                        <h4 class="mst-tour-card-title"><?php the_title(); ?></h4>
                                        <div class="mst-tour-card-footer">
                                            <div class="mst-tour-card-price"><?php echo $product->get_price_html(); ?></div>
                                            <?php if ($product_rating > 0): ?>
                                                <div class="mst-tour-card-rating">
                                                    <span class="mst-tour-rating-star">⭐</span>
                                                    <span><?php echo number_format($product_rating, 1); ?></span>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        <?php endwhile; wp_reset_postdata(); ?>
                    </div>
                </div>
            <?php endif; ?>
            
            <?php if ($testimonials): 
                $testimonials_data = maybe_unserialize($testimonials);
                if (is_array($testimonials_data) && !empty($testimonials_data)): ?>
                    <div class="mst-guide-section" style="background:#f8f9fa;">
                        <h2 class="mst-guide-section-title" style="font-size:30px;">Отзывы туристов</h2>
                        <div class="mst-testimonials-grid">
                            <?php foreach ($testimonials_data as $testimonial): ?>
                                <div class="mst-testimonial-card card-warm hover-lift-gentle transition-smooth">
                                    <div class="mst-testimonial-header">
                                        <span class="mst-testimonial-author"><?php echo esc_html($testimonial['author'] ?? 'Аноним'); ?></span>
                                        <div class="mst-testimonial-stars">
                                            <?php 
                                            $stars = intval($testimonial['rating'] ?? 5);
                                            for ($i = 0; $i < $stars; $i++): ?>
                                                <span class="mst-testimonial-star">⭐</span>
                                            <?php endfor; ?>
                                        </div>
                                    </div>
                                    <p class="mst-testimonial-text"><?php echo esc_html($testimonial['text'] ?? ''); ?></p>
                                    <span class="mst-testimonial-date"><?php echo esc_html($testimonial['date'] ?? ''); ?></span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif;
            endif; ?>
            
        </div>
        <?php
        return ob_get_clean();
    }
}

new MST_Guide_System();