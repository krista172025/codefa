<?php
namespace MST_Elementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

if (!defined('ABSPATH')) exit;

class Thank_You_Video extends Widget_Base {

    public function get_name() {
        return 'mst-thank-you-video';
    }

    public function get_title() {
        return __('Thank You Director Video', 'my-super-tour-elementor');
    }

    public function get_icon() {
        return 'eicon-youtube';
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
                'default' => __('Приветствие от директора', 'my-super-tour-elementor'),
            ]
        );

        $this->add_control(
            'section_subtitle',
            [
                'label' => __('Section Subtitle', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Персональное обращение от основателя My Super Tour', 'my-super-tour-elementor'),
            ]
        );

        $this->add_control(
            'video_url',
            [
                'label' => __('Video URL (YouTube/Vimeo)', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'description' => __('Enter YouTube or Vimeo embed URL', 'my-super-tour-elementor'),
            ]
        );

        $this->add_control(
            'thumbnail',
            [
                'label' => __('Video Thumbnail', 'my-super-tour-elementor'),
                'type' => Controls_Manager::MEDIA,
            ]
        );

        $this->add_control(
            'play_text',
            [
                'label' => __('Play Button Text', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Смотреть приветствие (1 минута)', 'my-super-tour-elementor'),
            ]
        );

        $this->add_control(
            'director_name',
            [
                'label' => __('Director Name', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Александр Петров', 'my-super-tour-elementor'),
            ]
        );

        $this->add_control(
            'director_title',
            [
                'label' => __('Director Title', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Основатель и Директор', 'my-super-tour-elementor'),
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
                'label' => __('Enable Liquid Glass Card', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => __('Title Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#1a1a1a',
                'selectors' => [
                    '{{WRAPPER}} .mst-video-title' => 'color: {{VALUE}};',
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
                    '{{WRAPPER}} .mst-video-subtitle' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'play_button_color',
            [
                'label' => __('Play Button Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'hsl(270, 70%, 60%)',
            ]
        );

        $this->add_control(
            'overlay_color_1',
            [
                'label' => __('Overlay Gradient Start', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'hsl(270, 70%, 60%)',
            ]
        );

        $this->add_control(
            'overlay_color_2',
            [
                'label' => __('Overlay Gradient End', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'hsl(45, 98%, 60%)',
            ]
        );

        $this->add_responsive_control(
            'video_aspect_ratio',
            [
                'label' => __('Video Aspect Ratio', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '16/9' => '16:9',
                    '4/3' => '4:3',
                    '21/9' => '21:9',
                ],
                'default' => '16/9',
            ]
        );

        $this->add_responsive_control(
            'border_radius',
            [
                'label' => __('Border Radius', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => ['px' => ['min' => 0, 'max' => 50]],
                'default' => ['size' => 24, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .mst-video-card' => 'border-radius: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .mst-video-container' => 'border-radius: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .mst-video-section' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $liquid_glass = $settings['enable_liquid_glass'] === 'yes';
        $play_color = $settings['play_button_color'] ?? 'hsl(270, 70%, 60%)';
        $overlay_1 = $settings['overlay_color_1'] ?? 'hsl(270, 70%, 60%)';
        $overlay_2 = $settings['overlay_color_2'] ?? 'hsl(45, 98%, 60%)';
        $aspect = $settings['video_aspect_ratio'] ?? '16/9';
        $video_url = $settings['video_url'] ?? '';
        $thumbnail_url = !empty($settings['thumbnail']['url']) ? $settings['thumbnail']['url'] : '';
        
        $unique_id = 'mst-video-' . $this->get_id();
        ?>
        <section class="mst-video-section">
            <div class="mst-video-header">
                <h2 class="mst-video-title"><?php echo esc_html($settings['section_title']); ?></h2>
                <p class="mst-video-subtitle"><?php echo esc_html($settings['section_subtitle']); ?></p>
            </div>
            
            <div class="mst-video-card<?php echo $liquid_glass ? ' mst-video-card-liquid-glass' : ''; ?>">
                <div class="mst-video-container" id="<?php echo esc_attr($unique_id); ?>" style="aspect-ratio: <?php echo esc_attr($aspect); ?>;">
                    <div class="mst-video-thumbnail" style="background: linear-gradient(135deg, <?php echo esc_attr($overlay_1); ?>60, <?php echo esc_attr($overlay_2); ?>60);">
                        <?php if ($thumbnail_url): ?>
                            <img src="<?php echo esc_url($thumbnail_url); ?>" alt="Video thumbnail">
                        <?php endif; ?>
                        
                        <div class="mst-video-play-overlay" onclick="mstPlayVideo<?php echo esc_attr($this->get_id()); ?>()">
                            <div class="mst-video-play-button" style="background: rgba(255,255,255,0.95);">
                                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="<?php echo esc_attr($play_color); ?>" stroke="none"><polygon points="5 3 19 12 5 21 5 3"/></svg>
                            </div>
                            <p class="mst-video-play-text"><?php echo esc_html($settings['play_text']); ?></p>
                        </div>
                        
                        <div class="mst-video-director-badge">
                            <strong><?php echo esc_html($settings['director_name']); ?></strong>
                            <span><?php echo esc_html($settings['director_title']); ?></span>
                        </div>
                    </div>
                    
                    <?php if ($video_url): ?>
                    <div class="mst-video-iframe" style="display: none;">
                        <iframe src="" data-src="<?php echo esc_url($video_url); ?>?autoplay=1" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen style="width: 100%; height: 100%;"></iframe>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </section>
        
        <script>
        function mstPlayVideo<?php echo esc_attr($this->get_id()); ?>() {
            var container = document.getElementById('<?php echo esc_attr($unique_id); ?>');
            var thumbnail = container.querySelector('.mst-video-thumbnail');
            var iframe = container.querySelector('.mst-video-iframe');
            if (iframe) {
                var iframeEl = iframe.querySelector('iframe');
                iframeEl.src = iframeEl.dataset.src;
                thumbnail.style.display = 'none';
                iframe.style.display = 'block';
            }
        }
        </script>
        <?php
    }
}
