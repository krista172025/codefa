<?php
namespace MySuperTourElementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Icons_Manager;

if (!defined('ABSPATH')) exit;

class Reviews_Section extends Widget_Base {

    public function get_name() {
        return 'mst_reviews_section';
    }

    public function get_title() {
        return __('Reviews Section', 'my-super-tour-elementor');
    }

    public function get_icon() {
        return 'eicon-review';
    }

    public function get_categories() {
        return ['my-super-tour'];
    }

    public function get_keywords() {
        return ['reviews', 'section', 'testimonials', 'rating', 'stats'];
    }

    protected function register_controls() {
        // =========================
        // HEADER SECTION
        // =========================
        $this->start_controls_section(
            'section_header',
            [
                'label' => __('Header', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'show_header',
            [
                'label' => __('Show Header', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'title',
            [
                'label' => __('Title', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Отзывы', 'my-super-tour-elementor'),
                'condition' => ['show_header' => 'yes'],
            ]
        );

        $this->add_control(
            'subtitle',
            [
                'label' => __('Subtitle', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Что говорят наши путешественники', 'my-super-tour-elementor'),
                'condition' => ['show_header' => 'yes'],
            ]
        );

        $this->end_controls_section();

        // =========================
        // STATS BAR SECTION
        // =========================
        $this->start_controls_section(
            'section_stats_bar',
            [
                'label' => __('Stats Bar', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'show_stats_bar',
            [
                'label' => __('Show Stats Bar', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'avg_rating',
            [
                'label' => __('Average Rating', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => '4.9',
                'condition' => ['show_stats_bar' => 'yes'],
            ]
        );

        $this->add_control(
            'avg_rating_label',
            [
                'label' => __('Rating Label', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Средний рейтинг', 'my-super-tour-elementor'),
                'condition' => ['show_stats_bar' => 'yes'],
            ]
        );

        $this->add_control(
            'total_reviews',
            [
                'label' => __('Total Reviews', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => '2,487',
                'condition' => ['show_stats_bar' => 'yes'],
            ]
        );

        $this->add_control(
            'total_reviews_label',
            [
                'label' => __('Reviews Label', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Всего отзывов', 'my-super-tour-elementor'),
                'condition' => ['show_stats_bar' => 'yes'],
            ]
        );

        $this->add_control(
            'star_rating',
            [
                'label' => __('Star Rating', 'my-super-tour-elementor'),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 5,
                'default' => 5,
                'condition' => ['show_stats_bar' => 'yes'],
            ]
        );

        $this->end_controls_section();

        // =========================
        // PLATFORM STATS SECTION
        // =========================
        $this->start_controls_section(
            'section_platform_stats',
            [
                'label' => __('Platform Stats', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'show_platform_stats',
            [
                'label' => __('Show Platform Stats', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'platform',
            [
                'label' => __('Platform', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SELECT,
                'default' => 'tripadvisor',
                'options' => [
                    'tripadvisor' => __('TripAdvisor', 'my-super-tour-elementor'),
                    'google' => __('Google Reviews', 'my-super-tour-elementor'),
                    'tripster' => __('Tripster', 'my-super-tour-elementor'),
                    'custom' => __('Custom', 'my-super-tour-elementor'),
                ],
            ]
        );

        $repeater->add_control(
            'platform_name',
            [
                'label' => __('Platform Name', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => '',
                'condition' => ['platform' => 'custom'],
            ]
        );

        $repeater->add_control(
            'platform_icon',
            [
                'label' => __('Platform Icon', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => '🛫',
                'condition' => ['platform' => 'custom'],
            ]
        );

        $repeater->add_control(
            'rating_value',
            [
                'label' => __('Rating', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => '4.9',
            ]
        );

        $repeater->add_control(
            'rating_text',
            [
                'label' => __('Rating Text', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Рейтинг 4.9 из 5', 'my-super-tour-elementor'),
            ]
        );

        $repeater->add_control(
            'reviews_count',
            [
                'label' => __('Reviews Count', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('На основе 2,487 отзывов', 'my-super-tour-elementor'),
            ]
        );

        $repeater->add_control(
            'star_rating',
            [
                'label' => __('Star Rating', 'my-super-tour-elementor'),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 5,
                'default' => 5,
            ]
        );

        $repeater->add_control(
            'link',
            [
                'label' => __('Link URL', 'my-super-tour-elementor'),
                'type' => Controls_Manager::URL,
                'placeholder' => __('https://...', 'my-super-tour-elementor'),
            ]
        );

        $this->add_control(
            'platforms',
            [
                'label' => __('Platforms', 'my-super-tour-elementor'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'platform' => 'tripster',
                        'rating_value' => '4.9',
                        'rating_text' => 'Рейтинг 4.9 из 5',
                        'reviews_count' => 'На основе 2,487 отзывов',
                        'star_rating' => 5,
                    ],
                    [
                        'platform' => 'google',
                        'rating_value' => '4.8',
                        'rating_text' => 'Рейтинг 4.8 из 5',
                        'reviews_count' => 'На основе 1,234 отзывов',
                        'star_rating' => 5,
                    ],
                ],
                'title_field' => '{{{ platform === "custom" ? platform_name : platform }}}',
                'condition' => ['show_platform_stats' => 'yes'],
            ]
        );

        $this->end_controls_section();

        // =========================
        // REVIEWS LIST SECTION
        // =========================
        $this->start_controls_section(
            'section_reviews',
            [
                'label' => __('Reviews List', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'show_reviews_list',
            [
                'label' => __('Show Reviews List', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $reviews_repeater = new Repeater();

        $reviews_repeater->add_control(
            'author_name',
            [
                'label' => __('Author Name', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Анна Смирнова',
            ]
        );

        $reviews_repeater->add_control(
            'author_initials',
            [
                'label' => __('Author Initials', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'АС',
            ]
        );

        $reviews_repeater->add_control(
            'review_date',
            [
                'label' => __('Date', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => '2 дня назад',
            ]
        );

        $reviews_repeater->add_control(
            'city',
            [
                'label' => __('City', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Прага',
            ]
        );

        $reviews_repeater->add_control(
            'tour_name',
            [
                'label' => __('Tour Name', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Историческая прогулка по старому городу',
            ]
        );

        $reviews_repeater->add_control(
            'review_text',
            [
                'label' => __('Review Text', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXTAREA,
                'default' => 'Потрясающая экскурсия! Гид невероятно знающий и харизматичный. Узнали столько интересных фактов, которые не найдешь в путеводителях.',
            ]
        );

        $reviews_repeater->add_control(
            'rating',
            [
                'label' => __('Rating', 'my-super-tour-elementor'),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 5,
                'default' => 5,
            ]
        );

        $reviews_repeater->add_control(
            'photos',
            [
                'label' => __('Photos (Gallery)', 'my-super-tour-elementor'),
                'type' => Controls_Manager::GALLERY,
                'description' => __('Add multiple photos. First photo is main, others are thumbnails.', 'my-super-tour-elementor'),
            ]
        );

        $reviews_repeater->add_control(
            'likes_count',
            [
                'label' => __('Likes Count', 'my-super-tour-elementor'),
                'type' => Controls_Manager::NUMBER,
                'default' => 24,
            ]
        );

        $this->add_control(
            'reviews',
            [
                'label' => __('Reviews', 'my-super-tour-elementor'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $reviews_repeater->get_controls(),
                'default' => [
                    [
                        'author_name' => 'Анна Смирнова',
                        'author_initials' => 'АС',
                        'review_date' => '2 дня назад',
                        'city' => 'Прага',
                        'tour_name' => 'Историческая прогулка по старому городу',
                        'review_text' => 'Потрясающая экскурсия! Гид невероятно знающий и харизматичный.',
                        'rating' => 5,
                        'likes_count' => 24,
                    ],
                    [
                        'author_name' => 'Дмитрий Ковалев',
                        'author_initials' => 'ДК',
                        'review_date' => '5 дней назад',
                        'city' => 'Пхукет',
                        'tour_name' => 'Морское приключение на острова',
                        'review_text' => 'Лучший день нашего отпуска! Кристально чистая вода, профессиональная команда.',
                        'rating' => 5,
                        'likes_count' => 18,
                    ],
                    [
                        'author_name' => 'Елена Петрова',
                        'author_initials' => 'ЕП',
                        'review_date' => '1 неделю назад',
                        'city' => 'Париж',
                        'tour_name' => 'Романтический Монмартр',
                        'review_text' => 'Незабываемая прогулка по уютным улочкам Монмартра.',
                        'rating' => 5,
                        'likes_count' => 32,
                    ],
                    [
                        'author_name' => 'Михаил Иванов',
                        'author_initials' => 'МИ',
                        'review_date' => '2 недели назад',
                        'city' => 'Рим',
                        'tour_name' => 'Античный Рим',
                        'review_text' => 'Отличный гид, много интересных фактов!',
                        'rating' => 4,
                        'likes_count' => 15,
                    ],
                ],
                'title_field' => '{{{ author_name }}}',
                'condition' => ['show_reviews_list' => 'yes'],
            ]
        );

        $this->add_control(
            'initial_reviews_count',
            [
                'label' => __('Initial Reviews to Show', 'my-super-tour-elementor'),
                'type' => Controls_Manager::NUMBER,
                'default' => 3,
                'min' => 1,
                'max' => 20,
                'description' => __('Number of reviews visible before clicking Load More', 'my-super-tour-elementor'),
                'condition' => ['show_reviews_list' => 'yes'],
            ]
        );

        $this->end_controls_section();

        // =========================
        // LOAD MORE BUTTON
        // =========================
        $this->start_controls_section(
            'section_load_more',
            [
                'label' => __('Load More Button', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'show_load_more',
            [
                'label' => __('Show Load More', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'load_more_text',
            [
                'label' => __('Button Text', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Загрузить еще', 'my-super-tour-elementor'),
                'condition' => ['show_load_more' => 'yes'],
            ]
        );

        $this->end_controls_section();

        // =========================
        // STYLE: HEADER
        // =========================
        $this->start_controls_section(
            'section_style_header',
            [
                'label' => __('Header Style', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => __('Title Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#1a1a2e',
                'selectors' => [
                    '{{WRAPPER}} .mst-reviews-section-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .mst-reviews-section-title',
            ]
        );

        $this->add_control(
            'subtitle_color',
            [
                'label' => __('Subtitle Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#666666',
                'selectors' => [
                    '{{WRAPPER}} .mst-reviews-section-subtitle' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // =========================
        // STYLE: STATS BAR
        // =========================
        $this->start_controls_section(
            'section_style_stats_bar',
            [
                'label' => __('Stats Bar Style', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'stats_bar_bg',
            [
                'label' => __('Background Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(255, 255, 255, 0.3)',
                'selectors' => [
                    '{{WRAPPER}} .mst-reviews-stats-bar' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'stats_bar_blur',
            [
                'label' => __('Backdrop Blur', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => ['min' => 0, 'max' => 50],
                ],
                'default' => ['size' => 20, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .mst-reviews-stats-bar' => 'backdrop-filter: blur({{SIZE}}{{UNIT}}); -webkit-backdrop-filter: blur({{SIZE}}{{UNIT}});',
                ],
            ]
        );

        $this->add_control(
            'stats_value_color',
            [
                'label' => __('Value Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#1a1a2e',
                'selectors' => [
                    '{{WRAPPER}} .mst-stats-bar-value' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'star_color',
            [
                'label' => __('Star Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#8B5CF6',
                'selectors' => [
                    '{{WRAPPER}} .mst-star-filled' => 'fill: {{VALUE}}; color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // =========================
        // STYLE: PLATFORM CARDS
        // =========================
        $this->start_controls_section(
            'section_style_platform',
            [
                'label' => __('Platform Cards Style', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'platform_card_bg',
            [
                'label' => __('Card Background', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(255, 255, 255, 0.35)',
                'selectors' => [
                    '{{WRAPPER}} .mst-platform-card' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'platform_card_blur',
            [
                'label' => __('Backdrop Blur', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => ['min' => 0, 'max' => 50],
                ],
                'default' => ['size' => 20, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .mst-platform-card' => 'backdrop-filter: blur({{SIZE}}{{UNIT}}); -webkit-backdrop-filter: blur({{SIZE}}{{UNIT}});',
                ],
            ]
        );

        $this->add_control(
            'platform_title_color',
            [
                'label' => __('Platform Title Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#1a1a2e',
                'selectors' => [
                    '{{WRAPPER}} .mst-platform-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // =========================
        // STYLE: REVIEW CARDS
        // =========================
        $this->start_controls_section(
            'section_style_review_card',
            [
                'label' => __('Review Cards Style', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'review_card_bg',
            [
                'label' => __('Card Background', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(255, 255, 255, 0.25)',
                'selectors' => [
                    '{{WRAPPER}} .mst-review-card' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'review_card_blur',
            [
                'label' => __('Backdrop Blur', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => ['min' => 0, 'max' => 50],
                ],
                'default' => ['size' => 15, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .mst-review-card' => 'backdrop-filter: blur({{SIZE}}{{UNIT}}); -webkit-backdrop-filter: blur({{SIZE}}{{UNIT}});',
                ],
            ]
        );

        $this->add_control(
            'author_name_color',
            [
                'label' => __('Author Name Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#1a1a2e',
                'selectors' => [
                    '{{WRAPPER}} .mst-review-author-name' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'review_text_color',
            [
                'label' => __('Review Text Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#333333',
                'selectors' => [
                    '{{WRAPPER}} .mst-review-text' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'city_badge_bg',
            [
                'label' => __('City Badge Background', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(139, 92, 246, 0.1)',
                'selectors' => [
                    '{{WRAPPER}} .mst-review-city-badge' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'city_badge_color',
            [
                'label' => __('City Badge Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#8B5CF6',
                'selectors' => [
                    '{{WRAPPER}} .mst-review-city-badge' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'avatar_bg',
            [
                'label' => __('Avatar Background', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#8B5CF6',
                'selectors' => [
                    '{{WRAPPER}} .mst-review-avatar' => 'background-color: {{VALUE}};',
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
            'button_border_color',
            [
                'label' => __('Border Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#8B5CF6',
                'selectors' => [
                    '{{WRAPPER}} .mst-load-more-btn' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_text_color',
            [
                'label' => __('Text Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#8B5CF6',
                'selectors' => [
                    '{{WRAPPER}} .mst-load-more-btn' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_hover_bg',
            [
                'label' => __('Hover Background', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#8B5CF6',
                'selectors' => [
                    '{{WRAPPER}} .mst-load-more-btn:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_hover_text',
            [
                'label' => __('Hover Text Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .mst-load-more-btn:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="mst-reviews-section">
            <?php if ($settings['show_header'] === 'yes'): ?>
            <!-- Header -->
            <div class="mst-reviews-header">
                <h1 class="mst-reviews-section-title"><?php echo esc_html($settings['title']); ?></h1>
                <p class="mst-reviews-section-subtitle"><?php echo esc_html($settings['subtitle']); ?></p>
            </div>
            <?php endif; ?>

            <?php if ($settings['show_stats_bar'] === 'yes'): ?>
            <!-- Stats Bar -->
            <div class="mst-reviews-stats-bar">
                <div class="mst-stats-bar-item">
                    <div class="mst-stats-bar-value"><?php echo esc_html($settings['avg_rating']); ?></div>
                    <div class="mst-stats-bar-stars">
                        <?php for ($i = 0; $i < (int)$settings['star_rating']; $i++): ?>
                        <svg class="mst-star-filled" width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                        </svg>
                        <?php endfor; ?>
                    </div>
                    <div class="mst-stats-bar-label"><?php echo esc_html($settings['avg_rating_label']); ?></div>
                </div>
            <div class="mst-stats-bar-divider"></div>
                <div class="mst-stats-bar-item">
                    <div class="mst-stats-bar-value"><?php echo esc_html($settings['total_reviews']); ?></div>
                    <div class="mst-stats-bar-label"><?php echo esc_html($settings['total_reviews_label']); ?></div>
                </div>
            </div>
            <?php endif; ?>

            <?php if ($settings['show_platform_stats'] === 'yes' && !empty($settings['platforms'])): ?>
            <!-- Platform Stats (TripAdvisor + Google) - AT TOP -->
            <div class="mst-platform-stats-grid mst-platform-top">
                <?php foreach ($settings['platforms'] as $platform): ?>
                <?php
                    $platform_name = '';
                    $platform_icon = '';
                    
                    switch ($platform['platform']) {
                        case 'tripadvisor':
                            $platform_name = 'TripAdvisor';
                            $platform_icon = '<svg viewBox="0 0 24 24" width="48" height="48" fill="#00af87"><circle cx="6.5" cy="12" r="4.5"/><circle cx="17.5" cy="12" r="4.5"/><circle cx="6.5" cy="12" r="2" fill="#fff"/><circle cx="17.5" cy="12" r="2" fill="#fff"/><path d="M12 5c-3 0-5.5 2-6.5 4h13c-1-2-3.5-4-6.5-4z"/></svg>';
                            break;
                        case 'google':
                            $platform_name = 'Google Reviews';
                            $platform_icon = '<svg viewBox="0 0 24 24" width="48" height="48"><path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/><path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/><path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/><path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/></svg>';
                            break;
                        case 'tripster':
                            $platform_name = 'TripAdvisor'; // Changed from Tripster
                            $platform_icon = '<svg viewBox="0 0 24 24" width="48" height="48" fill="#00af87"><circle cx="6.5" cy="12" r="4.5"/><circle cx="17.5" cy="12" r="4.5"/><circle cx="6.5" cy="12" r="2" fill="#fff"/><circle cx="17.5" cy="12" r="2" fill="#fff"/><path d="M12 5c-3 0-5.5 2-6.5 4h13c-1-2-3.5-4-6.5-4z"/></svg>';
                            break;
                        case 'custom':
                            $platform_name = $platform['platform_name'];
                            $platform_icon = $platform['platform_icon'];
                            break;
                    }
                    
                    $link_start = '';
                    $link_end = '';
                    if (!empty($platform['link']['url'])) {
                        $link_start = '<a href="' . esc_url($platform['link']['url']) . '"' . ($platform['link']['is_external'] ? ' target="_blank"' : '') . '>';
                        $link_end = '</a>';
                    }
                ?>
                <?php echo $link_start; ?>
                <div class="mst-platform-card">
                    <div class="mst-platform-icon">
                        <?php 
                        if (strpos($platform_icon, '<svg') !== false) {
                            echo $platform_icon;
                        } else {
                            echo '<span class="mst-platform-emoji">' . esc_html($platform_icon) . '</span>';
                        }
                        ?>
                    </div>
                    <h3 class="mst-platform-title"><?php echo esc_html($platform_name); ?></h3>
                    <div class="mst-platform-stars">
                        <?php for ($i = 0; $i < (int)$platform['star_rating']; $i++): ?>
                        <svg class="mst-star-filled" width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                        </svg>
                        <?php endfor; ?>
                    </div>
                    <p class="mst-platform-rating-text"><?php echo esc_html($platform['rating_text']); ?></p>
                    <p class="mst-platform-reviews-count"><?php echo esc_html($platform['reviews_count']); ?></p>
                </div>
                <?php echo $link_end; ?>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>

            <?php if ($settings['show_reviews_list'] === 'yes' && !empty($settings['reviews'])): 
                $initial_count = isset($settings['initial_reviews_count']) ? (int)$settings['initial_reviews_count'] : 3;
                $total_reviews = count($settings['reviews']);
                $has_more = $total_reviews > $initial_count;
            ?>
            <!-- Reviews List -->
            <div class="mst-reviews-list" data-initial-count="<?php echo esc_attr($initial_count); ?>">
                <?php foreach ($settings['reviews'] as $index => $review): 
                    $is_hidden = $index >= $initial_count;
                    $photos = isset($review['photos']) ? $review['photos'] : [];
                    $photo_count = count($photos);
                ?>
                <div class="mst-review-card <?php echo $is_hidden ? 'mst-review-hidden' : ''; ?>" style="animation-delay: <?php echo $index * 100; ?>ms;" data-review-index="<?php echo $index; ?>">
                    <div class="mst-review-card-grid">
                        <?php if (!empty($photos)): ?>
                        <div class="mst-review-photos-container">
                            <!-- Main Photo -->
                            <div class="mst-review-photo-main">
                                <img src="<?php echo esc_url($photos[0]['url']); ?>" 
                                     alt="<?php echo esc_attr($review['tour_name']); ?>"
                                     class="mst-review-photo-img mst-lightbox-trigger"
                                     data-gallery-id="review-<?php echo $index; ?>"
                                     data-photo-index="0">
                            </div>
                            
                            <?php if ($photo_count > 1): ?>
                            <!-- Thumbnail Photos -->
                            <div class="mst-review-photo-thumbs">
                                <?php if (isset($photos[1])): ?>
                                <div class="mst-review-photo-thumb">
                                    <img src="<?php echo esc_url($photos[1]['url']); ?>" 
                                         alt="Photo 2"
                                         class="mst-lightbox-trigger"
                                         data-gallery-id="review-<?php echo $index; ?>"
                                         data-photo-index="1">
                                </div>
                                <?php endif; ?>
                                
                                <?php if (isset($photos[2])): ?>
                                <div class="mst-review-photo-thumb mst-photo-more-container">
                                    <img src="<?php echo esc_url($photos[2]['url']); ?>" 
                                         alt="Photo 3"
                                         class="mst-lightbox-trigger"
                                         data-gallery-id="review-<?php echo $index; ?>"
                                         data-photo-index="2">
                                    <?php if ($photo_count > 3): ?>
                                    <div class="mst-photo-more-overlay mst-lightbox-trigger" data-gallery-id="review-<?php echo $index; ?>" data-photo-index="2">
                                        +<?php echo $photo_count - 3; ?>
                                    </div>
                                    <?php endif; ?>
                                </div>
                                <?php endif; ?>
                            </div>
                            <?php endif; ?>
                            
                            <!-- Hidden photos for lightbox gallery -->
                            <div class="mst-review-gallery-data" style="display: none;">
                                <?php foreach ($photos as $pi => $photo): ?>
                                <img src="<?php echo esc_url($photo['url']); ?>" data-index="<?php echo $pi; ?>">
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <div class="mst-review-card-content <?php echo empty($photos) ? 'mst-full-width' : ''; ?>">
                            <!-- Author Profile - Liquid Glass Container -->
                            <div class="mst-review-profile-glass">
                                <div class="mst-review-author">
                                    <div class="mst-review-avatar">
                                        <?php echo esc_html($review['author_initials']); ?>
                                    </div>
                                    <div class="mst-review-author-info">
                                        <h3 class="mst-review-author-name"><?php echo esc_html($review['author_name']); ?></h3>
                                        <span class="mst-review-date"><?php echo esc_html($review['review_date']); ?></span>
                                    </div>
                                </div>
                                <div class="mst-review-rating">
                                    <?php for ($i = 0; $i < (int)$review['rating']; $i++): ?>
                                    <svg class="mst-star-filled" width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                    </svg>
                                    <?php endfor; ?>
                                </div>
                            </div>

                            <div class="mst-review-meta">
                                <div class="mst-review-meta-item">
                                    <span class="mst-review-meta-label">Город:</span>
                                    <span class="mst-review-city-badge"><?php echo esc_html($review['city']); ?></span>
                                </div>
                                <div class="mst-review-meta-item">
                                    <span class="mst-review-meta-label">Экскурсия:</span>
                                    <span class="mst-review-tour-badge"><?php echo esc_html($review['tour_name']); ?></span>
                                </div>
                            </div>

                            <p class="mst-review-text"><?php echo esc_html($review['review_text']); ?></p>

                            <div class="mst-review-footer">
                                <button class="mst-helpful-btn">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M14 9V5a3 3 0 0 0-3-3l-4 9v11h11.28a2 2 0 0 0 2-1.7l1.38-9a2 2 0 0 0-2-2.3zM7 22H4a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2h3"></path>
                                    </svg>
                                    <span>Полезно (<?php echo esc_html($review['likes_count']); ?>)</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            
            <?php if ($settings['show_load_more'] === 'yes' && $has_more): ?>
            <!-- Load More Button - Directly under reviews -->
            <div class="mst-reviews-load-more">
                <button class="mst-load-more-btn mst-reviews-load-more-btn" data-total="<?php echo $total_reviews; ?>" data-initial="<?php echo $initial_count; ?>">
                    <?php echo esc_html($settings['load_more_text']); ?>
                </button>
            </div>
            <?php endif; ?>
            <?php endif; ?>
        </div>
        
        <!-- Lightbox Modal for Photo Gallery -->
        <div class="mst-reviews-lightbox" style="display: none;">
            <div class="mst-lightbox-overlay"></div>
            <div class="mst-lightbox-container">
                <button class="mst-lightbox-close">&times;</button>
                <button class="mst-lightbox-prev">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m15 18-6-6 6-6"/></svg>
                </button>
                <div class="mst-lightbox-content">
                    <img src="" alt="Review photo" class="mst-lightbox-image">
                </div>
                <button class="mst-lightbox-next">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 18 6-6-6-6"/></svg>
                </button>
                <div class="mst-lightbox-counter">
                    <span class="mst-lightbox-current">1</span> / <span class="mst-lightbox-total">1</span>
                </div>
            </div>
        </div>
        <?php
    }
}
