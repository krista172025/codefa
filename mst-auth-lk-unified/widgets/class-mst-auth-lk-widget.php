<?php
/**
 * MST Auth + LK Elementor Widget
 * Объединённый виджет: Авторизация + Личный Кабинет
 * 
 * Author: Telegram @l1ghtsun
 */

if (!defined('ABSPATH')) exit;

class MST_Auth_LK_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'mst_auth_lk';
    }

    public function get_title() {
        return __('MST Auth + ЛК', 'mst-auth-lk');
    }

    public function get_icon() {
        return 'eicon-lock-user';
    }

    public function get_categories() {
        return ['mysupertour'];
    }

    public function get_keywords() {
        return ['login', 'register', 'auth', 'profile', 'lk', 'вход', 'регистрация', 'кабинет'];
    }

    protected function register_controls() {
        
        // =====================================================
        // === TAB CONTENT ===
        // =====================================================
        
        // === CONTENT: GENERAL ===
        $this->start_controls_section('content_section', [
            'label' => __('Основные настройки', 'mst-auth-lk'),
            'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control('form_type', [
            'label' => __('Тип формы (для гостей)', 'mst-auth-lk'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => 'both',
            'options' => [
                'login' => __('Только вход', 'mst-auth-lk'),
                'register' => __('Только регистрация', 'mst-auth-lk'),
                'both' => __('Вход + Регистрация', 'mst-auth-lk'),
            ],
        ]);

        $this->add_control('show_icon', [
            'label' => __('Показать иконку', 'mst-auth-lk'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => 'yes',
        ]);

        $this->add_control('icon_type', [
            'label' => __('Тип иконки', 'mst-auth-lk'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => 'sparkle',
            'options' => [
                'sparkle' => '✨ ' . __('Звезда', 'mst-auth-lk'),
                'user' => '👤 ' . __('Пользователь', 'mst-auth-lk'),
                'lock' => '🔐 ' . __('Замок', 'mst-auth-lk'),
            ],
            'condition' => ['show_icon' => 'yes'],
        ]);

        $this->add_control('redirect_url', [
            'label' => __('Редирект после входа', 'mst-auth-lk'),
            'type' => \Elementor\Controls_Manager::URL,
            'placeholder' => home_url('/auth/'),
            'default' => ['url' => ''],
        ]);

        $this->end_controls_section();

        // === CONTENT: TEXTS ===
        $this->start_controls_section('content_texts', [
            'label' => __('Тексты форм', 'mst-auth-lk'),
            'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control('login_title', [
            'label' => __('Заголовок входа', 'mst-auth-lk'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => __('С возвращением!', 'mst-auth-lk'),
        ]);

        $this->add_control('login_subtitle', [
            'label' => __('Подзаголовок входа', 'mst-auth-lk'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => __('Войдите, чтобы продолжить', 'mst-auth-lk'),
        ]);

        $this->add_control('register_title', [
            'label' => __('Заголовок регистрации', 'mst-auth-lk'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => __('Создать аккаунт', 'mst-auth-lk'),
            'condition' => ['form_type' => ['register', 'both']],
        ]);

        $this->add_control('register_subtitle', [
            'label' => __('Подзаголовок регистрации', 'mst-auth-lk'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => __('Заполните форму для регистрации', 'mst-auth-lk'),
            'condition' => ['form_type' => ['register', 'both']],
        ]);

        $this->add_control('login_button_text', [
            'label' => __('Текст кнопки входа', 'mst-auth-lk'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => __('Войти', 'mst-auth-lk'),
        ]);

        $this->add_control('register_button_text', [
            'label' => __('Текст кнопки регистрации', 'mst-auth-lk'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => __('Зарегистрироваться', 'mst-auth-lk'),
            'condition' => ['form_type' => ['register', 'both']],
        ]);

        $this->end_controls_section();

        // === CONTENT: LK TABS ===
        $this->start_controls_section('content_lk_tabs', [
            'label' => __('Табы личного кабинета', 'mst-auth-lk'),
            'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
        ]);
        
        $this->add_control('show_orders', [
            'label' => __('Показать "Мои заказы"', 'mst-auth-lk'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => 'yes',
        ]);
        
        $this->add_control('orders_label', [
            'label' => __('Название таба заказов', 'mst-auth-lk'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => __('Мои заказы', 'mst-auth-lk'),
            'condition' => ['show_orders' => 'yes'],
        ]);
        
        $this->add_control('show_bookings', [
            'label' => __('Показать "Бронирования"', 'mst-auth-lk'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => 'yes',
        ]);
        
        $this->add_control('bookings_label', [
            'label' => __('Название таба бронирований', 'mst-auth-lk'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => __('Бронирования', 'mst-auth-lk'),
            'condition' => ['show_bookings' => 'yes'],
        ]);
        
        $this->add_control('show_affiliate', [
            'label' => __('Показать "Реферальная программа"', 'mst-auth-lk'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => 'yes',
        ]);
        
        $this->add_control('affiliate_label', [
            'label' => __('Название таба реферальной программы', 'mst-auth-lk'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => __('Реферальная программа', 'mst-auth-lk'),
            'condition' => ['show_affiliate' => 'yes'],
        ]);
        
        $this->add_control('show_wishlist', [
            'label' => __('Показать "Избранное"', 'mst-auth-lk'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => 'yes',
        ]);
        
        $this->add_control('wishlist_label', [
            'label' => __('Название таба избранного', 'mst-auth-lk'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => __('Избранное', 'mst-auth-lk'),
            'condition' => ['show_wishlist' => 'yes'],
        ]);

        $this->end_controls_section();

        // =====================================================
        // === TAB STYLE ===
        // =====================================================

        // === STYLE: GENERAL COLORS ===
        $this->start_controls_section('style_colors', [
            'label' => __('Основные цвета', 'mst-auth-lk'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('primary_color', [
            'label' => __('Основной цвет (сиреневый)', 'mst-auth-lk'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#9952E0',
            'selectors' => [
                '{{WRAPPER}} .mst-auth-icon' => 'color: {{VALUE}};',
                '{{WRAPPER}} .mst-auth-switch-link' => 'color: {{VALUE}};',
                '{{WRAPPER}} .mst-auth-input:focus' => 'border-color: {{VALUE}}; box-shadow: 0 0 0 3px {{VALUE}}1a;',
                '{{WRAPPER}} .mst-lk-nav-item.active' => 'color: {{VALUE}};',
                '{{WRAPPER}} .mst-lk-edit-btn' => 'background-color: {{VALUE}};',
                '{{WRAPPER}} .mst-price' => 'color: {{VALUE}};',
                '{{WRAPPER}} .mst-shop-grid-content h3 a:hover' => 'color: {{VALUE}};',
                '{{WRAPPER}} .mst-lk-btn-outline:hover' => 'border-color: {{VALUE}}; color: {{VALUE}};',
                '{{WRAPPER}} .mst-lk-avatar-circle' => 'border-color: {{VALUE}};',
            ],
        ]);

        $this->add_control('secondary_color', [
            'label' => __('Вторичный цвет (жёлтый)', 'mst-auth-lk'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#fbd603',
            'selectors' => [
                '{{WRAPPER}} .mst-rating .star' => 'color: {{VALUE}};',
                '{{WRAPPER}} .mst-affiliate-promo' => 'background: linear-gradient(135deg, {{VALUE}} 0%, {{VALUE}}dd 100%);',
            ],
        ]);

        $this->add_control('text_color', [
            'label' => __('Цвет текста', 'mst-auth-lk'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#1f2937',
            'selectors' => [
                '{{WRAPPER}} .mst-auth-title' => 'color: {{VALUE}};',
                '{{WRAPPER}} .mst-lk-user-name' => 'color: {{VALUE}};',
                '{{WRAPPER}} .mst-lk-section-title' => 'color: {{VALUE}};',
                '{{WRAPPER}} .mst-shop-grid-content h3 a' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_control('muted_text_color', [
            'label' => __('Цвет вторичного текста', 'mst-auth-lk'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#6b7280',
            'selectors' => [
                '{{WRAPPER}} .mst-auth-subtitle' => 'color: {{VALUE}};',
                '{{WRAPPER}} .mst-auth-switch' => 'color: {{VALUE}};',
                '{{WRAPPER}} .mst-lk-user-email' => 'color: {{VALUE}};',
                '{{WRAPPER}} .mst-lk-nav-item' => 'color: {{VALUE}};',
            ],
        ]);

        $this->end_controls_section();

        // === STYLE: CARD ===
        $this->start_controls_section('style_card', [
            'label' => __('Карточка формы', 'mst-auth-lk'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('card_bg', [
            'label' => __('Фон карточки', 'mst-auth-lk'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#ffffff',
            'selectors' => [
                '{{WRAPPER}} .mst-auth-card' => 'background-color: {{VALUE}};',
                '{{WRAPPER}} .mst-lk-content' => 'background-color: {{VALUE}};',
                '{{WRAPPER}} .mst-lk-sidebar' => 'background-color: {{VALUE}};',
            ],
        ]);

        $this->add_responsive_control('card_padding', [
            'label' => __('Внутренние отступы', 'mst-auth-lk'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em', '%'],
            'default' => [
                'top' => '48',
                'right' => '48',
                'bottom' => '48',
                'left' => '48',
                'unit' => 'px',
            ],
            'selectors' => [
                '{{WRAPPER}} .mst-auth-card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->add_control('card_radius', [
            'label' => __('Скругление углов', 'mst-auth-lk'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'default' => ['size' => 24, 'unit' => 'px'],
            'range' => ['px' => ['min' => 0, 'max' => 50]],
            'selectors' => [
                '{{WRAPPER}} .mst-auth-card' => 'border-radius: {{SIZE}}px;',
                '{{WRAPPER}} .mst-lk-content' => 'border-radius: {{SIZE}}px;',
                '{{WRAPPER}} .mst-lk-sidebar' => 'border-radius: {{SIZE}}px;',
                '{{WRAPPER}} .mst-lk-top-profile' => 'border-radius: {{SIZE}}px;',
            ],
        ]);

        $this->add_group_control(\Elementor\Group_Control_Border::get_type(), [
            'name' => 'card_border',
            'label' => __('Граница', 'mst-auth-lk'),
            'selector' => '{{WRAPPER}} .mst-auth-card, {{WRAPPER}} .mst-lk-content',
        ]);

        $this->add_group_control(\Elementor\Group_Control_Box_Shadow::get_type(), [
            'name' => 'card_shadow',
            'label' => __('Тень', 'mst-auth-lk'),
            'selector' => '{{WRAPPER}} .mst-auth-card, {{WRAPPER}} .mst-lk-content, {{WRAPPER}} .mst-lk-sidebar',
        ]);

        $this->add_control('card_max_width', [
            'label' => __('Максимальная ширина', 'mst-auth-lk'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px', '%'],
            'range' => [
                'px' => ['min' => 300, 'max' => 800],
                '%' => ['min' => 50, 'max' => 100],
            ],
            'default' => ['size' => 420, 'unit' => 'px'],
            'selectors' => [
                '{{WRAPPER}} .mst-auth-card' => 'max-width: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->end_controls_section();

        // === STYLE: ICON ===
        $this->start_controls_section('style_icon', [
            'label' => __('Иконка', 'mst-auth-lk'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            'condition' => ['show_icon' => 'yes'],
        ]);

        $this->add_control('icon_color', [
            'label' => __('Цвет иконки', 'mst-auth-lk'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#9952E0',
            'selectors' => [
                '{{WRAPPER}} .mst-auth-icon' => 'color: {{VALUE}};',
                '{{WRAPPER}} .mst-auth-icon svg' => 'fill: {{VALUE}}; stroke: {{VALUE}};',
            ],
        ]);

        $this->add_control('icon_size', [
            'label' => __('Размер иконки', 'mst-auth-lk'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'range' => ['px' => ['min' => 30, 'max' => 120]],
            'default' => ['size' => 60, 'unit' => 'px'],
            'selectors' => [
                '{{WRAPPER}} .mst-auth-icon' => 'width: {{SIZE}}px; height: {{SIZE}}px;',
            ],
        ]);

        $this->add_control('icon_margin', [
            'label' => __('Отступ снизу', 'mst-auth-lk'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'range' => ['px' => ['min' => 0, 'max' => 60]],
            'default' => ['size' => 24, 'unit' => 'px'],
            'selectors' => [
                '{{WRAPPER}} .mst-auth-icon' => 'margin-bottom: {{SIZE}}px;',
            ],
        ]);

        $this->end_controls_section();

        // === STYLE: TYPOGRAPHY ===
        $this->start_controls_section('style_typography', [
            'label' => __('Типографика', 'mst-auth-lk'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
            'name' => 'title_typography',
            'label' => __('Заголовок', 'mst-auth-lk'),
            'selector' => '{{WRAPPER}} .mst-auth-title, {{WRAPPER}} .mst-lk-section-title',
        ]);

        $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
            'name' => 'subtitle_typography',
            'label' => __('Подзаголовок', 'mst-auth-lk'),
            'selector' => '{{WRAPPER}} .mst-auth-subtitle',
        ]);

        $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
            'name' => 'label_typography',
            'label' => __('Подписи полей', 'mst-auth-lk'),
            'selector' => '{{WRAPPER}} .mst-auth-field label',
        ]);

        $this->end_controls_section();

        // === STYLE: INPUT FIELDS ===
        $this->start_controls_section('style_inputs', [
            'label' => __('Поля ввода', 'mst-auth-lk'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('input_bg', [
            'label' => __('Фон поля', 'mst-auth-lk'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#ffffff',
            'selectors' => [
                '{{WRAPPER}} .mst-auth-input' => 'background-color: {{VALUE}};',
            ],
        ]);

        $this->add_control('input_text_color', [
            'label' => __('Цвет текста', 'mst-auth-lk'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#1f2937',
            'selectors' => [
                '{{WRAPPER}} .mst-auth-input' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_control('input_placeholder_color', [
            'label' => __('Цвет placeholder', 'mst-auth-lk'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#9ca3af',
            'selectors' => [
                '{{WRAPPER}} .mst-auth-input::placeholder' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_control('input_border_color', [
            'label' => __('Цвет границы', 'mst-auth-lk'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#e5e7eb',
            'selectors' => [
                '{{WRAPPER}} .mst-auth-input' => 'border-color: {{VALUE}};',
            ],
        ]);

        $this->add_control('input_focus_border_color', [
            'label' => __('Цвет границы (фокус)', 'mst-auth-lk'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#9952E0',
            'selectors' => [
                '{{WRAPPER}} .mst-auth-input:focus' => 'border-color: {{VALUE}}; box-shadow: 0 0 0 3px {{VALUE}}1a;',
            ],
        ]);

        $this->add_control('input_radius', [
            'label' => __('Скругление', 'mst-auth-lk'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'range' => ['px' => ['min' => 0, 'max' => 25]],
            'default' => ['size' => 12, 'unit' => 'px'],
            'selectors' => [
                '{{WRAPPER}} .mst-auth-input' => 'border-radius: {{SIZE}}px;',
            ],
        ]);

        $this->add_responsive_control('input_padding', [
            'label' => __('Внутренние отступы', 'mst-auth-lk'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px'],
            'default' => [
                'top' => '14',
                'right' => '16',
                'bottom' => '14',
                'left' => '16',
                'unit' => 'px',
            ],
            'selectors' => [
                '{{WRAPPER}} .mst-auth-input' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->end_controls_section();

        // === STYLE: BUTTONS ===
        $this->start_controls_section('style_button', [
            'label' => __('Кнопки', 'mst-auth-lk'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
        ]);

        $this->start_controls_tabs('button_tabs');

        // Normal state
        $this->start_controls_tab('button_normal', [
            'label' => __('Обычное', 'mst-auth-lk'),
        ]);

        $this->add_control('button_bg', [
            'label' => __('Фон кнопки', 'mst-auth-lk'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#9952E0',
        ]);

        $this->add_control('button_bg_gradient', [
            'label' => __('Градиент', 'mst-auth-lk'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => 'yes',
        ]);

        $this->add_control('button_bg_gradient_end', [
            'label' => __('Конечный цвет градиента', 'mst-auth-lk'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#7B3FC4',
            'condition' => ['button_bg_gradient' => 'yes'],
        ]);

        $this->add_control('button_text_color', [
            'label' => __('Цвет текста', 'mst-auth-lk'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#ffffff',
            'selectors' => [
                '{{WRAPPER}} .mst-auth-btn, {{WRAPPER}} .mst-lk-btn-primary' => 'color: {{VALUE}};',
            ],
        ]);

        $this->end_controls_tab();

        // Hover state
        $this->start_controls_tab('button_hover', [
            'label' => __('Наведение', 'mst-auth-lk'),
        ]);

        $this->add_control('button_hover_bg', [
            'label' => __('Фон при наведении', 'mst-auth-lk'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#7B3FC4',
        ]);

        $this->add_control('button_hover_text_color', [
            'label' => __('Цвет текста при наведении', 'mst-auth-lk'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#ffffff',
            'selectors' => [
                '{{WRAPPER}} .mst-auth-btn:hover, {{WRAPPER}} .mst-lk-btn-primary:hover' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_control('button_hover_transform', [
            'label' => __('Эффект подъёма', 'mst-auth-lk'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => 'yes',
        ]);

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_control('button_divider', [
            'type' => \Elementor\Controls_Manager::DIVIDER,
        ]);

        $this->add_control('button_radius', [
            'label' => __('Скругление кнопки', 'mst-auth-lk'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'default' => ['size' => 12, 'unit' => 'px'],
            'range' => ['px' => ['min' => 0, 'max' => 30]],
            'selectors' => [
                '{{WRAPPER}} .mst-auth-btn, {{WRAPPER}} .mst-lk-btn' => 'border-radius: {{SIZE}}px;',
            ],
        ]);

        $this->add_responsive_control('button_padding', [
            'label' => __('Внутренние отступы', 'mst-auth-lk'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px'],
            'default' => [
                'top' => '14',
                'right' => '24',
                'bottom' => '14',
                'left' => '24',
                'unit' => 'px',
            ],
            'selectors' => [
                '{{WRAPPER}} .mst-auth-btn, {{WRAPPER}} .mst-lk-btn-primary' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
            'name' => 'button_typography',
            'label' => __('Типографика кнопки', 'mst-auth-lk'),
            'selector' => '{{WRAPPER}} .mst-auth-btn, {{WRAPPER}} .mst-lk-btn',
        ]);

        $this->add_group_control(\Elementor\Group_Control_Box_Shadow::get_type(), [
            'name' => 'button_shadow',
            'label' => __('Тень кнопки', 'mst-auth-lk'),
            'selector' => '{{WRAPPER}} .mst-auth-btn:hover, {{WRAPPER}} .mst-lk-btn-primary:hover',
        ]);

        $this->end_controls_section();

        // === STYLE: LK PROFILE ===
        $this->start_controls_section('style_lk_profile', [
            'label' => __('Личный кабинет - Профиль', 'mst-auth-lk'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('lk_profile_bg', [
            'label' => __('Фон профиля', 'mst-auth-lk'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '',
            'selectors' => [
                '{{WRAPPER}} .mst-lk-top-profile' => 'background: {{VALUE}};',
            ],
        ]);

        $this->add_control('lk_profile_gradient', [
            'label' => __('Градиент профиля', 'mst-auth-lk'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => 'yes',
        ]);

        $this->add_control('lk_avatar_size', [
            'label' => __('Размер аватара', 'mst-auth-lk'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'range' => ['px' => ['min' => 60, 'max' => 200]],
            'default' => ['size' => 100, 'unit' => 'px'],
            'selectors' => [
                '{{WRAPPER}} .mst-lk-avatar-circle' => 'width: {{SIZE}}px; height: {{SIZE}}px;',
            ],
        ]);

        $this->add_control('lk_avatar_border_width', [
            'label' => __('Толщина рамки аватара', 'mst-auth-lk'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'range' => ['px' => ['min' => 1, 'max' => 10]],
            'default' => ['size' => 4, 'unit' => 'px'],
            'selectors' => [
                '{{WRAPPER}} .mst-lk-avatar-circle' => 'border-width: {{SIZE}}px;',
            ],
        ]);

        $this->end_controls_section();

        // === STYLE: LK NAVIGATION ===
        $this->start_controls_section('style_lk_nav', [
            'label' => __('Личный кабинет - Навигация', 'mst-auth-lk'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('lk_nav_bg', [
            'label' => __('Фон сайдбара', 'mst-auth-lk'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#ffffff',
            'selectors' => [
                '{{WRAPPER}} .mst-lk-sidebar' => 'background-color: {{VALUE}};',
            ],
        ]);

        $this->add_control('lk_nav_item_color', [
            'label' => __('Цвет текста пунктов', 'mst-auth-lk'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#4b5563',
            'selectors' => [
                '{{WRAPPER}} .mst-lk-nav-item' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_control('lk_nav_item_active_bg', [
            'label' => __('Фон активного пункта', 'mst-auth-lk'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '',
            'selectors' => [
                '{{WRAPPER}} .mst-lk-nav-item.active' => 'background: linear-gradient(135deg, {{VALUE}}22 0%, {{VALUE}}11 100%);',
            ],
        ]);

        $this->add_control('lk_nav_item_active_color', [
            'label' => __('Цвет активного пункта', 'mst-auth-lk'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#9952E0',
            'selectors' => [
                '{{WRAPPER}} .mst-lk-nav-item.active' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_control('lk_nav_item_radius', [
            'label' => __('Скругление пунктов', 'mst-auth-lk'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'range' => ['px' => ['min' => 0, 'max' => 20]],
            'default' => ['size' => 12, 'unit' => 'px'],
            'selectors' => [
                '{{WRAPPER}} .mst-lk-nav-item' => 'border-radius: {{SIZE}}px;',
            ],
        ]);

        $this->end_controls_section();

        // === STYLE: LK CARDS ===
        $this->start_controls_section('style_lk_cards', [
            'label' => __('Личный кабинет - Карточки товаров', 'mst-auth-lk'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('lk_card_bg', [
            'label' => __('Фон карточки', 'mst-auth-lk'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#ffffff',
            'selectors' => [
                '{{WRAPPER}} .mst-shop-grid-card' => 'background-color: {{VALUE}};',
            ],
        ]);

        $this->add_control('lk_card_radius', [
            'label' => __('Скругление карточки', 'mst-auth-lk'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'range' => ['px' => ['min' => 0, 'max' => 30]],
            'default' => ['size' => 16, 'unit' => 'px'],
            'selectors' => [
                '{{WRAPPER}} .mst-shop-grid-card' => 'border-radius: {{SIZE}}px;',
            ],
        ]);

        $this->add_control('lk_card_image_height', [
            'label' => __('Высота изображения', 'mst-auth-lk'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'range' => ['px' => ['min' => 100, 'max' => 400]],
            'default' => ['size' => 220, 'unit' => 'px'],
            'selectors' => [
                '{{WRAPPER}} .mst-shop-grid-image' => 'height: {{SIZE}}px; min-height: {{SIZE}}px; max-height: {{SIZE}}px;',
            ],
        ]);

        $this->add_group_control(\Elementor\Group_Control_Box_Shadow::get_type(), [
            'name' => 'lk_card_shadow',
            'label' => __('Тень карточки', 'mst-auth-lk'),
            'selector' => '{{WRAPPER}} .mst-shop-grid-card',
        ]);

        $this->end_controls_section();

        // === STYLE: SPACING ===
        $this->start_controls_section('style_spacing', [
            'label' => __('Отступы', 'mst-auth-lk'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('field_spacing', [
            'label' => __('Отступ между полями', 'mst-auth-lk'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'range' => ['px' => ['min' => 10, 'max' => 40]],
            'default' => ['size' => 20, 'unit' => 'px'],
            'selectors' => [
                '{{WRAPPER}} .mst-auth-field' => 'margin-bottom: {{SIZE}}px;',
            ],
        ]);

        $this->add_control('section_spacing', [
            'label' => __('Отступ между секциями ЛК', 'mst-auth-lk'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'range' => ['px' => ['min' => 10, 'max' => 50]],
            'default' => ['size' => 24, 'unit' => 'px'],
            'selectors' => [
                '{{WRAPPER}} .mst-lk-bottom-wrapper' => 'gap: {{SIZE}}px;',
            ],
        ]);

        $this->end_controls_section();
    }

    protected function render() {
        $s = $this->get_settings_for_display();
        $redirect = !empty($s['redirect_url']['url']) ? $s['redirect_url']['url'] : home_url('/auth/');
        
        // Формируем настройки табов для передачи в шаблон
        $tabs_settings = [
            'orders' => [
                'enabled' => $s['show_orders'] === 'yes', 
                'icon' => '📦', 
                'label' => !empty($s['orders_label']) ? $s['orders_label'] : __('Мои заказы', 'mst-auth-lk')
            ],
            'bookings' => [
                'enabled' => $s['show_bookings'] === 'yes', 
                'icon' => '📅', 
                'label' => !empty($s['bookings_label']) ? $s['bookings_label'] : __('Бронирования', 'mst-auth-lk')
            ],
            'affiliate' => [
                'enabled' => $s['show_affiliate'] === 'yes', 
                'icon' => '💰', 
                'label' => !empty($s['affiliate_label']) ? $s['affiliate_label'] : __('Реферальная программа', 'mst-auth-lk')
            ],
            'wishlist' => [
                'enabled' => $s['show_wishlist'] === 'yes', 
                'icon' => '❤️', 
                'label' => !empty($s['wishlist_label']) ? $s['wishlist_label'] : __('Избранное', 'mst-auth-lk')
            ],
        ];
        
        // Генерируем динамические стили для кнопок
        $this->render_dynamic_styles($s);
        
        // Проверяем авторизацию
        if (is_user_logged_in() && !$this->is_editor()) {
            // Показываем личный кабинет
            $this->render_lk($s, $tabs_settings);
        } else {
            // Показываем форму авторизации
            $this->render_auth_form($s, $redirect);
        }
    }
    
    private function render_dynamic_styles($s) {
        $button_bg = !empty($s['button_bg']) ? $s['button_bg'] : '#9952E0';
        $button_bg_end = !empty($s['button_bg_gradient_end']) ? $s['button_bg_gradient_end'] : '#7B3FC4';
        $button_hover = !empty($s['button_hover_bg']) ? $s['button_hover_bg'] : '#7B3FC4';
        $use_gradient = $s['button_bg_gradient'] === 'yes';
        $hover_transform = $s['button_hover_transform'] === 'yes';
        
        ?>
        <style>
            .elementor-element-<?php echo $this->get_id(); ?> .mst-auth-btn,
            .elementor-element-<?php echo $this->get_id(); ?> .mst-lk-btn-primary {
                <?php if ($use_gradient): ?>
                background: linear-gradient(135deg, <?php echo $button_bg; ?> 0%, <?php echo $button_bg_end; ?> 100%);
                <?php else: ?>
                background: <?php echo $button_bg; ?>;
                <?php endif; ?>
            }
            
            .elementor-element-<?php echo $this->get_id(); ?> .mst-auth-btn:hover,
            .elementor-element-<?php echo $this->get_id(); ?> .mst-lk-btn-primary:hover {
                <?php if ($use_gradient): ?>
                background: linear-gradient(135deg, <?php echo $button_hover; ?> 0%, <?php echo $button_bg; ?> 100%);
                <?php else: ?>
                background: <?php echo $button_hover; ?>;
                <?php endif; ?>
                <?php if ($hover_transform): ?>
                transform: translateY(-2px);
                box-shadow: 0 10px 30px <?php echo $button_bg; ?>4d;
                <?php endif; ?>
            }
            
            <?php if ($s['lk_profile_gradient'] === 'yes'): ?>
            .elementor-element-<?php echo $this->get_id(); ?> .mst-lk-top-profile {
                background: linear-gradient(135deg, #f8f9ff 0%, #fff 100%);
            }
            <?php endif; ?>
        </style>
        <?php
    }
    
    private function render_auth_form($s, $redirect) {
        $uid = 'mst-auth-' . uniqid();
        $form_type = $s['form_type'];
        
        // Тексты из настроек
        $login_btn_text = !empty($s['login_button_text']) ? $s['login_button_text'] : __('Войти', 'mst-auth-lk');
        $register_btn_text = !empty($s['register_button_text']) ? $s['register_button_text'] : __('Зарегистрироваться', 'mst-auth-lk');
        $register_subtitle = !empty($s['register_subtitle']) ? $s['register_subtitle'] : __('Заполните форму для регистрации', 'mst-auth-lk');
        ?>
        
        <div class="mst-auth-wrapper" id="<?php echo esc_attr($uid); ?>">
            <div class="mst-auth-card">
                
                <?php if ($s['show_icon'] === 'yes'): ?>
                <div class="mst-auth-icon">
                    <?php echo $this->render_icon($s); ?>
                </div>
                <?php endif; ?>
                
                <!-- LOGIN FORM -->
                <div class="mst-auth-form-container" data-form="login" <?php echo $form_type === 'register' ? 'style="display:none;"' : ''; ?>>
                    <h2 class="mst-auth-title"><?php echo esc_html($s['login_title']); ?></h2>
                    <p class="mst-auth-subtitle"><?php echo esc_html($s['login_subtitle']); ?></p>
                    
                    <form class="mst-auth-form" data-action="login">
                        <div class="mst-auth-field">
                            <label for="<?php echo $uid; ?>-email">Email</label>
                            <input type="email" id="<?php echo $uid; ?>-email" name="email" class="mst-auth-input" placeholder="your@email.com" required autocomplete="email">
                        </div>
                        
                        <div class="mst-auth-field">
                            <label for="<?php echo $uid; ?>-password"><?php _e('Пароль', 'mst-auth-lk'); ?></label>
                            <div class="mst-auth-password-wrap">
                                <input type="password" id="<?php echo $uid; ?>-password" name="password" class="mst-auth-input" placeholder="••••••••" required autocomplete="current-password">
                                <button type="button" class="mst-auth-toggle-password" aria-label="<?php _e('Показать пароль', 'mst-auth-lk'); ?>">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                        <circle cx="12" cy="12" r="3"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        
                        <div class="mst-auth-error" style="display:none;"></div>
                        
                        <button type="submit" class="mst-auth-btn"><?php echo esc_html($login_btn_text); ?></button>
                        
                        <input type="hidden" name="redirect" value="<?php echo esc_url($redirect); ?>">
                    </form>
                    
                    <?php if ($form_type === 'both'): ?>
                    <p class="mst-auth-switch">
                        <?php _e('Нет аккаунта?', 'mst-auth-lk'); ?> <a href="javascript:void(0)" class="mst-auth-switch-link" data-target="register"><?php _e('Зарегистрируйтесь', 'mst-auth-lk'); ?></a>
                    </p>
                    <?php endif; ?>
                    
                    <p class="mst-auth-forgot">
                        <a href="javascript:void(0)" class="mst-auth-switch-link" data-target="forgot"><?php _e('Забыли пароль?', 'mst-auth-lk'); ?></a>
                    </p>
                </div>
                
                <!-- FORGOT PASSWORD FORM -->
                <div class="mst-auth-form-container" data-form="forgot" style="display:none;">
                    <h2 class="mst-auth-title"><?php _e('Сброс пароля', 'mst-auth-lk'); ?></h2>
                    <p class="mst-auth-subtitle"><?php _e('Введите email для получения инструкций', 'mst-auth-lk'); ?></p>
                    
                    <form class="mst-auth-form" data-action="forgot">
                        <div class="mst-auth-field">
                            <label for="<?php echo $uid; ?>-forgot-email">Email</label>
                            <input type="email" id="<?php echo $uid; ?>-forgot-email" name="email" class="mst-auth-input" placeholder="your@email.com" required autocomplete="email">
                        </div>
                        
                        <div class="mst-auth-error" style="display:none;"></div>
                        <div class="mst-auth-success" style="display:none;"></div>
                        
                        <button type="submit" class="mst-auth-btn"><?php _e('Отправить', 'mst-auth-lk'); ?></button>
                    </form>
                    
                    <p class="mst-auth-switch">
                        <a href="javascript:void(0)" class="mst-auth-switch-link" data-target="login">← <?php _e('Вернуться к входу', 'mst-auth-lk'); ?></a>
                    </p>
                </div>
                
                <!-- REGISTER FORM -->
                <?php if ($form_type === 'register' || $form_type === 'both'): ?>
                <div class="mst-auth-form-container" data-form="register" <?php echo $form_type !== 'register' ? 'style="display:none;"' : ''; ?>>
                    <h2 class="mst-auth-title"><?php echo esc_html($s['register_title']); ?></h2>
                    <p class="mst-auth-subtitle"><?php echo esc_html($register_subtitle); ?></p>
                    
                    <form class="mst-auth-form" data-action="register">
                        <div class="mst-auth-field">
                            <label for="<?php echo $uid; ?>-reg-name"><?php _e('Имя', 'mst-auth-lk'); ?></label>
                            <input type="text" id="<?php echo $uid; ?>-reg-name" name="display_name" class="mst-auth-input" placeholder="<?php _e('Ваше имя', 'mst-auth-lk'); ?>" required autocomplete="name">
                        </div>
                        
                        <div class="mst-auth-field">
                            <label for="<?php echo $uid; ?>-reg-email">Email</label>
                            <input type="email" id="<?php echo $uid; ?>-reg-email" name="email" class="mst-auth-input" placeholder="your@email.com" required autocomplete="email">
                        </div>
                        
                        <div class="mst-auth-field">
                            <label for="<?php echo $uid; ?>-reg-password"><?php _e('Пароль', 'mst-auth-lk'); ?></label>
                            <div class="mst-auth-password-wrap">
                                <input type="password" id="<?php echo $uid; ?>-reg-password" name="password" class="mst-auth-input" placeholder="<?php _e('Минимум 6 символов', 'mst-auth-lk'); ?>" required minlength="6" autocomplete="new-password">
                                <button type="button" class="mst-auth-toggle-password" aria-label="<?php _e('Показать пароль', 'mst-auth-lk'); ?>">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                        <circle cx="12" cy="12" r="3"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        
                        <div class="mst-auth-error" style="display:none;"></div>
                        
                        <button type="submit" class="mst-auth-btn"><?php echo esc_html($register_btn_text); ?></button>
                        
                        <input type="hidden" name="redirect" value="<?php echo esc_url($redirect); ?>">
                    </form>
                    
                    <?php if ($form_type === 'both'): ?>
                    <p class="mst-auth-switch">
                        <?php _e('Уже есть аккаунт?', 'mst-auth-lk'); ?> <a href="javascript:void(0)" class="mst-auth-switch-link" data-target="login"><?php _e('Войдите', 'mst-auth-lk'); ?></a>
                    </p>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
                
            </div>
        </div>
        
        <?php $this->render_auth_scripts($uid); ?>
        
        <?php
    }
    
    private function render_lk($s, $tabs) {
        $user = wp_get_current_user();
        $custom_avatar = get_user_meta($user->ID, 'mst_lk_avatar', true);
        $avatar_url = $custom_avatar ? wp_get_attachment_url($custom_avatar) : get_avatar_url($user->ID, ['size' => 400]);
        $user_bonuses = get_user_meta($user->ID, 'mst_user_bonuses', true) ?: 0;
        $user_status = get_user_meta($user->ID, 'mst_user_status', true) ?: 'bronze';
        $user_status_label = get_user_meta($user->ID, 'mst_user_status_label', true) ?: __('Бронзовый статус', 'mst-auth-lk');
        
        $status_colors = [
            'bronze' => '#CD7F32',
            'silver' => '#C0C0C0', 
            'gold' => '#FFD700',
            'guide' => '#9952E0'
        ];
        $border_color = $status_colors[$user_status] ?? '#CD7F32';
        
        // Включаем шаблон профиля
        include MST_AUTH_LK_DIR . 'templates/profile.php';
    }
    
    private function render_auth_scripts($uid) {
        ?>
        <script>
        (function() {
            var wrapper = document.getElementById('<?php echo $uid; ?>');
            if (!wrapper) return;
            
            // Toggle password visibility
            wrapper.querySelectorAll('.mst-auth-toggle-password').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    var input = this.previousElementSibling;
                    input.type = input.type === 'password' ? 'text' : 'password';
                });
            });
            
            // Switch between forms
            wrapper.querySelectorAll('.mst-auth-switch-link').forEach(function(link) {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    var target = this.getAttribute('data-target');
                    wrapper.querySelectorAll('.mst-auth-form-container').forEach(function(form) {
                        form.style.display = form.getAttribute('data-form') === target ? 'block' : 'none';
                    });
                });
            });
            
            // Form submission
            wrapper.querySelectorAll('.mst-auth-form').forEach(function(form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    var btn = form.querySelector('.mst-auth-btn');
                    var errorDiv = form.querySelector('.mst-auth-error');
                    var successDiv = form.querySelector('.mst-auth-success');
                    var action = form.getAttribute('data-action');
                    var formData = new FormData(form);
                    
                    btn.disabled = true;
                    btn.textContent = '<?php _e('Загрузка...', 'mst-auth-lk'); ?>';
                    errorDiv.style.display = 'none';
                    if (successDiv) successDiv.style.display = 'none';
                    
                    formData.append('action', 'mst_auth_' + action);
                    formData.append('nonce', mstAuthLK.auth_nonce);
                    
                    fetch(mstAuthLK.ajax_url, {
                        method: 'POST',
                        body: formData,
                        credentials: 'same-origin'
                    })
                    .then(function(r) { return r.json(); })
                    .then(function(data) {
                        if (data.success) {
                            if (action === 'forgot') {
                                if (successDiv) {
                                    successDiv.textContent = data.data.message;
                                    successDiv.style.display = 'block';
                                }
                                btn.disabled = false;
                                btn.textContent = '<?php _e('Отправить', 'mst-auth-lk'); ?>';
                                form.reset();
                            } else {
                                window.location.href = data.data.redirect;
                            }
                        } else {
                            errorDiv.textContent = data.data || '<?php _e('Ошибка', 'mst-auth-lk'); ?>';
                            errorDiv.style.display = 'block';
                            btn.disabled = false;
                            btn.textContent = action === 'login' ? '<?php _e('Войти', 'mst-auth-lk'); ?>' : 
                                              action === 'register' ? '<?php _e('Зарегистрироваться', 'mst-auth-lk'); ?>' : 
                                              '<?php _e('Отправить', 'mst-auth-lk'); ?>';
                        }
                    })
                    .catch(function() {
                        errorDiv.textContent = '<?php _e('Ошибка сети', 'mst-auth-lk'); ?>';
                        errorDiv.style.display = 'block';
                        btn.disabled = false;
                    });
                });
            });
        })();
        </script>
        <?php
    }

    private function render_icon($s) {
        switch ($s['icon_type']) {
            case 'sparkle':
                return '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 0L14.59 9.41L24 12L14.59 14.59L12 24L9.41 14.59L0 12L9.41 9.41L12 0Z"/></svg>';
            case 'user':
                return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>';
            case 'lock':
                return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>';
            default:
                return '';
        }
    }

    private function is_editor() {
        return \Elementor\Plugin::$instance->editor->is_edit_mode() || \Elementor\Plugin::$instance->preview->is_preview_mode();
    }
}
