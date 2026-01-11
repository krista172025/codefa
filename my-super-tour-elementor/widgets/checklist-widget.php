<?php
namespace MST_Elementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;

if (!defined('ABSPATH')) exit;

/**
 * Checklist Widget - Liquid Glass Design
 * Standalone widget extracted from Single Blog Article
 */
class Checklist_Widget extends Widget_Base {

    public function get_name() {
        return 'mst-checklist';
    }

    public function get_title() {
        return __('Checklist (Liquid Glass)', 'my-super-tour-elementor');
    }

    public function get_icon() {
        return 'eicon-checkbox';
    }

    public function get_categories() {
        return ['my-super-tour'];
    }

    public function get_keywords() {
        return ['checklist', 'list', 'check', 'todo', 'liquid', 'glass'];
    }

    protected function register_controls() {
        // =============================================
        // CONTENT SECTION
        // =============================================
        $this->start_controls_section(
            'section_content',
            [
                'label' => __('Чек-лист', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'checklist_title',
            [
                'label' => __('Заголовок', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Что взять с собой', 'my-super-tour-elementor'),
            ]
        );

        $this->add_control(
            'show_icon',
            [
                'label' => __('Показывать иконку заголовка', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'item_text',
            [
                'label' => __('Пункт', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Пункт списка',
            ]
        );

        $repeater->add_control(
            'is_important',
            [
                'label' => __('Важный', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
            ]
        );

        $repeater->add_control(
            'is_checked',
            [
                'label' => __('Выполнено', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
                'description' => __('Визуальный стиль "выполнено"', 'my-super-tour-elementor'),
            ]
        );

        $this->add_control(
            'checklist_items',
            [
                'label' => __('Пункты', 'my-super-tour-elementor'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    ['item_text' => 'Удобная обувь', 'is_important' => ''],
                    ['item_text' => 'Бутылка воды', 'is_important' => ''],
                    ['item_text' => 'Солнцезащитный крем', 'is_important' => 'yes'],
                    ['item_text' => 'Головной убор', 'is_important' => ''],
                    ['item_text' => 'Камера или телефон', 'is_important' => ''],
                    ['item_text' => 'Документы', 'is_important' => 'yes'],
                ],
                'title_field' => '{{{ item_text }}}',
            ]
        );

        $this->add_control(
            'columns',
            [
                'label' => __('Колонки', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SELECT,
                'default' => 'auto',
                'options' => [
                    'auto' => __('Авто', 'my-super-tour-elementor'),
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                ],
            ]
        );

        $this->add_control(
            'enable_interactive',
            [
                'label' => __('Интерактивный режим', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
                'description' => __('Позволяет пользователям отмечать пункты', 'my-super-tour-elementor'),
            ]
        );

        $this->add_control(
            'persist_state',
            [
                'label' => __('Сохранять состояние', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
                'description' => __('Сохранять отмеченные пункты между перезагрузками страницы', 'my-super-tour-elementor'),
                'condition' => ['enable_interactive' => 'yes'],
            ]
        );

        $this->end_controls_section();

        // =============================================
        // STYLE SECTION
        // =============================================
        $this->start_controls_section(
            'section_style',
            [
                'label' => __('Стили', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'primary_color',
            [
                'label' => __('Основной цвет', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#9952E0',
            ]
        );

        $this->add_control(
            'secondary_color',
            [
                'label' => __('Акцентный цвет (важные)', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#fbd603',
            ]
        );

        $this->add_control(
            'glass_blur',
            [
                'label' => __('Glass Blur (px)', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => ['px' => ['min' => 0, 'max' => 40]],
                'default' => ['size' => 16],
            ]
        );

        $this->add_control(
            'glass_opacity',
            [
                'label' => __('Glass Opacity', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => ['px' => ['min' => 0, 'max' => 100]],
                'default' => ['size' => 85],
            ]
        );

        $this->add_responsive_control(
            'border_radius',
            [
                'label' => __('Border Radius', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => ['px' => ['min' => 0, 'max' => 50]],
                'default' => ['size' => 24],
            ]
        );

        $this->add_responsive_control(
            'padding',
            [
                'label' => __('Padding', 'my-super-tour-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'default' => [
                    'top' => 32,
                    'right' => 32,
                    'bottom' => 32,
                    'left' => 32,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .mst-checklist-widget' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'heading_color',
            [
                'label' => __('Цвет заголовка', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#1a1a2e',
            ]
        );

        $this->add_control(
            'text_color',
            [
                'label' => __('Цвет текста', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#4b5563',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'label' => __('Типографика заголовка', 'my-super-tour-elementor'),
                'selector' => '{{WRAPPER}} .mst-checklist-title',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'item_typography',
                'label' => __('Типографика пунктов', 'my-super-tour-elementor'),
                'selector' => '{{WRAPPER}} .mst-checklist-text',
            ]
        );

        $this->end_controls_section();

        // =============================================
        // ITEM STYLE SECTION
        // =============================================
        $this->start_controls_section(
            'section_item_style',
            [
                'label' => __('Стиль пунктов', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'item_padding',
            [
                'label' => __('Padding пункта', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => ['px' => ['min' => 8, 'max' => 32]],
                'default' => ['size' => 14],
            ]
        );

        $this->add_responsive_control(
            'item_gap',
            [
                'label' => __('Отступ между пунктами', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => ['px' => ['min' => 4, 'max' => 24]],
                'default' => ['size' => 12],
            ]
        );

        $this->add_responsive_control(
            'item_border_radius',
            [
                'label' => __('Border Radius пункта', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => ['px' => ['min' => 0, 'max' => 24]],
                'default' => ['size' => 12],
            ]
        );

        $this->add_responsive_control(
            'check_size',
            [
                'label' => __('Размер чекбокса', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => ['px' => ['min' => 16, 'max' => 40]],
                'default' => ['size' => 24],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        
        if (empty($settings['checklist_items'])) {
            return;
        }

        $unique_id = 'mst-checklist-' . $this->get_id();
        $primary_color = $settings['primary_color'];
        $secondary_color = $settings['secondary_color'];
        $glass_blur = $settings['glass_blur']['size'] ?? 16;
        $glass_opacity = ($settings['glass_opacity']['size'] ?? 85) / 100;
        $border_radius = $settings['border_radius']['size'] ?? 24;
        $heading_color = $settings['heading_color'];
        $text_color = $settings['text_color'];
        
        $columns = $settings['columns'];
        $item_padding = $settings['item_padding']['size'] ?? 14;
        $item_gap = $settings['item_gap']['size'] ?? 12;
        $item_border_radius = $settings['item_border_radius']['size'] ?? 12;
        $check_size = $settings['check_size']['size'] ?? 24;
        
        $interactive = $settings['enable_interactive'] === 'yes';
        $persist_state = $interactive && ($settings['persist_state'] ?? '') === 'yes';
        $show_icon = $settings['show_icon'] === 'yes';
        
        $grid_style = $columns === 'auto' 
            ? 'grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));' 
            : 'grid-template-columns: repeat(' . intval($columns) . ', 1fr);';
        ?>
        
        <section id="<?php echo esc_attr($unique_id); ?>" class="mst-checklist-widget mst-liquid-glass<?php echo $interactive ? ' mst-interactive' : ''; ?>">
            <?php if (!empty($settings['checklist_title'])): ?>
            <h2 class="mst-checklist-title">
                <?php if ($show_icon): ?>
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M9 11l3 3L22 4"></path>
                    <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
                </svg>
                <?php endif; ?>
                <?php echo esc_html($settings['checklist_title']); ?>
            </h2>
            <?php endif; ?>
            
            <ul class="mst-checklist-list" style="<?php echo esc_attr($grid_style); ?>">
                <?php foreach ($settings['checklist_items'] as $index => $item): 
                    $is_important = $item['is_important'] === 'yes';
                    $is_checked = $item['is_checked'] === 'yes';
                    $item_class = 'mst-checklist-item';
                    if ($is_important) $item_class .= ' mst-important';
                    if ($is_checked) $item_class .= ' mst-checked';
                ?>
                <li class="<?php echo esc_attr($item_class); ?>" data-index="<?php echo $index; ?>">
                    <span class="mst-checklist-check">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                    </span>
                    <span class="mst-checklist-text"><?php echo esc_html($item['item_text']); ?></span>
                    <?php if ($is_important): ?>
                    <span class="mst-checklist-badge">Важно!</span>
                    <?php endif; ?>
                </li>
                <?php endforeach; ?>
            </ul>
        </section>
        
        <style>
        /* ===============================================
           CHECKLIST WIDGET - LIQUID GLASS DESIGN
           =============================================== */
        #<?php echo esc_attr($unique_id); ?> {
            --primary-color: <?php echo esc_attr($primary_color); ?>;
            --secondary-color: <?php echo esc_attr($secondary_color); ?>;
            --glass-blur: <?php echo esc_attr($glass_blur); ?>px;
            --glass-opacity: <?php echo esc_attr($glass_opacity); ?>;
            --heading-color: <?php echo esc_attr($heading_color); ?>;
            --text-color: <?php echo esc_attr($text_color); ?>;
            --item-padding: <?php echo esc_attr($item_padding); ?>px;
            --item-gap: <?php echo esc_attr($item_gap); ?>px;
            --item-radius: <?php echo esc_attr($item_border_radius); ?>px;
            --check-size: <?php echo esc_attr($check_size); ?>px;
        }
        
        #<?php echo esc_attr($unique_id); ?>.mst-liquid-glass {
            background: linear-gradient(
                135deg,
                rgba(255, 255, 255, calc(var(--glass-opacity) + 0.1)),
                rgba(255, 255, 255, calc(var(--glass-opacity) - 0.15))
            );
            backdrop-filter: blur(var(--glass-blur)) saturate(180%);
            -webkit-backdrop-filter: blur(var(--glass-blur)) saturate(180%);
            border: 1px solid rgba(255, 255, 255, 0.5);
            border-radius: <?php echo esc_attr($border_radius); ?>px;
            box-shadow: 
                0 8px 32px -8px rgba(0, 0, 0, 0.08),
                0 4px 16px -4px rgba(153, 82, 224, 0.06),
                inset 0 1px 0 rgba(255, 255, 255, 0.8);
            position: relative;
            overflow: hidden;
        }
        
        /* Grain texture */
        #<?php echo esc_attr($unique_id); ?>.mst-liquid-glass::before {
            content: '';
            position: absolute;
            inset: 0;
            background: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.65' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)'/%3E%3C/svg%3E");
            opacity: 0.03;
            pointer-events: none;
            mix-blend-mode: overlay;
        }
        
        #<?php echo esc_attr($unique_id); ?> .mst-checklist-title {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--heading-color);
            margin: 0 0 24px 0;
            position: relative;
            z-index: 1;
        }
        
        #<?php echo esc_attr($unique_id); ?> .mst-checklist-title svg {
            color: var(--primary-color);
            flex-shrink: 0;
        }
        
        #<?php echo esc_attr($unique_id); ?> .mst-checklist-list {
            list-style: none;
            padding: 0;
            margin: 0;
            display: grid;
            gap: var(--item-gap);
            position: relative;
            z-index: 1;
        }
        
        #<?php echo esc_attr($unique_id); ?> .mst-checklist-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: var(--item-padding) calc(var(--item-padding) + 2px);
            background: rgba(255, 255, 255, 0.5);
            border-radius: var(--item-radius);
            border: 1px solid rgba(255, 255, 255, 0.4);
            transition: transform 0.2s ease, background 0.2s ease, box-shadow 0.2s ease;
        }
        
        #<?php echo esc_attr($unique_id); ?> .mst-checklist-item:hover {
            transform: translateX(4px);
            background: rgba(255, 255, 255, 0.7);
            box-shadow: 0 4px 12px -4px rgba(153, 82, 224, 0.1);
        }
        
        #<?php echo esc_attr($unique_id); ?> .mst-checklist-item.mst-important {
            background: linear-gradient(135deg, rgba(251, 214, 3, 0.12), rgba(251, 214, 3, 0.03));
            border-color: var(--secondary-color);
        }
        
        #<?php echo esc_attr($unique_id); ?> .mst-checklist-item.mst-checked {
            opacity: 0.6;
        }
        
        #<?php echo esc_attr($unique_id); ?> .mst-checklist-item.mst-checked .mst-checklist-text {
            text-decoration: line-through;
        }
        
        #<?php echo esc_attr($unique_id); ?> .mst-checklist-check {
            width: var(--check-size);
            height: var(--check-size);
            background: var(--primary-color);
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            color: white;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        
        #<?php echo esc_attr($unique_id); ?>.mst-interactive .mst-checklist-item {
            cursor: pointer;
        }
        
        #<?php echo esc_attr($unique_id); ?>.mst-interactive .mst-checklist-check {
            background: transparent;
            border: 2px solid var(--primary-color);
            color: transparent;
        }
        
        #<?php echo esc_attr($unique_id); ?>.mst-interactive .mst-checklist-item.mst-checked .mst-checklist-check {
            background: var(--primary-color);
            color: white;
        }
        
        #<?php echo esc_attr($unique_id); ?>.mst-interactive .mst-checklist-item:hover .mst-checklist-check {
            transform: scale(1.1);
            box-shadow: 0 2px 8px rgba(153, 82, 224, 0.3);
        }
        
        #<?php echo esc_attr($unique_id); ?> .mst-checklist-text {
            font-size: 0.9375rem;
            color: var(--text-color);
            flex: 1;
            line-height: 1.4;
        }
        
        #<?php echo esc_attr($unique_id); ?> .mst-checklist-badge {
            font-size: 0.6875rem;
            font-weight: 600;
            padding: 4px 8px;
            background: var(--secondary-color);
            color: #1a1a2e;
            border-radius: 6px;
            text-transform: uppercase;
            letter-spacing: 0.02em;
            flex-shrink: 0;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            #<?php echo esc_attr($unique_id); ?> .mst-checklist-list {
                grid-template-columns: 1fr !important;
            }
        }
        </style>
        
        <?php if ($interactive): ?>
        <script>
        (function() {
            const container = document.getElementById('<?php echo esc_js($unique_id); ?>');
            if (!container) return;
            
            const items = container.querySelectorAll('.mst-checklist-item');
            const storageKey = 'mst-checklist-<?php echo esc_js($this->get_id()); ?>';
            const persistState = <?php echo $persist_state ? 'true' : 'false'; ?>;
            
            // Load saved state only if persist is enabled
            let savedState = {};
            if (persistState) {
                try {
                    savedState = JSON.parse(localStorage.getItem(storageKey) || '{}');
                } catch(e) {}
            }
            
            items.forEach(function(item) {
                const index = item.getAttribute('data-index');
                
                // Only restore state if persist is enabled
                if (persistState && savedState[index]) {
                    item.classList.add('mst-checked');
                }
                
                item.addEventListener('click', function() {
                    item.classList.toggle('mst-checked');
                    
                    // Only save state if persist is enabled
                    if (persistState) {
                        savedState[index] = item.classList.contains('mst-checked');
                        try {
                            localStorage.setItem(storageKey, JSON.stringify(savedState));
                        } catch(e) {}
                    }
                });
            });
        })();
        </script>
        <?php endif; ?>
        
        <?php
    }
}
