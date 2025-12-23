<?php
namespace MST_Elementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

if (!defined('ABSPATH')) exit;

class Feature_Card extends Widget_Base {

    public function get_name() {
        return 'mst-feature-card';
    }

    public function get_title() {
        return __('Feature Card (With Image)', 'my-super-tour-elementor');
    }

    public function get_icon() {
        return 'eicon-image-box';
    }

    public function get_categories() {
        return ['my-super-tour'];
    }

    protected function register_controls() {
        // Content Section
        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Content', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'image',
            [
                'label' => __('Image', 'my-super-tour-elementor'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $this->add_control(
            'badge_text',
            [
                'label' => __('Badge Text', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Профессионально', 'my-super-tour-elementor'),
            ]
        );

        $this->add_control(
            'badge_icon',
            [
                'label' => __('Badge Icon', 'my-super-tour-elementor'),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-star',
                    'library' => 'solid',
                ],
            ]
        );

        $this->add_control(
            'title',
            [
                'label' => __('Title', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Маршруты разработаны архитектором!', 'my-super-tour-elementor'),
            ]
        );

        $this->add_control(
            'description',
            [
                'label' => __('Description', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXTAREA,
                'default' => __('С выстроенной сценографией и эффектами Вау! Минимум лестниц, максимум всемирноизвестных сокровищ.', 'my-super-tour-elementor'),
            ]
        );

        $this->add_control(
            'quote_text',
            [
                'label' => __('Quote Text (Optional)', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXTAREA,
                'description' => __('Optional quote to display below description', 'my-super-tour-elementor'),
            ]
        );

        $this->add_control(
            'quote_author',
            [
                'label' => __('Quote Author', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
            ]
        );

        $this->add_control(
            'link',
            [
                'label' => __('Link', 'my-super-tour-elementor'),
                'type' => Controls_Manager::URL,
            ]
        );

        $this->end_controls_section();

        // Badge Position
        $this->start_controls_section(
            'badge_position_section',
            [
                'label' => __('Badge Position', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_responsive_control(
            'badge_horizontal',
            [
                'label' => __('Horizontal Position', 'my-super-tour-elementor'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __('Left', 'my-super-tour-elementor'),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => __('Center', 'my-super-tour-elementor'),
                        'icon' => 'eicon-h-align-center',
                    ],
                    'right' => [
                        'title' => __('Right', 'my-super-tour-elementor'),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'default' => 'left',
            ]
        );

        $this->add_responsive_control(
            'badge_vertical',
            [
                'label' => __('Vertical Position', 'my-super-tour-elementor'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'top' => [
                        'title' => __('Top', 'my-super-tour-elementor'),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'bottom' => [
                        'title' => __('Bottom', 'my-super-tour-elementor'),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                ],
                'default' => 'top',
            ]
        );

        $this->add_responsive_control(
            'badge_offset_x',
            [
                'label' => __('Horizontal Offset', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => ['min' => -50, 'max' => 50],
                ],
                'default' => ['size' => 16, 'unit' => 'px'],
            ]
        );

        $this->add_responsive_control(
            'badge_offset_y',
            [
                'label' => __('Vertical Offset', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => ['min' => -50, 'max' => 50],
                ],
                'default' => ['size' => 16, 'unit' => 'px'],
            ]
        );

        $this->end_controls_section();

        // Text Alignment
        $this->start_controls_section(
            'text_alignment_section',
            [
                'label' => __('Text Alignment', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_responsive_control(
            'content_alignment',
            [
                'label' => __('Content Alignment', 'my-super-tour-elementor'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __('Left', 'my-super-tour-elementor'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => __('Center', 'my-super-tour-elementor'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => __('Right', 'my-super-tour-elementor'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'left',
                'selectors' => [
                    '{{WRAPPER}} .mst-feature-content' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Style - Card
        $this->start_controls_section(
            'style_card',
            [
                'label' => __('Card Style', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'card_bg_color',
            [
                'label' => __('Background Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .mst-feature-card' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'card_min_height',
            [
                'label' => __('Card Min Height (for equal heights)', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => ['min' => 300, 'max' => 700],
                ],
                'default' => ['size' => 450, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .mst-feature-card' => 'min-height: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .mst-feature-card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_height',
            [
                'label' => __('Image Height', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => ['min' => 150, 'max' => 500],
                ],
                'default' => ['size' => 280, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .mst-feature-image' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_border_radius',
            [
                'label' => __('Image Border Radius', 'my-super-tour-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'default' => [
                    'top' => '20',
                    'right' => '20',
                    'bottom' => '20',
                    'left' => '20',
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .mst-feature-image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Style - Badge
        $this->start_controls_section(
            'style_badge',
            [
                'label' => __('Badge Style', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'badge_liquid_glass',
            [
                'label' => __('Enable Liquid Glass', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'badge_glass_opacity',
            [
                'label' => __('Glass Opacity', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => ['min' => 0.1, 'max' => 1, 'step' => 0.05],
                ],
                'default' => ['size' => 0.9],
                'condition' => [
                    'badge_liquid_glass' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'badge_bg_color',
            [
                'label' => __('Background', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'hsl(270, 70%, 60%)',
                'condition' => [
                    'badge_liquid_glass!' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'badge_text_color',
            [
                'label' => __('Text Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#1a1a1a',
            ]
        );

        $this->add_control(
            'badge_icon_color',
            [
                'label' => __('Icon Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'hsl(270, 70%, 60%)',
            ]
        );

        $this->add_control(
            'badge_icon_liquid_glass',
            [
                'label' => __('Icon Liquid Glass Background', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'description' => __('Add liquid glass effect around icon', 'my-super-tour-elementor'),
            ]
        );

        $this->add_responsive_control(
            'badge_padding',
            [
                'label' => __('Padding', 'my-super-tour-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'default' => [
                    'top' => '8',
                    'right' => '16',
                    'bottom' => '8',
                    'left' => '16',
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .mst-feature-badge' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'badge_border_radius',
            [
                'label' => __('Border Radius', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => ['min' => 0, 'max' => 50],
                ],
                'default' => ['size' => 20, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .mst-feature-badge' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Style - Typography
        $this->start_controls_section(
            'style_typography',
            [
                'label' => __('Typography', 'my-super-tour-elementor'),
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
                    '{{WRAPPER}} .mst-feature-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .mst-feature-title',
            ]
        );

        $this->add_control(
            'description_color',
            [
                'label' => __('Description Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'hsl(0, 0%, 40%)',
                'selectors' => [
                    '{{WRAPPER}} .mst-feature-description' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'quote_color',
            [
                'label' => __('Quote Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'hsl(270, 70%, 50%)',
                'selectors' => [
                    '{{WRAPPER}} .mst-feature-quote' => 'color: {{VALUE}};',
                ],
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
            'enable_hover',
            [
                'label' => __('Enable Hover Effect', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'hover_scale',
            [
                'label' => __('Hover Scale', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => ['min' => 1, 'max' => 1.1, 'step' => 0.01],
                ],
                'default' => ['size' => 1.02],
                'selectors' => [
                    '{{WRAPPER}} .mst-feature-card:hover' => 'transform: translateY(-4px) scale({{SIZE}});',
                ],
                'condition' => [
                    'enable_hover' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $link_url = $settings['link']['url'] ?? '';
        $enable_hover = $settings['enable_hover'] === 'yes';
        $badge_liquid = $settings['badge_liquid_glass'] === 'yes';
        $badge_icon_liquid = $settings['badge_icon_liquid_glass'] === 'yes';
        $badge_opacity = $settings['badge_glass_opacity']['size'] ?? 0.9;
        $card_min_height = isset($settings['card_min_height']['size']) ? $settings['card_min_height']['size'] : 450;
        
        $badge_h = $settings['badge_horizontal'] ?? 'left';
        $badge_v = $settings['badge_vertical'] ?? 'top';
        $badge_offset_x = $settings['badge_offset_x']['size'] ?? 16;
        $badge_offset_y = $settings['badge_offset_y']['size'] ?? 16;
        $badge_text_color = $settings['badge_text_color'] ?? '#1a1a1a';
        $badge_icon_color = $settings['badge_icon_color'] ?? 'hsl(270, 70%, 60%)';
        $badge_bg_color = $settings['badge_bg_color'] ?? 'hsl(270, 70%, 60%)';
        
        // Calculate badge positioning
        $badge_style = 'position: absolute; display: inline-flex; align-items: center; gap: 8px;';
        if ($badge_v === 'top') {
            $badge_style .= ' top: ' . $badge_offset_y . 'px;';
        } else {
            $badge_style .= ' bottom: ' . $badge_offset_y . 'px;';
        }
        if ($badge_h === 'left') {
            $badge_style .= ' left: ' . $badge_offset_x . 'px;';
        } elseif ($badge_h === 'right') {
            $badge_style .= ' right: ' . $badge_offset_x . 'px;';
        } else {
            $badge_style .= ' left: 50%; transform: translateX(-50%);';
        }
        
        // Badge background style
        if ($badge_liquid) {
            $badge_style .= " background: rgba(255, 255, 255, {$badge_opacity}); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.3); box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08), inset 0 1px 2px rgba(255, 255, 255, 0.6);";
        } else {
            $badge_style .= " background: {$badge_bg_color};";
        }
        $badge_style .= " color: {$badge_text_color}; padding: 8px 16px; border-radius: 20px; font-size: 14px; font-weight: 500;";
        
        // Icon style with optional liquid glass
        $icon_wrapper_style = '';
        if ($badge_icon_liquid) {
            $icon_wrapper_style = 'display: inline-flex; align-items: center; justify-content: center; width: 28px; height: 28px; border-radius: 50%; background: rgba(255, 255, 255, 0.6); backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.4);';
        }
        
        $card_classes = ['mst-feature-card'];
        if ($enable_hover) {
            $card_classes[] = 'mst-feature-card-hover';
        }
        ?>
        <div class="<?php echo esc_attr(implode(' ', $card_classes)); ?>" style="min-height: <?php echo esc_attr($card_min_height); ?>px; display: flex; flex-direction: column; border-radius: 24px; overflow: hidden; box-shadow: 0 8px 32px rgba(0,0,0,0.08); transition: all 0.3s ease;">
            <?php if (!empty($link_url)): ?>
            <a href="<?php echo esc_url($link_url); ?>" class="mst-feature-link" style="text-decoration: none; color: inherit; display: flex; flex-direction: column; flex: 1;">
            <?php endif; ?>
            
            <div class="mst-feature-image-wrapper" style="position: relative; padding: 12px;">
                <div class="mst-feature-image" style="overflow: hidden; border-radius: 20px;">
                    <img src="<?php echo esc_url($settings['image']['url']); ?>" alt="<?php echo esc_attr($settings['title']); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                </div>
                
                <?php if (!empty($settings['badge_text'])): ?>
                <div class="mst-feature-badge" style="<?php echo esc_attr($badge_style); ?>">
                    <?php if (!empty($settings['badge_icon']['value'])): ?>
                        <?php if ($badge_icon_liquid): ?>
                        <span class="mst-feature-badge-icon-wrapper" style="<?php echo esc_attr($icon_wrapper_style); ?>">
                            <span class="mst-feature-badge-icon" style="color: <?php echo esc_attr($badge_icon_color); ?>; font-size: 14px; line-height: 1;">
                                <?php \Elementor\Icons_Manager::render_icon($settings['badge_icon'], ['aria-hidden' => 'true']); ?>
                            </span>
                        </span>
                        <?php else: ?>
                        <span class="mst-feature-badge-icon" style="color: <?php echo esc_attr($badge_icon_color); ?>;">
                            <?php \Elementor\Icons_Manager::render_icon($settings['badge_icon'], ['aria-hidden' => 'true']); ?>
                        </span>
                        <?php endif; ?>
                    <?php endif; ?>
                    <span><?php echo esc_html($settings['badge_text']); ?></span>
                </div>
                <?php endif; ?>
            </div>
            
            <div class="mst-feature-content" style="padding: 8px 16px 20px; flex: 1; display: flex; flex-direction: column;">
                <h3 class="mst-feature-title" style="font-size: 18px; font-weight: 700; margin: 0 0 8px 0; line-height: 1.3;"><?php echo esc_html($settings['title']); ?></h3>
                <p class="mst-feature-description" style="font-size: 14px; line-height: 1.5; margin: 0; color: #666;"><?php echo esc_html($settings['description']); ?></p>
                
                <?php if (!empty($settings['quote_text'])): ?>
                <blockquote class="mst-feature-quote" style="margin: 12px 0 0 0; padding: 0; font-style: italic; font-size: 14px; color: hsl(270, 70%, 50%);">
                    "<?php echo esc_html($settings['quote_text']); ?>"
                    <?php if (!empty($settings['quote_author'])): ?>
                    <cite style="font-style: normal; display: block; margin-top: 4px;">— <?php echo esc_html($settings['quote_author']); ?></cite>
                    <?php endif; ?>
                </blockquote>
                <?php endif; ?>
            </div>
            
            <?php if (!empty($link_url)): ?>
            </a>
            <?php endif; ?>
        </div>
        <?php
    }
}
