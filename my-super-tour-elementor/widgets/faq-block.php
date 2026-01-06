<?php
namespace MST_Elementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;

if (!defined('ABSPATH')) exit;

class FAQ_Block extends Widget_Base {

    public function get_name() {
        return 'mst-faq-block';
    }

    public function get_title() {
        return __('FAQ Block', 'my-super-tour-elementor');
    }

    public function get_icon() {
        return 'eicon-help-o';
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
            'block_title',
            [
                'label' => __('Block Title', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Вопросы и ответы',
            ]
        );

        $this->add_control(
            'block_subtitle',
            [
                'label' => __('Block Subtitle', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Ответы на часто задаваемые вопросы об экскурсии',
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'question',
            [
                'label' => __('Question', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Как забронировать экскурсию?',
            ]
        );

        $repeater->add_control(
            'answer',
            [
                'label' => __('Answer', 'my-super-tour-elementor'),
                'type' => Controls_Manager::WYSIWYG,
                'default' => 'Выберите удобную дату и время, оплатите бронирование онлайн. После оплаты вы получите подтверждение на email с инструкциями.',
            ]
        );

        $this->add_control(
            'faq_items',
            [
                'label' => __('FAQ Items', 'my-super-tour-elementor'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'question' => 'Как забронировать экскурсию?',
                        'answer' => 'Выберите удобную дату и время, оплатите бронирование онлайн. После оплаты вы получите подтверждение на email с инструкциями.',
                    ],
                    [
                        'question' => 'Можно ли отменить или перенести экскурсию?',
                        'answer' => 'Да, вы можете бесплатно отменить или перенести экскурсию за 48 часов до начала. При отмене менее чем за 48 часов возврат составит 50% от стоимости.',
                    ],
                    [
                        'question' => 'Что включено в стоимость?',
                        'answer' => 'В стоимость входит: услуги профессионального гида, входные билеты (если указано в описании), трансфер от места встречи (для выездных экскурсий).',
                    ],
                    [
                        'question' => 'Подходит ли экскурсия для детей?',
                        'answer' => 'Да, большинство наших экскурсий подходят для детей. Рекомендуемый возраст указан в описании каждой экскурсии. Для детей до 6 лет участие обычно бесплатное.',
                    ],
                    [
                        'question' => 'Как связаться с гидом?',
                        'answer' => 'После бронирования вы получите контактные данные гида. Также можно задать вопрос через форму на сайте до оплаты.',
                    ],
                ],
                'title_field' => '{{{ question }}}',
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
                'label' => __('Enable Liquid Glass', 'my-super-tour-elementor'),
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
            'glass_gradient_start',
            [
                'label' => __('Glass Gradient Start', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(254, 250, 243, 0.75)',
                'condition' => ['enable_liquid_glass' => 'yes'],
            ]
        );

        $this->add_control(
            'glass_gradient_middle',
            [
                'label' => __('Glass Gradient Middle', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(155, 135, 245, 0.15)',
                'condition' => ['enable_liquid_glass' => 'yes'],
            ]
        );

        $this->add_control(
            'glass_gradient_end',
            [
                'label' => __('Glass Gradient End', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(254, 247, 205, 0.1)',
                'condition' => ['enable_liquid_glass' => 'yes'],
            ]
        );

        $this->add_control(
            'border_color',
            [
                'label' => __('Border Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(255, 255, 255, 0.5)',
            ]
        );

        $this->add_responsive_control(
            'card_border_radius',
            [
                'label' => __('Card Border Radius', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => ['px' => ['min' => 0, 'max' => 50]],
                'default' => ['size' => 24, 'unit' => 'px'],
            ]
        );

        $this->add_control(
            'heading_color',
            [
                'label' => __('Heading Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#1a1a1a',
            ]
        );

        $this->add_control(
            'subtitle_color',
            [
                'label' => __('Subtitle Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#666666',
            ]
        );

        $this->add_control(
            'question_color',
            [
                'label' => __('Question Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#1a1a1a',
            ]
        );

        $this->add_control(
            'answer_color',
            [
                'label' => __('Answer Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#555555',
            ]
        );

        $this->add_control(
            'icon_color',
            [
                'label' => __('Icon Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'hsl(270, 70%, 60%)',
            ]
        );

        $this->add_control(
            'item_bg_color',
            [
                'label' => __('Item Background', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(255, 255, 255, 0.6)',
            ]
        );

        $this->add_control(
            'item_border_color',
            [
                'label' => __('Item Border Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(0, 0, 0, 0.06)',
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $liquid_glass = $settings['enable_liquid_glass'] === 'yes';
        
        $block_class = 'mst-faq-block';
        if ($liquid_glass) $block_class .= ' mst-liquid-glass';
        
        $gradient_start = esc_attr($settings['glass_gradient_start']);
        $gradient_middle = esc_attr($settings['glass_gradient_middle']);
        $gradient_end = esc_attr($settings['glass_gradient_end']);
        $border_color = esc_attr($settings['border_color']);
        $heading_color = esc_attr($settings['heading_color']);
        $subtitle_color = esc_attr($settings['subtitle_color']);
        $question_color = esc_attr($settings['question_color']);
        $answer_color = esc_attr($settings['answer_color']);
        $icon_color = esc_attr($settings['icon_color']);
        $item_bg_color = esc_attr($settings['item_bg_color']);
        $item_border_color = esc_attr($settings['item_border_color']);
        
        $unique_id = 'mst-faq-' . $this->get_id();
        ?>
        <style>
            #<?php echo $unique_id; ?>.mst-faq-block {
                position: relative;
                padding: 48px;
                background: <?php echo esc_attr($settings['card_bg_color']); ?>;
                border-radius: <?php echo esc_attr($settings['card_border_radius']['size'] . $settings['card_border_radius']['unit']); ?>;
                box-sizing: border-box;
                overflow: hidden;
                border: 1px solid <?php echo $border_color; ?>;
            }
            
            #<?php echo $unique_id; ?>.mst-faq-block.mst-liquid-glass {
                background: linear-gradient(135deg, 
                    <?php echo $gradient_start; ?> 0%, 
                    <?php echo str_replace('0.75', '0.65', $gradient_start); ?> 30%,
                    <?php echo $gradient_middle; ?> 70%,
                    <?php echo $gradient_end; ?> 100%
                );
                backdrop-filter: blur(20px);
                -webkit-backdrop-filter: blur(20px);
                box-shadow: 
                    0 8px 32px rgba(0,0,0,0.04),
                    inset 0 1px 0 rgba(255,255,255,0.8);
            }
            
            #<?php echo $unique_id; ?>.mst-faq-block.mst-liquid-glass::before {
                content: '';
                position: absolute;
                inset: 0;
                opacity: 0.25;
                background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 400 400' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noiseFilter'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='4.8' numOctaves='5' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noiseFilter)'/%3E%3C/svg%3E");
                mix-blend-mode: soft-light;
                pointer-events: none;
                border-radius: inherit;
            }
            
            #<?php echo $unique_id; ?> .mst-faq-header {
                text-align: center;
                margin-bottom: 40px;
                position: relative;
                z-index: 1;
            }
            
            #<?php echo $unique_id; ?> .mst-faq-heading {
                font-size: 32px;
                font-weight: 700;
                color: <?php echo $heading_color; ?>;
                margin: 0 0 12px 0;
            }
            
            #<?php echo $unique_id; ?> .mst-faq-subtitle {
                font-size: 16px;
                color: <?php echo $subtitle_color; ?>;
                margin: 0;
            }
            
            #<?php echo $unique_id; ?> .mst-faq-list {
                display: flex;
                flex-direction: column;
                gap: 12px;
                position: relative;
                z-index: 1;
            }
            
            #<?php echo $unique_id; ?> .mst-faq-item {
                background: <?php echo $item_bg_color; ?>;
                border-radius: 16px;
                border: 1px solid <?php echo $item_border_color; ?>;
                overflow: hidden;
                transition: box-shadow 0.3s ease;
            }
            
            #<?php echo $unique_id; ?> .mst-faq-item:hover {
                box-shadow: 0 4px 20px rgba(0,0,0,0.06);
            }
            
            #<?php echo $unique_id; ?> .mst-faq-question {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 16px;
                padding: 20px 24px;
                cursor: pointer;
                user-select: none;
                transition: background 0.2s ease;
            }
            
            #<?php echo $unique_id; ?> .mst-faq-question:hover {
                background: rgba(255, 255, 255, 0.4);
            }
            
            #<?php echo $unique_id; ?> .mst-faq-question-text {
                font-size: 17px;
                font-weight: 600;
                color: <?php echo $question_color; ?>;
                line-height: 1.4;
            }
            
            #<?php echo $unique_id; ?> .mst-faq-icon {
                flex-shrink: 0;
                width: 32px;
                height: 32px;
                border-radius: 50%;
                background: rgba(155, 135, 245, 0.12);
                display: flex;
                align-items: center;
                justify-content: center;
                color: <?php echo $icon_color; ?>;
                transition: transform 0.3s ease, background 0.3s ease;
            }
            
            #<?php echo $unique_id; ?> .mst-faq-item.active .mst-faq-icon {
                transform: rotate(180deg);
                background: <?php echo $icon_color; ?>;
                color: white;
            }
            
            #<?php echo $unique_id; ?> .mst-faq-answer {
                max-height: 0;
                overflow: hidden;
                transition: max-height 0.3s ease, padding 0.3s ease;
            }
            
            #<?php echo $unique_id; ?> .mst-faq-item.active .mst-faq-answer {
                max-height: 500px;
            }
            
            #<?php echo $unique_id; ?> .mst-faq-answer-content {
                padding: 0 24px 24px 24px;
                font-size: 15px;
                line-height: 1.7;
                color: <?php echo $answer_color; ?>;
            }
            
            #<?php echo $unique_id; ?> .mst-faq-answer-content p {
                margin: 0 0 12px 0;
            }
            
            #<?php echo $unique_id; ?> .mst-faq-answer-content p:last-child {
                margin-bottom: 0;
            }
            
            /* Responsive */
            @media (max-width: 991px) {
                #<?php echo $unique_id; ?>.mst-faq-block {
                    padding: 36px;
                }
            }
            
            @media (max-width: 767px) {
                #<?php echo $unique_id; ?>.mst-faq-block {
                    padding: 24px;
                }
                
                #<?php echo $unique_id; ?> .mst-faq-heading {
                    font-size: 24px;
                }
                
                #<?php echo $unique_id; ?> .mst-faq-question {
                    padding: 16px 20px;
                }
                
                #<?php echo $unique_id; ?> .mst-faq-question-text {
                    font-size: 15px;
                }
                
                #<?php echo $unique_id; ?> .mst-faq-answer-content {
                    padding: 0 20px 20px 20px;
                    font-size: 14px;
                }
            }
        </style>
        
        <div id="<?php echo $unique_id; ?>" class="<?php echo esc_attr($block_class); ?>">
            <div class="mst-faq-header">
                <h2 class="mst-faq-heading"><?php echo esc_html($settings['block_title']); ?></h2>
                <?php if (!empty($settings['block_subtitle'])): ?>
                <p class="mst-faq-subtitle"><?php echo esc_html($settings['block_subtitle']); ?></p>
                <?php endif; ?>
            </div>
            
            <div class="mst-faq-list">
                <?php foreach ($settings['faq_items'] as $index => $item): ?>
                <div class="mst-faq-item" data-faq-item>
                    <div class="mst-faq-question" data-faq-trigger>
                        <span class="mst-faq-question-text"><?php echo esc_html($item['question']); ?></span>
                        <span class="mst-faq-icon">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="6 9 12 15 18 9"/>
                            </svg>
                        </span>
                    </div>
                    <div class="mst-faq-answer">
                        <div class="mst-faq-answer-content">
                            <?php echo wp_kses_post($item['answer']); ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        
        <script>
        (function() {
            const container = document.getElementById('<?php echo $unique_id; ?>');
            if (!container) return;
            
            const items = container.querySelectorAll('[data-faq-item]');
            
            items.forEach(item => {
                const trigger = item.querySelector('[data-faq-trigger]');
                if (!trigger) return;
                
                trigger.addEventListener('click', () => {
                    const isActive = item.classList.contains('active');
                    
                    // Close all items
                    items.forEach(i => i.classList.remove('active'));
                    
                    // Open clicked if it wasn't active
                    if (!isActive) {
                        item.classList.add('active');
                    }
                });
            });
        })();
        </script>
        <?php
    }
}
