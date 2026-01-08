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
        // REVIEWS SOURCE SECTION
        // =========================
        $this->start_controls_section(
            'section_source',
            [
                'label' => __('Reviews Source', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'review_source',
            [
                'label' => __('Reviews Source', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SELECT,
                'default' => 'fake',
                'options' => [
                    'fake' => __('Fake Reviews (Manual)', 'my-super-tour-elementor'),
                    'live' => __('Real Reviews (API)', 'my-super-tour-elementor'),
                    'mixed' => __('Mixed (Real + Fake)', 'my-super-tour-elementor'),
                ],
                'description' => __('Choose where reviews come from', 'my-super-tour-elementor'),
            ]
        );

        $this->add_control(
            'live_reviews_count',
            [
                'label' => __('Live Reviews Count', 'my-super-tour-elementor'),
                'type' => Controls_Manager::NUMBER,
                'default' => 10,
                'min' => 1,
                'max' => 50,
                'condition' => ['review_source!' => 'fake'],
            ]
        );

        $this->add_control(
            'live_guide_id',
            [
                'label' => __('Guide ID', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'description' => __('Leave empty for all reviews, or enter guide user ID', 'my-super-tour-elementor'),
                'condition' => ['review_source!' => 'fake'],
            ]
        );

        $this->add_control(
            'live_product_id',
            [
                'label' => __('Product ID', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'description' => __('Leave empty for all reviews, or enter product ID', 'my-super-tour-elementor'),
                'condition' => ['review_source!' => 'fake'],
            ]
        );

        $this->end_controls_section();

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

        $this->add_control('show_header', ['label' => __('Show Header', 'my-super-tour-elementor'), 'type' => Controls_Manager::SWITCHER, 'default' => 'yes']);
        $this->add_control('title', ['label' => __('Title', 'my-super-tour-elementor'), 'type' => Controls_Manager::TEXT, 'default' => __('Отзывы', 'my-super-tour-elementor'), 'condition' => ['show_header' => 'yes']]);
        $this->add_control('subtitle', ['label' => __('Subtitle', 'my-super-tour-elementor'), 'type' => Controls_Manager::TEXT, 'default' => __('Что говорят наши путешественники', 'my-super-tour-elementor'), 'condition' => ['show_header' => 'yes']]);

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

        $this->add_control('show_stats_bar', ['label' => __('Show Stats Bar', 'my-super-tour-elementor'), 'type' => Controls_Manager::SWITCHER, 'default' => 'yes']);
        $this->add_control('avg_rating', ['label' => __('Average Rating', 'my-super-tour-elementor'), 'type' => Controls_Manager::TEXT, 'default' => '4.9', 'condition' => ['show_stats_bar' => 'yes']]);
        $this->add_control('avg_rating_label', ['label' => __('Rating Label', 'my-super-tour-elementor'), 'type' => Controls_Manager::TEXT, 'default' => __('Средний рейтинг', 'my-super-tour-elementor'), 'condition' => ['show_stats_bar' => 'yes']]);
        $this->add_control('total_reviews', ['label' => __('Total Reviews', 'my-super-tour-elementor'), 'type' => Controls_Manager::TEXT, 'default' => '2,487', 'condition' => ['show_stats_bar' => 'yes']]);
        $this->add_control('total_reviews_label', ['label' => __('Reviews Label', 'my-super-tour-elementor'), 'type' => Controls_Manager::TEXT, 'default' => __('Всего отзывов', 'my-super-tour-elementor'), 'condition' => ['show_stats_bar' => 'yes']]);
        $this->add_control('star_rating', ['label' => __('Star Rating', 'my-super-tour-elementor'), 'type' => Controls_Manager::NUMBER, 'min' => 1, 'max' => 5, 'default' => 5, 'condition' => ['show_stats_bar' => 'yes']]);

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

        $this->add_control('show_platform_stats', ['label' => __('Show Platform Stats', 'my-super-tour-elementor'), 'type' => Controls_Manager::SWITCHER, 'default' => 'yes']);

        $repeater = new Repeater();
        $repeater->add_control('platform', ['label' => __('Platform', 'my-super-tour-elementor'), 'type' => Controls_Manager::SELECT, 'default' => 'tripadvisor', 'options' => ['tripadvisor' => 'TripAdvisor', 'google' => 'Google Reviews', 'tripster' => 'Tripster', 'custom' => 'Custom']]);
        $repeater->add_control('platform_name', ['label' => __('Platform Name', 'my-super-tour-elementor'), 'type' => Controls_Manager::TEXT, 'condition' => ['platform' => 'custom']]);
        $repeater->add_control('platform_icon', ['label' => __('Platform Icon', 'my-super-tour-elementor'), 'type' => Controls_Manager::TEXT, 'default' => '🛫', 'condition' => ['platform' => 'custom']]);
        $repeater->add_control('rating_value', ['label' => __('Rating', 'my-super-tour-elementor'), 'type' => Controls_Manager::TEXT, 'default' => '4.9']);
        $repeater->add_control('rating_text', ['label' => __('Rating Text', 'my-super-tour-elementor'), 'type' => Controls_Manager::TEXT, 'default' => 'Рейтинг 4.9 из 5']);
        $repeater->add_control('reviews_count', ['label' => __('Reviews Count', 'my-super-tour-elementor'), 'type' => Controls_Manager::TEXT, 'default' => 'На основе 2,487 отзывов']);
        $repeater->add_control('star_rating', ['label' => __('Star Rating', 'my-super-tour-elementor'), 'type' => Controls_Manager::NUMBER, 'min' => 1, 'max' => 5, 'default' => 5]);
        $repeater->add_control('link', ['label' => __('Link URL', 'my-super-tour-elementor'), 'type' => Controls_Manager::URL]);

        $this->add_control('platforms', [
            'label' => __('Platforms', 'my-super-tour-elementor'),
            'type' => Controls_Manager::REPEATER,
            'fields' => $repeater->get_controls(),
            'default' => [
                ['platform' => 'tripster', 'rating_value' => '4.9', 'rating_text' => 'Рейтинг 4.9 из 5', 'reviews_count' => 'На основе 2,487 отзывов', 'star_rating' => 5],
                ['platform' => 'google', 'rating_value' => '4.8', 'rating_text' => 'Рейтинг 4.8 из 5', 'reviews_count' => 'На основе 1,234 отзывов', 'star_rating' => 5],
            ],
            'title_field' => '{{{ platform === "custom" ? platform_name : platform }}}',
            'condition' => ['show_platform_stats' => 'yes'],
        ]);

        $this->end_controls_section();

        // =========================
        // REVIEWS LIST SECTION (MANUAL)
        // =========================
        $this->start_controls_section(
            'section_reviews',
            [
                'label' => __('Manual Reviews', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
                'condition' => ['review_source' => ['fake', 'mixed']],
            ]
        );

        $this->add_control('show_reviews_list', ['label' => __('Show Reviews List', 'my-super-tour-elementor'), 'type' => Controls_Manager::SWITCHER, 'default' => 'yes']);

        $reviews_repeater = new Repeater();
        $reviews_repeater->add_control('author_name', ['label' => __('Author Name', 'my-super-tour-elementor'), 'type' => Controls_Manager::TEXT, 'default' => 'Анна Смирнова']);
        $reviews_repeater->add_control('author_initials', ['label' => __('Author Initials', 'my-super-tour-elementor'), 'type' => Controls_Manager::TEXT, 'default' => 'АС']);
        $reviews_repeater->add_control('review_date', ['label' => __('Date', 'my-super-tour-elementor'), 'type' => Controls_Manager::TEXT, 'default' => '2 дня назад']);
        $reviews_repeater->add_control('city', ['label' => __('City', 'my-super-tour-elementor'), 'type' => Controls_Manager::TEXT, 'default' => 'Прага']);
        $reviews_repeater->add_control('tour_name', ['label' => __('Tour Name', 'my-super-tour-elementor'), 'type' => Controls_Manager::TEXT, 'default' => 'Историческая прогулка по старому городу']);
        $reviews_repeater->add_control('review_text', ['label' => __('Review Text', 'my-super-tour-elementor'), 'type' => Controls_Manager::TEXTAREA, 'default' => 'Потрясающая экскурсия! Гид невероятно знающий и харизматичный.']);
        $reviews_repeater->add_control('rating', ['label' => __('Rating', 'my-super-tour-elementor'), 'type' => Controls_Manager::NUMBER, 'min' => 1, 'max' => 5, 'default' => 5]);
        $reviews_repeater->add_control('photos', ['label' => __('Photos (Gallery)', 'my-super-tour-elementor'), 'type' => Controls_Manager::GALLERY]);
        $reviews_repeater->add_control('likes_count', ['label' => __('Likes Count', 'my-super-tour-elementor'), 'type' => Controls_Manager::NUMBER, 'default' => 24]);

        $this->add_control('reviews', [
            'label' => __('Reviews', 'my-super-tour-elementor'),
            'type' => Controls_Manager::REPEATER,
            'fields' => $reviews_repeater->get_controls(),
            'default' => [
                ['author_name' => 'Анна Смирнова', 'author_initials' => 'АС', 'review_date' => '2 дня назад', 'city' => 'Прага', 'tour_name' => 'Историческая прогулка', 'review_text' => 'Потрясающая экскурсия!', 'rating' => 5, 'likes_count' => 24],
                ['author_name' => 'Дмитрий Ковалев', 'author_initials' => 'ДК', 'review_date' => '5 дней назад', 'city' => 'Пхукет', 'tour_name' => 'Морское приключение', 'review_text' => 'Лучший день нашего отпуска!', 'rating' => 5, 'likes_count' => 18],
            ],
            'title_field' => '{{{ author_name }}}',
        ]);

        $this->add_control('initial_reviews_count', ['label' => __('Initial Reviews to Show', 'my-super-tour-elementor'), 'type' => Controls_Manager::NUMBER, 'default' => 3, 'min' => 1, 'max' => 20]);

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

        $this->add_control('show_load_more', ['label' => __('Show Load More', 'my-super-tour-elementor'), 'type' => Controls_Manager::SWITCHER, 'default' => 'yes']);
        $this->add_control('load_more_text', ['label' => __('Button Text', 'my-super-tour-elementor'), 'type' => Controls_Manager::TEXT, 'default' => __('Загрузить еще', 'my-super-tour-elementor'), 'condition' => ['show_load_more' => 'yes']]);

        $this->end_controls_section();

        // =========================
        // STYLE SECTIONS
        // =========================
        $this->start_controls_section('section_style_header', ['label' => __('Header Style', 'my-super-tour-elementor'), 'tab' => Controls_Manager::TAB_STYLE]);
        $this->add_control('title_color', ['label' => __('Title Color', 'my-super-tour-elementor'), 'type' => Controls_Manager::COLOR, 'default' => '#1a1a2e', 'selectors' => ['{{WRAPPER}} .mst-reviews-section-title' => 'color: {{VALUE}};']]);
        $this->add_control('subtitle_color', ['label' => __('Subtitle Color', 'my-super-tour-elementor'), 'type' => Controls_Manager::COLOR, 'default' => '#666666', 'selectors' => ['{{WRAPPER}} .mst-reviews-section-subtitle' => 'color: {{VALUE}};']]);
        $this->end_controls_section();

        $this->start_controls_section('section_style_review_card', ['label' => __('Review Cards Style', 'my-super-tour-elementor'), 'tab' => Controls_Manager::TAB_STYLE]);
        $this->add_control('review_card_bg', ['label' => __('Card Background', 'my-super-tour-elementor'), 'type' => Controls_Manager::COLOR, 'default' => 'rgba(255, 255, 255, 0.25)', 'selectors' => ['{{WRAPPER}} .mst-review-card' => 'background-color: {{VALUE}};']]);
        $this->add_control('author_name_color', ['label' => __('Author Name Color', 'my-super-tour-elementor'), 'type' => Controls_Manager::COLOR, 'default' => '#1a1a2e', 'selectors' => ['{{WRAPPER}} .mst-review-author-name' => 'color: {{VALUE}};']]);
        $this->add_control('review_text_color', ['label' => __('Review Text Color', 'my-super-tour-elementor'), 'type' => Controls_Manager::COLOR, 'default' => '#333333', 'selectors' => ['{{WRAPPER}} .mst-review-text' => 'color: {{VALUE}};']]);
        $this->add_control('city_badge_bg', ['label' => __('City Badge Background', 'my-super-tour-elementor'), 'type' => Controls_Manager::COLOR, 'default' => 'rgba(139, 92, 246, 0.1)', 'selectors' => ['{{WRAPPER}} .mst-review-city-badge' => 'background-color: {{VALUE}};']]);
        $this->add_control('city_badge_color', ['label' => __('City Badge Color', 'my-super-tour-elementor'), 'type' => Controls_Manager::COLOR, 'default' => '#8B5CF6', 'selectors' => ['{{WRAPPER}} .mst-review-city-badge' => 'color: {{VALUE}};']]);
        $this->add_control('avatar_bg', ['label' => __('Avatar Background', 'my-super-tour-elementor'), 'type' => Controls_Manager::COLOR, 'default' => '#8B5CF6', 'selectors' => ['{{WRAPPER}} .mst-review-avatar' => 'background-color: {{VALUE}};']]);
        $this->add_control('star_color', ['label' => __('Star Color', 'my-super-tour-elementor'), 'type' => Controls_Manager::COLOR, 'default' => '#8B5CF6', 'selectors' => ['{{WRAPPER}} .mst-star-filled' => 'fill: {{VALUE}}; color: {{VALUE}};']]);
        $this->end_controls_section();

        $this->start_controls_section('section_style_button', ['label' => __('Button Style', 'my-super-tour-elementor'), 'tab' => Controls_Manager::TAB_STYLE]);
        $this->add_control('button_border_color', ['label' => __('Border Color', 'my-super-tour-elementor'), 'type' => Controls_Manager::COLOR, 'default' => '#8B5CF6', 'selectors' => ['{{WRAPPER}} .mst-load-more-btn' => 'border-color: {{VALUE}};']]);
        $this->add_control('button_text_color', ['label' => __('Text Color', 'my-super-tour-elementor'), 'type' => Controls_Manager::COLOR, 'default' => '#8B5CF6', 'selectors' => ['{{WRAPPER}} .mst-load-more-btn' => 'color: {{VALUE}};']]);
        $this->add_control('button_hover_bg', ['label' => __('Hover Background', 'my-super-tour-elementor'), 'type' => Controls_Manager::COLOR, 'default' => '#8B5CF6', 'selectors' => ['{{WRAPPER}} .mst-load-more-btn:hover' => 'background-color: {{VALUE}};']]);
        $this->add_control('button_hover_text', ['label' => __('Hover Text Color', 'my-super-tour-elementor'), 'type' => Controls_Manager::COLOR, 'default' => '#ffffff', 'selectors' => ['{{WRAPPER}} .mst-load-more-btn:hover' => 'color: {{VALUE}};']]);
        $this->end_controls_section();
    }

    /**
     * Get live reviews from database
     */
    private function get_live_reviews($settings) {
        global $wpdb;
        
        $limit = isset($settings['live_reviews_count']) ? intval($settings['live_reviews_count']) : 10;
        $guide_id = !empty($settings['live_guide_id']) ? intval($settings['live_guide_id']) : 0;
        $product_id = !empty($settings['live_product_id']) ? intval($settings['live_product_id']) : 0;
        
        $where_clauses = ["c.comment_approved = 1", "c.comment_type = 'review'"];
        $join_clauses = "JOIN {$wpdb->commentmeta} cm ON c.comment_ID = cm.comment_id AND cm.meta_key = 'rating'";
        
        if ($guide_id) {
            $join_clauses .= $wpdb->prepare(
                " JOIN {$wpdb->commentmeta} cm_guide ON c.comment_ID = cm_guide.comment_id AND cm_guide.meta_key = 'mst_guide_id' AND cm_guide.meta_value = %d",
                $guide_id
            );
        }
        
        if ($product_id) {
            $where_clauses[] = $wpdb->prepare("c.comment_post_ID = %d", $product_id);
        }
        
        $where = implode(' AND ', $where_clauses);
        
        $sql = "SELECT c.*, 
                       cm.meta_value as rating,
                       cm2.meta_value as user_city,
                       cm3.meta_value as review_photos
                FROM {$wpdb->comments} c
                {$join_clauses}
                LEFT JOIN {$wpdb->commentmeta} cm2 ON c.comment_ID = cm2.comment_id AND cm2.meta_key = 'mst_user_city'
                LEFT JOIN {$wpdb->commentmeta} cm3 ON c.comment_ID = cm3.comment_id AND cm3.meta_key = 'mst_review_photos'
                WHERE {$where}
                ORDER BY c.comment_date DESC
                LIMIT %d";
        
        $comments = $wpdb->get_results($wpdb->prepare($sql, $limit));
        $reviews = [];
        
        foreach ($comments as $c) {
            $name_parts = explode(' ', $c->comment_author);
            $initials = mb_substr($name_parts[0], 0, 1) . (isset($name_parts[1]) ? mb_substr($name_parts[1], 0, 1) : '');
            
            $product = wc_get_product($c->comment_post_ID);
            $tour_name = $product ? $product->get_name() : '';
            
            $city = '';
            if ($product) {
                $city = $product->get_attribute('pa_city');
            }
            if (empty($city) && !empty($c->user_city)) {
                $city = $c->user_city;
            }
            
            $photos = !empty($c->review_photos) ? maybe_unserialize($c->review_photos) : [];
            $gallery_photos = [];
            foreach ($photos as $photo_id) {
                $url = is_numeric($photo_id) ? wp_get_attachment_image_url($photo_id, 'medium') : $photo_id;
                if ($url) {
                    $gallery_photos[] = ['url' => $url];
                }
            }
            
            $reviews[] = [
                'author_name' => $c->comment_author,
                'author_initials' => mb_strtoupper($initials),
                'review_date' => human_time_diff(strtotime($c->comment_date)) . ' назад',
                'city' => $city,
                'tour_name' => $tour_name,
                'review_text' => $c->comment_content,
                'rating' => intval($c->rating) ?: 5,
                'photos' => $gallery_photos,
                'likes_count' => rand(10, 50),
            ];
        }
        
        return $reviews;
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        
        // Get reviews based on source
        $review_source = $settings['review_source'] ?? 'fake';
        $all_reviews = [];
        
        if ($review_source === 'live') {
            $all_reviews = $this->get_live_reviews($settings);
        } elseif ($review_source === 'mixed') {
            $live_reviews = $this->get_live_reviews($settings);
            $fake_reviews = isset($settings['reviews']) ? $settings['reviews'] : [];
            $all_reviews = array_merge($live_reviews, $fake_reviews);
        } else {
            $all_reviews = isset($settings['reviews']) ? $settings['reviews'] : [];
        }
        
        $initial_count = isset($settings['initial_reviews_count']) ? (int)$settings['initial_reviews_count'] : 3;
        $total_reviews = count($all_reviews);
        $has_more = $total_reviews > $initial_count;
        ?>
        <style>
        /* Reviews Section - Full Width Cards v2.0 */
        .mst-reviews-section {
            padding: 20px 0;
        }
        .mst-reviews-header {
            text-align: center;
            margin-bottom: 40px;
        }
        .mst-reviews-section-title {
            font-size: 36px;
            font-weight: 700;
            margin: 0 0 10px 0;
        }
        .mst-reviews-section-subtitle {
            font-size: 18px;
            margin: 0;
        }
        
        /* Stats Bar */
        .mst-reviews-stats-bar {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 40px;
            margin-bottom: 40px;
            padding: 20px;
            background: rgba(255,255,255,0.5);
            backdrop-filter: blur(10px);
            border-radius: 16px;
        }
        .mst-stats-bar-item {
            text-align: center;
        }
        .mst-stats-bar-value {
            font-size: 32px;
            font-weight: 700;
            color: #8B5CF6;
        }
        .mst-stats-bar-stars {
            display: flex;
            justify-content: center;
            gap: 2px;
            margin: 5px 0;
        }
        .mst-stats-bar-label {
            font-size: 14px;
            color: #666;
        }
        .mst-stats-bar-divider {
            width: 1px;
            height: 50px;
            background: rgba(0,0,0,0.1);
        }
        
        /* Platform Stats */
        .mst-platform-stats-grid {
            display: flex;
            gap: 20px;
            justify-content: center;
            flex-wrap: wrap;
            margin-bottom: 40px;
        }
        .mst-platform-card {
            background: rgba(255,255,255,0.6);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            padding: 24px;
            text-align: center;
            min-width: 200px;
            border: 1px solid rgba(255,255,255,0.3);
        }
        .mst-platform-icon svg {
            width: 48px;
            height: 48px;
        }
        .mst-platform-title {
            font-size: 16px;
            font-weight: 600;
            margin: 10px 0 8px 0;
        }
        .mst-platform-stars {
            display: flex;
            justify-content: center;
            gap: 2px;
            margin-bottom: 8px;
        }
        .mst-platform-rating-text {
            font-size: 14px;
            color: #333;
            margin: 0 0 4px 0;
        }
        .mst-platform-reviews-count {
            font-size: 12px;
            color: #666;
            margin: 0;
        }
        
        /* Reviews List */
        .mst-reviews-list {
            display: flex;
            flex-direction: column;
            gap: 24px;
        }
        
        /* REVIEW CARD - FULL WIDTH */
        .mst-review-card {
            width: 100%;
            background: rgba(255,255,255,0.6);
            backdrop-filter: blur(12px);
            border-radius: 20px;
            padding: 24px;
            border: 1px solid rgba(255,255,255,0.3);
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
        }
        .mst-review-card:hover {
            box-shadow: 0 8px 30px rgba(139, 92, 246, 0.1);
            transform: translateY(-2px);
        }
        .mst-review-card.mst-review-hidden {
            display: none;
        }
        
        /* Card Grid - Photos Left, Content Right - FULL WIDTH */
        .mst-review-card-grid {
            display: grid;
            grid-template-columns: auto 1fr;
            gap: 24px;
            width: 100%;
        }
        
        /* When no photos - content takes full width */
        .mst-review-card-grid:not(:has(.mst-review-photos-container)) {
            grid-template-columns: 1fr;
        }
        
        /* Content area - always stretches full width */
        .mst-review-card-content {
            flex: 1;
            min-width: 0;
            width: 100%;
        }
        .mst-review-card-content.mst-full-width {
            grid-column: 1 / -1;
        }
        
        /* Photos Container - FIXED for 1-2 photos */
        .mst-review-photos-container {
            display: flex;
            flex-direction: column;
            gap: 8px;
            width: 280px;
            flex-shrink: 0;
        }
        
        .mst-review-photo-main {
            width: 100%;
            height: 180px;
            border-radius: 12px;
            overflow: hidden;
        }
        .mst-review-photo-main img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        /* Thumbnails row - flex layout for 1-2 photos */
        .mst-review-photo-thumbs {
            display: flex;
            gap: 8px;
            width: 100%;
        }
        
        .mst-review-photo-thumb {
            flex: 1;
            min-width: 0;
            height: 80px;
            border-radius: 8px;
            overflow: hidden;
            position: relative;
        }
        .mst-review-photo-thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        /* When only 1 thumbnail - stretch to full width */
        .mst-review-photo-thumbs .mst-review-photo-thumb:only-child {
            flex: 1;
        }
        
        /* More photos overlay */
        .mst-photo-more-overlay {
            position: absolute;
            inset: 0;
            background: rgba(0,0,0,0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 18px;
            font-weight: 600;
        }
        
        /* Profile Glass Block */
        .mst-review-profile-glass {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 16px;
            flex-wrap: wrap;
        }
        .mst-review-author {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .mst-review-avatar {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            color: #fff;
            font-size: 16px;
        }
        .mst-review-author-name {
            font-size: 16px;
            font-weight: 600;
            margin: 0;
        }
        .mst-review-date {
            font-size: 13px;
            color: #888;
        }
        .mst-review-rating {
            display: flex;
            gap: 2px;
        }
        
        /* Meta Info */
        .mst-review-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 16px;
            margin-bottom: 16px;
        }
        .mst-review-meta-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
        }
        .mst-review-meta-label {
            color: #888;
        }
        .mst-review-city-badge,
        .mst-review-tour-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }
        .mst-review-city-badge {
            background: rgba(139, 92, 246, 0.1);
            color: #8B5CF6;
        }
        .mst-review-tour-badge {
            background: rgba(0,0,0,0.05);
            color: #555;
        }
        
        /* Review Text */
        .mst-review-text {
            font-size: 15px;
            line-height: 1.6;
            margin: 0 0 16px 0;
        }
        
        /* Footer */
        .mst-review-footer {
            display: flex;
            align-items: center;
            gap: 16px;
        }
        .mst-helpful-btn {
            display: flex;
            align-items: center;
            gap: 6px;
            background: none;
            border: 1px solid rgba(0,0,0,0.1);
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 13px;
            cursor: pointer;
            transition: all 0.2s;
            color: #666;
        }
        .mst-helpful-btn:hover {
            border-color: #8B5CF6;
            color: #8B5CF6;
        }
        
        /* Load More */
        .mst-reviews-load-more {
            text-align: center;
            margin-top: 30px;
        }
        .mst-load-more-btn {
            background: transparent;
            border: 2px solid #8B5CF6;
            color: #8B5CF6;
            padding: 14px 40px;
            border-radius: 30px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }
        .mst-load-more-btn:hover {
            background: #8B5CF6;
            color: #fff;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .mst-review-card-grid {
                grid-template-columns: 1fr;
            }
            .mst-review-photos-container {
                width: 100%;
            }
            .mst-review-photo-main {
                height: 200px;
            }
            .mst-reviews-stats-bar {
                flex-direction: column;
                gap: 20px;
            }
            .mst-stats-bar-divider {
                width: 80%;
                height: 1px;
            }
        }
        </style>
        
        <div class="mst-reviews-section">
            <?php if ($settings['show_header'] === 'yes'): ?>
            <div class="mst-reviews-header">
                <h1 class="mst-reviews-section-title"><?php echo esc_html($settings['title']); ?></h1>
                <p class="mst-reviews-section-subtitle"><?php echo esc_html($settings['subtitle']); ?></p>
            </div>
            <?php endif; ?>

            <?php if ($settings['show_stats_bar'] === 'yes'): ?>
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
            <div class="mst-platform-stats-grid mst-platform-top">
                <?php foreach ($settings['platforms'] as $platform): 
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
                            $platform_name = 'TripAdvisor';
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
                        $link_start = '<a href="' . esc_url($platform['link']['url']) . '"' . (!empty($platform['link']['is_external']) ? ' target="_blank"' : '') . '>';
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

            <?php if ($settings['show_reviews_list'] === 'yes' && !empty($all_reviews)): ?>
            <div class="mst-reviews-list" data-initial-count="<?php echo esc_attr($initial_count); ?>">
                <?php foreach ($all_reviews as $index => $review): 
                    $is_hidden = $index >= $initial_count;
                    $photos = isset($review['photos']) ? $review['photos'] : [];
                    $photo_count = count($photos);
                    $has_photos = $photo_count > 0;
                ?>
                <div class="mst-review-card <?php echo $is_hidden ? 'mst-review-hidden' : ''; ?>" data-review-index="<?php echo $index; ?>">
                    <div class="mst-review-card-grid">
                        <?php if ($has_photos): ?>
                        <div class="mst-review-photos-container">
                            <div class="mst-review-photo-main">
                                <img src="<?php echo esc_url($photos[0]['url']); ?>" alt="<?php echo esc_attr($review['tour_name']); ?>" class="mst-review-photo-img">
                            </div>
                            <?php if ($photo_count > 1): ?>
                            <div class="mst-review-photo-thumbs">
                                <?php if (isset($photos[1])): ?>
                                <div class="mst-review-photo-thumb">
                                    <img src="<?php echo esc_url($photos[1]['url']); ?>" alt="Photo 2">
                                </div>
                                <?php endif; ?>
                                <?php if (isset($photos[2])): ?>
                                <div class="mst-review-photo-thumb mst-photo-more-container">
                                    <img src="<?php echo esc_url($photos[2]['url']); ?>" alt="Photo 3">
                                    <?php if ($photo_count > 3): ?>
                                    <div class="mst-photo-more-overlay">+<?php echo $photo_count - 3; ?></div>
                                    <?php endif; ?>
                                </div>
                                <?php endif; ?>
                            </div>
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>
                        
                        <div class="mst-review-card-content <?php echo !$has_photos ? 'mst-full-width' : ''; ?>">
                            <div class="mst-review-profile-glass">
                                <div class="mst-review-author">
                                    <div class="mst-review-avatar"><?php echo esc_html($review['author_initials']); ?></div>
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
            <div class="mst-reviews-load-more">
                <button class="mst-load-more-btn mst-reviews-load-more-btn" data-total="<?php echo $total_reviews; ?>" data-initial="<?php echo $initial_count; ?>">
                    <?php echo esc_html($settings['load_more_text']); ?>
                </button>
            </div>
            <script>
            (function() {
                document.addEventListener('DOMContentLoaded', function() {
                    var btn = document.querySelector('.mst-reviews-load-more-btn');
                    if (!btn) return;
                    
                    var shown = parseInt(btn.getAttribute('data-initial'));
                    var total = parseInt(btn.getAttribute('data-total'));
                    
                    btn.addEventListener('click', function() {
                        var cards = document.querySelectorAll('.mst-review-card.mst-review-hidden');
                        var count = 0;
                        cards.forEach(function(card) {
                            if (count < 3) {
                                card.classList.remove('mst-review-hidden');
                                count++;
                            }
                        });
                        shown += count;
                        if (shown >= total) {
                            btn.style.display = 'none';
                        }
                    });
                });
            })();
            </script>
            <?php endif; ?>
            <?php endif; ?>
        </div>
        <?php
    }
}
