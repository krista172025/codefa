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
        add_shortcode('mst_guides_list', [$this, 'render_guides_list']);
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
            
            // Use new /guide/{id} URL format
            $guide_url = home_url('/guide/' . $guide_id);
            
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
    }
    
    public function render_guides_list($atts) {
        $atts = shortcode_atts([
            'per_page' => 12,
            'columns' => 3,
            'orderby' => 'display_name',
            'order' => 'ASC',
        ], $atts);
        
        // Get all guides
        $guides = get_users([
            'meta_key' => 'mst_user_status',
            'meta_value' => 'guide',
            'number' => intval($atts['per_page']),
            'orderby' => $atts['orderby'],
            'order' => $atts['order']
        ]);
        
        if (empty($guides)) {
            return '<div style="text-align:center;padding:60px 20px;"><p>Гиды не найдены</p></div>';
        }
        
        // Get guide profile page for links
        $guide_profile_url = '';
        $pages = get_posts([
            'post_type' => 'page',
            'posts_per_page' => 1,
            's' => '[mst_guide_profile]'
        ]);
        if (!empty($pages)) {
            $guide_profile_url = get_permalink($pages[0]->ID);
        }
        
        ob_start();
        ?>
        <style>
        .mst-guides-list-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .mst-guides-grid {
            display: grid;
            grid-template-columns: repeat(<?php echo intval($atts['columns']); ?>, 1fr);
            gap: 30px;
            margin-bottom: 40px;
        }
        
        @media (max-width: 992px) {
            .mst-guides-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 24px;
            }
        }
        
        @media (max-width: 576px) {
            .mst-guides-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }
        }
        
        .mst-guide-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            overflow: hidden;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        }
        
        .mst-guide-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            border-color: rgba(255, 255, 255, 0.3);
        }
        
        .mst-guide-card-photo {
            position: relative;
            height: 280px;
            overflow: hidden;
        }
        
        .mst-guide-card-photo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }
        
        .mst-guide-card:hover .mst-guide-card-photo img {
            transform: scale(1.05);
        }
        
        .mst-guide-card-photo-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, rgba(0,0,0,0.7) 0%, transparent 50%);
        }
        
        .mst-guide-card-photo-info {
            position: absolute;
            bottom: 16px;
            left: 16px;
            right: 16px;
            color: white;
        }
        
        .mst-guide-card-name {
            font-size: 22px;
            font-weight: 700;
            margin: 0 0 8px;
            color: white;
        }
        
        .mst-guide-card-location {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 14px;
            opacity: 0.95;
        }
        
        .mst-guide-card-content {
            padding: 20px;
        }
        
        .mst-guide-card-badges {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 16px;
        }
        
        .mst-guide-badge {
            padding: 6px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }
        
        .mst-guide-badge.verified {
            background: #E0F2FE;
            color: #0EA5E9;
        }
        
        .mst-guide-badge.my-super-tour {
            background: #FFF4E6;
            color: #F59E0B;
        }
        
        .mst-guide-badge.partner {
            background: #F0FDF4;
            color: #22C55E;
        }
        
        .mst-guide-badge.doctor {
            background: #FAE8FF;
            color: #C026D3;
        }
        
        .mst-guide-card-stats {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 16px;
            padding-bottom: 16px;
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
        }
        
        .mst-guide-stat {
            text-align: center;
        }
        
        .mst-guide-stat-label {
            font-size: 12px;
            color: #666;
            margin-bottom: 4px;
        }
        
        .mst-guide-stat-value {
            font-size: 18px;
            font-weight: 700;
            color: #333;
        }
        
        .mst-guide-card-languages,
        .mst-guide-card-specialties {
            margin-bottom: 16px;
        }
        
        .mst-guide-section-title {
            font-size: 13px;
            color: #666;
            margin-bottom: 8px;
            font-weight: 600;
        }
        
        .mst-guide-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
        }
        
        .mst-guide-tag {
            padding: 4px 10px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 500;
            background: rgba(0, 0, 0, 0.05);
            color: #555;
            border: 1px solid rgba(0, 0, 0, 0.1);
        }
        
        .mst-guide-view-profile-btn {
            display: block;
            width: 100%;
            padding: 12px 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-align: center;
            text-decoration: none;
            border-radius: 12px;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }
        
        .mst-guide-view-profile-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
            color: white;
            text-decoration: none;
        }
        
        .mst-guide-rating {
            display: flex;
            align-items: center;
            gap: 4px;
            font-size: 14px;
        }
        
        .mst-guide-rating-star {
            color: #FFA500;
        }
        
        .mst-guide-rating-value {
            font-weight: 700;
            color: #333;
        }
        
        .mst-guide-rating-count {
            color: #999;
            font-size: 13px;
        }
        </style>
        
        <div class="mst-guides-list-container">
            <div class="mst-guides-grid">
                <?php foreach ($guides as $guide): 
                    $guide_id = $guide->ID;
                    $custom_avatar = get_user_meta($guide_id, 'mst_lk_avatar', true);
                    $avatar_url = $custom_avatar ? wp_get_attachment_url($custom_avatar) : get_avatar_url($guide_id, ['size' => 400]);
                    
                    $rating = get_user_meta($guide_id, 'mst_guide_rating', true) ?: '5.0';
                    $reviews_count = get_user_meta($guide_id, 'mst_guide_reviews_count', true) ?: '0';
                    $languages = get_user_meta($guide_id, 'mst_guide_languages', true) ?: '';
                    $specialization = get_user_meta($guide_id, 'mst_guide_specialization', true) ?: '';
                    $city = get_user_meta($guide_id, 'mst_guide_city', true) ?: '';
                    $tours_count = get_user_meta($guide_id, 'mst_guide_tours_count', true) ?: '0';
                    $experience_years = get_user_meta($guide_id, 'mst_guide_experience_years', true) ?: '0';
                    
                    // Determine badges
                    $is_verified = floatval($rating) >= 4.5;
                    $is_my_super_tour = true; // All guides are My Super Tour
                    $is_partner = floatval($experience_years) >= 5;
                    $is_doctor = strpos(strtolower($guide->display_name), 'доктор') !== false || 
                                 strpos(strtolower(get_user_meta($guide_id, 'mst_guide_achievements', true) ?: ''), 'доктор') !== false;
                    
                    // Use new /guide/{id} URL format
                    $profile_url = home_url('/guide/' . $guide_id);
                ?>
                <div class="mst-guide-card">
                    <div class="mst-guide-card-photo">
                        <img src="<?php echo esc_url($avatar_url); ?>" alt="<?php echo esc_attr($guide->display_name); ?>">
                        <div class="mst-guide-card-photo-overlay"></div>
                        <div class="mst-guide-card-photo-info">
                            <h3 class="mst-guide-card-name"><?php echo esc_html($guide->display_name); ?></h3>
                            <?php if ($city): ?>
                            <div class="mst-guide-card-location">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/>
                                    <circle cx="12" cy="10" r="3" fill="rgba(255,255,255,0.8)"/>
                                </svg>
                                <span><?php echo esc_html($city); ?></span>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="mst-guide-card-content">
                        <div class="mst-guide-card-badges">
                            <?php if ($is_verified): ?>
                            <span class="mst-guide-badge verified">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                                    <polyline points="22 4 12 14.01 9 11.01"/>
                                </svg>
                                Верифицирован
                            </span>
                            <?php endif; ?>
                            
                            <?php if ($is_my_super_tour): ?>
                            <span class="mst-guide-badge my-super-tour">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                                    <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/>
                                </svg>
                                My Super Tour
                            </span>
                            <?php endif; ?>
                            
                            <?php if ($is_partner): ?>
                            <span class="mst-guide-badge partner">
                                Партнер
                            </span>
                            <?php endif; ?>
                            
                            <?php if ($is_doctor): ?>
                            <span class="mst-guide-badge doctor">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M22 10v6M2 10l10-5 10 5-10 5z"/>
                                    <path d="M6 12v5c3 3 9 3 12 0v-5"/>
                                </svg>
                                Доктор наук
                            </span>
                            <?php endif; ?>
                        </div>
                        
                        <div class="mst-guide-card-stats">
                            <div class="mst-guide-stat">
                                <div class="mst-guide-stat-label">Рейтинг</div>
                                <div class="mst-guide-rating">
                                    <span class="mst-guide-rating-star">★</span>
                                    <span class="mst-guide-rating-value"><?php echo esc_html($rating); ?></span>
                                    <span class="mst-guide-rating-count">(<?php echo esc_html($reviews_count); ?>)</span>
                                </div>
                            </div>
                            
                            <div class="mst-guide-stat">
                                <div class="mst-guide-stat-label">Экскурсий</div>
                                <div class="mst-guide-stat-value"><?php echo esc_html($tours_count); ?></div>
                            </div>
                        </div>
                        
                        <?php if ($languages): ?>
                        <div class="mst-guide-card-languages">
                            <div class="mst-guide-section-title">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display:inline-block;vertical-align:middle;">
                                    <path d="M5 8h10M5 8c0 4.97 5 7 5 7s5-2.03 5-7M3 3h18M3 21h8"/>
                                </svg>
                                Языки:
                            </div>
                            <div class="mst-guide-tags">
                                <?php foreach (array_slice(explode(',', $languages), 0, 3) as $lang): ?>
                                <span class="mst-guide-tag"><?php echo esc_html(trim($lang)); ?></span>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <?php if ($specialization): ?>
                        <div class="mst-guide-card-specialties">
                            <div class="mst-guide-section-title">Специализация:</div>
                            <div class="mst-guide-tags">
                                <?php foreach (array_slice(explode(',', $specialization), 0, 3) as $spec): ?>
                                <span class="mst-guide-tag"><?php echo esc_html(trim($spec)); ?></span>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <a href="<?php echo esc_url($profile_url); ?>" class="mst-guide-view-profile-btn">
                            Посмотреть профиль
                        </a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
        return ob_get_clean();
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
            
            <div style="background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);border-radius:20px;padding:50px 40px;color:#fff;margin-bottom:40px;">
                <div style="display:flex;gap:30px;align-items:flex-start;flex-wrap:wrap;">
                    <div style="flex-shrink:0;">
                        <div style="width:180px;height:180px;border-radius:50%;padding:5px;background:<?php echo $border_color; ?>;box-shadow:0 10px 30px rgba(0,0,0,0.3);">
                            <img src="<?php echo esc_url($avatar_url); ?>" alt="<?php echo esc_attr($guide->display_name); ?>" style="width:170px;height:170px;border-radius:50%;object-fit:cover;border:5px solid #fff;">
                        </div>
                    </div>
                    <div style="flex:1;min-width:300px;">
                        <h1 style="font-size:42px;font-weight:700;margin:0 0 10px;color:#fff;"><?php echo esc_html($guide->display_name); ?></h1>
                        <?php if ($city): ?>
                            <div style="font-size:18px;margin-bottom:15px;opacity:0.95;">📍 <?php echo esc_html($city); ?></div>
                        <?php endif; ?>
                        <div style="display:flex;align-items:center;gap:8px;font-size:22px;margin-bottom:30px;">
                            <span style="font-size:26px;">⭐</span>
                            <span style="font-weight:700;"><?php echo esc_html($rating); ?></span>
                            <span style="opacity:0.85;">(<?php echo esc_html($reviews_count); ?> отзывов)</span>
                        </div>
                        <div style="display:flex;gap:50px;flex-wrap:wrap;">
                            <div>
                                <div style="font-size:14px;opacity:0.8;margin-bottom:5px;">Опыт</div>
                                <div style="font-size:32px;font-weight:700;"><?php echo esc_html($experience_years); ?> лет</div>
                            </div>
                            <div>
                                <div style="font-size:14px;opacity:0.8;margin-bottom:5px;">Туров проведено</div>
                                <div style="font-size:32px;font-weight:700;"><?php echo esc_html($tours_count); ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <?php if ($languages): ?>
                <div style="background:#fff;border-radius:15px;padding:30px;margin-bottom:30px;box-shadow:0 2px 10px rgba(0,0,0,0.05);">
                    <h3 style="font-size:22px;font-weight:700;margin:0 0 20px;color:#333;">🗣️ Языки</h3>
                    <div style="display:flex;flex-wrap:wrap;gap:10px;">
                        <?php foreach (explode(',', $languages) as $lang): ?>
                            <span style="padding:10px 18px;border-radius:20px;font-size:15px;font-weight:600;background:#FFF4E6;color:#F59E0B;"><?php echo esc_html(trim($lang)); ?></span>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
            
            <?php if ($specialization): ?>
                <div style="background:#fff;border-radius:15px;padding:30px;margin-bottom:30px;box-shadow:0 2px 10px rgba(0,0,0,0.05);">
                    <h3 style="font-size:22px;font-weight:700;margin:0 0 20px;color:#333;">🎯 Специализация</h3>
                    <div style="display:flex;flex-wrap:wrap;gap:10px;">
                        <?php foreach (explode(',', $specialization) as $spec): ?>
                            <span style="padding:10px 18px;border-radius:20px;font-size:15px;font-weight:600;background:#E0F2FE;color:#0EA5E9;"><?php echo esc_html(trim($spec)); ?></span>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
            
            <?php if ($experience): ?>
                <div style="background:#fff;border-radius:15px;padding:30px;margin-bottom:30px;box-shadow:0 2px 10px rgba(0,0,0,0.05);">
                    <h3 style="font-size:22px;font-weight:700;margin:0 0 20px;color:#333;">📖 О гиде</h3>
                    <div style="line-height:1.8;font-size:16px;color:#555;"><?php echo nl2br(esc_html($experience)); ?></div>
                </div>
            <?php endif; ?>
            
            <?php if ($achievements): ?>
                <div style="background:#fff;border-radius:15px;padding:30px;margin-bottom:30px;box-shadow:0 2px 10px rgba(0,0,0,0.05);">
                    <h3 style="font-size:22px;font-weight:700;margin:0 0 20px;color:#333;">🏆 Достижения</h3>
                    <ul style="list-style:none;padding:0;margin:0;">
                        <?php foreach (explode("\n", $achievements) as $achievement): 
                            if (trim($achievement)): ?>
                                <li style="padding:12px 0;border-bottom:1px solid #f0f0f0;font-size:16px;color:#555;">🎖️ <?php echo esc_html(trim($achievement)); ?></li>
                            <?php endif;
                        endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            
            <?php if ($tours_query->have_posts()): ?>
                <div style="background:#fff;border-radius:15px;padding:30px;box-shadow:0 2px 10px rgba(0,0,0,0.05);">
                    <h2 style="font-size:30px;font-weight:700;margin:0 0 30px;color:#333;">Популярные туры</h2>
                    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:25px;">
                        <?php while ($tours_query->have_posts()): $tours_query->the_post();
                            $product = wc_get_product(get_the_ID());
                            ?>
                            <div style="border-radius:12px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,0.08);transition:transform 0.2s,box-shadow 0.2s;">
                                <a href="<?php the_permalink(); ?>" style="text-decoration:none;color:inherit;display:block;">
                                    <?php if (has_post_thumbnail()): ?>
                                        <?php the_post_thumbnail('medium', ['style' => 'width:100%;height:200px;object-fit:cover;']); ?>
                                    <?php endif; ?>
                                    <div style="padding:20px;">
                                        <h4 style="font-size:16px;font-weight:600;margin:0 0 15px;color:#333;"><?php the_title(); ?></h4>
                                        <div style="color:#00c896;font-weight:700;font-size:18px;"><?php echo $product->get_price_html(); ?></div>
                                    </div>
                                </a>
                            </div>
                        <?php endwhile; wp_reset_postdata(); ?>
                    </div>
                </div>
            <?php endif; ?>
            
        </div>
        <?php
        return ob_get_clean();
    }
}

new MST_Guide_System();