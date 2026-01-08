<?php
namespace MST_Elementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;

if (!defined('ABSPATH')) exit;

/**
 * Single Product Blog Widget - v2.0
 * ПОЛНОЦЕННАЯ СТАТЬЯ ОБ ЭКСКУРСИИ
 * Автоматическая генерация SEO-контента из данных товара
 * Features: Auto-generated article, photo gallery, FAQ, liquid glass design
 */
class Single_Product_Blog extends Widget_Base {

    public function get_name() {
        return 'mst-single-product-blog';
    }

    public function get_title() {
        return __('Single Product Blog', 'my-super-tour-elementor');
    }

    public function get_icon() {
        return 'eicon-post-content';
    }

    public function get_categories() {
        return ['my-super-tour'];
    }

    public function get_keywords() {
        return ['blog', 'article', 'content', 'seo', 'product', 'tour', 'single'];
    }

    protected function register_controls() {
        // =============================================
        // CONTENT SOURCE SECTION
        // =============================================
        $this->start_controls_section(
            'section_source',
            [
                'label' => __('Content Source', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'content_source',
            [
                'label' => __('Content Source', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SELECT,
                'default' => 'auto',
                'options' => [
                    'auto' => __('Auto (Current Product)', 'my-super-tour-elementor'),
                    'manual' => __('Manual Content', 'my-super-tour-elementor'),
                ],
            ]
        );

        $this->add_control(
            'product_id',
            [
                'label' => __('Product ID', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'description' => __('Leave empty for current product', 'my-super-tour-elementor'),
                'condition' => ['content_source' => 'auto'],
            ]
        );

        $this->end_controls_section();

        // =============================================
        // AUTO CONTENT SETTINGS
        // =============================================
        $this->start_controls_section(
            'section_auto_content',
            [
                'label' => __('Auto Content Settings', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
                'condition' => ['content_source' => 'auto'],
            ]
        );

        $this->add_control(
            'show_intro',
            [
                'label' => __('Show Introduction', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_what_included',
            [
                'label' => __('Show What is Included', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_highlights',
            [
                'label' => __('Show Tour Highlights', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_program',
            [
                'label' => __('Show Tour Program', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_important_info',
            [
                'label' => __('Show Important Info', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_meeting_point',
            [
                'label' => __('Show Meeting Point', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_guide_info',
            [
                'label' => __('Show Guide Info', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->end_controls_section();

        // =============================================
        // PHOTO GALLERY SECTION
        // =============================================
        $this->start_controls_section(
            'section_gallery',
            [
                'label' => __('Photo Gallery', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'show_gallery',
            [
                'label' => __('Show Gallery', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'gallery_source',
            [
                'label' => __('Gallery Source', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SELECT,
                'default' => 'product',
                'options' => [
                    'product' => __('Product Gallery', 'my-super-tour-elementor'),
                    'manual' => __('Manual Selection', 'my-super-tour-elementor'),
                ],
                'condition' => ['show_gallery' => 'yes'],
            ]
        );

        $this->add_control(
            'gallery_images',
            [
                'label' => __('Gallery Images', 'my-super-tour-elementor'),
                'type' => Controls_Manager::GALLERY,
                'condition' => [
                    'show_gallery' => 'yes',
                    'gallery_source' => 'manual',
                ],
            ]
        );

        $this->add_control(
            'gallery_layout',
            [
                'label' => __('Gallery Layout', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SELECT,
                'default' => 'masonry',
                'options' => [
                    'grid' => __('Grid', 'my-super-tour-elementor'),
                    'masonry' => __('Masonry', 'my-super-tour-elementor'),
                ],
                'condition' => ['show_gallery' => 'yes'],
            ]
        );

        $this->add_control(
            'gallery_columns',
            [
                'label' => __('Columns', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SELECT,
                'default' => '3',
                'options' => [
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                ],
                'condition' => ['show_gallery' => 'yes'],
            ]
        );

        $this->add_control(
            'gallery_title',
            [
                'label' => __('Gallery Title', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Фотографии экскурсии', 'my-super-tour-elementor'),
                'condition' => ['show_gallery' => 'yes'],
            ]
        );

        $this->end_controls_section();

        // =============================================
        // FAQ SECTION
        // =============================================
        $this->start_controls_section(
            'section_faq',
            [
                'label' => __('FAQ Section', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'show_faq',
            [
                'label' => __('Show FAQ', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'faq_source',
            [
                'label' => __('FAQ Source', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SELECT,
                'default' => 'auto',
                'options' => [
                    'auto' => __('Auto Generate', 'my-super-tour-elementor'),
                    'manual' => __('Manual', 'my-super-tour-elementor'),
                ],
                'condition' => ['show_faq' => 'yes'],
            ]
        );

        $this->add_control(
            'faq_title',
            [
                'label' => __('FAQ Title', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Часто задаваемые вопросы', 'my-super-tour-elementor'),
                'condition' => ['show_faq' => 'yes'],
            ]
        );

        $faq_repeater = new Repeater();

        $faq_repeater->add_control(
            'question',
            [
                'label' => __('Question', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Вопрос?', 'my-super-tour-elementor'),
            ]
        );

        $faq_repeater->add_control(
            'answer',
            [
                'label' => __('Answer', 'my-super-tour-elementor'),
                'type' => Controls_Manager::WYSIWYG,
                'default' => __('Ответ на вопрос...', 'my-super-tour-elementor'),
            ]
        );

        $this->add_control(
            'faq_items',
            [
                'label' => __('FAQ Items', 'my-super-tour-elementor'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $faq_repeater->get_controls(),
                'default' => [],
                'title_field' => '{{{ question }}}',
                'condition' => [
                    'show_faq' => 'yes',
                    'faq_source' => 'manual',
                ],
            ]
        );

        $this->end_controls_section();

        // =============================================
        // SCHEMA SECTION
        // =============================================
        $this->start_controls_section(
            'section_schema',
            [
                'label' => __('SEO Schema', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'enable_schema',
            [
                'label' => __('Enable JSON-LD Schema', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'schema_type',
            [
                'label' => __('Schema Type', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SELECT,
                'default' => 'TouristTrip',
                'options' => [
                    'TouristTrip' => 'TouristTrip',
                    'Product' => 'Product',
                    'Event' => 'Event',
                    'Article' => 'Article',
                ],
                'condition' => ['enable_schema' => 'yes'],
            ]
        );

        $this->end_controls_section();

        // =============================================
        // STYLE - GENERAL
        // =============================================
        $this->start_controls_section(
            'style_general',
            [
                'label' => __('General Style', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'primary_color',
            [
                'label' => __('Primary Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#9952E0',
            ]
        );

        $this->add_control(
            'secondary_color',
            [
                'label' => __('Secondary Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#fbd603',
            ]
        );

        $this->add_control(
            'glass_blur',
            [
                'label' => __('Glass Blur (px)', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => ['min' => 0, 'max' => 40],
                ],
                'default' => ['size' => 16],
            ]
        );

        $this->add_control(
            'section_gap',
            [
                'label' => __('Section Gap', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => ['min' => 20, 'max' => 100],
                ],
                'default' => ['size' => 48],
            ]
        );

        $this->add_control(
            'heading_color',
            [
                'label' => __('Heading Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#1a1a2e',
            ]
        );

        $this->add_control(
            'text_color',
            [
                'label' => __('Text Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#4b5563',
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Get product data for auto-generation
     */
    private function get_product_data($product_id) {
        if (!$product_id || !function_exists('wc_get_product')) {
            return null;
        }
        
        $product = wc_get_product($product_id);
        if (!$product) {
            return null;
        }
        
        $data = [
            'id' => $product_id,
            'name' => $product->get_name(),
            'description' => $product->get_description(),
            'short_description' => $product->get_short_description(),
            'price' => $product->get_price(),
            'price_html' => $product->get_price_html(),
            'image_id' => $product->get_image_id(),
            'gallery_ids' => $product->get_gallery_image_ids(),
            'categories' => [],
            'attributes' => [],
        ];
        
        // Get categories
        $terms = get_the_terms($product_id, 'product_cat');
        if ($terms && !is_wp_error($terms)) {
            foreach ($terms as $term) {
                $data['categories'][] = $term->name;
            }
        }
        
        // Get attributes
        $attrs = $product->get_attributes();
        foreach ($attrs as $key => $attr) {
            $attr_name = wc_attribute_label($key);
            if ($attr->is_taxonomy()) {
                $values = wc_get_product_terms($product_id, $key, ['fields' => 'names']);
                $data['attributes'][$attr_name] = implode(', ', $values);
            } else {
                $data['attributes'][$attr_name] = $attr->get_options();
            }
        }
        
        // Get custom meta
        $data['duration'] = get_post_meta($product_id, '_mst_duration', true) ?: get_post_meta($product_id, 'duration', true) ?: '';
        $data['group_size'] = get_post_meta($product_id, '_mst_group_size', true) ?: '';
        $data['meeting_point'] = get_post_meta($product_id, '_mst_meeting_point', true) ?: '';
        $data['included'] = get_post_meta($product_id, '_mst_included', true) ?: '';
        $data['not_included'] = get_post_meta($product_id, '_mst_not_included', true) ?: '';
        $data['important_info'] = get_post_meta($product_id, '_mst_important_info', true) ?: '';
        $data['program'] = get_post_meta($product_id, '_mst_program', true) ?: '';
        $data['highlights'] = get_post_meta($product_id, '_mst_highlights', true) ?: '';
        
        // Get guide info
        $guide_id = get_post_meta($product_id, '_mst_guide_id', true);
        if ($guide_id) {
            $guide = get_userdata($guide_id);
            if ($guide) {
                $data['guide'] = [
                    'id' => $guide_id,
                    'name' => $guide->display_name,
                    'bio' => get_user_meta($guide_id, 'mst_guide_bio', true) ?: get_user_meta($guide_id, 'mst_guide_experience', true),
                    'avatar' => get_user_meta($guide_id, 'mst_lk_avatar', true),
                    'rating' => get_user_meta($guide_id, 'mst_guide_rating', true) ?: '5.0',
                    'reviews_count' => get_user_meta($guide_id, 'mst_guide_reviews_count', true) ?: '0',
                ];
            }
        }
        
        // City from attribute or category
        $data['city'] = '';
        if (!empty($data['attributes']['Город'])) {
            $data['city'] = $data['attributes']['Город'];
        } elseif (!empty($data['attributes']['City'])) {
            $data['city'] = $data['attributes']['City'];
        } elseif ($product->get_attribute('pa_city')) {
            $data['city'] = $product->get_attribute('pa_city');
        }
        
        return $data;
    }

    /**
     * Generate auto FAQ based on product data
     */
    private function generate_auto_faq($data) {
        $faq = [];
        
        $tour_name = $data['name'] ?? 'эту экскурсию';
        $city = $data['city'] ?? 'этого города';
        
        $faq[] = [
            'question' => 'Как забронировать ' . mb_strtolower($tour_name) . '?',
            'answer' => 'Выберите удобную дату и время в календаре, укажите количество участников и нажмите кнопку бронирования. Вы можете уточнить детали у гида до оплаты.',
        ];
        
        if (!empty($data['duration'])) {
            $faq[] = [
                'question' => 'Сколько длится экскурсия?',
                'answer' => 'Продолжительность экскурсии составляет ' . $data['duration'] . '. Время может немного варьироваться в зависимости от маршрута и количества участников.',
            ];
        }
        
        $faq[] = [
            'question' => 'Можно ли отменить бронирование?',
            'answer' => 'Да, бесплатная отмена доступна за 24 часа до начала экскурсии. Для отмены свяжитесь с нами или гидом через личный кабинет.',
        ];
        
        $faq[] = [
            'question' => 'Подходит ли экскурсия для детей?',
            'answer' => 'Экскурсия подходит для детей от 6 лет в сопровождении взрослых. Для малышей рекомендуем уточнить детали у гида заранее.',
        ];
        
        if (!empty($data['meeting_point'])) {
            $faq[] = [
                'question' => 'Где место встречи?',
                'answer' => 'Встреча проходит: ' . $data['meeting_point'] . '. Точный адрес и инструкции будут отправлены после подтверждения бронирования.',
            ];
        }
        
        $faq[] = [
            'question' => 'Что нужно взять с собой?',
            'answer' => 'Рекомендуем надеть удобную обувь для прогулок, взять воду и хорошее настроение. В зависимости от погоды может понадобиться зонт или солнцезащитные очки.',
        ];
        
        return $faq;
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        
        // Get product data
        $product_id = 0;
        $data = null;
        
        if ($settings['content_source'] === 'auto') {
            if (!empty($settings['product_id'])) {
                $product_id = intval($settings['product_id']);
            } elseif (function_exists('wc_get_product')) {
                global $product;
                if (is_a($product, 'WC_Product')) {
                    $product_id = $product->get_id();
                } elseif (is_singular('product')) {
                    $product_id = get_the_ID();
                }
            }
            
            if ($product_id) {
                $data = $this->get_product_data($product_id);
            }
        }
        
        $primary_color = $settings['primary_color'] ?? '#9952E0';
        $secondary_color = $settings['secondary_color'] ?? '#fbd603';
        $glass_blur = $settings['glass_blur']['size'] ?? 16;
        $section_gap = $settings['section_gap']['size'] ?? 48;
        $heading_color = $settings['heading_color'] ?? '#1a1a2e';
        $text_color = $settings['text_color'] ?? '#4b5563';
        
        $unique_id = 'mst-spb-' . $this->get_id();
        
        if (!$data) {
            echo '<div class="mst-spb-no-product" style="text-align: center; padding: 40px; background: rgba(255,255,255,0.8); border-radius: 16px;">';
            echo '<p style="font-size: 16px; color: #666;">Данные о туре не найдены. Убедитесь, что вы находитесь на странице товара или укажите Product ID.</p>';
            echo '</div>';
            return;
        }
        ?>
        
        <article class="mst-single-product-blog" id="<?php echo esc_attr($unique_id); ?>" itemscope itemtype="https://schema.org/Article">
            
            <!-- HERO INTRO SECTION -->
            <?php if ($settings['show_intro'] === 'yes'): ?>
            <header class="mst-spb-hero">
                <h1 class="mst-spb-title" itemprop="headline"><?php echo esc_html($data['name']); ?></h1>
                
                <?php if (!empty($data['city']) || !empty($data['duration'])): ?>
                <div class="mst-spb-meta">
                    <?php if (!empty($data['city'])): ?>
                    <span class="mst-spb-meta-item mst-spb-city">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                        <?php echo esc_html($data['city']); ?>
                    </span>
                    <?php endif; ?>
                    
                    <?php if (!empty($data['duration'])): ?>
                    <span class="mst-spb-meta-item mst-spb-duration">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        <?php echo esc_html($data['duration']); ?>
                    </span>
                    <?php endif; ?>
                    
                    <?php if (!empty($data['group_size'])): ?>
                    <span class="mst-spb-meta-item">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                        <?php echo esc_html($data['group_size']); ?>
                    </span>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
                
                <?php if (!empty($data['short_description'])): ?>
                <p class="mst-spb-intro-text" itemprop="description">
                    <?php echo wp_kses_post($data['short_description']); ?>
                </p>
                <?php endif; ?>
            </header>
            <?php endif; ?>
            
            <!-- PHOTO GALLERY -->
            <?php if ($settings['show_gallery'] === 'yes'): ?>
            <section class="mst-spb-section mst-spb-gallery-section" aria-label="Фотогалерея">
                <?php if (!empty($settings['gallery_title'])): ?>
                <h2 class="mst-spb-section-title"><?php echo esc_html($settings['gallery_title']); ?></h2>
                <?php endif; ?>
                
                <?php
                $gallery_images = [];
                
                if ($settings['gallery_source'] === 'product' && !empty($data['gallery_ids'])) {
                    $all_ids = $data['gallery_ids'];
                    if ($data['image_id']) {
                        array_unshift($all_ids, $data['image_id']);
                    }
                    foreach ($all_ids as $id) {
                        $url = wp_get_attachment_image_url($id, 'large');
                        $thumb = wp_get_attachment_image_url($id, 'medium');
                        $alt = get_post_meta($id, '_wp_attachment_image_alt', true);
                        if ($url) {
                            $gallery_images[] = ['url' => $url, 'thumb' => $thumb, 'alt' => $alt];
                        }
                    }
                } else {
                    foreach ($settings['gallery_images'] as $img) {
                        $gallery_images[] = [
                            'url' => $img['url'],
                            'thumb' => $img['url'],
                            'alt' => '',
                        ];
                    }
                }
                
                $columns = intval($settings['gallery_columns'] ?? 3);
                $layout = $settings['gallery_layout'] ?? 'masonry';
                ?>
                
                <?php if (!empty($gallery_images)): ?>
                <div class="mst-spb-gallery-grid mst-spb-gallery-<?php echo esc_attr($layout); ?>" style="--columns: <?php echo $columns; ?>;">
                    <?php foreach ($gallery_images as $index => $img): ?>
                    <figure class="mst-spb-gallery-item" data-index="<?php echo $index; ?>">
                        <img src="<?php echo esc_url($img['thumb']); ?>" 
                             data-full="<?php echo esc_url($img['url']); ?>"
                             alt="<?php echo esc_attr($img['alt'] ?: $data['name'] . ' - фото ' . ($index + 1)); ?>"
                             loading="lazy"
                             class="mst-spb-gallery-img">
                    </figure>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </section>
            <?php endif; ?>
            
            <!-- FULL DESCRIPTION -->
            <?php if (!empty($data['description'])): ?>
            <section class="mst-spb-section mst-spb-description" itemprop="articleBody">
                <h2 class="mst-spb-section-title">Об экскурсии</h2>
                <div class="mst-spb-content-text">
                    <?php echo wp_kses_post($data['description']); ?>
                </div>
            </section>
            <?php endif; ?>
            
            <!-- HIGHLIGHTS -->
            <?php if ($settings['show_highlights'] === 'yes' && !empty($data['highlights'])): ?>
            <section class="mst-spb-section mst-spb-highlights">
                <h2 class="mst-spb-section-title">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                    Что вас ждет
                </h2>
                <div class="mst-spb-highlights-content">
                    <?php echo wp_kses_post($data['highlights']); ?>
                </div>
            </section>
            <?php endif; ?>
            
            <!-- PROGRAM -->
            <?php if ($settings['show_program'] === 'yes' && !empty($data['program'])): ?>
            <section class="mst-spb-section mst-spb-program">
                <h2 class="mst-spb-section-title">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>
                    Программа экскурсии
                </h2>
                <div class="mst-spb-program-content">
                    <?php echo wp_kses_post($data['program']); ?>
                </div>
            </section>
            <?php endif; ?>
            
            <!-- WHAT IS INCLUDED -->
            <?php if ($settings['show_what_included'] === 'yes' && (!empty($data['included']) || !empty($data['not_included']))): ?>
            <section class="mst-spb-section mst-spb-included">
                <h2 class="mst-spb-section-title">Что включено</h2>
                <div class="mst-spb-included-grid">
                    <?php if (!empty($data['included'])): ?>
                    <div class="mst-spb-included-col mst-spb-included-yes">
                        <h3 class="mst-spb-included-heading">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                            Включено
                        </h3>
                        <div class="mst-spb-included-list"><?php echo wp_kses_post($data['included']); ?></div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($data['not_included'])): ?>
                    <div class="mst-spb-included-col mst-spb-included-no">
                        <h3 class="mst-spb-included-heading">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                            Не включено
                        </h3>
                        <div class="mst-spb-included-list"><?php echo wp_kses_post($data['not_included']); ?></div>
                    </div>
                    <?php endif; ?>
                </div>
            </section>
            <?php endif; ?>
            
            <!-- MEETING POINT -->
            <?php if ($settings['show_meeting_point'] === 'yes' && !empty($data['meeting_point'])): ?>
            <section class="mst-spb-section mst-spb-meeting">
                <h2 class="mst-spb-section-title">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                    Место встречи
                </h2>
                <div class="mst-spb-meeting-content">
                    <?php echo wp_kses_post($data['meeting_point']); ?>
                </div>
            </section>
            <?php endif; ?>
            
            <!-- IMPORTANT INFO -->
            <?php if ($settings['show_important_info'] === 'yes' && !empty($data['important_info'])): ?>
            <section class="mst-spb-section mst-spb-important">
                <h2 class="mst-spb-section-title">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                    Важная информация
                </h2>
                <div class="mst-spb-important-content">
                    <?php echo wp_kses_post($data['important_info']); ?>
                </div>
            </section>
            <?php endif; ?>
            
            <!-- GUIDE INFO -->
            <?php if ($settings['show_guide_info'] === 'yes' && !empty($data['guide'])): ?>
            <section class="mst-spb-section mst-spb-guide">
                <h2 class="mst-spb-section-title">Ваш гид</h2>
                <div class="mst-spb-guide-card">
                    <div class="mst-spb-guide-avatar">
                        <?php 
                        $avatar_url = '';
                        if (!empty($data['guide']['avatar'])) {
                            $avatar_url = wp_get_attachment_url($data['guide']['avatar']);
                        }
                        if (!$avatar_url) {
                            $avatar_url = get_avatar_url($data['guide']['id'], ['size' => 120]);
                        }
                        ?>
                        <img src="<?php echo esc_url($avatar_url); ?>" alt="<?php echo esc_attr($data['guide']['name']); ?>">
                    </div>
                    <div class="mst-spb-guide-info">
                        <h3 class="mst-spb-guide-name">
                            <?php echo esc_html($data['guide']['name']); ?>
                            <svg class="mst-spb-verified" width="18" height="18" viewBox="0 0 24 24" fill="#10b981" stroke="white" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                        </h3>
                        <div class="mst-spb-guide-rating">
                            <span class="mst-spb-guide-stars">★ <?php echo esc_html($data['guide']['rating']); ?></span>
                            <span class="mst-spb-guide-reviews"><?php echo esc_html($data['guide']['reviews_count']); ?> отзывов</span>
                        </div>
                        <?php if (!empty($data['guide']['bio'])): ?>
                        <p class="mst-spb-guide-bio"><?php echo esc_html($data['guide']['bio']); ?></p>
                        <?php endif; ?>
                        <a href="<?php echo esc_url(home_url('/guide-profile/?guide_id=' . $data['guide']['id'])); ?>" class="mst-spb-guide-link">
                            Посмотреть профиль
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                        </a>
                    </div>
                </div>
            </section>
            <?php endif; ?>
            
            <!-- FAQ -->
            <?php 
            $faq_items = [];
            if ($settings['show_faq'] === 'yes') {
                if ($settings['faq_source'] === 'auto') {
                    $faq_items = $this->generate_auto_faq($data);
                } else {
                    $faq_items = $settings['faq_items'] ?? [];
                }
            }
            
            if (!empty($faq_items)):
            ?>
            <section class="mst-spb-section mst-spb-faq" aria-label="FAQ" itemscope itemtype="https://schema.org/FAQPage">
                <h2 class="mst-spb-section-title"><?php echo esc_html($settings['faq_title']); ?></h2>
                
                <div class="mst-spb-faq-list">
                    <?php foreach ($faq_items as $index => $faq): ?>
                    <div class="mst-spb-faq-item" itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">
                        <button class="mst-spb-faq-question" aria-expanded="false" aria-controls="faq-<?php echo $index; ?>">
                            <span itemprop="name"><?php echo esc_html($faq['question']); ?></span>
                            <svg class="mst-spb-faq-arrow" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg>
                        </button>
                        <div class="mst-spb-faq-answer" id="faq-<?php echo $index; ?>" itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer">
                            <div itemprop="text"><?php echo wp_kses_post($faq['answer']); ?></div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </section>
            <?php endif; ?>
            
        </article>
        
        <?php if ($settings['enable_schema'] === 'yes' && $data): ?>
        <!-- JSON-LD Schema -->
        <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "<?php echo esc_js($settings['schema_type']); ?>",
            "name": "<?php echo esc_js($data['name']); ?>",
            "description": "<?php echo esc_js(wp_strip_all_tags($data['short_description'] ?: $data['description'])); ?>",
            "url": "<?php echo esc_url(get_permalink($product_id)); ?>",
            <?php if ($settings['schema_type'] === 'Product'): ?>
            "offers": {
                "@type": "Offer",
                "price": "<?php echo esc_js($data['price']); ?>",
                "priceCurrency": "<?php echo esc_js(function_exists('get_woocommerce_currency') ? get_woocommerce_currency() : 'RUB'); ?>",
                "availability": "https://schema.org/InStock"
            },
            <?php endif; ?>
            <?php if (!empty($data['image_id'])): ?>
            "image": "<?php echo esc_url(wp_get_attachment_url($data['image_id'])); ?>",
            <?php endif; ?>
            <?php if (!empty($data['city'])): ?>
            "touristType": "City Tour",
            "location": {
                "@type": "Place",
                "name": "<?php echo esc_js($data['city']); ?>"
            },
            <?php endif; ?>
            <?php if (!empty($data['guide'])): ?>
            "provider": {
                "@type": "Person",
                "name": "<?php echo esc_js($data['guide']['name']); ?>"
            },
            <?php endif; ?>
            "datePublished": "<?php echo esc_js(get_the_date('c', $product_id)); ?>"
        }
        </script>
        <?php endif; ?>
        
        <style>
        /* ===============================================
           SINGLE PRODUCT BLOG - READABLE ARTICLE v2.0
           =============================================== */
        #<?php echo esc_attr($unique_id); ?> {
            --primary-color: <?php echo esc_attr($primary_color); ?>;
            --secondary-color: <?php echo esc_attr($secondary_color); ?>;
            --glass-blur: <?php echo esc_attr($glass_blur); ?>px;
            --section-gap: <?php echo esc_attr($section_gap); ?>px;
            --heading-color: <?php echo esc_attr($heading_color); ?>;
            --text-color: <?php echo esc_attr($text_color); ?>;
        }
        
        .mst-single-product-blog {
            display: flex;
            flex-direction: column;
            gap: var(--section-gap);
            max-width: 900px;
            margin: 0 auto;
            padding: 0 1rem;
            font-family: inherit;
        }
        
        /* Hero Section */
        .mst-spb-hero {
            text-align: center;
            padding: 48px 32px;
            background: linear-gradient(135deg, rgba(255,255,255,0.95), rgba(255,255,255,0.85));
            backdrop-filter: blur(var(--glass-blur)) saturate(180%);
            -webkit-backdrop-filter: blur(var(--glass-blur)) saturate(180%);
            border-radius: 24px;
            border: 1.5px solid rgba(255,255,255,0.5);
            box-shadow: 0 8px 32px -8px rgba(153, 82, 224, 0.12);
        }
        
        .mst-spb-title {
            font-size: 2.25rem;
            font-weight: 800;
            margin: 0 0 20px 0;
            color: var(--heading-color);
            line-height: 1.2;
        }
        
        .mst-spb-meta {
            display: flex;
            justify-content: center;
            gap: 24px;
            flex-wrap: wrap;
            margin-bottom: 20px;
        }
        
        .mst-spb-meta-item {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 0.9375rem;
            color: var(--text-color);
        }
        
        .mst-spb-meta-item svg {
            color: var(--primary-color);
        }
        
        .mst-spb-city {
            color: var(--primary-color);
            font-weight: 600;
        }
        
        .mst-spb-intro-text {
            font-size: 1.125rem;
            line-height: 1.7;
            color: var(--text-color);
            margin: 0;
            max-width: 700px;
            margin: 0 auto;
        }
        
        /* Section Common */
        .mst-spb-section {
            padding: 32px;
            border-radius: 24px;
            background: linear-gradient(135deg, rgba(255,255,255,0.92), rgba(255,255,255,0.8));
            backdrop-filter: blur(var(--glass-blur)) saturate(180%);
            -webkit-backdrop-filter: blur(var(--glass-blur)) saturate(180%);
            border: 1.5px solid rgba(255,255,255,0.45);
            box-shadow: 0 4px 24px -4px rgba(0,0,0,0.06);
        }
        
        .mst-spb-section-title {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--heading-color);
            margin: 0 0 24px 0;
        }
        
        .mst-spb-section-title svg {
            color: var(--primary-color);
            flex-shrink: 0;
        }
        
        .mst-spb-content-text {
            font-size: 1rem;
            line-height: 1.85;
            color: var(--text-color);
        }
        
        .mst-spb-content-text p {
            margin: 0 0 1rem 0;
        }
        
        .mst-spb-content-text p:last-child {
            margin-bottom: 0;
        }
        
        /* Gallery */
        .mst-spb-gallery-section {
            padding: 24px;
        }
        
        .mst-spb-gallery-grid {
            display: grid;
            grid-template-columns: repeat(var(--columns, 3), 1fr);
            gap: 12px;
        }
        
        .mst-spb-gallery-masonry {
            display: block;
            column-count: var(--columns, 3);
            column-gap: 12px;
        }
        
        .mst-spb-gallery-masonry .mst-spb-gallery-item {
            break-inside: avoid;
            margin-bottom: 12px;
        }
        
        .mst-spb-gallery-item {
            margin: 0;
            border-radius: 12px;
            overflow: hidden;
            cursor: pointer;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .mst-spb-gallery-item:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 24px -6px rgba(153, 82, 224, 0.2);
        }
        
        .mst-spb-gallery-img {
            width: 100%;
            height: auto;
            display: block;
            transition: transform 0.4s ease;
        }
        
        .mst-spb-gallery-item:hover .mst-spb-gallery-img {
            transform: scale(1.03);
        }
        
        /* Included Grid */
        .mst-spb-included-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 24px;
        }
        
        .mst-spb-included-col {
            padding: 20px;
            border-radius: 16px;
            background: rgba(255,255,255,0.6);
        }
        
        .mst-spb-included-heading {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 1rem;
            font-weight: 600;
            margin: 0 0 12px 0;
            color: var(--heading-color);
        }
        
        .mst-spb-included-list {
            font-size: 0.9375rem;
            line-height: 1.7;
            color: var(--text-color);
        }
        
        .mst-spb-included-list ul {
            margin: 0;
            padding-left: 20px;
        }
        
        .mst-spb-included-list li {
            margin-bottom: 6px;
        }
        
        /* Guide Card */
        .mst-spb-guide-card {
            display: flex;
            gap: 24px;
            align-items: flex-start;
            padding: 24px;
            background: rgba(255,255,255,0.5);
            border-radius: 20px;
        }
        
        .mst-spb-guide-avatar {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            overflow: hidden;
            flex-shrink: 0;
            border: 3px solid var(--primary-color);
        }
        
        .mst-spb-guide-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .mst-spb-guide-info {
            flex: 1;
        }
        
        .mst-spb-guide-name {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 1.25rem;
            font-weight: 700;
            margin: 0 0 8px 0;
            color: var(--heading-color);
        }
        
        .mst-spb-guide-rating {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 12px;
        }
        
        .mst-spb-guide-stars {
            color: var(--secondary-color);
            font-weight: 600;
        }
        
        .mst-spb-guide-reviews {
            color: var(--text-color);
            font-size: 0.875rem;
        }
        
        .mst-spb-guide-bio {
            font-size: 0.9375rem;
            line-height: 1.6;
            color: var(--text-color);
            margin: 0 0 16px 0;
        }
        
        .mst-spb-guide-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: var(--primary-color);
            font-weight: 600;
            text-decoration: none;
            font-size: 0.9375rem;
        }
        
        .mst-spb-guide-link:hover {
            text-decoration: underline;
        }
        
        /* FAQ */
        .mst-spb-faq-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        
        .mst-spb-faq-item {
            border-radius: 14px;
            background: rgba(255,255,255,0.6);
            border: 1px solid rgba(255,255,255,0.4);
            overflow: hidden;
        }
        
        .mst-spb-faq-question {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            padding: 18px 20px;
            background: transparent;
            border: none;
            cursor: pointer;
            font-size: 1rem;
            font-weight: 600;
            color: var(--heading-color);
            text-align: left;
            transition: background 0.2s;
        }
        
        .mst-spb-faq-question:hover {
            background: rgba(153, 82, 224, 0.04);
        }
        
        .mst-spb-faq-arrow {
            flex-shrink: 0;
            transition: transform 0.3s ease;
            color: var(--primary-color);
        }
        
        .mst-spb-faq-item.active .mst-spb-faq-arrow {
            transform: rotate(180deg);
        }
        
        .mst-spb-faq-answer {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease, padding 0.3s ease;
        }
        
        .mst-spb-faq-item.active .mst-spb-faq-answer {
            max-height: 500px;
            padding: 0 20px 18px 20px;
        }
        
        .mst-spb-faq-answer > div {
            font-size: 0.9375rem;
            line-height: 1.7;
            color: var(--text-color);
        }
        
        /* Lightbox */
        .mst-spb-lightbox {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.92);
            z-index: 999999;
            display: none;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .mst-spb-lightbox.active {
            display: flex;
            opacity: 1;
        }
        
        .mst-spb-lightbox img {
            max-width: 90%;
            max-height: 90%;
            border-radius: 12px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.5);
        }
        
        .mst-spb-lightbox-close {
            position: absolute;
            top: 20px;
            right: 20px;
            width: 48px;
            height: 48px;
            background: rgba(255,255,255,0.15);
            backdrop-filter: blur(10px);
            border: none;
            border-radius: 50%;
            cursor: pointer;
            color: white;
            font-size: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        /* Mobile */
        @media (max-width: 768px) {
            .mst-spb-gallery-grid,
            .mst-spb-gallery-masonry {
                --columns: 2;
                column-count: 2;
            }
            
            .mst-spb-title {
                font-size: 1.625rem;
            }
            
            .mst-spb-hero,
            .mst-spb-section {
                padding: 24px 20px;
            }
            
            .mst-spb-guide-card {
                flex-direction: column;
                align-items: center;
                text-align: center;
            }
            
            .mst-spb-guide-rating {
                justify-content: center;
            }
        }
        
        @media (max-width: 480px) {
            .mst-spb-gallery-grid,
            .mst-spb-gallery-masonry {
                --columns: 1;
                column-count: 1;
            }
        }
        </style>
        
        <script>
        (function() {
            document.addEventListener('DOMContentLoaded', function() {
                var container = document.getElementById('<?php echo esc_js($unique_id); ?>');
                if (!container) return;
                
                // FAQ Accordion
                var faqItems = container.querySelectorAll('.mst-spb-faq-item');
                faqItems.forEach(function(item) {
                    var question = item.querySelector('.mst-spb-faq-question');
                    if (question) {
                        question.addEventListener('click', function() {
                            var isActive = item.classList.contains('active');
                            
                            faqItems.forEach(function(i) {
                                i.classList.remove('active');
                                var btn = i.querySelector('.mst-spb-faq-question');
                                if (btn) btn.setAttribute('aria-expanded', 'false');
                            });
                            
                            if (!isActive) {
                                item.classList.add('active');
                                question.setAttribute('aria-expanded', 'true');
                            }
                        });
                    }
                });
                
                // Gallery Lightbox
                var galleryItems = container.querySelectorAll('.mst-spb-gallery-item');
                if (galleryItems.length === 0) return;
                
                var lightbox = document.createElement('div');
                lightbox.className = 'mst-spb-lightbox';
                lightbox.innerHTML = '<button class="mst-spb-lightbox-close">&times;</button><img src="" alt="Lightbox">';
                document.body.appendChild(lightbox);
                
                var lightboxImg = lightbox.querySelector('img');
                var lightboxClose = lightbox.querySelector('.mst-spb-lightbox-close');
                
                galleryItems.forEach(function(item) {
                    item.addEventListener('click', function() {
                        var img = item.querySelector('img');
                        var fullUrl = img.getAttribute('data-full') || img.src;
                        lightboxImg.src = fullUrl;
                        lightbox.classList.add('active');
                        document.body.style.overflow = 'hidden';
                    });
                });
                
                lightboxClose.addEventListener('click', function() {
                    lightbox.classList.remove('active');
                    document.body.style.overflow = '';
                });
                
                lightbox.addEventListener('click', function(e) {
                    if (e.target === lightbox) {
                        lightbox.classList.remove('active');
                        document.body.style.overflow = '';
                    }
                });
                
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape' && lightbox.classList.contains('active')) {
                        lightbox.classList.remove('active');
                        document.body.style.overflow = '';
                    }
                });
            });
        })();
        </script>
        <?php
    }
}
