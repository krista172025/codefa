<?php
namespace MST_Elementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if (!defined('ABSPATH')) exit;

/**
 * Single Product Toast - "Советуем к прочтению"
 * Выводит уведомление справа сверху со ссылкой на статью
 * 
 * ИНТЕГРАЦИЯ: Используется нативное поле WooCommerce вместо сторонних мета-полей
 * Поле добавляется на вкладке "Общие" или отдельной вкладке в редакторе товара
 */
class Single_Product_Toast extends Widget_Base {

    public function get_name() {
        return 'mst-single-product-toast';
    }

    public function get_title() {
        return __('Reading Recommendation Toast', 'my-super-tour-elementor');
    }

    public function get_icon() {
        return 'eicon-alert';
    }

    public function get_categories() {
        return ['my-super-tour'];
    }

    public function get_keywords() {
        return ['toast', 'notification', 'reading', 'article', 'recommend'];
    }

    protected function register_controls() {
        // =============================================
        // CONTENT SECTION
        // =============================================
        $this->start_controls_section(
            'section_content',
            [
                'label' => __('Настройки', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'source',
            [
                'label' => __('Источник ссылки', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SELECT,
                'default' => 'woo_native',
                'options' => [
                    'woo_native' => __('WooCommerce (нативное поле)', 'my-super-tour-elementor'),
                    'manual' => __('Ручной URL', 'my-super-tour-elementor'),
                ],
                'description' => __('Рекомендуется: используйте поле "Рекомендуемая статья" в редакторе товара WooCommerce', 'my-super-tour-elementor'),
            ]
        );

        $this->add_control(
            'woo_info',
            [
                'type' => Controls_Manager::RAW_HTML,
                'raw' => '<div style="padding: 12px; background: linear-gradient(135deg, rgba(153, 82, 224, 0.1), rgba(123, 63, 196, 0.05)); border-radius: 8px; font-size: 12px; color: #6b7280;">
                    <strong style="color: #9952E0;">💡 Как использовать:</strong><br>
                    1. Откройте товар в WooCommerce<br>
                    2. Найдите вкладку "Статья" (или поле на вкладке "Общие")<br>
                    3. Вставьте URL статьи или выберите из списка записей
                </div>',
                'condition' => ['source' => 'woo_native'],
            ]
        );

        $this->add_control(
            'manual_url',
            [
                'label' => __('URL статьи', 'my-super-tour-elementor'),
                'type' => Controls_Manager::URL,
                'placeholder' => 'https://example.com/article',
                'condition' => ['source' => 'manual'],
            ]
        );

        $this->add_control(
            'manual_title',
            [
                'label' => __('Заголовок статьи', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Полезная статья',
                'condition' => ['source' => 'manual'],
            ]
        );

        $this->add_control(
            'toast_label',
            [
                'label' => __('Метка тоста', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Советуем к прочтению перед визитом', 'my-super-tour-elementor'),
            ]
        );

        $this->add_control(
            'toast_icon',
            [
                'label' => __('Иконка', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SELECT,
                'default' => 'book',
                'options' => [
                    'book' => __('📖 Книга', 'my-super-tour-elementor'),
                    'bulb' => __('💡 Лампочка', 'my-super-tour-elementor'),
                    'star' => __('⭐ Звезда', 'my-super-tour-elementor'),
                    'info' => __('ℹ️ Инфо', 'my-super-tour-elementor'),
                    'pin' => __('📌 Пин', 'my-super-tour-elementor'),
                    'fire' => __('🔥 Огонь', 'my-super-tour-elementor'),
                    'sparkles' => __('✨ Блестки', 'my-super-tour-elementor'),
                ],
            ]
        );

        $this->add_control(
            'show_delay',
            [
                'label' => __('Задержка появления (сек)', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => ['px' => ['min' => 0, 'max' => 10, 'step' => 0.5]],
                'default' => ['size' => 2],
            ]
        );

        $this->add_control(
            'auto_hide',
            [
                'label' => __('Авто-скрытие', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
            ]
        );

        $this->add_control(
            'hide_delay',
            [
                'label' => __('Скрыть через (сек)', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => ['px' => ['min' => 3, 'max' => 30]],
                'default' => ['size' => 10],
                'condition' => ['auto_hide' => 'yes'],
            ]
        );

        $this->add_control(
            'show_excerpt',
            [
                'label' => __('Показать описание статьи', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
            ]
        );

        $this->end_controls_section();

        // =============================================
        // STYLE SECTION
        // =============================================
        $this->start_controls_section(
            'section_style',
            [
                'label' => __('Стили', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'position',
            [
                'label' => __('Позиция', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SELECT,
                'default' => 'top-right',
                'options' => [
                    'top-right' => __('Справа сверху', 'my-super-tour-elementor'),
                    'top-left' => __('Слева сверху', 'my-super-tour-elementor'),
                    'bottom-right' => __('Справа снизу', 'my-super-tour-elementor'),
                    'bottom-left' => __('Слева снизу', 'my-super-tour-elementor'),
                ],
            ]
        );

        $this->add_control(
            'primary_color',
            [
                'label' => __('Основной цвет', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#9952E0',
            ]
        );

        $this->add_control(
            'bg_color',
            [
                'label' => __('Цвет фона', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
            ]
        );

        $this->add_control(
            'text_color',
            [
                'label' => __('Цвет текста', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#1a1a2e',
            ]
        );

        $this->add_control(
            'glass_effect',
            [
                'label' => __('Эффект стекла (Liquid Glass)', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'enable_shimmer',
            [
                'label' => __('Переливающийся эффект', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Get article data from WooCommerce native field
     */
    private function get_article_from_woo() {
        if (!is_singular('product') && !(function_exists('wc_get_product') && get_post_type() === 'product')) {
            return null;
        }
        
        $product_id = get_the_ID();
        
        // Try to get linked article ID first
        $article_id = get_post_meta($product_id, '_mst_linked_article_id', true);
        
        if ($article_id) {
            $article = get_post(intval($article_id));
            if ($article && $article->post_status === 'publish') {
                return [
                    'url' => get_permalink($article->ID),
                    'title' => $article->post_title,
                    'excerpt' => has_excerpt($article->ID) ? get_the_excerpt($article->ID) : wp_trim_words($article->post_content, 20),
                ];
            }
        }
        
        // Fallback to URL field
        $article_url = get_post_meta($product_id, '_mst_recommended_article_url', true);
        if ($article_url) {
            // Try to get post from URL
            $post_id = url_to_postid($article_url);
            if ($post_id) {
                $article = get_post($post_id);
                if ($article) {
                    return [
                        'url' => $article_url,
                        'title' => $article->post_title,
                        'excerpt' => has_excerpt($post_id) ? get_the_excerpt($post_id) : wp_trim_words($article->post_content, 20),
                    ];
                }
            }
            
            // Use URL with custom title
            $article_title = get_post_meta($product_id, '_mst_recommended_article_title', true);
            return [
                'url' => $article_url,
                'title' => $article_title ?: __('Читать статью', 'my-super-tour-elementor'),
                'excerpt' => '',
            ];
        }
        
        return null;
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        
        $article_url = '';
        $article_title = '';
        $article_excerpt = '';
        
        if ($settings['source'] === 'woo_native') {
            $article_data = $this->get_article_from_woo();
            if ($article_data) {
                $article_url = $article_data['url'];
                $article_title = $article_data['title'];
                $article_excerpt = $article_data['excerpt'];
            }
        } else {
            $article_url = $settings['manual_url']['url'] ?? '';
            $article_title = $settings['manual_title'] ?? '';
        }
        
        // Don't render if no URL
        if (empty($article_url)) {
            if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
                echo '<div style="padding: 24px; background: linear-gradient(135deg, rgba(153, 82, 224, 0.1), rgba(123, 63, 196, 0.05)); border-radius: 16px; text-align: center;">';
                echo '<p style="margin: 0 0 8px; color: #1a1a2e; font-weight: 600;">🔔 Тост уведомление</p>';
                echo '<p style="margin: 0; color: #6b7280; font-size: 14px;">Укажите ссылку на статью в редакторе товара WooCommerce или выберите ручной режим</p>';
                echo '</div>';
            }
            return;
        }
        
        if (empty($article_title)) {
            $article_title = 'Читать статью';
        }
        
        $icons = [
            'book' => '📖',
            'bulb' => '💡',
            'star' => '⭐',
            'info' => 'ℹ️',
            'pin' => '📌',
            'fire' => '🔥',
            'sparkles' => '✨',
        ];
        $icon = $icons[$settings['toast_icon']] ?? '📖';
        
        $primary_color = $settings['primary_color'] ?? '#9952E0';
        $bg_color = $settings['bg_color'] ?? '#ffffff';
        $text_color = $settings['text_color'] ?? '#1a1a2e';
        $position = $settings['position'] ?? 'top-right';
        $show_delay = ($settings['show_delay']['size'] ?? 2) * 1000;
        $auto_hide = $settings['auto_hide'] === 'yes';
        $hide_delay = ($settings['hide_delay']['size'] ?? 10) * 1000;
        $glass = $settings['glass_effect'] === 'yes';
        $shimmer = $settings['enable_shimmer'] === 'yes';
        $show_excerpt = $settings['show_excerpt'] === 'yes' && !empty($article_excerpt);
        
        $unique_id = 'mst-toast-' . $this->get_id();
        
        // Position styles
        $pos_styles = [
            'top-right' => 'top: 100px; right: 20px;',
            'top-left' => 'top: 100px; left: 20px;',
            'bottom-right' => 'bottom: 20px; right: 20px;',
            'bottom-left' => 'bottom: 20px; left: 20px;',
        ];
        $pos_style = $pos_styles[$position] ?? $pos_styles['top-right'];
        
        $slide_from = strpos($position, 'right') !== false ? 'translateX(120%)' : 'translateX(-120%)';
        ?>
        
        <div class="mst-reading-toast" id="<?php echo esc_attr($unique_id); ?>" role="alert" aria-live="polite">
            <div class="mst-toast-content<?php echo $shimmer ? ' mst-toast-shimmer' : ''; ?>">
                <button class="mst-toast-close" aria-label="<?php esc_attr_e('Закрыть', 'my-super-tour-elementor'); ?>">&times;</button>
                
                <div class="mst-toast-icon"><?php echo $icon; ?></div>
                
                <div class="mst-toast-body">
                    <span class="mst-toast-label"><?php echo esc_html($settings['toast_label']); ?></span>
                    <a href="<?php echo esc_url($article_url); ?>" class="mst-toast-link" target="_blank" rel="noopener">
                        <?php echo esc_html($article_title); ?>
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                            <polyline points="12 5 19 12 12 19"></polyline>
                        </svg>
                    </a>
                    <?php if ($show_excerpt): ?>
                    <p class="mst-toast-excerpt"><?php echo esc_html($article_excerpt); ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <style>
        @keyframes mst-toast-shimmer {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }
        
        #<?php echo esc_attr($unique_id); ?> {
            position: fixed;
            <?php echo $pos_style; ?>
            z-index: 99999;
            max-width: 380px;
            width: calc(100vw - 40px);
            opacity: 0;
            visibility: hidden;
            transform: <?php echo $slide_from; ?>;
            transition: all 0.5s cubic-bezier(0.16, 1, 0.3, 1);
            pointer-events: none;
        }
        
        #<?php echo esc_attr($unique_id); ?>.show {
            opacity: 1;
            visibility: visible;
            transform: translateX(0);
            pointer-events: auto;
        }
        
        #<?php echo esc_attr($unique_id); ?> .mst-toast-content {
            display: flex;
            align-items: flex-start;
            gap: 14px;
            padding: 18px 22px;
            background: <?php echo esc_attr($bg_color); ?>;
            border-radius: 20px;
            box-shadow: 0 16px 48px -12px rgba(0, 0, 0, 0.18), 0 6px 20px -6px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
            <?php if ($glass): ?>
            background: linear-gradient(135deg, rgba(255,255,255,0.95), rgba(255,255,255,0.85));
            backdrop-filter: blur(20px) saturate(180%);
            -webkit-backdrop-filter: blur(20px) saturate(180%);
            border: 1px solid rgba(255,255,255,0.6);
            <?php endif; ?>
        }
        
        <?php if ($shimmer): ?>
        #<?php echo esc_attr($unique_id); ?> .mst-toast-content.mst-toast-shimmer::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(
                90deg,
                transparent,
                rgba(255, 255, 255, 0.4),
                rgba(153, 82, 224, 0.1),
                rgba(251, 214, 3, 0.1),
                rgba(255, 255, 255, 0.4),
                transparent
            );
            background-size: 200% 100%;
            animation: mst-toast-shimmer 4s ease-in-out infinite;
            pointer-events: none;
            border-radius: 20px;
        }
        <?php endif; ?>
        
        #<?php echo esc_attr($unique_id); ?> .mst-toast-content::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 5px;
            height: 100%;
            background: linear-gradient(180deg, <?php echo esc_attr($primary_color); ?>, #7B3FC4, #fbd603);
            border-radius: 20px 0 0 20px;
        }
        
        #<?php echo esc_attr($unique_id); ?> .mst-toast-close {
            position: absolute;
            top: 10px;
            right: 10px;
            width: 30px;
            height: 30px;
            background: rgba(0,0,0,0.04);
            border: none;
            border-radius: 50%;
            cursor: pointer;
            font-size: 18px;
            color: #9ca3af;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.2s, color 0.2s, transform 0.2s;
            z-index: 1;
        }
        
        #<?php echo esc_attr($unique_id); ?> .mst-toast-close:hover {
            background: rgba(0,0,0,0.08);
            color: #6b7280;
            transform: rotate(90deg);
        }
        
        #<?php echo esc_attr($unique_id); ?> .mst-toast-icon {
            font-size: 32px;
            flex-shrink: 0;
            line-height: 1;
        }
        
        #<?php echo esc_attr($unique_id); ?> .mst-toast-body {
            display: flex;
            flex-direction: column;
            gap: 6px;
            padding-right: 28px;
            flex: 1;
        }
        
        #<?php echo esc_attr($unique_id); ?> .mst-toast-label {
            font-size: 0.8125rem;
            color: #6b7280;
            font-weight: 500;
            line-height: 1.4;
        }
        
        #<?php echo esc_attr($unique_id); ?> .mst-toast-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 1.0625rem;
            font-weight: 700;
            color: <?php echo esc_attr($primary_color); ?>;
            text-decoration: none;
            transition: color 0.2s, gap 0.2s;
            line-height: 1.3;
        }
        
        #<?php echo esc_attr($unique_id); ?> .mst-toast-link:hover {
            color: #7B3FC4;
            gap: 12px;
        }
        
        #<?php echo esc_attr($unique_id); ?> .mst-toast-link svg {
            transition: transform 0.2s;
            flex-shrink: 0;
        }
        
        #<?php echo esc_attr($unique_id); ?> .mst-toast-link:hover svg {
            transform: translateX(4px);
        }
        
        #<?php echo esc_attr($unique_id); ?> .mst-toast-excerpt {
            font-size: 0.8125rem;
            color: #6b7280;
            line-height: 1.5;
            margin: 4px 0 0;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        @media (max-width: 480px) {
            #<?php echo esc_attr($unique_id); ?> {
                <?php if (strpos($position, 'top') !== false): ?>
                top: 80px;
                <?php else: ?>
                bottom: 80px;
                <?php endif; ?>
                right: 10px;
                left: 10px;
                max-width: none;
                width: auto;
            }
            
            #<?php echo esc_attr($unique_id); ?> .mst-toast-content {
                padding: 16px 18px;
            }
            
            #<?php echo esc_attr($unique_id); ?> .mst-toast-icon {
                font-size: 28px;
            }
            
            #<?php echo esc_attr($unique_id); ?> .mst-toast-link {
                font-size: 1rem;
            }
        }
        </style>
        
        <script>
        (function() {
            var toast = document.getElementById('<?php echo esc_js($unique_id); ?>');
            if (!toast) return;
            
            var closeBtn = toast.querySelector('.mst-toast-close');
            var shown = false;
            var sessionKey = 'mst_toast_dismissed_<?php echo esc_js($this->get_id()); ?>';
            
            // Check if already dismissed this session
            if (sessionStorage.getItem(sessionKey)) {
                return;
            }
            
            // Show toast after delay
            setTimeout(function() {
                toast.classList.add('show');
                shown = true;
                
                <?php if ($auto_hide): ?>
                // Auto-hide
                setTimeout(function() {
                    hideToast();
                }, <?php echo intval($hide_delay); ?>);
                <?php endif; ?>
            }, <?php echo intval($show_delay); ?>);
            
            function hideToast() {
                toast.classList.remove('show');
                sessionStorage.setItem(sessionKey, '1');
            }
            
            closeBtn.addEventListener('click', hideToast);
        })();
        </script>
        
        <?php
    }
}

// =============================================
// WOOCOMMERCE INTEGRATION - ADD NATIVE FIELD
// =============================================

/**
 * Добавляем вкладку "Статья" в редактор товара WooCommerce
 */
add_filter('woocommerce_product_data_tabs', function($tabs) {
    $tabs['mst_article'] = [
        'label'    => __('📝 Статья', 'my-super-tour-elementor'),
        'target'   => 'mst_article_product_data',
        'class'    => [],
        'priority' => 80,
    ];
    return $tabs;
});

/**
 * Контент вкладки "Статья"
 */
add_action('woocommerce_product_data_panels', function() {
    global $post;
    ?>
    <div id="mst_article_product_data" class="panel woocommerce_options_panel">
        <div class="options_group">
            <p class="form-field" style="padding: 12px 12px 0; margin-bottom: 0;">
                <span style="display: block; padding: 16px; background: linear-gradient(135deg, rgba(153, 82, 224, 0.08), rgba(123, 63, 196, 0.04)); border-radius: 12px; font-size: 13px; color: #4b5563; line-height: 1.6;">
                    <strong style="color: #9952E0; display: block; margin-bottom: 8px;">💡 Рекомендуемая статья</strong>
                    Привяжите статью к этому товару. Она будет отображаться:<br>
                    • В виджете <strong>Reading Recommendation Toast</strong> (уведомление)<br>
                    • В виджете <strong>Blog Article</strong> (полная статья на странице товара)
                </span>
            </p>
            
            <?php
            // Поле выбора статьи (ID поста)
            $linked_article_id = get_post_meta($post->ID, '_mst_linked_article_id', true);
            
            // Получаем все опубликованные записи
            $posts = get_posts([
                'post_type' => 'post',
                'posts_per_page' => -1,
                'post_status' => 'publish',
                'orderby' => 'title',
                'order' => 'ASC',
            ]);
            
            $options = ['' => __('— Выберите статью —', 'my-super-tour-elementor')];
            foreach ($posts as $p) {
                $options[$p->ID] = $p->post_title . ' (ID: ' . $p->ID . ')';
            }
            
            woocommerce_wp_select([
                'id'          => '_mst_linked_article_id',
                'label'       => __('Привязанная статья', 'my-super-tour-elementor'),
                'description' => __('Выберите статью из списка записей (posts)', 'my-super-tour-elementor'),
                'desc_tip'    => true,
                'options'     => $options,
                'value'       => $linked_article_id,
            ]);
            
            // Альтернатива: ручной URL
            woocommerce_wp_text_input([
                'id'          => '_mst_recommended_article_url',
                'label'       => __('Или укажите URL статьи', 'my-super-tour-elementor'),
                'placeholder' => 'https://example.com/article',
                'description' => __('Если статья находится на внешнем сайте или вы хотите указать URL вручную', 'my-super-tour-elementor'),
                'desc_tip'    => true,
            ]);
            
            woocommerce_wp_text_input([
                'id'          => '_mst_recommended_article_title',
                'label'       => __('Заголовок статьи (для URL)', 'my-super-tour-elementor'),
                'placeholder' => __('Название статьи', 'my-super-tour-elementor'),
                'description' => __('Если указан URL вручную, укажите заголовок', 'my-super-tour-elementor'),
                'desc_tip'    => true,
            ]);
            ?>
        </div>
    </div>
    
    <style>
    #mst_article_product_data {
        padding: 12px;
    }
    
    #mst_article_product_data .options_group {
        border: none;
    }
    
    #woocommerce-product-data ul.wc-tabs li.mst_article_options a::before {
        content: "\f497";
        font-family: dashicons;
    }
    </style>
    <?php
});

/**
 * Сохранение полей
 */
add_action('woocommerce_process_product_meta', function($post_id) {
    if (isset($_POST['_mst_linked_article_id'])) {
        update_post_meta($post_id, '_mst_linked_article_id', sanitize_text_field($_POST['_mst_linked_article_id']));
    }
    
    if (isset($_POST['_mst_recommended_article_url'])) {
        update_post_meta($post_id, '_mst_recommended_article_url', esc_url_raw($_POST['_mst_recommended_article_url']));
    }
    
    if (isset($_POST['_mst_recommended_article_title'])) {
        update_post_meta($post_id, '_mst_recommended_article_title', sanitize_text_field($_POST['_mst_recommended_article_title']));
    }
});
