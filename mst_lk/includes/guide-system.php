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
        add_action('init', [$this, 'add_rewrite_rules']);
        add_filter('query_vars', [$this, 'add_query_vars']);
        add_action('template_redirect', [$this, 'guide_profile_redirect']);
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
    
    // Add rewrite rules for /guide/{id}
    public function add_rewrite_rules() {
        add_rewrite_rule('^guide/([0-9]+)/?$', 'index.php?mst_guide_profile=1&guide_id=$matches[1]', 'top');
        add_rewrite_rule('^guides/?$', 'index.php?mst_guides_list=1', 'top');
    }
    
    // Add query vars
    public function add_query_vars($vars) {
        $vars[] = 'mst_guide_profile';
        $vars[] = 'mst_guides_list';
        $vars[] = 'guide_id';
        return $vars;
    }
    
    // Handle template redirect for guide profile
    public function guide_profile_redirect() {
        $guide_profile = get_query_var('mst_guide_profile');
        $guides_list = get_query_var('mst_guides_list');
        
        if ($guide_profile) {
            $guide_id = get_query_var('guide_id');
            if ($guide_id) {
                // Load guide profile template
                include MST_LK_PLUGIN_DIR . 'templates/guide-profile-new.php';
                exit;
            }
        }
        
        if ($guides_list) {
            // Load guides list template
            include MST_LK_PLUGIN_DIR . 'templates/guides-list.php';
            exit;
        }
    }
    
    // Render guides list shortcode
    public function render_guides_list($atts = []) {
        $atts = shortcode_atts([
            'per_page' => 12,
            'orderby' => 'display_name',
            'order' => 'ASC'
        ], $atts);
        
        $guides = get_users([
            'meta_key' => 'mst_user_status',
            'meta_value' => 'guide',
            'orderby' => $atts['orderby'],
            'order' => $atts['order'],
            'number' => $atts['per_page']
        ]);
        
        if (empty($guides)) {
            return '<p style="text-align:center;padding:60px 20px;">Гиды не найдены</p>';
        }
        
        ob_start();
        ?>
        <div class="mst-guides-grid" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:30px;max-width:1200px;margin:0 auto;padding:40px 20px;">
            <?php foreach ($guides as $guide): 
                $guide_id = $guide->ID;
                $custom_avatar = get_user_meta($guide_id, 'mst_lk_avatar', true);
                $avatar_url = $custom_avatar ? wp_get_attachment_url($custom_avatar) : get_avatar_url($guide_id, ['size' => 200]);
                
                $rating = get_user_meta($guide_id, 'mst_guide_rating', true) ?: '5.0';
                $reviews_count = get_user_meta($guide_id, 'mst_guide_reviews_count', true) ?: '0';
                $languages = get_user_meta($guide_id, 'mst_guide_languages', true) ?: '';
                $specialization = get_user_meta($guide_id, 'mst_guide_specialization', true) ?: '';
                $city = get_user_meta($guide_id, 'mst_guide_city', true) ?: '';
                $experience_years = get_user_meta($guide_id, 'mst_guide_experience_years', true) ?: '0';
                
                $user_status = get_user_meta($guide_id, 'mst_user_status', true) ?: 'guide';
                $status_colors = [
                    'bronze' => '#CD7F32',
                    'silver' => '#C0C0C0', 
                    'gold' => '#FFD700',
                    'guide' => '#00c896'
                ];
                $border_color = $status_colors[$user_status] ?? '#00c896';
                $guide_url = home_url('/guide/' . $guide_id);
            ?>
            <a href="<?php echo esc_url($guide_url); ?>" style="text-decoration:none;color:inherit;">
                <div style="background:#fff;border-radius:20px;padding:25px;box-shadow:0 2px 15px rgba(0,0,0,0.08);transition:transform 0.3s,box-shadow 0.3s;cursor:pointer;" onmouseover="this.style.transform='translateY(-5px)';this.style.boxShadow='0 8px 25px rgba(0,0,0,0.12)';" onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 2px 15px rgba(0,0,0,0.08)';">
                    <div style="text-align:center;margin-bottom:20px;">
                        <div style="width:120px;height:120px;margin:0 auto 15px;border-radius:50%;padding:4px;background:<?php echo $border_color; ?>;box-shadow:0 6px 20px rgba(0,0,0,0.15);">
                            <img src="<?php echo esc_url($avatar_url); ?>" alt="<?php echo esc_attr($guide->display_name); ?>" style="width:112px;height:112px;border-radius:50%;object-fit:cover;border:4px solid #fff;">
                        </div>
                        <h3 style="font-size:22px;font-weight:700;margin:0 0 8px;color:#333;"><?php echo esc_html($guide->display_name); ?></h3>
                        <?php if ($city): ?>
                            <div style="color:#666;font-size:14px;margin-bottom:12px;">📍 <?php echo esc_html($city); ?></div>
                        <?php endif; ?>
                        <div style="display:flex;align-items:center;justify-content:center;gap:5px;font-size:18px;margin-bottom:15px;">
                            <span style="color:#ffa500;">⭐</span>
                            <span style="font-weight:700;"><?php echo esc_html($rating); ?></span>
                            <span style="color:#999;font-size:14px;">(<?php echo esc_html($reviews_count); ?>)</span>
                        </div>
                    </div>
                    
                    <?php if ($languages): ?>
                    <div style="margin-bottom:15px;">
                        <div style="font-size:12px;color:#999;margin-bottom:8px;font-weight:600;">ЯЗЫКИ</div>
                        <div style="display:flex;flex-wrap:wrap;gap:6px;justify-content:center;">
                            <?php foreach (explode(',', $languages) as $lang): ?>
                                <span style="padding:6px 12px;border-radius:15px;font-size:12px;font-weight:600;background:#FFF4E6;color:#F59E0B;"><?php echo esc_html(trim($lang)); ?></span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if ($specialization): ?>
                    <div style="margin-bottom:15px;">
                        <div style="font-size:12px;color:#999;margin-bottom:8px;font-weight:600;">СПЕЦИАЛИЗАЦИЯ</div>
                        <div style="display:flex;flex-wrap:wrap;gap:6px;justify-content:center;">
                            <?php 
                            $specs = explode(',', $specialization);
                            $display_specs = array_slice($specs, 0, 2);
                            foreach ($display_specs as $spec): ?>
                                <span style="padding:6px 12px;border-radius:15px;font-size:12px;font-weight:600;background:#E0F2FE;color:#0EA5E9;"><?php echo esc_html(trim($spec)); ?></span>
                            <?php endforeach; ?>
                            <?php if (count($specs) > 2): ?>
                                <span style="padding:6px 12px;border-radius:15px;font-size:12px;font-weight:600;background:#F3F4F6;color:#6B7280;">+<?php echo count($specs) - 2; ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <div style="text-align:center;padding-top:15px;border-top:1px solid #f0f0f0;">
                        <span style="color:#00c896;font-weight:600;font-size:14px;">Опыт: <?php echo esc_html($experience_years); ?> лет</span>
                    </div>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
        <?php
        return ob_get_clean();
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