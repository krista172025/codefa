<?php
namespace MST_Elementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;

if (!defined('ABSPATH')) exit;

class Guest_Carousel extends Widget_Base {

    public function get_name() {
        return 'mst-guest-carousel';
    }

    public function get_title() {
        return __('Guest Carousel (Наши гости)', 'my-super-tour-elementor');
    }

    public function get_icon() {
        return 'eicon-person';
    }

    public function get_categories() {
        return ['my-super-tour'];
    }

    public function get_script_depends() {
        return [];
    }

    protected function register_controls() {
        // Header Section
        $this->start_controls_section(
            'header_section',
            [
                'label' => __('Section Header', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'section_title',
            [
                'label' => __('Title', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Наши гости', 'my-super-tour-elementor'),
            ]
        );

        $this->add_control(
            'section_subtitle',
            [
                'label' => __('Subtitle', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXTAREA,
                'default' => __('Реальные фотографии путешественников, которые выбрали нас', 'my-super-tour-elementor'),
            ]
        );

        $this->end_controls_section();

        // Guests Section
        $this->start_controls_section(
            'guests_section',
            [
                'label' => __('Guests', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'guest_image',
            [
                'label' => __('Photo', 'my-super-tour-elementor'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $repeater->add_control(
            'guest_name',
            [
                'label' => __('Name', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Гость', 'my-super-tour-elementor'),
            ]
        );

        $repeater->add_control(
            'guest_city',
            [
                'label' => __('City', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Москва', 'my-super-tour-elementor'),
            ]
        );

        $repeater->add_control(
            'guest_date',
            [
                'label' => __('Date', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Октябрь 2024', 'my-super-tour-elementor'),
            ]
        );

        $repeater->add_control(
            'guest_link',
            [
                'label' => __('Link', 'my-super-tour-elementor'),
                'type' => Controls_Manager::URL,
            ]
        );

        $this->add_control(
            'guests',
            [
                'label' => __('Guests', 'my-super-tour-elementor'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    ['guest_name' => __('Семья Петровых', 'my-super-tour-elementor'), 'guest_city' => 'Москва', 'guest_date' => 'Октябрь 2024'],
                    ['guest_name' => __('Анна и Максим', 'my-super-tour-elementor'), 'guest_city' => 'Санкт-Петербург', 'guest_date' => 'Сентябрь 2024'],
                    ['guest_name' => __('Группа друзей', 'my-super-tour-elementor'), 'guest_city' => 'Казань', 'guest_date' => 'Август 2024'],
                    ['guest_name' => __('Николай и Вера', 'my-super-tour-elementor'), 'guest_city' => 'Екатеринбург', 'guest_date' => 'Июль 2024'],
                ],
                'title_field' => '{{{ guest_name }}}',
            ]
        );

        $this->end_controls_section();

        // Carousel Settings
        $this->start_controls_section(
            'carousel_section',
            [
                'label' => __('Carousel Settings', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_responsive_control(
            'slides_per_view',
            [
                'label' => __('Slides Per View', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                ],
                'default' => '4',
                'tablet_default' => '2',
                'mobile_default' => '1',
            ]
        );

        $this->end_controls_section();

        // Style - Section
        $this->start_controls_section(
            'style_section',
            [
                'label' => __('Section Style', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => __('Title Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#1a1a1a',
                'selectors' => [
                    '{{WRAPPER}} .mst-guest-section-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .mst-guest-section-title',
            ]
        );

        $this->add_control(
            'subtitle_color',
            [
                'label' => __('Subtitle Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'hsl(270, 70%, 60%)',
                'selectors' => [
                    '{{WRAPPER}} .mst-guest-section-subtitle' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Style - Cards
        $this->start_controls_section(
            'style_cards',
            [
                'label' => __('Card Style', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'card_height',
            [
                'label' => __('Card Height', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => ['min' => 200, 'max' => 600],
                ],
                'default' => ['size' => 320, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .mst-guest-card' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'card_border_radius',
            [
                'label' => __('Border Radius', 'my-super-tour-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'default' => [
                    'top' => '24',
                    'right' => '24',
                    'bottom' => '24',
                    'left' => '24',
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .mst-guest-card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'overlay_color',
            [
                'label' => __('Overlay Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'hsl(270, 70%, 40%)',
            ]
        );

        $this->end_controls_section();

        // Style - Guest Info
        $this->start_controls_section(
            'style_guest_info',
            [
                'label' => __('Guest Info Style', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'name_color',
            [
                'label' => __('Name Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .mst-guest-name' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'meta_color',
            [
                'label' => __('City/Date Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(255,255,255,0.9)',
                'selectors' => [
                    '{{WRAPPER}} .mst-guest-meta' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'icon_color',
            [
                'label' => __('Icon Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'hsl(45, 98%, 60%)',
                'selectors' => [
                    '{{WRAPPER}} .mst-guest-meta svg' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Arrow Style
        $this->start_controls_section(
            'style_arrows',
            [
                'label' => __('Navigation Arrows', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'arrow_bg_color',
            [
                'label' => __('Arrow Background', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .mst-guest-carousel-prev, {{WRAPPER}} .mst-guest-carousel-next' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'arrow_color',
            [
                'label' => __('Arrow Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#1a1a1a',
                'selectors' => [
                    '{{WRAPPER}} .mst-guest-carousel-prev, {{WRAPPER}} .mst-guest-carousel-next' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'arrow_hover_bg',
            [
                'label' => __('Arrow Hover Background', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'hsl(270, 70%, 60%)',
                'selectors' => [
                    '{{WRAPPER}} .mst-guest-carousel-prev:hover, {{WRAPPER}} .mst-guest-carousel-next:hover' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'arrow_hover_color',
            [
                'label' => __('Arrow Hover Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .mst-guest-carousel-prev:hover, {{WRAPPER}} .mst-guest-carousel-next:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $overlay_color = $settings['overlay_color'] ?? 'hsl(270, 70%, 40%)';
        $slides_per_view = $settings['slides_per_view'] ?? '4';
        $unique_id = 'mst-guest-carousel-' . $this->get_id();
        ?>
        <div class="mst-guest-carousel-section" id="<?php echo esc_attr($unique_id); ?>" data-slides="<?php echo esc_attr($slides_per_view); ?>">
            <?php if (!empty($settings['section_title']) || !empty($settings['section_subtitle'])): ?>
                <div class="mst-guest-section-header">
                    <?php if (!empty($settings['section_title'])): ?>
                        <h2 class="mst-guest-section-title"><?php echo esc_html($settings['section_title']); ?></h2>
                    <?php endif; ?>
                    <?php if (!empty($settings['section_subtitle'])): ?>
                        <p class="mst-guest-section-subtitle"><?php echo esc_html($settings['section_subtitle']); ?></p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <div class="mst-guest-carousel-container">
                <button class="mst-guest-carousel-prev" aria-label="Previous" onclick="mstGuestCarouselPrev('<?php echo esc_attr($unique_id); ?>')">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
                </button>
                
                <div class="mst-guest-carousel-wrapper">
                    <div class="mst-guest-carousel-track">
                        <?php foreach ($settings['guests'] as $index => $guest): 
                            $link_url = $guest['guest_link']['url'] ?? '';
                            $tag = !empty($link_url) ? 'a' : 'div';
                            $link_attrs = !empty($link_url) ? 'href="' . esc_url($link_url) . '"' : '';
                        ?>
                            <<?php echo $tag; ?> class="mst-guest-card" <?php echo $link_attrs; ?>>
                                <img src="<?php echo esc_url($guest['guest_image']['url']); ?>" alt="<?php echo esc_attr($guest['guest_name']); ?>" class="mst-guest-image">
                                <div class="mst-guest-overlay" style="background: linear-gradient(180deg, transparent 40%, <?php echo esc_attr($overlay_color); ?> 100%);"></div>
                                <div class="mst-guest-info">
                                    <h4 class="mst-guest-name"><?php echo esc_html($guest['guest_name']); ?></h4>
                                    <div class="mst-guest-meta">
                                        <?php if (!empty($guest['guest_city'])): ?>
                                            <span class="mst-guest-city">
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                                                <?php echo esc_html($guest['guest_city']); ?>
                                            </span>
                                        <?php endif; ?>
                                        <?php if (!empty($guest['guest_date'])): ?>
                                            <span class="mst-guest-date">
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                                                <?php echo esc_html($guest['guest_date']); ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </<?php echo $tag; ?>>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <button class="mst-guest-carousel-next" aria-label="Next" onclick="mstGuestCarouselNext('<?php echo esc_attr($unique_id); ?>')">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
                </button>
            </div>
        </div>
        
        <script>
            if (typeof mstGuestCarouselState === 'undefined') {
                var mstGuestCarouselState = {};
            }
            mstGuestCarouselState['<?php echo esc_attr($unique_id); ?>'] = { currentIndex: 0 };
            
            function mstGuestCarouselPrev(id) {
                var container = document.getElementById(id);
                var track = container.querySelector('.mst-guest-carousel-track');
                var cards = track.querySelectorAll('.mst-guest-card');
                var slides = parseInt(container.dataset.slides) || 4;
                var state = mstGuestCarouselState[id];
                
                if (state.currentIndex > 0) {
                    state.currentIndex--;
                    var cardWidth = cards[0].offsetWidth + 16;
                    track.style.transform = 'translateX(-' + (state.currentIndex * cardWidth) + 'px)';
                }
            }
            
            function mstGuestCarouselNext(id) {
                var container = document.getElementById(id);
                var track = container.querySelector('.mst-guest-carousel-track');
                var cards = track.querySelectorAll('.mst-guest-card');
                var slides = parseInt(container.dataset.slides) || 4;
                var state = mstGuestCarouselState[id];
                var maxIndex = Math.max(0, cards.length - slides);
                
                if (state.currentIndex < maxIndex) {
                    state.currentIndex++;
                    var cardWidth = cards[0].offsetWidth + 16;
                    track.style.transform = 'translateX(-' + (state.currentIndex * cardWidth) + 'px)';
                }
            }
        </script>
        <?php
    }
}
