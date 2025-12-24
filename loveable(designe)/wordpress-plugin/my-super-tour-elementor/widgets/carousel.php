<?php
namespace MST_Elementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;

if (!defined('ABSPATH')) exit;

class Carousel extends Widget_Base {

    public function get_name() {
        return 'mst-carousel';
    }

    public function get_title() {
        return __('Carousel', 'my-super-tour-elementor');
    }

    public function get_icon() {
        return 'eicon-slider-push';
    }

    public function get_categories() {
        return ['my-super-tour'];
    }

    protected function register_controls() {
        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Carousel Items', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'image',
            [
                'label' => __('Image', 'my-super-tour-elementor'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $repeater->add_control(
            'title',
            [
                'label' => __('Title', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Item Title', 'my-super-tour-elementor'),
            ]
        );

        $repeater->add_control(
            'subtitle',
            [
                'label' => __('Subtitle', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Item Subtitle', 'my-super-tour-elementor'),
            ]
        );

        $repeater->add_control(
            'badge_text',
            [
                'label' => __('Badge Text', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'placeholder' => '156 экскурсий',
            ]
        );

        $repeater->add_control(
            'link',
            [
                'label' => __('Link', 'my-super-tour-elementor'),
                'type' => Controls_Manager::URL,
                'default' => [
                    'url' => '#',
                ],
            ]
        );

        $this->add_control(
            'carousel_items',
            [
                'label' => __('Items', 'my-super-tour-elementor'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    ['title' => __('Paris', 'my-super-tour-elementor'), 'subtitle' => 'Франция'],
                    ['title' => __('Rome', 'my-super-tour-elementor'), 'subtitle' => 'Италия'],
                    ['title' => __('Barcelona', 'my-super-tour-elementor'), 'subtitle' => 'Испания'],
                    ['title' => __('Prague', 'my-super-tour-elementor'), 'subtitle' => 'Чехия'],
                ],
                'title_field' => '{{{ title }}}',
            ]
        );

        $this->end_controls_section();

        // Overlay Settings
        $this->start_controls_section(
            'overlay_section',
            [
                'label' => __('Overlay Settings', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'enable_overlay',
            [
                'label' => __('Enable Gradient Overlay', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'overlay_color',
            [
                'label' => __('Overlay Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'hsl(270, 70%, 60%)',
                'condition' => [
                    'enable_overlay' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'overlay_opacity',
            [
                'label' => __('Overlay Opacity', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => ['min' => 0, 'max' => 1, 'step' => 0.05],
                ],
                'default' => ['size' => 0.5],
                'condition' => [
                    'enable_overlay' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'badge_bg_color',
            [
                'label' => __('Badge Background Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(255, 255, 255, 0.2)',
                'description' => __('Background color for badge text', 'my-super-tour-elementor'),
            ]
        );

        $this->end_controls_section();

        // Arrows Settings
        $this->start_controls_section(
            'arrows_section',
            [
                'label' => __('Arrow Settings', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'arrows_inside',
            [
                'label' => __('Arrows Inside Container', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'description' => __('Place arrows inside the carousel container', 'my-super-tour-elementor'),
            ]
        );

        $this->add_control(
            'arrows_offset',
            [
                'label' => __('Arrows Offset', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => ['min' => -50, 'max' => 100],
                ],
                'default' => ['size' => 16, 'unit' => 'px'],
                'condition' => [
                    'arrows_inside' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();

        // Style Tab
        $this->start_controls_section(
            'style_carousel',
            [
                'label' => __('Carousel Style', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'item_gap',
            [
                'label' => __('Gap Between Items', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'range' => [
                    'px' => ['min' => 0, 'max' => 100],
                ],
                'default' => ['size' => 16, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .mst-carousel-track' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'arrow_bg_color',
            [
                'label' => __('Arrow Background', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .mst-carousel-prev, {{WRAPPER}} .mst-carousel-next' => 'background-color: {{VALUE}};',
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
                    '{{WRAPPER}} .mst-carousel-prev, {{WRAPPER}} .mst-carousel-next' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'arrow_hover_bg_color',
            [
                'label' => __('Arrow Hover Background', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'hsl(270, 70%, 60%)',
                'selectors' => [
                    '{{WRAPPER}} .mst-carousel-prev:hover, {{WRAPPER}} .mst-carousel-next:hover' => 'background-color: {{VALUE}};',
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
                    '{{WRAPPER}} .mst-carousel-prev:hover, {{WRAPPER}} .mst-carousel-next:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Item Style
        $this->start_controls_section(
            'style_item',
            [
                'label' => __('Item Style', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'item_height',
            [
                'label' => __('Item Height', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => ['min' => 150, 'max' => 500],
                ],
                'default' => ['size' => 320, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .mst-carousel-item' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'item_border_radius',
            [
                'label' => __('Border Radius', 'my-super-tour-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default' => [
                    'top' => 24,
                    'right' => 24,
                    'bottom' => 24,
                    'left' => 24,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .mst-carousel-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'items_per_view',
            [
                'label' => __('Items Per View', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SELECT,
                'default' => '4',
                'options' => [
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                    '5' => '5',
                ],
            ]
        );

        $this->end_controls_section();

        // Typography
        $this->start_controls_section(
            'style_typography',
            [
                'label' => __('Typography', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'label' => __('Title Typography', 'my-super-tour-elementor'),
                'selector' => '{{WRAPPER}} .mst-carousel-content h4',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'subtitle_typography',
                'label' => __('Subtitle Typography', 'my-super-tour-elementor'),
                'selector' => '{{WRAPPER}} .mst-carousel-content p',
            ]
        );

        $this->end_controls_section();

        // Hover Effects
        $this->start_controls_section(
            'style_hover',
            [
                'label' => __('Hover Effects', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'hover_scale',
            [
                'label' => __('Hover Scale', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => ['min' => 1, 'max' => 1.2, 'step' => 0.01],
                ],
                'default' => ['size' => 1.02],
                'selectors' => [
                    '{{WRAPPER}} .mst-carousel-item:hover' => 'transform: scale({{SIZE}});',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $enable_overlay = $settings['enable_overlay'] === 'yes';
        $arrows_inside = $settings['arrows_inside'] === 'yes';
        $arrows_offset = isset($settings['arrows_offset']['size']) ? $settings['arrows_offset']['size'] : 16;
        $overlay_color = !empty($settings['overlay_color']) ? $settings['overlay_color'] : 'hsl(270, 70%, 60%)';
        $overlay_opacity = isset($settings['overlay_opacity']['size']) ? $settings['overlay_opacity']['size'] : 0.5;
        $items_per_view = isset($settings['items_per_view']) ? $settings['items_per_view'] : 4;
        $badge_bg_color = !empty($settings['badge_bg_color']) ? $settings['badge_bg_color'] : 'rgba(255, 255, 255, 0.2)';
        
        $container_class = 'mst-carousel-container';
        if ($arrows_inside) {
            $container_class .= ' mst-carousel-arrows-inside';
        }
        ?>
        <div class="<?php echo esc_attr($container_class); ?>" data-items="<?php echo esc_attr($items_per_view); ?>">
            <div class="mst-carousel-wrapper">
                <div class="mst-carousel-track">
                    <?php foreach ($settings['carousel_items'] as $item): 
                        $link_url = !empty($item['link']['url']) ? $item['link']['url'] : '#';
                    ?>
                        <a href="<?php echo esc_url($link_url); ?>" class="mst-carousel-item">
                            <img src="<?php echo esc_url($item['image']['url']); ?>" alt="<?php echo esc_attr($item['title']); ?>">
                            <?php if ($enable_overlay): ?>
                                <div class="mst-carousel-overlay" style="background: linear-gradient(180deg, transparent 40%, <?php echo esc_attr($overlay_color); ?> 100%); opacity: <?php echo esc_attr($overlay_opacity); ?>;"></div>
                            <?php endif; ?>
                            <div class="mst-carousel-content">
                                <h4><?php echo esc_html($item['title']); ?></h4>
                                <?php if (!empty($item['subtitle'])): ?>
                                    <p><?php echo esc_html($item['subtitle']); ?></p>
                                <?php endif; ?>
                                <?php if (!empty($item['badge_text'])): ?>
                                    <span class="mst-carousel-badge" style="background: <?php echo esc_attr($badge_bg_color); ?>;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                                        <?php echo esc_html($item['badge_text']); ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
                <!-- Arrows inside wrapper -->
                <?php if ($arrows_inside): ?>
                <button class="mst-carousel-prev mst-arrow-inside" style="left: <?php echo esc_attr($arrows_offset); ?>px;" aria-label="Previous">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                </button>
                <button class="mst-carousel-next mst-arrow-inside" style="right: <?php echo esc_attr($arrows_offset); ?>px;" aria-label="Next">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
                </button>
                <?php endif; ?>
            </div>
            <!-- Arrows outside wrapper -->
            <?php if (!$arrows_inside): ?>
            <button class="mst-carousel-prev" aria-label="Previous">‹</button>
            <button class="mst-carousel-next" aria-label="Next">›</button>
            <?php endif; ?>
        </div>
        <?php
    }
}
