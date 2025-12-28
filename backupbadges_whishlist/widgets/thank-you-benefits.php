<?php
namespace MST_Elementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;

if (!defined('ABSPATH')) exit;

class Thank_You_Benefits extends Widget_Base {

    public function get_name() {
        return 'mst-thank-you-benefits';
    }

    public function get_title() {
        return __('Thank You Benefits Section', 'my-super-tour-elementor');
    }

    public function get_icon() {
        return 'eicon-star';
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
            'section_title',
            [
                'label' => __('Section Title', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Ваши бонусы и подарки', 'my-super-tour-elementor'),
            ]
        );

        $this->add_control(
            'section_subtitle',
            [
                'label' => __('Section Subtitle', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Специально для вас мы подготовили приятные сюрпризы', 'my-super-tour-elementor'),
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'benefit_icon',
            [
                'label' => __('Icon', 'my-super-tour-elementor'),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-gift',
                    'library' => 'solid',
                ],
            ]
        );

        $repeater->add_control(
            'benefit_text',
            [
                'label' => __('Benefit Text', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Бесплатный путеводитель по городу в подарок', 'my-super-tour-elementor'),
            ]
        );

        $repeater->add_control(
            'icon_color',
            [
                'label' => __('Icon Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'hsl(45, 98%, 50%)',
            ]
        );

        $this->add_control(
            'benefits',
            [
                'label' => __('Benefits', 'my-super-tour-elementor'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    ['benefit_text' => 'Бесплатный путеводитель по городу в подарок', 'icon_color' => 'hsl(45, 98%, 50%)'],
                    ['benefit_text' => 'Скидка 10% на следующее бронирование', 'icon_color' => 'hsl(270, 70%, 60%)'],
                    ['benefit_text' => '+500 бонусных баллов на ваш счет', 'icon_color' => 'hsl(160, 60%, 50%)'],
                    ['benefit_text' => 'Бесплатная отмена за 24 часа до тура', 'icon_color' => 'hsl(45, 98%, 50%)'],
                ],
                'title_field' => '{{{ benefit_text }}}',
            ]
        );

        $this->end_controls_section();

        // Style Section
        $this->start_controls_section(
            'style_section',
            [
                'label' => __('Style', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'enable_liquid_glass',
            [
                'label' => __('Enable Liquid Glass Cards', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'card_bg_color',
            [
                'label' => __('Card Background', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'condition' => ['enable_liquid_glass' => ''],
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => __('Title Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#1a1a1a',
                'selectors' => [
                    '{{WRAPPER}} .mst-benefits-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'subtitle_color',
            [
                'label' => __('Subtitle Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'hsl(0, 0%, 50%)',
                'selectors' => [
                    '{{WRAPPER}} .mst-benefits-subtitle' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'text_color',
            [
                'label' => __('Benefit Text Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#1a1a1a',
                'selectors' => [
                    '{{WRAPPER}} .mst-benefit-text' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'columns',
            [
                'label' => __('Columns', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                ],
                'default' => '2',
            ]
        );

        $this->add_responsive_control(
            'gap',
            [
                'label' => __('Gap', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => ['px' => ['min' => 8, 'max' => 48]],
                'default' => ['size' => 16, 'unit' => 'px'],
            ]
        );

        $this->add_responsive_control(
            'card_padding',
            [
                'label' => __('Card Padding', 'my-super-tour-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'default' => [
                    'top' => '24',
                    'right' => '24',
                    'bottom' => '24',
                    'left' => '24',
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .mst-benefit-card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'card_border_radius',
            [
                'label' => __('Card Border Radius', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => ['px' => ['min' => 0, 'max' => 40]],
                'default' => ['size' => 20, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .mst-benefit-card' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'section_padding',
            [
                'label' => __('Section Padding', 'my-super-tour-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'default' => [
                    'top' => '60',
                    'right' => '24',
                    'bottom' => '60',
                    'left' => '24',
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .mst-benefits-section' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $liquid_glass = $settings['enable_liquid_glass'] === 'yes';
        $columns = $settings['columns'] ?? '2';
        $gap = $settings['gap']['size'] ?? 16;
        $card_bg = $settings['card_bg_color'] ?? '#ffffff';
        ?>
        <section class="mst-benefits-section">
            <div class="mst-benefits-header">
                <h2 class="mst-benefits-title"><?php echo esc_html($settings['section_title']); ?></h2>
                <p class="mst-benefits-subtitle"><?php echo esc_html($settings['section_subtitle']); ?></p>
            </div>
            
            <div class="mst-benefits-grid" style="display: grid; grid-template-columns: repeat(<?php echo esc_attr($columns); ?>, 1fr); gap: <?php echo esc_attr($gap); ?>px;">
                <?php foreach ($settings['benefits'] as $benefit): 
                    $icon_color = $benefit['icon_color'] ?? 'hsl(270, 70%, 60%)';
                    $card_style = '';
                    if (!$liquid_glass) {
                        $card_style = 'background-color: ' . esc_attr($card_bg) . ';';
                    }
                ?>
                    <div class="mst-benefit-card<?php echo $liquid_glass ? ' mst-benefit-card-liquid-glass' : ''; ?>" style="<?php echo $card_style; ?>">
                        <div class="mst-benefit-icon" style="background: <?php echo esc_attr($icon_color); ?>20; width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            <?php if (!empty($benefit['benefit_icon']['value'])): ?>
                                <span style="color: <?php echo esc_attr($icon_color); ?>; font-size: 20px;">
                                    <?php \Elementor\Icons_Manager::render_icon($benefit['benefit_icon'], ['aria-hidden' => 'true']); ?>
                                </span>
                            <?php endif; ?>
                        </div>
                        <p class="mst-benefit-text"><?php echo esc_html($benefit['benefit_text']); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
        <?php
    }
}
