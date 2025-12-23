<?php
namespace MST_Elementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if (!defined('ABSPATH')) exit;

class Floating_Glass_Orbs extends Widget_Base {

    public function get_name() {
        return 'mst-floating-glass-orbs';
    }

    public function get_title() {
        return __('Floating Glass Orbs', 'my-super-tour-elementor');
    }

    public function get_icon() {
        return 'eicon-animation';
    }

    public function get_categories() {
        return ['my-super-tour'];
    }

    protected function register_controls() {
        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Settings', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'orb_count',
            [
                'label' => __('Number of Orbs', 'my-super-tour-elementor'),
                'type' => Controls_Manager::NUMBER,
                'default' => 3,
                'min' => 1,
                'max' => 10,
            ]
        );

        $this->add_control(
            'enable_parallax',
            [
                'label' => __('Enable Parallax', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'container_height',
            [
                'label' => __('Container Height', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'vh'],
                'range' => [
                    'px' => ['min' => 100, 'max' => 1000],
                    'vh' => ['min' => 10, 'max' => 100],
                ],
                'default' => ['size' => 400, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .mst-floating-orbs-container' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Style Tab
        $this->start_controls_section(
            'style_orbs',
            [
                'label' => __('Orbs Style', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'orb_color_1',
            [
                'label' => __('Orb Color 1 (Purple)', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'hsl(270, 70%, 75%)',
            ]
        );

        $this->add_control(
            'orb_color_2',
            [
                'label' => __('Orb Color 2 (Yellow)', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'hsl(45, 98%, 75%)',
            ]
        );

        $this->add_control(
            'orb_opacity',
            [
                'label' => __('Orb Opacity', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => ['min' => 0, 'max' => 1, 'step' => 0.05],
                ],
                'default' => ['size' => 0.5],
            ]
        );

        $this->add_responsive_control(
            'orb_size',
            [
                'label' => __('Orb Size', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => ['min' => 100, 'max' => 800],
                ],
                'default' => ['size' => 300, 'unit' => 'px'],
            ]
        );

        $this->add_control(
            'orb_blur',
            [
                'label' => __('Blur Amount', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => ['min' => 0, 'max' => 200],
                ],
                'default' => ['size' => 80, 'unit' => 'px'],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $orb_count = intval($settings['orb_count']);
        $parallax = $settings['enable_parallax'] === 'yes' ? 'data-parallax="true"' : '';
        $color1 = $settings['orb_color_1'] ?? 'hsl(270, 70%, 75%)';
        $color2 = $settings['orb_color_2'] ?? 'hsl(45, 98%, 75%)';
        $opacity = $settings['orb_opacity']['size'] ?? 0.5;
        $size = $settings['orb_size']['size'] ?? 300;
        $blur = $settings['orb_blur']['size'] ?? 80;

        // Predefined positions for orbs
        $positions = [
            ['top' => '10%', 'left' => '-5%'],
            ['top' => '60%', 'right' => '-10%'],
            ['bottom' => '5%', 'left' => '30%'],
            ['top' => '30%', 'left' => '50%'],
            ['bottom' => '20%', 'right' => '20%'],
            ['top' => '50%', 'left' => '10%'],
            ['top' => '20%', 'right' => '30%'],
            ['bottom' => '30%', 'left' => '60%'],
            ['top' => '70%', 'left' => '40%'],
            ['bottom' => '10%', 'right' => '5%'],
        ];
        ?>
        <div class="mst-floating-orbs-container" <?php echo $parallax; ?>>
            <?php for ($i = 0; $i < $orb_count; $i++): 
                $pos = $positions[$i % count($positions)];
                $color = ($i % 2 === 0) ? $color1 : $color2;
                $sizeVariation = $size * (0.8 + ($i * 0.15));
                $posStyle = '';
                foreach ($pos as $key => $value) {
                    $posStyle .= "$key: $value; ";
                }
            ?>
                <div class="mst-glass-orb" 
                     data-orb-index="<?php echo $i; ?>"
                     style="
                        <?php echo $posStyle; ?>
                        width: <?php echo $sizeVariation; ?>px;
                        height: <?php echo $sizeVariation; ?>px;
                        background: radial-gradient(circle, <?php echo $color; ?> 0%, transparent 70%);
                        opacity: <?php echo $opacity; ?>;
                        filter: blur(<?php echo $blur; ?>px);
                     "></div>
            <?php endfor; ?>
        </div>
        <?php
    }
}
