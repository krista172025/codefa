<?php
namespace MST_Elementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

if (!defined('ABSPATH')) exit;

class Logo_Explainer extends Widget_Base {

    public function get_name() {
        return 'mst-logo-explainer';
    }

    public function get_title() {
        return __('Logo Explainer (MST)', 'my-super-tour-elementor');
    }

    public function get_icon() {
        return 'eicon-logo';
    }

    public function get_categories() {
        return ['my-super-tour'];
    }

    protected function register_controls() {
        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Logo & Text', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'logo_image',
            [
                'label' => __('Logo Image', 'my-super-tour-elementor'),
                'type' => Controls_Manager::MEDIA,
            ]
        );

        $this->add_control(
            'letter_m_main',
            ['label' => __('M - Main Text', 'my-super-tour-elementor'), 'type' => Controls_Manager::TEXT, 'default' => 'My']
        );
        $this->add_control(
            'letter_m_sub',
            ['label' => __('M - Subtitle', 'my-super-tour-elementor'), 'type' => Controls_Manager::TEXT, 'default' => 'Ваши']
        );
        $this->add_control(
            'letter_s_main',
            ['label' => __('S - Main Text', 'my-super-tour-elementor'), 'type' => Controls_Manager::TEXT, 'default' => 'Super']
        );
        $this->add_control(
            'letter_s_sub',
            ['label' => __('S - Subtitle', 'my-super-tour-elementor'), 'type' => Controls_Manager::TEXT, 'default' => 'Лучшие']
        );
        $this->add_control(
            'letter_t_main',
            ['label' => __('T - Main Text', 'my-super-tour-elementor'), 'type' => Controls_Manager::TEXT, 'default' => 'Tour']
        );
        $this->add_control(
            'letter_t_sub',
            ['label' => __('T - Subtitle', 'my-super-tour-elementor'), 'type' => Controls_Manager::TEXT, 'default' => 'Экскурсии']
        );

        $this->add_control(
            'animation_type',
            [
                'label' => __('Animation Effect', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SELECT,
                'default' => 'glow_ring',
                'options' => [
                    'glow_ring' => __('✨ Glow Ring', 'my-super-tour-elementor'),
                    'shimmer' => __('💫 Shimmer Wave', 'my-super-tour-elementor'),
                    'pulse_shadow' => __('💜 Pulse Shadow', 'my-super-tour-elementor'),
                    'rotate_glow' => __('🔄 Rotating Glow', 'my-super-tour-elementor'),
                    'particles' => __('🎆 Particle Burst', 'my-super-tour-elementor'),
                    'none' => __('None', 'my-super-tour-elementor'),
                ],
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

        $this->add_responsive_control(
            'logo_size',
            [
                'label' => __('Logo Size', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => ['px' => ['min' => 40, 'max' => 200]],
                'default' => ['size' => 80, 'unit' => 'px'],
            ]
        );

        $this->add_responsive_control(
            'main_font_size',
            [
                'label' => __('Main Text Font Size', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => ['px' => ['min' => 16, 'max' => 120]],
                'default' => ['size' => 48, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .mst-logo-letter-main' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'sub_font_size',
            [
                'label' => __('Subtitle Font Size', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => ['px' => ['min' => 8, 'max' => 32]],
                'default' => ['size' => 14, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .mst-logo-letter-sub' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'text_animation_m',
            [
                'label' => __('M - Text Animation', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SELECT,
                'default' => 'wave',
                'options' => [
                    'none' => __('None', 'my-super-tour-elementor'),
                    'wave' => __('🌊 Wave Float', 'my-super-tour-elementor'),
                    'pulse' => __('💓 Gentle Pulse', 'my-super-tour-elementor'),
                    'glow' => __('✨ Color Glow', 'my-super-tour-elementor'),
                    'bounce' => __('🎾 Soft Bounce', 'my-super-tour-elementor'),
                    'swing' => __('🎢 Swing', 'my-super-tour-elementor'),
                    'shake' => __('📳 Shake', 'my-super-tour-elementor'),
                    'rubberband' => __('🎈 Rubberband', 'my-super-tour-elementor'),
                ],
            ]
        );
        
        $this->add_control(
            'text_animation_s',
            [
                'label' => __('S - Text Animation', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SELECT,
                'default' => 'glow',
                'options' => [
                    'none' => __('None', 'my-super-tour-elementor'),
                    'wave' => __('🌊 Wave Float', 'my-super-tour-elementor'),
                    'pulse' => __('💓 Gentle Pulse', 'my-super-tour-elementor'),
                    'glow' => __('✨ Color Glow', 'my-super-tour-elementor'),
                    'bounce' => __('🎾 Soft Bounce', 'my-super-tour-elementor'),
                    'swing' => __('🎢 Swing', 'my-super-tour-elementor'),
                    'shake' => __('📳 Shake', 'my-super-tour-elementor'),
                    'rubberband' => __('🎈 Rubberband', 'my-super-tour-elementor'),
                ],
            ]
        );
        
        $this->add_control(
            'text_animation_t',
            [
                'label' => __('T - Text Animation', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SELECT,
                'default' => 'bounce',
                'options' => [
                    'none' => __('None', 'my-super-tour-elementor'),
                    'wave' => __('🌊 Wave Float', 'my-super-tour-elementor'),
                    'pulse' => __('💓 Gentle Pulse', 'my-super-tour-elementor'),
                    'glow' => __('✨ Color Glow', 'my-super-tour-elementor'),
                    'bounce' => __('🎾 Soft Bounce', 'my-super-tour-elementor'),
                    'swing' => __('🎢 Swing', 'my-super-tour-elementor'),
                    'shake' => __('📳 Shake', 'my-super-tour-elementor'),
                    'rubberband' => __('🎈 Rubberband', 'my-super-tour-elementor'),
                ],
            ]
        );

        $this->add_control(
            'letter_m_color',
            ['label' => __('M Color', 'my-super-tour-elementor'), 'type' => Controls_Manager::COLOR, 'default' => 'hsl(270, 70%, 60%)']
        );
        $this->add_control(
            'letter_s_color',
            ['label' => __('S Color', 'my-super-tour-elementor'), 'type' => Controls_Manager::COLOR, 'default' => 'hsl(45, 98%, 50%)']
        );
        $this->add_control(
            'letter_t_color',
            ['label' => __('T Color', 'my-super-tour-elementor'), 'type' => Controls_Manager::COLOR, 'default' => 'hsl(270, 70%, 60%)']
        );
        $this->add_control(
            'subtitle_color',
            ['label' => __('Subtitle Color', 'my-super-tour-elementor'), 'type' => Controls_Manager::COLOR, 'default' => '#888888']
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            ['name' => 'main_typography', 'label' => __('Main Text', 'my-super-tour-elementor'), 'selector' => '{{WRAPPER}} .mst-logo-letter-main']
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            ['name' => 'sub_typography', 'label' => __('Subtitle', 'my-super-tour-elementor'), 'selector' => '{{WRAPPER}} .mst-logo-letter-sub']
        );

        $this->end_controls_section();
    }

    protected function render() {
        $s = $this->get_settings_for_display();
        $logo_size = $s['logo_size']['size'] ?? 80;
        $anim = $s['animation_type'] ?? 'glow_ring';
        $anim_m = $s['text_animation_m'] ?? 'wave';
        $anim_s = $s['text_animation_s'] ?? 'glow';
        $anim_t = $s['text_animation_t'] ?? 'bounce';
        $unique_id = 'mst-logo-explainer-' . $this->get_id();
        ?>
        <style>
            #<?php echo esc_attr($unique_id); ?> { text-align: center; }
            #<?php echo esc_attr($unique_id); ?> .mst-logo-img-wrap {
                width: <?php echo esc_attr($logo_size); ?>px;
                height: <?php echo esc_attr($logo_size); ?>px;
                margin: 0 auto 24px;
                border-radius: 50%;
                background: #fff;
                box-shadow: 0 8px 32px rgba(0,0,0,0.1);
                display: flex; align-items: center; justify-content: center;
                position: relative;
                overflow: visible;
                cursor: pointer;
                transition: transform 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
            }
            #<?php echo esc_attr($unique_id); ?> .mst-logo-img-wrap:hover {
                transform: scale(1.08);
            }
            #<?php echo esc_attr($unique_id); ?> .mst-logo-img-wrap img { width: 70%; height: 70%; object-fit: contain; }
            
            /* Letters container - inline with subtitles between */
            #<?php echo esc_attr($unique_id); ?> .mst-logo-letters { 
                display: flex; 
                justify-content: center; 
                align-items: baseline; 
                gap: 8px; 
                flex-wrap: wrap; 
            }
            #<?php echo esc_attr($unique_id); ?> .mst-logo-word {
                display: inline-flex;
                align-items: baseline;
            }
            #<?php echo esc_attr($unique_id); ?> .mst-logo-letter-main { 
                font-size: 48px; 
                font-weight: 800; 
                line-height: 1; 
                display: inline;
            }
            #<?php echo esc_attr($unique_id); ?> .mst-logo-letter-main .mst-first-letter {
                display: inline;
            }
            #<?php echo esc_attr($unique_id); ?> .mst-logo-letter-main .mst-rest-letters {
                display: inline;
                font-size: 0.55em;
                vertical-align: baseline;
            }
            /* Subtitle between words - inline */
            #<?php echo esc_attr($unique_id); ?> .mst-logo-letter-sub { 
                font-size: 14px; 
                color: <?php echo esc_attr($s['subtitle_color']); ?>; 
                display: inline;
                margin: 0 12px;
                font-style: italic;
                opacity: 0.8;
            }
            
            /* ===== ANIMATION KEYFRAMES ===== */
            @keyframes mst-text-wave-<?php echo esc_attr($unique_id); ?> {
                0%, 100% { transform: translateY(0); }
                50% { transform: translateY(-6px); }
            }
            @keyframes mst-text-pulse-<?php echo esc_attr($unique_id); ?> {
                0%, 100% { transform: scale(1); opacity: 1; }
                50% { transform: scale(1.08); opacity: 0.85; }
            }
            @keyframes mst-text-glow-<?php echo esc_attr($unique_id); ?> {
                0% { text-shadow: 0 0 0 transparent; filter: brightness(1); }
                100% { text-shadow: 0 0 20px currentColor; filter: brightness(1.2); }
            }
            @keyframes mst-text-bounce-<?php echo esc_attr($unique_id); ?> {
                0%, 100% { transform: translateY(0); }
                30% { transform: translateY(-10px); }
                50% { transform: translateY(-5px); }
                70% { transform: translateY(-8px); }
            }
            @keyframes mst-text-swing-<?php echo esc_attr($unique_id); ?> {
                0%, 100% { transform: rotate(0deg); }
                25% { transform: rotate(5deg); }
                75% { transform: rotate(-5deg); }
            }
            @keyframes mst-text-shake-<?php echo esc_attr($unique_id); ?> {
                0%, 100% { transform: translateX(0); }
                10%, 30%, 50%, 70%, 90% { transform: translateX(-3px); }
                20%, 40%, 60%, 80% { transform: translateX(3px); }
            }
            @keyframes mst-text-rubberband-<?php echo esc_attr($unique_id); ?> {
                0%, 100% { transform: scaleX(1) scaleY(1); }
                30% { transform: scaleX(1.15) scaleY(0.85); }
                40% { transform: scaleX(0.9) scaleY(1.1); }
                50% { transform: scaleX(1.05) scaleY(0.95); }
                65% { transform: scaleX(0.98) scaleY(1.02); }
            }
            
            /* ===== INDIVIDUAL LETTER ANIMATIONS ===== */
            <?php if ($anim_m !== 'none'): ?>
            #<?php echo esc_attr($unique_id); ?> .mst-logo-word.mst-word-m { 
                animation: mst-text-<?php echo esc_attr($anim_m); ?>-<?php echo esc_attr($unique_id); ?> <?php echo $anim_m === 'shake' ? '0.8s' : ($anim_m === 'rubberband' ? '1.5s' : '2.5s'); ?> ease-in-out infinite<?php echo in_array($anim_m, ['glow']) ? ' alternate' : ''; ?>; 
            }
            <?php endif; ?>
            
            <?php if ($anim_s !== 'none'): ?>
            #<?php echo esc_attr($unique_id); ?> .mst-logo-word.mst-word-s { 
                animation: mst-text-<?php echo esc_attr($anim_s); ?>-<?php echo esc_attr($unique_id); ?> <?php echo $anim_s === 'shake' ? '0.8s' : ($anim_s === 'rubberband' ? '1.5s' : '2.5s'); ?> ease-in-out infinite<?php echo in_array($anim_s, ['glow']) ? ' alternate' : ''; ?>; 
                animation-delay: 0.3s;
            }
            <?php endif; ?>
            
            <?php if ($anim_t !== 'none'): ?>
            #<?php echo esc_attr($unique_id); ?> .mst-logo-word.mst-word-t { 
                animation: mst-text-<?php echo esc_attr($anim_t); ?>-<?php echo esc_attr($unique_id); ?> <?php echo $anim_t === 'shake' ? '0.8s' : ($anim_t === 'rubberband' ? '1.5s' : '2.5s'); ?> ease-in-out infinite<?php echo in_array($anim_t, ['glow']) ? ' alternate' : ''; ?>; 
                animation-delay: 0.6s;
            }
            <?php endif; ?>
            
            /* ===== MOBILE RESPONSIVE - ALIGNED BASELINE ===== */
            /* Font sizes now controlled via Elementor responsive controls */
            @media (max-width: 768px) {
                #<?php echo esc_attr($unique_id); ?> .mst-logo-letters {
                    display: flex;
                    flex-direction: row;
                    align-items: center;
                    justify-content: center;
                    gap: 0;
                    flex-wrap: nowrap;
                }
                #<?php echo esc_attr($unique_id); ?> .mst-logo-word {
                    display: inline-flex;
                    align-items: center;
                }
                #<?php echo esc_attr($unique_id); ?> .mst-logo-letter-sub {
                    display: none;
                }
                #<?php echo esc_attr($unique_id); ?> .mst-logo-letter-main {
                    line-height: 1;
                }
                #<?php echo esc_attr($unique_id); ?> .mst-logo-letter-main .mst-first-letter {
                    font-size: inherit;
                }
                #<?php echo esc_attr($unique_id); ?> .mst-logo-letter-main .mst-rest-letters {
                    font-size: 0.65em;
                }
            }
            /* ===== PREMIUM HOVER EFFECTS ===== */
            
            /* Glow Ring Effect */
            <?php if ($anim === 'glow_ring'): ?>
            #<?php echo esc_attr($unique_id); ?> .mst-logo-img-wrap::before {
                content: '';
                position: absolute;
                inset: -6px;
                border-radius: 50%;
                background: conic-gradient(from 0deg, hsl(270, 70%, 60%), hsl(45, 98%, 50%), hsl(270, 70%, 60%));
                opacity: 0;
                transition: opacity 0.4s ease;
                z-index: -1;
            }
            #<?php echo esc_attr($unique_id); ?> .mst-logo-img-wrap::after {
                content: '';
                position: absolute;
                inset: -3px;
                border-radius: 50%;
                background: #fff;
                z-index: -1;
            }
            #<?php echo esc_attr($unique_id); ?> .mst-logo-img-wrap:hover::before {
                opacity: 1;
                animation: mst-rotate-glow 3s linear infinite;
            }
            @keyframes mst-rotate-glow {
                from { transform: rotate(0deg); }
                to { transform: rotate(360deg); }
            }
            <?php endif; ?>
            
            /* Shimmer Wave Effect */
            <?php if ($anim === 'shimmer'): ?>
            #<?php echo esc_attr($unique_id); ?> .mst-logo-img-wrap::before {
                content: '';
                position: absolute;
                inset: 0;
                border-radius: 50%;
                background: linear-gradient(135deg, transparent 40%, rgba(255,255,255,0.8) 50%, transparent 60%);
                background-size: 200% 200%;
                opacity: 0;
                transition: opacity 0.3s ease;
            }
            #<?php echo esc_attr($unique_id); ?> .mst-logo-img-wrap:hover::before {
                opacity: 1;
                animation: mst-shimmer 1.5s ease-in-out infinite;
            }
            @keyframes mst-shimmer {
                0% { background-position: 200% 200%; }
                100% { background-position: -200% -200%; }
            }
            <?php endif; ?>
            
            /* Pulse Shadow Effect */
            <?php if ($anim === 'pulse_shadow'): ?>
            #<?php echo esc_attr($unique_id); ?> .mst-logo-img-wrap {
                transition: box-shadow 0.4s ease, transform 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
            }
            #<?php echo esc_attr($unique_id); ?> .mst-logo-img-wrap:hover {
                box-shadow: 
                    0 0 0 4px rgba(147, 51, 234, 0.2),
                    0 0 0 8px rgba(147, 51, 234, 0.1),
                    0 0 40px rgba(147, 51, 234, 0.3),
                    0 8px 32px rgba(0,0,0,0.1);
                animation: mst-pulse-shadow 1.5s ease-in-out infinite;
            }
            @keyframes mst-pulse-shadow {
                0%, 100% { 
                    box-shadow: 
                        0 0 0 4px rgba(147, 51, 234, 0.2),
                        0 0 0 8px rgba(147, 51, 234, 0.1),
                        0 0 40px rgba(147, 51, 234, 0.3),
                        0 8px 32px rgba(0,0,0,0.1);
                }
                50% { 
                    box-shadow: 
                        0 0 0 8px rgba(147, 51, 234, 0.15),
                        0 0 0 16px rgba(147, 51, 234, 0.05),
                        0 0 60px rgba(147, 51, 234, 0.4),
                        0 8px 32px rgba(0,0,0,0.1);
                }
            }
            <?php endif; ?>
            
            /* Rotating Glow Effect */
            <?php if ($anim === 'rotate_glow'): ?>
            #<?php echo esc_attr($unique_id); ?> .mst-logo-img-wrap::before {
                content: '';
                position: absolute;
                width: 120%;
                height: 120%;
                background: linear-gradient(45deg, 
                    transparent 20%, 
                    rgba(255, 204, 0, 0.4) 40%, 
                    rgba(147, 51, 234, 0.4) 60%, 
                    transparent 80%);
                border-radius: 50%;
                opacity: 0;
                transition: opacity 0.4s ease;
                z-index: -1;
            }
            #<?php echo esc_attr($unique_id); ?> .mst-logo-img-wrap:hover::before {
                opacity: 1;
                animation: mst-orbit 4s linear infinite;
            }
            @keyframes mst-orbit {
                from { transform: rotate(0deg); }
                to { transform: rotate(360deg); }
            }
            <?php endif; ?>
            
            /* Particle Burst Effect */
            <?php if ($anim === 'particles'): ?>
            #<?php echo esc_attr($unique_id); ?> .mst-particle {
                position: absolute;
                width: 6px;
                height: 6px;
                border-radius: 50%;
                pointer-events: none;
                opacity: 0;
            }
            #<?php echo esc_attr($unique_id); ?> .mst-logo-img-wrap:hover .mst-particle {
                animation: mst-particle-fly 1s ease-out forwards;
            }
            @keyframes mst-particle-fly {
                0% { opacity: 1; transform: translate(0, 0) scale(1); }
                100% { opacity: 0; transform: translate(var(--px), var(--py)) scale(0); }
            }
            <?php endif; ?>
        </style>
        <div id="<?php echo esc_attr($unique_id); ?>">
            <div class="mst-logo-img-wrap">
                <?php if (!empty($s['logo_image']['url'])): ?>
                    <img src="<?php echo esc_url($s['logo_image']['url']); ?>" alt="Logo">
                <?php else: ?>
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none"><path d="M13 10V3L4 14H11V21L20 10H13Z" fill="#FFCC00" stroke="#FFCC00" stroke-width="2"/></svg>
                <?php endif; ?>
                
                <?php if ($anim === 'particles'): ?>
                    <?php for ($i = 0; $i < 8; $i++): 
                        $angle = ($i / 8) * 360;
                        $px = cos(deg2rad($angle)) * 60;
                        $py = sin(deg2rad($angle)) * 60;
                        $colors = ['hsl(270, 70%, 60%)', 'hsl(45, 98%, 50%)', 'hsl(270, 70%, 75%)', 'hsl(45, 98%, 70%)'];
                        $color = $colors[$i % 4];
                        $delay = $i * 0.05;
                    ?>
                    <span class="mst-particle" style="left: 50%; top: 50%; --px: <?php echo $px; ?>px; --py: <?php echo $py; ?>px; background: <?php echo $color; ?>; animation-delay: <?php echo $delay; ?>s;"></span>
                    <?php endfor; ?>
                <?php endif; ?>
            </div>
            
            <!-- Letters with subtitles BETWEEN them (inline) -->
            <div class="mst-logo-letters">
                <span class="mst-logo-word mst-word-m">
                    <span class="mst-logo-letter-main" style="color: <?php echo esc_attr($s['letter_m_color']); ?>;">
                        <?php 
                        $m_main = $s['letter_m_main'];
                        echo '<span class="mst-first-letter">' . esc_html(mb_substr($m_main, 0, 1)) . '</span>';
                        if (mb_strlen($m_main) > 1) {
                            echo '<span class="mst-rest-letters">' . esc_html(mb_substr($m_main, 1)) . '</span>';
                        }
                        ?>
                    </span>
                </span>
                <span class="mst-logo-letter-sub"><?php echo esc_html($s['letter_m_sub']); ?></span>
                
                <span class="mst-logo-word mst-word-s">
                    <span class="mst-logo-letter-main" style="color: <?php echo esc_attr($s['letter_s_color']); ?>;">
                        <?php 
                        $s_main = $s['letter_s_main'];
                        echo '<span class="mst-first-letter">' . esc_html(mb_substr($s_main, 0, 1)) . '</span>';
                        if (mb_strlen($s_main) > 1) {
                            echo '<span class="mst-rest-letters">' . esc_html(mb_substr($s_main, 1)) . '</span>';
                        }
                        ?>
                    </span>
                </span>
                <span class="mst-logo-letter-sub"><?php echo esc_html($s['letter_s_sub']); ?></span>
                
                <span class="mst-logo-word mst-word-t">
                    <span class="mst-logo-letter-main" style="color: <?php echo esc_attr($s['letter_t_color']); ?>;">
                        <?php 
                        $t_main = $s['letter_t_main'];
                        echo '<span class="mst-first-letter">' . esc_html(mb_substr($t_main, 0, 1)) . '</span>';
                        if (mb_strlen($t_main) > 1) {
                            echo '<span class="mst-rest-letters">' . esc_html(mb_substr($t_main, 1)) . '</span>';
                        }
                        ?>
                    </span>
                </span>
                <span class="mst-logo-letter-sub"><?php echo esc_html($s['letter_t_sub']); ?></span>
            </div>
        </div>
        <?php
    }
}