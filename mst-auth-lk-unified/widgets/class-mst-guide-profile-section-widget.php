<?php
namespace MySuperTourElementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;

if (!defined('ABSPATH')) exit;

class Guide_Profile_Section extends Widget_Base {

    public function get_name() {
        return 'mst_guide_profile_section';
    }

    public function get_title() {
        return __('MST Guide Profile Section', 'mst-auth-lk');
    }

    public function get_icon() {
        return 'eicon-single-page';
    }

    public function get_categories() {
        return ['mysupertour'];
    }

    public function get_keywords() {
        return ['guide', 'profile', 'section', 'tours', 'reviews', 'testimonials'];
    }

    protected function register_controls() {
        // =========================
        // GUIDE DETAILS SECTION
        // =========================
        $this->start_controls_section(
            'section_guide_details',
            [
                'label' => __('Guide Details', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'guide_photo',
            [
                'label' => __('Guide Photo', 'my-super-tour-elementor'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $this->add_control(
            'guide_name',
            [
                'label' => __('Name', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Мария Иванова', 'my-super-tour-elementor'),
            ]
        );

        $this->add_control(
            'guide_location',
            [
                'label' => __('Location', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Санкт-Петербург', 'my-super-tour-elementor'),
            ]
        );

        $this->add_control(
            'guide_bio',
            [
                'label' => __('Bio', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXTAREA,
                'default' => __('Профессиональный гид с 8-летним опытом. Специализируюсь на исторических турах. Влюблена в архитектуру и историю своего города.', 'my-super-tour-elementor'),
                'rows' => 5,
            ]
        );

        $this->add_control(
            'guide_rating',
            [
                'label' => __('Rating', 'my-super-tour-elementor'),
                'type' => Controls_Manager::NUMBER,
                'default' => 4.9,
                'min' => 0,
                'max' => 5,
                'step' => 0.1,
            ]
        );

        $this->add_control(
            'guide_reviews_count',
            [
                'label' => __('Reviews Count', 'my-super-tour-elementor'),
                'type' => Controls_Manager::NUMBER,
                'default' => 127,
            ]
        );

        $this->add_control(
            'guide_experience',
            [
                'label' => __('Years of Experience', 'my-super-tour-elementor'),
                'type' => Controls_Manager::NUMBER,
                'default' => 8,
            ]
        );

        $this->add_control(
            'guide_tours_count',
            [
                'label' => __('Tours Conducted', 'my-super-tour-elementor'),
                'type' => Controls_Manager::NUMBER,
                'default' => 234,
            ]
        );

        $this->add_control(
            'is_verified',
            [
                'label' => __('Verified Guide', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'is_our_guide',
            [
                'label' => __('My Super Tour Guide', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'academic_title',
            [
                'label' => __('Academic Title (PhD, etc)', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
            ]
        );

        $this->add_control(
            'book_button_text',
            [
                'label' => __('Book Button Text', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Забронировать тур', 'my-super-tour-elementor'),
            ]
        );

        $this->add_control(
            'book_button_link',
            [
                'label' => __('Book Button Link', 'my-super-tour-elementor'),
                'type' => Controls_Manager::URL,
                'default' => ['url' => '#booking'],
            ]
        );

        $this->end_controls_section();

        // =========================
        // LANGUAGES SECTION
        // =========================
        $this->start_controls_section(
            'section_languages',
            [
                'label' => __('Languages', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $langs_repeater = new Repeater();
        $langs_repeater->add_control(
            'language',
            [
                'label' => __('Language', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Русский',
            ]
        );

        $this->add_control(
            'languages',
            [
                'label' => __('Languages', 'my-super-tour-elementor'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $langs_repeater->get_controls(),
                'default' => [
                    ['language' => 'Русский'],
                    ['language' => 'Английский'],
                    ['language' => 'Французский'],
                ],
                'title_field' => '{{{ language }}}',
            ]
        );

        $this->end_controls_section();

        // =========================
        // SPECIALTIES SECTION
        // =========================
        $this->start_controls_section(
            'section_specialties',
            [
                'label' => __('Specialties', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $specs_repeater = new Repeater();
        $specs_repeater->add_control(
            'specialty',
            [
                'label' => __('Specialty', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'История',
            ]
        );

        $this->add_control(
            'specialties',
            [
                'label' => __('Specialties', 'my-super-tour-elementor'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $specs_repeater->get_controls(),
                'default' => [
                    ['specialty' => 'Исторические туры'],
                    ['specialty' => 'Музеи'],
                    ['specialty' => 'Архитектура'],
                ],
                'title_field' => '{{{ specialty }}}',
            ]
        );

        $this->end_controls_section();

        // =========================
        // ACHIEVEMENTS SECTION
        // =========================
        $this->start_controls_section(
            'section_achievements',
            [
                'label' => __('Achievements', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $achievements_repeater = new Repeater();
        $achievements_repeater->add_control(
            'achievement',
            [
                'label' => __('Achievement', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Лучший гид 2023',
            ]
        );

        $this->add_control(
            'achievements',
            [
                'label' => __('Achievements', 'my-super-tour-elementor'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $achievements_repeater->get_controls(),
                'default' => [
                    ['achievement' => 'Лучший гид 2023 года'],
                    ['achievement' => 'Сертифицированный историк'],
                    ['achievement' => '500+ довольных туристов'],
                ],
                'title_field' => '{{{ achievement }}}',
            ]
        );

        $this->end_controls_section();

        // =========================
        // WOOCOMMERCE TOURS SECTION
        // =========================
        $this->start_controls_section(
            'section_woo_tours',
            [
                'label' => __('WooCommerce Tours', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'show_woo_tours',
            [
                'label' => __('Show WooCommerce Tours', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'tours_section_title',
            [
                'label' => __('Section Title', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Популярные туры', 'my-super-tour-elementor'),
                'condition' => ['show_woo_tours' => 'yes'],
            ]
        );

        $this->add_control(
            'tours_source',
            [
                'label' => __('Tours Source', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SELECT,
                'default' => 'category',
                'options' => [
                    'category' => __('By Category', 'my-super-tour-elementor'),
                    'tag' => __('By Tag', 'my-super-tour-elementor'),
                    'attribute' => __('By Attribute (Guide)', 'my-super-tour-elementor'),
                    'manual' => __('Manual Selection', 'my-super-tour-elementor'),
                ],
                'condition' => ['show_woo_tours' => 'yes'],
            ]
        );

        // Get WooCommerce categories
        $product_categories = [];
        if (class_exists('WooCommerce')) {
            $terms = get_terms(['taxonomy' => 'product_cat', 'hide_empty' => false]);
            if (!is_wp_error($terms)) {
                foreach ($terms as $term) {
                    $product_categories[$term->slug] = $term->name;
                }
            }
        }

        $this->add_control(
            'tours_category',
            [
                'label' => __('Category', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SELECT2,
                'options' => $product_categories,
                'multiple' => true,
                'condition' => [
                    'show_woo_tours' => 'yes',
                    'tours_source' => 'category',
                ],
            ]
        );

        // Get WooCommerce tags
        $product_tags = [];
        if (class_exists('WooCommerce')) {
            $tags = get_terms(['taxonomy' => 'product_tag', 'hide_empty' => false]);
            if (!is_wp_error($tags)) {
                foreach ($tags as $tag) {
                    $product_tags[$tag->slug] = $tag->name;
                }
            }
        }

        $this->add_control(
            'tours_tag',
            [
                'label' => __('Tag', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SELECT2,
                'options' => $product_tags,
                'multiple' => true,
                'condition' => [
                    'show_woo_tours' => 'yes',
                    'tours_source' => 'tag',
                ],
            ]
        );

        $this->add_control(
            'guide_attribute_name',
            [
                'label' => __('Guide Attribute Name', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'pa_guide',
                'description' => __('Enter WooCommerce attribute slug (e.g., pa_guide)', 'my-super-tour-elementor'),
                'condition' => [
                    'show_woo_tours' => 'yes',
                    'tours_source' => 'attribute',
                ],
            ]
        );

        $this->add_control(
            'guide_attribute_value',
            [
                'label' => __('Guide Attribute Value', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => '',
                'description' => __('Enter the guide name/slug to filter by', 'my-super-tour-elementor'),
                'condition' => [
                    'show_woo_tours' => 'yes',
                    'tours_source' => 'attribute',
                ],
            ]
        );

        // Get WooCommerce products for manual selection
        $products = [];
        if (class_exists('WooCommerce')) {
            $args = ['post_type' => 'product', 'posts_per_page' => -1, 'post_status' => 'publish'];
            $query = new \WP_Query($args);
            if ($query->have_posts()) {
                while ($query->have_posts()) {
                    $query->the_post();
                    $products[get_the_ID()] = get_the_title();
                }
                wp_reset_postdata();
            }
        }

        $this->add_control(
            'manual_products',
            [
                'label' => __('Select Products', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SELECT2,
                'options' => $products,
                'multiple' => true,
                'condition' => [
                    'show_woo_tours' => 'yes',
                    'tours_source' => 'manual',
                ],
            ]
        );

        $this->add_control(
            'tours_count',
            [
                'label' => __('Number of Tours', 'my-super-tour-elementor'),
                'type' => Controls_Manager::NUMBER,
                'default' => 3,
                'min' => 1,
                'max' => 12,
                'condition' => ['show_woo_tours' => 'yes'],
            ]
        );

        $this->add_control(
            'tours_columns',
            [
                'label' => __('Columns', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SELECT,
                'default' => '3',
                'options' => [
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                ],
                'condition' => ['show_woo_tours' => 'yes'],
            ]
        );

        $this->add_control(
            'show_tour_price',
            [
                'label' => __('Show Price', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'condition' => ['show_woo_tours' => 'yes'],
            ]
        );

        $this->add_control(
            'show_tour_rating',
            [
                'label' => __('Show Rating', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'condition' => ['show_woo_tours' => 'yes'],
            ]
        );

        $this->add_control(
            'show_tour_duration',
            [
                'label' => __('Show Duration', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'condition' => ['show_woo_tours' => 'yes'],
            ]
        );

        $this->add_control(
            'duration_attribute',
            [
                'label' => __('Duration Attribute', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'pa_duration',
                'condition' => [
                    'show_woo_tours' => 'yes',
                    'show_tour_duration' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();

        // =========================
        // MANUAL TOURS (FALLBACK)
        // =========================
        $this->start_controls_section(
            'section_manual_tours',
            [
                'label' => __('Manual Tours (No WooCommerce)', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'use_manual_tours',
            [
                'label' => __('Use Manual Tours', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
                'description' => __('Enable to use manually entered tours instead of WooCommerce', 'my-super-tour-elementor'),
            ]
        );

        $tours_repeater = new Repeater();
        
        $tours_repeater->add_control(
            'tour_image',
            [
                'label' => __('Image', 'my-super-tour-elementor'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $tours_repeater->add_control(
            'tour_title',
            [
                'label' => __('Title', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Название тура',
            ]
        );

        $tours_repeater->add_control(
            'tour_duration',
            [
                'label' => __('Duration', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => '3 часа',
            ]
        );

        $tours_repeater->add_control(
            'tour_price',
            [
                'label' => __('Price', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'от 3500₽',
            ]
        );

        $tours_repeater->add_control(
            'tour_rating',
            [
                'label' => __('Rating', 'my-super-tour-elementor'),
                'type' => Controls_Manager::NUMBER,
                'default' => 5.0,
                'min' => 0,
                'max' => 5,
                'step' => 0.1,
            ]
        );

        $tours_repeater->add_control(
            'tour_link',
            [
                'label' => __('Link', 'my-super-tour-elementor'),
                'type' => Controls_Manager::URL,
                'default' => ['url' => '#'],
            ]
        );

        $this->add_control(
            'manual_tours_list',
            [
                'label' => __('Tours', 'my-super-tour-elementor'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $tours_repeater->get_controls(),
                'default' => [
                    [
                        'tour_title' => 'Эрмитаж: Шедевры',
                        'tour_duration' => '3 часа',
                        'tour_price' => 'от 3500₽',
                        'tour_rating' => 5.0,
                    ],
                    [
                        'tour_title' => 'Ночной Петербург',
                        'tour_duration' => '2.5 часа',
                        'tour_price' => 'от 2800₽',
                        'tour_rating' => 4.9,
                    ],
                    [
                        'tour_title' => 'Царское Село',
                        'tour_duration' => '5 часов',
                        'tour_price' => 'от 5000₽',
                        'tour_rating' => 5.0,
                    ],
                ],
                'title_field' => '{{{ tour_title }}}',
                'condition' => ['use_manual_tours' => 'yes'],
            ]
        );

        $this->end_controls_section();

        // =========================
        // TESTIMONIALS/REVIEWS SECTION
        // =========================
        $this->start_controls_section(
            'section_testimonials',
            [
                'label' => __('Tourist Reviews', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'show_testimonials',
            [
                'label' => __('Show Testimonials', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'testimonials_section_title',
            [
                'label' => __('Section Title', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Отзывы туристов', 'my-super-tour-elementor'),
                'condition' => ['show_testimonials' => 'yes'],
            ]
        );

        $this->add_control(
            'testimonials_source',
            [
                'label' => __('Reviews Source', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SELECT,
                'default' => 'manual',
                'options' => [
                    'manual' => __('Manual Entry', 'my-super-tour-elementor'),
                    'woo_reviews' => __('WooCommerce Reviews', 'my-super-tour-elementor'),
                ],
                'condition' => ['show_testimonials' => 'yes'],
            ]
        );

        $this->add_control(
            'woo_reviews_count',
            [
                'label' => __('Number of Reviews', 'my-super-tour-elementor'),
                'type' => Controls_Manager::NUMBER,
                'default' => 6,
                'min' => 1,
                'max' => 20,
                'condition' => [
                    'show_testimonials' => 'yes',
                    'testimonials_source' => 'woo_reviews',
                ],
            ]
        );

        $testimonials_repeater = new Repeater();

        $testimonials_repeater->add_control(
            'author',
            [
                'label' => __('Author', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Анна С.',
            ]
        );

        $testimonials_repeater->add_control(
            'author_photo',
            [
                'label' => __('Author Photo', 'my-super-tour-elementor'),
                'type' => Controls_Manager::MEDIA,
            ]
        );

        $testimonials_repeater->add_control(
            'rating',
            [
                'label' => __('Rating', 'my-super-tour-elementor'),
                'type' => Controls_Manager::NUMBER,
                'default' => 5,
                'min' => 1,
                'max' => 5,
            ]
        );

        $testimonials_repeater->add_control(
            'text',
            [
                'label' => __('Review Text', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXTAREA,
                'default' => 'Невероятная экскурсия! Гид рассказала так много интересного об истории города. Рекомендую!',
            ]
        );

        $testimonials_repeater->add_control(
            'date',
            [
                'label' => __('Date', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => '2 дня назад',
            ]
        );

        $testimonials_repeater->add_control(
            'tour_name',
            [
                'label' => __('Tour Name', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => '',
            ]
        );

        $testimonials_repeater->add_control(
            'photos',
            [
                'label' => __('Review Photos', 'my-super-tour-elementor'),
                'type' => Controls_Manager::GALLERY,
            ]
        );

        $this->add_control(
            'testimonials',
            [
                'label' => __('Testimonials', 'my-super-tour-elementor'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $testimonials_repeater->get_controls(),
                'default' => [
                    [
                        'author' => 'Анна С.',
                        'rating' => 5,
                        'text' => 'Невероятная экскурсия! Мария рассказала так много интересного об истории города. Рекомендую!',
                        'date' => '2 дня назад',
                    ],
                    [
                        'author' => 'John M.',
                        'rating' => 5,
                        'text' => 'Best tour guide in St. Petersburg! Very knowledgeable and friendly. Her English is perfect.',
                        'date' => '1 неделю назад',
                    ],
                    [
                        'author' => 'Елена К.',
                        'rating' => 5,
                        'text' => 'Профессионал своего дела. Время пролетело незаметно. Обязательно вернемся на другие туры!',
                        'date' => '2 недели назад',
                    ],
                ],
                'title_field' => '{{{ author }}}',
                'condition' => [
                    'show_testimonials' => 'yes',
                    'testimonials_source' => 'manual',
                ],
            ]
        );

        $this->add_control(
            'initial_testimonials_count',
            [
                'label' => __('Initial Reviews to Show', 'my-super-tour-elementor'),
                'type' => Controls_Manager::NUMBER,
                'default' => 3,
                'min' => 1,
                'max' => 20,
                'condition' => ['show_testimonials' => 'yes'],
            ]
        );

        $this->add_control(
            'show_load_more_reviews',
            [
                'label' => __('Show Load More Button', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'condition' => ['show_testimonials' => 'yes'],
            ]
        );

        $this->add_control(
            'load_more_reviews_text',
            [
                'label' => __('Load More Text', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Все отзывы', 'my-super-tour-elementor'),
                'condition' => [
                    'show_testimonials' => 'yes',
                    'show_load_more_reviews' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'all_reviews_link',
            [
                'label' => __('All Reviews Link', 'my-super-tour-elementor'),
                'type' => Controls_Manager::URL,
                'description' => __('Link to reviews section widget', 'my-super-tour-elementor'),
                'condition' => [
                    'show_testimonials' => 'yes',
                    'show_load_more_reviews' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();

        // =========================
        // STYLE: HERO CARD
        // =========================
        $this->start_controls_section(
            'section_style_hero',
            [
                'label' => __('Hero Card Style', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'enable_liquid_glass',
            [
                'label' => __('Enable Liquid Glass', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_responsive_control(
            'hero_border_radius',
            [
                'label' => __('Border Radius', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => ['px' => ['min' => 0, 'max' => 50]],
                'default' => ['size' => 24, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .mst-guide-profile-hero' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'avatar_size',
            [
                'label' => __('Avatar Size', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => ['px' => ['min' => 80, 'max' => 200]],
                'default' => ['size' => 128, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .mst-guide-profile-avatar' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // =========================
        // STYLE: COLORS
        // =========================
        $this->start_controls_section(
            'section_style_colors',
            [
                'label' => __('Colors', 'my-super-tour-elementor'),
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
                'label' => __('Secondary/Accent Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#fbd603',
            ]
        );

        $this->add_control(
            'rating_color',
            [
                'label' => __('Rating Star Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#fbd603',
            ]
        );

        $this->add_control(
            'verified_color',
            [
                'label' => __('Verified Badge Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#10b981',
            ]
        );

        $this->add_control(
            'text_color',
            [
                'label' => __('Text Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#1a1a1a',
                'selectors' => [
                    '{{WRAPPER}} .mst-guide-profile-name' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .mst-guide-profile-section-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'muted_color',
            [
                'label' => __('Muted Text Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#737373',
                'selectors' => [
                    '{{WRAPPER}} .mst-guide-profile-location' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .mst-guide-profile-bio' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'language_tag_bg',
            [
                'label' => __('Language Tag Background', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(153, 82, 224, 0.1)',
                'selectors' => [
                    '{{WRAPPER}} .mst-guide-profile-language' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'specialty_tag_bg',
            [
                'label' => __('Specialty Tag Background', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(251, 214, 3, 0.2)',
                'selectors' => [
                    '{{WRAPPER}} .mst-guide-profile-specialty' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // =========================
        // STYLE: BUTTON
        // =========================
        $this->start_controls_section(
            'section_style_button',
            [
                'label' => __('Button Style', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'button_bg_color',
            [
                'label' => __('Background Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#9952E0',
                'selectors' => [
                    '{{WRAPPER}} .mst-guide-profile-book-btn' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_text_color',
            [
                'label' => __('Text Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .mst-guide-profile-book-btn' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'button_border_radius',
            [
                'label' => __('Border Radius', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => ['px' => ['min' => 0, 'max' => 30]],
                'default' => ['size' => 12, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .mst-guide-profile-book-btn' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // =========================
        // STYLE: TOURS CARDS
        // =========================
        $this->start_controls_section(
            'section_style_tours',
            [
                'label' => __('Tour Cards Style', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'tour_card_border_radius',
            [
                'label' => __('Card Border Radius', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => ['px' => ['min' => 0, 'max' => 40]],
                'default' => ['size' => 20, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .mst-guide-tour-card' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'tour_image_height',
            [
                'label' => __('Image Height', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => ['px' => ['min' => 120, 'max' => 300]],
                'default' => ['size' => 180, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .mst-guide-tour-image' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // =========================
        // STYLE: TESTIMONIALS
        // =========================
        $this->start_controls_section(
            'section_style_testimonials',
            [
                'label' => __('Testimonials Style', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'testimonial_card_border_radius',
            [
                'label' => __('Card Border Radius', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => ['px' => ['min' => 0, 'max' => 40]],
                'default' => ['size' => 20, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .mst-guide-testimonial-card' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'testimonial_bg_color',
            [
                'label' => __('Card Background', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(251, 214, 3, 0.05)',
                'selectors' => [
                    '{{WRAPPER}} .mst-guide-testimonial-card' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $liquid_glass = $settings['enable_liquid_glass'] === 'yes';
        $primary_color = $settings['primary_color'] ?? '#9952E0';
        $secondary_color = $settings['secondary_color'] ?? '#fbd603';
        $rating_color = $settings['rating_color'] ?? '#fbd603';
        $verified_color = $settings['verified_color'] ?? '#10b981';
        $widget_id = $this->get_id();
        ?>
        <style>
        /* Guide Profile Section - Inline CSS */
        .mst-guide-profile-section { font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif; padding: 2rem 0; }
        .mst-guide-profile-hero-section { max-width: 1200px; margin: 0 auto; padding: 0 1rem; }
        .mst-guide-profile-hero { border-radius: 1.5rem; padding: 2rem; background: linear-gradient(135deg, rgba(255,255,255,0.9), rgba(255,255,255,0.7)); border: 1.5px solid rgba(255,255,255,0.5); }
        .mst-guide-profile-hero.mst-liquid-glass { background: linear-gradient(135deg, rgba(255,255,255,0.85), rgba(255,255,255,0.65)); backdrop-filter: blur(28px) saturate(200%); -webkit-backdrop-filter: blur(28px) saturate(200%); border: 1.5px solid rgba(255,255,255,0.4); box-shadow: 0 12px 40px -10px rgba(153, 82, 224, 0.2), inset 0 2px 4px rgba(255,255,255,0.6); }
        .mst-guide-profile-grid { display: grid; grid-template-columns: 1fr 2fr; gap: 2rem; }
        @media (max-width: 768px) { .mst-guide-profile-grid { grid-template-columns: 1fr; } }
        .mst-guide-profile-left { display: flex; flex-direction: column; align-items: center; text-align: center; padding: 1.5rem; border-right: 1px solid rgba(153, 82, 224, 0.1); }
        @media (max-width: 768px) { .mst-guide-profile-left { border-right: none; border-bottom: 1px solid rgba(153, 82, 224, 0.1); padding-bottom: 2rem; } }
        .mst-guide-profile-avatar-wrap { position: relative; margin-bottom: 1rem; }
        .mst-guide-profile-avatar { width: 128px; height: 128px; border-radius: 50%; overflow: hidden; border: 4px solid rgba(153, 82, 224, 0.2); box-shadow: 0 8px 24px -8px rgba(153, 82, 224, 0.25); transition: transform 0.35s ease, box-shadow 0.35s ease; }
        .mst-guide-profile-avatar:hover { box-shadow: 0 12px 32px -8px rgba(153, 82, 224, 0.35); }
        .mst-guide-profile-avatar img { width: 100%; height: 100%; object-fit: cover; }
        .mst-guide-profile-badges { display: flex; gap: 0.5rem; margin-top: 0.75rem; justify-content: center; }
        .mst-guide-profile-badge-verified, .mst-guide-profile-badge-academic { display: inline-flex; align-items: center; gap: 0.25rem; padding: 0.35rem 0.65rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; }
        .mst-guide-profile-name { font-size: 1.5rem; font-weight: 700; margin: 0.5rem 0; color: #1a1a2e; }
        .mst-guide-profile-location { display: flex; align-items: center; gap: 0.35rem; color: #6b7280; font-size: 0.875rem; margin-bottom: 0.75rem; }
        .mst-guide-profile-rating { display: flex; align-items: center; gap: 0.35rem; margin-bottom: 0.75rem; }
        .mst-guide-profile-rating-value { font-size: 1.125rem; font-weight: 600; color: #1a1a2e; }
        .mst-guide-profile-reviews-count { font-size: 0.875rem; color: #6b7280; }
        .mst-guide-profile-our-badge { display: inline-flex; align-items: center; gap: 0.35rem; padding: 0.4rem 0.8rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; border: 1px solid; margin-bottom: 1rem; }
        .mst-guide-profile-stats { width: 100%; display: flex; flex-direction: column; gap: 0.5rem; margin-bottom: 1.5rem; }
        .mst-guide-profile-stat { display: flex; justify-content: space-between; font-size: 0.875rem; }
        .mst-guide-profile-stat-label { color: #6b7280; }
        .mst-guide-profile-stat-value { font-weight: 600; color: #1a1a2e; }
        .mst-guide-profile-actions { width: 100%; }
        .mst-guide-profile-book-btn { display: flex; align-items: center; justify-content: center; gap: 0.5rem; width: 100%; padding: 0.875rem 1.5rem; background: <?php echo esc_attr($primary_color); ?>; color: white; border: none; border-radius: 12px; font-size: 0.9rem; font-weight: 600; cursor: pointer; text-decoration: none; transition: box-shadow 0.3s ease; margin-bottom: 0.75rem; }
        .mst-guide-profile-book-btn:hover { box-shadow: 0 8px 24px -6px <?php echo esc_attr($primary_color); ?>80; }
        .mst-guide-profile-secondary-actions { display: flex; gap: 0.5rem; }
        .mst-guide-profile-icon-btn { flex: 1; display: flex; align-items: center; justify-content: center; padding: 0.75rem; background: rgba(255,255,255,0.7); border: 1.5px solid rgba(153, 82, 224, 0.15); border-radius: 12px; cursor: pointer; transition: all 0.3s ease; }
        .mst-guide-profile-icon-btn:hover { background: rgba(255,255,255,0.9); border-color: <?php echo esc_attr($primary_color); ?>40; }
        .mst-guide-profile-right { padding: 1rem; }
        .mst-guide-profile-info-block { margin-bottom: 1.5rem; }
        .mst-guide-profile-info-block h3 { font-size: 0.9rem; font-weight: 600; color: #1a1a2e; margin: 0 0 0.75rem 0; }
        .mst-guide-profile-info-header { display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.75rem; }
        .mst-guide-profile-info-header h3 { margin: 0; }
        .mst-guide-profile-tags { display: flex; flex-wrap: wrap; gap: 0.5rem; }
        .mst-guide-profile-language { display: inline-flex; padding: 0.4rem 0.75rem; background: rgba(251, 214, 3, 0.12); border: 1.5px solid rgba(251, 214, 3, 0.3); border-radius: 9999px; font-size: 0.8rem; font-weight: 500; }
        .mst-guide-profile-specialty { display: inline-flex; padding: 0.4rem 0.75rem; background: rgba(22, 191, 255, 0.1); color: #0891b2; border: 1.5px solid rgba(22, 191, 255, 0.25); border-radius: 9999px; font-size: 0.8rem; font-weight: 500; }
        .mst-guide-profile-bio { color: #4b5563; line-height: 1.7; margin: 0; font-size: 0.9rem; }
        .mst-guide-profile-achievements { display: flex; flex-direction: column; gap: 0.5rem; }
        .mst-guide-profile-achievement { display: flex; align-items: center; gap: 0.5rem; padding: 0.6rem 1rem; border-radius: 12px; font-size: 0.875rem; }
        .mst-guide-profile-tours-section, .mst-guide-profile-testimonials-section { max-width: 1200px; margin: 3rem auto; padding: 0 1rem; }
        .mst-guide-profile-section-title { font-size: 1.75rem; font-weight: 700; color: #1a1a2e; margin: 0 0 1.5rem 0; }
        .mst-guide-profile-tours-grid { display: grid; grid-template-columns: repeat(var(--columns, 3), 1fr); gap: 1.5rem; }
        @media (max-width: 1024px) { .mst-guide-profile-tours-grid { grid-template-columns: repeat(2, 1fr); } }
        @media (max-width: 640px) { .mst-guide-profile-tours-grid { grid-template-columns: 1fr; } }
        .mst-guide-tour-card { display: block; border-radius: 20px; overflow: hidden; background: white; border: 1.5px solid rgba(153, 82, 224, 0.1); text-decoration: none; transition: all 0.35s ease; }
        .mst-guide-tour-card.mst-liquid-glass { background: linear-gradient(135deg, rgba(255,255,255,0.85), rgba(255,255,255,0.65)); backdrop-filter: blur(20px) saturate(180%); -webkit-backdrop-filter: blur(20px) saturate(180%); border: 1.5px solid rgba(255,255,255,0.4); box-shadow: 0 8px 32px -8px rgba(153, 82, 224, 0.15); }
        .mst-guide-tour-card:hover { border-color: rgba(255,255,255,0.5); box-shadow: 0 16px 48px -12px rgba(153, 82, 224, 0.25); }
        .mst-guide-tour-image { height: 180px; overflow: hidden; }
        .mst-guide-tour-image img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s ease; }
        .mst-guide-tour-card:hover .mst-guide-tour-image img { transform: scale(1.08); }
        .mst-guide-tour-content { padding: 1rem; }
        .mst-guide-tour-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.5rem; }
        .mst-guide-tour-title { font-size: 0.95rem; font-weight: 600; color: #1a1a2e; margin: 0; flex: 1; }
        .mst-guide-tour-rating { display: flex; align-items: center; gap: 0.25rem; font-size: 0.875rem; font-weight: 500; }
        .mst-guide-tour-meta { display: flex; justify-content: space-between; align-items: center; font-size: 0.85rem; }
        .mst-guide-tour-duration { color: #6b7280; }
        .mst-guide-tour-price { font-weight: 600; color: #1a1a2e; }
        .mst-guide-profile-testimonials-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem; }
        @media (max-width: 1024px) { .mst-guide-profile-testimonials-grid { grid-template-columns: repeat(2, 1fr); } }
        @media (max-width: 640px) { .mst-guide-profile-testimonials-grid { grid-template-columns: 1fr; } }
        .mst-guide-testimonial-card { padding: 1.5rem; border-radius: 20px; background: rgba(251, 214, 3, 0.05); border: 1px solid rgba(251, 214, 3, 0.15); transition: all 0.35s ease; }
        .mst-guide-testimonial-card.mst-liquid-glass-subtle { background: linear-gradient(135deg, rgba(255,255,255,0.6), rgba(255,255,255,0.4)); backdrop-filter: blur(16px) saturate(160%); -webkit-backdrop-filter: blur(16px) saturate(160%); border: 1px solid rgba(255,255,255,0.3); }
        .mst-guide-testimonial-card:hover { box-shadow: 0 12px 32px -8px rgba(251, 214, 3, 0.2); }
        .mst-guide-testimonial-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem; }
        .mst-guide-testimonial-author { display: flex; align-items: center; gap: 0.75rem; }
        .mst-guide-testimonial-avatar { width: 40px; height: 40px; border-radius: 50%; overflow: hidden; }
        .mst-guide-testimonial-avatar img { width: 100%; height: 100%; object-fit: cover; }
        .mst-guide-testimonial-avatar-initials { display: flex; align-items: center; justify-content: center; font-size: 1rem; font-weight: 600; }
        .mst-guide-testimonial-author-info { display: flex; flex-direction: column; }
        .mst-guide-testimonial-name { font-weight: 600; color: #1a1a2e; font-size: 0.9rem; }
        .mst-guide-testimonial-date { font-size: 0.75rem; color: #6b7280; }
        .mst-guide-testimonial-rating { display: flex; gap: 0.125rem; }
        .mst-guide-testimonial-tour { display: flex; align-items: center; gap: 0.35rem; font-size: 0.8rem; color: #6b7280; margin-bottom: 0.75rem; }
        .mst-guide-testimonial-text { color: #4b5563; line-height: 1.6; font-size: 0.9rem; margin: 0 0 1rem 0; }
        .mst-guide-testimonial-photos { display: flex; gap: 0.5rem; }
        .mst-guide-testimonial-photo { border-radius: 12px; overflow: hidden; cursor: pointer; }
        .mst-guide-testimonial-photo.mst-main-photo { width: 80px; height: 60px; }
        .mst-guide-testimonial-photo.mst-thumb-photo { width: 50px; height: 60px; position: relative; }
        .mst-guide-testimonial-photo img { width: 100%; height: 100%; object-fit: cover; }
        .mst-guide-testimonial-photo-counter { position: absolute; inset: 0; display: flex; align-items: center; justify-content: center; background: rgba(0,0,0,0.5); color: white; font-size: 0.75rem; font-weight: 600; }
        .mst-guide-profile-load-more-wrap { text-align: center; margin-top: 2rem; }
        .mst-guide-profile-load-more-btn { display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.875rem 2rem; color: white; border: none; border-radius: 12px; font-size: 0.9rem; font-weight: 600; cursor: pointer; text-decoration: none; transition: box-shadow 0.3s ease; }
        .mst-guide-profile-load-more-btn:hover { box-shadow: 0 8px 24px -6px <?php echo esc_attr($primary_color); ?>60; }
        .mst-guide-profile-reviews-total { opacity: 0.85; }
        .mst-hidden { display: none !important; }
        .mst-no-woo, .mst-no-tours { grid-column: 1 / -1; text-align: center; color: #6b7280; padding: 2rem; }
        </style>
        <div class="mst-guide-profile-section" data-widget-id="<?php echo esc_attr($widget_id); ?>">
            
            <!-- HERO SECTION -->
            <section class="mst-guide-profile-hero-section">
                <div class="mst-guide-profile-hero<?php echo $liquid_glass ? ' mst-liquid-glass' : ''; ?>">
                    <div class="mst-guide-profile-grid">
                        
                        <!-- Left Column: Avatar & Quick Info -->
                        <div class="mst-guide-profile-left">
                            <div class="mst-guide-profile-avatar-wrap">
                                <div class="mst-guide-profile-avatar" style="border-color: <?php echo esc_attr($primary_color); ?>20;">
                                    <img src="<?php echo esc_url($settings['guide_photo']['url']); ?>" alt="<?php echo esc_attr($settings['guide_name']); ?>">
                                </div>
                                
                                <!-- Badges -->
                                <div class="mst-guide-profile-badges">
                                    <?php if ($settings['is_verified'] === 'yes'): ?>
                                    <span class="mst-guide-profile-badge-verified" style="background: <?php echo esc_attr($verified_color); ?>20; color: <?php echo esc_attr($verified_color); ?>;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                                    </span>
                                    <?php endif; ?>
                                    
                                    <?php if (!empty($settings['academic_title'])): ?>
                                    <span class="mst-guide-profile-badge-academic" style="background: <?php echo esc_attr($secondary_color); ?>30;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>
                                        <?php echo esc_html($settings['academic_title']); ?>
                                    </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <h1 class="mst-guide-profile-name"><?php echo esc_html($settings['guide_name']); ?></h1>
                            
                            <div class="mst-guide-profile-location">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                                <span><?php echo esc_html($settings['guide_location']); ?></span>
                            </div>
                            
                            <div class="mst-guide-profile-rating">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="<?php echo esc_attr($rating_color); ?>" stroke="none"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                                <span class="mst-guide-profile-rating-value"><?php echo esc_html($settings['guide_rating']); ?></span>
                                <span class="mst-guide-profile-reviews-count">(<?php echo esc_html($settings['guide_reviews_count']); ?> отзывов)</span>
                            </div>
                            
                            <?php if ($settings['is_our_guide'] === 'yes'): ?>
                            <div class="mst-guide-profile-our-badge" style="background: <?php echo esc_attr($primary_color); ?>15; border-color: <?php echo esc_attr($primary_color); ?>40;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="<?php echo esc_attr($primary_color); ?>" stroke="none"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>
                                <span style="color: <?php echo esc_attr($primary_color); ?>;">My Super Tour</span>
                            </div>
                            <?php endif; ?>
                            
                            <!-- Stats -->
                            <div class="mst-guide-profile-stats">
                                <div class="mst-guide-profile-stat">
                                    <span class="mst-guide-profile-stat-label">Опыт</span>
                                    <span class="mst-guide-profile-stat-value"><?php echo esc_html($settings['guide_experience']); ?> лет</span>
                                </div>
                                <div class="mst-guide-profile-stat">
                                    <span class="mst-guide-profile-stat-label">Туров проведено</span>
                                    <span class="mst-guide-profile-stat-value"><?php echo esc_html($settings['guide_tours_count']); ?></span>
                                </div>
                            </div>
                            
                            <!-- Action Buttons -->
                            <div class="mst-guide-profile-actions">
                                <?php 
                                $book_url = $settings['book_button_link']['url'] ?? '#';
                                $book_target = $settings['book_button_link']['is_external'] ? ' target="_blank"' : '';
                                ?>
                                <a href="<?php echo esc_url($book_url); ?>"<?php echo $book_target; ?> class="mst-guide-profile-book-btn">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                                    <?php echo esc_html($settings['book_button_text']); ?>
                                </a>
                                <div class="mst-guide-profile-secondary-actions">
                                    <button class="mst-guide-profile-icon-btn" aria-label="Like">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/></svg>
                                    </button>
                                    <button class="mst-guide-profile-icon-btn" aria-label="Share">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/></svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Right Column: Detailed Info -->
                        <div class="mst-guide-profile-right">
                            
                            <!-- Languages -->
                            <?php if (!empty($settings['languages'])): ?>
                            <div class="mst-guide-profile-info-block">
                                <div class="mst-guide-profile-info-header">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="<?php echo esc_attr($primary_color); ?>" stroke-width="2"><path d="m5 8 6 6"/><path d="m4 14 6-6 2-3"/><path d="M2 5h12"/><path d="M7 2h1"/><path d="m22 22-5-10-5 10"/><path d="M14 18h6"/></svg>
                                    <h3>Языки</h3>
                                </div>
                                <div class="mst-guide-profile-tags">
                                    <?php foreach ($settings['languages'] as $lang): ?>
                                    <span class="mst-guide-profile-language" style="color: <?php echo esc_attr($primary_color); ?>;"><?php echo esc_html($lang['language']); ?></span>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            <?php endif; ?>
                            
                            <!-- Specialties -->
                            <?php if (!empty($settings['specialties'])): ?>
                            <div class="mst-guide-profile-info-block">
                                <div class="mst-guide-profile-info-header">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="<?php echo esc_attr($primary_color); ?>" stroke-width="2"><circle cx="12" cy="8" r="6"/><path d="M15.477 12.89 17 22l-5-3-5 3 1.523-9.11"/></svg>
                                    <h3>Специализация</h3>
                                </div>
                                <div class="mst-guide-profile-tags">
                                    <?php foreach ($settings['specialties'] as $spec): ?>
                                    <span class="mst-guide-profile-specialty"><?php echo esc_html($spec['specialty']); ?></span>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            <?php endif; ?>
                            
                            <!-- Bio -->
                            <div class="mst-guide-profile-info-block">
                                <h3>О гиде</h3>
                                <p class="mst-guide-profile-bio"><?php echo esc_html($settings['guide_bio']); ?></p>
                            </div>
                            
                            <!-- Achievements -->
                            <?php if (!empty($settings['achievements'])): ?>
                            <div class="mst-guide-profile-info-block">
                                <h3>Достижения</h3>
                                <div class="mst-guide-profile-achievements">
                                    <?php foreach ($settings['achievements'] as $achievement): ?>
                                    <div class="mst-guide-profile-achievement" style="background: <?php echo esc_attr($primary_color); ?>08;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="<?php echo esc_attr($primary_color); ?>" stroke-width="2"><circle cx="12" cy="8" r="6"/><path d="M15.477 12.89 17 22l-5-3-5 3 1.523-9.11"/></svg>
                                        <span><?php echo esc_html($achievement['achievement']); ?></span>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            <?php endif; ?>
                            
                        </div>
                    </div>
                </div>
            </section>
            
            <!-- TOURS SECTION -->
            <?php if ($settings['show_woo_tours'] === 'yes' || $settings['use_manual_tours'] === 'yes'): ?>
            <section class="mst-guide-profile-tours-section">
                <h2 class="mst-guide-profile-section-title"><?php echo esc_html($settings['tours_section_title']); ?></h2>
                
                <div class="mst-guide-profile-tours-grid" style="--columns: <?php echo esc_attr($settings['tours_columns']); ?>;">
                    <?php
                    if ($settings['use_manual_tours'] === 'yes') {
                        // Manual tours
                        foreach ($settings['manual_tours_list'] as $tour):
                            $tour_url = $tour['tour_link']['url'] ?? '#';
                            $tour_target = !empty($tour['tour_link']['is_external']) ? ' target="_blank"' : '';
                    ?>
                    <a href="<?php echo esc_url($tour_url); ?>"<?php echo $tour_target; ?> class="mst-guide-tour-card<?php echo $liquid_glass ? ' mst-liquid-glass' : ''; ?>">
                        <div class="mst-guide-tour-image">
                            <img src="<?php echo esc_url($tour['tour_image']['url']); ?>" alt="<?php echo esc_attr($tour['tour_title']); ?>">
                        </div>
                        <div class="mst-guide-tour-content">
                            <div class="mst-guide-tour-header">
                                <h3 class="mst-guide-tour-title"><?php echo esc_html($tour['tour_title']); ?></h3>
                                <div class="mst-guide-tour-rating">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="<?php echo esc_attr($rating_color); ?>" stroke="none"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                                    <span><?php echo esc_html($tour['tour_rating']); ?></span>
                                </div>
                            </div>
                            <div class="mst-guide-tour-meta">
                                <span class="mst-guide-tour-duration"><?php echo esc_html($tour['tour_duration']); ?></span>
                                <span class="mst-guide-tour-price"><?php echo esc_html($tour['tour_price']); ?></span>
                            </div>
                        </div>
                    </a>
                    <?php
                        endforeach;
                    } else {
                        // WooCommerce tours
                        $this->render_woo_tours($settings, $liquid_glass, $rating_color);
                    }
                    ?>
                </div>
            </section>
            <?php endif; ?>
            
            <!-- TESTIMONIALS SECTION -->
            <?php if ($settings['show_testimonials'] === 'yes'): ?>
            <section class="mst-guide-profile-testimonials-section">
                <h2 class="mst-guide-profile-section-title"><?php echo esc_html($settings['testimonials_section_title']); ?></h2>
                
                <div class="mst-guide-profile-testimonials-grid">
                    <?php
                    $testimonials = [];
                    $initial_count = intval($settings['initial_testimonials_count']);
                    
                    if ($settings['testimonials_source'] === 'woo_reviews') {
                        $testimonials = $this->get_woo_reviews($settings);
                    } else {
                        $testimonials = $settings['testimonials'];
                    }
                    
                    foreach ($testimonials as $index => $testimonial):
                        $hidden_class = $index >= $initial_count ? ' mst-hidden' : '';
                    ?>
                    <div class="mst-guide-testimonial-card<?php echo $hidden_class; ?><?php echo $liquid_glass ? ' mst-liquid-glass-subtle' : ''; ?>">
                        <div class="mst-guide-testimonial-header">
                            <div class="mst-guide-testimonial-author">
                                <?php if (!empty($testimonial['author_photo']['url'])): ?>
                                <div class="mst-guide-testimonial-avatar">
                                    <img src="<?php echo esc_url($testimonial['author_photo']['url']); ?>" alt="">
                                </div>
                                <?php else: ?>
                                <div class="mst-guide-testimonial-avatar mst-guide-testimonial-avatar-initials" style="background: <?php echo esc_attr($primary_color); ?>15; color: <?php echo esc_attr($primary_color); ?>;">
                                    <?php echo esc_html(mb_substr($testimonial['author'], 0, 1)); ?>
                                </div>
                                <?php endif; ?>
                                <div class="mst-guide-testimonial-author-info">
                                    <span class="mst-guide-testimonial-name"><?php echo esc_html($testimonial['author']); ?></span>
                                    <?php if (!empty($testimonial['date'])): ?>
                                    <span class="mst-guide-testimonial-date"><?php echo esc_html($testimonial['date']); ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="mst-guide-testimonial-rating">
                                <?php for ($i = 0; $i < intval($testimonial['rating']); $i++): ?>
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="<?php echo esc_attr($rating_color); ?>" stroke="none"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                                <?php endfor; ?>
                            </div>
                        </div>
                        
                        <?php if (!empty($testimonial['tour_name'])): ?>
                        <div class="mst-guide-testimonial-tour">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                            <?php echo esc_html($testimonial['tour_name']); ?>
                        </div>
                        <?php endif; ?>
                        
                        <p class="mst-guide-testimonial-text"><?php echo esc_html($testimonial['text']); ?></p>
                        
                        <?php if (!empty($testimonial['photos'])): ?>
                        <div class="mst-guide-testimonial-photos">
                            <?php 
                            $photos = $testimonial['photos'];
                            $photo_count = count($photos);
                            $display_photos = array_slice($photos, 0, 3);
                            ?>
                            <?php foreach ($display_photos as $i => $photo): ?>
                            <div class="mst-guide-testimonial-photo<?php echo $i === 0 ? ' mst-main-photo' : ' mst-thumb-photo'; ?>" data-lightbox="testimonial-<?php echo esc_attr($index); ?>">
                                <img src="<?php echo esc_url($photo['url']); ?>" alt="">
                                <?php if ($i === 2 && $photo_count > 3): ?>
                                <span class="mst-guide-testimonial-photo-counter">+<?php echo ($photo_count - 3); ?></span>
                                <?php endif; ?>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                </div>
                
                <?php if ($settings['show_load_more_reviews'] === 'yes' && count($testimonials) > $initial_count): ?>
                <div class="mst-guide-profile-load-more-wrap">
                    <?php if (!empty($settings['all_reviews_link']['url'])): ?>
                    <a href="<?php echo esc_url($settings['all_reviews_link']['url']); ?>" class="mst-guide-profile-load-more-btn" style="background: <?php echo esc_attr($primary_color); ?>;">
                        <?php echo esc_html($settings['load_more_reviews_text']); ?>
                        <span class="mst-guide-profile-reviews-total">(<?php echo count($testimonials); ?>)</span>
                    </a>
                    <?php else: ?>
                    <button class="mst-guide-profile-load-more-btn mst-load-more-trigger" style="background: <?php echo esc_attr($primary_color); ?>;" data-widget="<?php echo esc_attr($widget_id); ?>">
                        <?php echo esc_html($settings['load_more_reviews_text']); ?>
                        <span class="mst-guide-profile-reviews-total">(<?php echo count($testimonials); ?>)</span>
                    </button>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </section>
            <?php endif; ?>
            
        </div>
        <?php
    }

    private function render_woo_tours($settings, $liquid_glass, $rating_color) {
        if (!class_exists('WooCommerce')) {
            echo '<p class="mst-no-woo">WooCommerce не установлен</p>';
            return;
        }

        $args = [
            'post_type' => 'product',
            'posts_per_page' => intval($settings['tours_count']),
            'post_status' => 'publish',
        ];

        // Build query based on source
        switch ($settings['tours_source']) {
            case 'category':
                if (!empty($settings['tours_category'])) {
                    $args['tax_query'] = [
                        [
                            'taxonomy' => 'product_cat',
                            'field' => 'slug',
                            'terms' => $settings['tours_category'],
                        ],
                    ];
                }
                break;
                
            case 'tag':
                if (!empty($settings['tours_tag'])) {
                    $args['tax_query'] = [
                        [
                            'taxonomy' => 'product_tag',
                            'field' => 'slug',
                            'terms' => $settings['tours_tag'],
                        ],
                    ];
                }
                break;
                
            case 'attribute':
                $attr_name = $settings['guide_attribute_name'];
                $attr_value = $settings['guide_attribute_value'];
                if (!empty($attr_name) && !empty($attr_value)) {
                    $args['tax_query'] = [
                        [
                            'taxonomy' => $attr_name,
                            'field' => 'slug',
                            'terms' => $attr_value,
                        ],
                    ];
                }
                break;
                
            case 'manual':
                if (!empty($settings['manual_products'])) {
                    $args['post__in'] = $settings['manual_products'];
                    $args['orderby'] = 'post__in';
                }
                break;
        }

        $query = new \WP_Query($args);

        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $product = wc_get_product(get_the_ID());
                if (!$product) continue;

                $image_url = get_the_post_thumbnail_url(get_the_ID(), 'medium');
                $title = get_the_title();
                $permalink = get_permalink();
                $price = $product->get_price_html();
                
                // Get duration from attribute
                $duration = '';
                if ($settings['show_tour_duration'] === 'yes') {
                    $duration_attr = str_replace('pa_', '', $settings['duration_attribute']);
                    $duration = $product->get_attribute($duration_attr);
                }
                
                // Get rating
                $rating = $product->get_average_rating();
                ?>
                <a href="<?php echo esc_url($permalink); ?>" class="mst-guide-tour-card<?php echo $liquid_glass ? ' mst-liquid-glass' : ''; ?>">
                    <div class="mst-guide-tour-image">
                        <?php if ($image_url): ?>
                        <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($title); ?>">
                        <?php endif; ?>
                    </div>
                    <div class="mst-guide-tour-content">
                        <div class="mst-guide-tour-header">
                            <h3 class="mst-guide-tour-title"><?php echo esc_html($title); ?></h3>
                            <?php if ($settings['show_tour_rating'] === 'yes' && $rating > 0): ?>
                            <div class="mst-guide-tour-rating">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="<?php echo esc_attr($rating_color); ?>" stroke="none"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                                <span><?php echo esc_html(number_format($rating, 1)); ?></span>
                            </div>
                            <?php endif; ?>
                        </div>
                        <div class="mst-guide-tour-meta">
                            <?php if ($settings['show_tour_duration'] === 'yes' && $duration): ?>
                            <span class="mst-guide-tour-duration"><?php echo esc_html($duration); ?></span>
                            <?php endif; ?>
                            <?php if ($settings['show_tour_price'] === 'yes'): ?>
                            <span class="mst-guide-tour-price"><?php echo $price; ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                </a>
                <?php
            }
            wp_reset_postdata();
        } else {
            echo '<p class="mst-no-tours">Туры не найдены</p>';
        }
    }

    private function get_woo_reviews($settings) {
        if (!class_exists('WooCommerce')) {
            return [];
        }

        $reviews = [];
        $args = [
            'status' => 'approve',
            'type' => 'review',
            'number' => intval($settings['woo_reviews_count']),
        ];

        $comments = get_comments($args);
        
        foreach ($comments as $comment) {
            $rating = get_comment_meta($comment->comment_ID, 'rating', true);
            $reviews[] = [
                'author' => $comment->comment_author,
                'author_photo' => ['url' => get_avatar_url($comment->comment_author_email)],
                'rating' => $rating ?: 5,
                'text' => $comment->comment_content,
                'date' => human_time_diff(strtotime($comment->comment_date)) . ' назад',
                'tour_name' => get_the_title($comment->comment_post_ID),
                'photos' => [],
            ];
        }

        return $reviews;
    }
}
