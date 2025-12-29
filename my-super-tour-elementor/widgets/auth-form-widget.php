<?php
/**
 * MST Auth Form Widget - Login/Register
 * Elementor widget for beautiful auth forms
 */

if (!defined('ABSPATH')) exit;

class MST_Auth_Form_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'mst_auth_form';
    }

    public function get_title() {
        return 'MST Форма Входа';
    }

    public function get_icon() {
        return 'eicon-lock-user';
    }

    public function get_categories() {
        return ['mysupertour'];
    }

    public function get_keywords() {
        return ['login', 'register', 'auth', 'form', 'вход', 'регистрация'];
    }

    protected function register_controls() {
        
        // === CONTENT SECTION ===
        $this->start_controls_section('content_section', [
            'label' => 'Контент',
            'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control('form_type', [
            'label' => 'Тип формы',
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => 'login',
            'options' => [
                'login' => 'Вход',
                'register' => 'Регистрация',
                'both' => 'Вход + Регистрация (переключение)',
            ],
        ]);

        $this->add_control('show_icon', [
            'label' => 'Показать иконку',
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => 'yes',
        ]);

        $this->add_control('icon_type', [
            'label' => 'Тип иконки',
            'type' => \Elementor\Controls_Manager:: SELECT,
            'default' => 'sparkle',
            'options' => [
                'sparkle' => '✨ Звезда',
                'user' => '👤 Пользователь',
                'lock' => '🔐 Замок',
                'custom' => 'Своя иконка',
            ],
            'condition' => ['show_icon' => 'yes'],
        ]);

        $this->add_control('custom_icon', [
            'label' => 'Своя иконка',
            'type' => \Elementor\Controls_Manager:: ICONS,
            'condition' => ['icon_type' => 'custom', 'show_icon' => 'yes'],
        ]);

        $this->add_control('login_title', [
            'label' => 'Заголовок входа',
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => 'С возвращением! ',
        ]);

        $this->add_control('login_subtitle', [
            'label' => 'Подзаголовок входа',
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => 'Войдите, чтобы продолжить',
        ]);

        $this->add_control('register_title', [
            'label' => 'Заголовок регистрации',
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => 'Создать аккаунт',
            'condition' => ['form_type' => ['register', 'both']],
        ]);

        $this->add_control('register_subtitle', [
            'label' => 'Подзаголовок регистрации',
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => 'Заполните форму для регистрации',
            'condition' => ['form_type' => ['register', 'both']],
        ]);

        $this->add_control('redirect_url', [
            'label' => 'Редирект после входа',
            'type' => \Elementor\Controls_Manager::URL,
            'placeholder' => home_url('/auth/'),
            'default' => ['url' => ''],
        ]);

        $this->end_controls_section();

        // === STYLE: CARD ===
        $this->start_controls_section('style_card', [
            'label' => 'Карточка',
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('card_bg', [
            'label' => 'Фон карточки',
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#ffffff',
            'selectors' => ['.mst-auth-card' => 'background-color: {{VALUE}};'],
        ]);

        $this->add_control('card_radius', [
            'label' => 'Скругление',
            'type' => \Elementor\Controls_Manager:: SLIDER,
            'default' => ['size' => 24],
            'range' => ['px' => ['min' => 0, 'max' => 50]],
            'selectors' => ['.mst-auth-card' => 'border-radius: {{SIZE}}px;'],
        ]);

        $this->add_group_control(\Elementor\Group_Control_Box_Shadow::get_type(), [
            'name' => 'card_shadow',
            'selector' => '{{WRAPPER}} .mst-auth-card',
        ]);

        $this->add_responsive_control('card_padding', [
            'label' => 'Внутренний отступ',
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'default' => ['top' => 48, 'right' => 48, 'bottom' => 48, 'left' => 48, 'unit' => 'px'],
            'selectors' => ['.mst-auth-card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('card_max_width', [
            'label' => 'Макс. ширина',
            'type' => \Elementor\Controls_Manager::SLIDER,
            'default' => ['size' => 420],
            'range' => ['px' => ['min' => 300, 'max' => 600]],
            'selectors' => ['.mst-auth-card' => 'max-width: {{SIZE}}px;'],
        ]);

        $this->end_controls_section();

        // === STYLE: ICON ===
        $this->start_controls_section('style_icon', [
            'label' => 'Иконка',
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            'condition' => ['show_icon' => 'yes'],
        ]);

        $this->add_control('icon_bg', [
            'label' => 'Фон иконки',
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#9952E0',
            'selectors' => ['.mst-auth-icon' => 'background: linear-gradient(135deg, {{VALUE}} 0%, ' . $this->hex_darker('{{VALUE}}', 20) . ' 100%);'],
        ]);

        $this->add_control('icon_color', [
            'label' => 'Цвет иконки',
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#ffffff',
            'selectors' => ['.mst-auth-icon svg, .mst-auth-icon' => 'color: {{VALUE}}; fill: {{VALUE}};'],
        ]);

        $this->add_control('icon_size', [
            'label' => 'Размер',
            'type' => \Elementor\Controls_Manager:: SLIDER,
            'default' => ['size' => 64],
            'range' => ['px' => ['min' => 40, 'max' => 100]],
            'selectors' => ['.mst-auth-icon' => 'width: {{SIZE}}px; height: {{SIZE}}px;'],
        ]);

        $this->end_controls_section();

        // === STYLE: TYPOGRAPHY ===
        $this->start_controls_section('style_typography', [
            'label' => 'Типографика',
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('title_color', [
            'label' => 'Цвет заголовка',
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#9952E0',
            'selectors' => ['.mst-auth-title' => 'color: {{VALUE}};'],
        ]);

        $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
            'name' => 'title_typography',
            'selector' => '{{WRAPPER}} .mst-auth-title',
        ]);

        $this->add_control('subtitle_color', [
            'label' => 'Цвет подзаголовка',
            'type' => \Elementor\Controls_Manager:: COLOR,
            'default' => '#666666',
            'selectors' => ['.mst-auth-subtitle' => 'color: {{VALUE}};'],
        ]);

        $this->end_controls_section();

        // === STYLE: INPUTS ===
        $this->start_controls_section('style_inputs', [
            'label' => 'Поля ввода',
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('input_border_color', [
            'label' => 'Цвет рамки',
            'type' => \Elementor\Controls_Manager:: COLOR,
            'default' => '#e0e0e0',
            'selectors' => ['.mst-auth-input' => 'border-color: {{VALUE}};'],
        ]);

        $this->add_control('input_focus_color', [
            'label' => 'Цвет при фокусе',
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#9952E0',
            'selectors' => ['.mst-auth-input: focus' => 'border-color: {{VALUE}}; box-shadow: 0 0 0 3px {{VALUE}}22;'],
        ]);

        $this->add_control('input_radius', [
            'label' => 'Скругление',
            'type' => \Elementor\Controls_Manager::SLIDER,
            'default' => ['size' => 12],
            'range' => ['px' => ['min' => 0, 'max' => 30]],
            'selectors' => ['.mst-auth-input' => 'border-radius: {{SIZE}}px;'],
        ]);

        $this->end_controls_section();

        // === STYLE: BUTTON ===
        $this->start_controls_section('style_button', [
            'label' => 'Кнопка',
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('button_bg', [
            'label' => 'Фон кнопки',
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#9952E0',
            'selectors' => ['.mst-auth-btn' => 'background: linear-gradient(135deg, {{VALUE}} 0%, #7b3bbf 100%);'],
        ]);

        $this->add_control('button_color', [
            'label' => 'Цвет текста',
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#ffffff',
            'selectors' => ['.mst-auth-btn' => 'color: {{VALUE}};'],
        ]);

        $this->add_control('button_radius', [
            'label' => 'Скругление',
            'type' => \Elementor\Controls_Manager::SLIDER,
            'default' => ['size' => 12],
            'range' => ['px' => ['min' => 0, 'max' => 30]],
            'selectors' => ['.mst-auth-btn' => 'border-radius: {{SIZE}}px;'],
        ]);

        $this->end_controls_section();
    }

    private function hex_darker($hex, $percent) {
        return $hex; // Simplified - use CSS calc in real implementation
    }

    protected function render() {
        $s = $this->get_settings_for_display();
        $form_type = $s['form_type'];
        $redirect = ! empty($s['redirect_url']['url']) ? $s['redirect_url']['url'] : home_url('/auth/');
        $uid = 'mst-auth-' .uniqid();
        
        // Check if user is logged in
        if (is_user_logged_in() && !$this->is_editor()) {
            echo '<div class="mst-auth-logged-in"><p>Вы уже вошли в систему.  <a href="' .  esc_url(home_url('/auth/')) . '">Перейти в личный кабинет</a></p></div>';
            return;
        }
        ?>
        
        <div class="mst-auth-wrapper" id="<?php echo $uid; ?>">
            <div class="mst-auth-card">
                
                <?php if ($s['show_icon'] === 'yes'): ?>
                <div class="mst-auth-icon">
                    <?php echo $this->render_icon($s); ?>
                </div>
                <?php endif; ?>
                
                <!-- LOGIN FORM -->
                <div class="mst-auth-form-container" data-form="login" <?php echo $form_type === 'register' ? 'style="display: none;"' : ''; ?>>
                    <h2 class="mst-auth-title"><?php echo esc_html($s['login_title']); ?></h2>
                    <p class="mst-auth-subtitle"><?php echo esc_html($s['login_subtitle']); ?></p>
                    
                    <form class="mst-auth-form" data-action="login">
                        <div class="mst-auth-field">
                            <label for="<?php echo $uid; ?>-email">Email</label>
                            <input type="email" id="<?php echo $uid; ?>-email" name="email" class="mst-auth-input" placeholder="your@email.com" required>
                        </div>
                        
                        <div class="mst-auth-field">
                            <label for="<?php echo $uid; ?>-password">Пароль</label>
                            <div class="mst-auth-password-wrap">
                                <input type="password" id="<?php echo $uid; ?>-password" name="password" class="mst-auth-input" placeholder="••••••••" required>
                                <button type="button" class="mst-auth-toggle-password" aria-label="Показать пароль">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                        <circle cx="12" cy="12" r="3"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        
                        <div class="mst-auth-error" style="display:none;"></div>
                        
                        <button type="submit" class="mst-auth-btn">Войти</button>
                        
                        <input type="hidden" name="redirect" value="<?php echo esc_url($redirect); ?>">
                    </form>
                    
                    <?php if ($form_type === 'both'): ?>
                    <p class="mst-auth-switch">
                        Нет аккаунта?  <a href="#" class="mst-auth-switch-link" data-target="register">Зарегистрируйтесь</a>
                    </p>
                    <?php endif; ?>
                    
                    <p class="mst-auth-forgot">
                        <a href="<?php echo wp_lostpassword_url(); ?>">Забыли пароль? </a>
                    </p>
                </div>
                
                <!-- REGISTER FORM -->
                <?php if ($form_type === 'register' || $form_type === 'both'): ?>
                <div class="mst-auth-form-container" data-form="register" <?php echo $form_type !== 'register' ? 'style="display:none;"' : ''; ?>>
                    <h2 class="mst-auth-title"><?php echo esc_html($s['register_title']); ?></h2>
                    <p class="mst-auth-subtitle"><?php echo esc_html($s['register_subtitle']); ?></p>
                    
                    <form class="mst-auth-form" data-action="register">
                        <div class="mst-auth-field">
                            <label for="<?php echo $uid; ?>-reg-name">Имя</label>
                            <input type="text" id="<?php echo $uid; ?>-reg-name" name="display_name" class="mst-auth-input" placeholder="Ваше имя" required>
                        </div>
                        
                        <div class="mst-auth-field">
                            <label for="<?php echo $uid; ?>-reg-email">Email</label>
                            <input type="email" id="<?php echo $uid; ?>-reg-email" name="email" class="mst-auth-input" placeholder="your@email.com" required>
                        </div>
                        
                        <div class="mst-auth-field">
                            <label for="<?php echo $uid; ?>-reg-password">Пароль</label>
                            <div class="mst-auth-password-wrap">
                                <input type="password" id="<?php echo $uid; ?>-reg-password" name="password" class="mst-auth-input" placeholder="Минимум 8 символов" required minlength="8">
                                <button type="button" class="mst-auth-toggle-password" aria-label="Показать пароль">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                        <circle cx="12" cy="12" r="3"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        
                        <div class="mst-auth-error" style="display:none;"></div>
                        
                        <button type="submit" class="mst-auth-btn">Зарегистрироваться</button>
                        
                        <input type="hidden" name="redirect" value="<?php echo esc_url($redirect); ?>">
                    </form>
                    
                    <?php if ($form_type === 'both'): ?>
                    <p class="mst-auth-switch">
                        Уже есть аккаунт? <a href="#" class="mst-auth-switch-link" data-target="login">Войдите</a>
                    </p>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
                
            </div>
        </div>
        
        <style>
        .mst-auth-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100%;
        }
        .mst-auth-card {
            width: 100%;
            max-width: 420px;
            background: #fff;
            border-radius: 24px;
            padding: 48px;
            box-shadow: 0 8px 40px rgba(0,0,0,0.08);
            text-align: center;
        }
        .mst-auth-icon {
            width: 64px;
            height: 64px;
            margin: 0 auto 24px;
            background: linear-gradient(135deg, #9952E0 0%, #7b3bbf 100%);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
        }
        .mst-auth-icon svg {
            width: 32px;
            height: 32px;
        }
        .mst-auth-title {
            font-size: 28px;
            font-weight: 700;
            color: #9952E0;
            margin: 0 0 8px;
        }
        .mst-auth-subtitle {
            font-size: 15px;
            color: #666;
            margin: 0 0 32px;
        }
        .mst-auth-field {
            margin-bottom: 20px;
            text-align: left;
        }
        .mst-auth-field label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
        }
        .mst-auth-input {
            width: 100%;
            padding: 14px 16px;
            border: 1px solid #e0e0e0;
            border-radius: 12px;
            font-size: 15px;
            transition: all 0.2s;
            box-sizing: border-box;
        }
        .mst-auth-input: focus {
            border-color: #9952E0;
            box-shadow: 0 0 0 3px rgba(153,82,224,0.1);
            outline: none;
        }
        .mst-auth-password-wrap {
            position: relative;
        }
        .mst-auth-password-wrap .mst-auth-input {
            padding-right: 48px;
        }
        .mst-auth-toggle-password {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #999;
            cursor: pointer;
            padding: 4px;
        }
        .mst-auth-toggle-password:hover {
            color: #9952E0;
        }
        .mst-auth-btn {
            width: 100%;
            padding: 16px 24px;
            background: linear-gradient(135deg, #9952E0 0%, #7b3bbf 100%);
            color: #fff;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            margin-top: 8px;
        }
        .mst-auth-btn: hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(153,82,224,0.4);
        }
        .mst-auth-btn:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }
        .mst-auth-error {
            background: #fee2e2;
            color: #dc2626;
            padding: 12px 16px;
            border-radius: 8px;
            font-size: 14px;
            margin-bottom: 16px;
            text-align: left;
        }
        .mst-auth-switch, .mst-auth-forgot {
            margin-top: 20px;
            font-size: 14px;
            color: #666;
        }
        .mst-auth-switch a, .mst-auth-forgot a {
            color: #9952E0;
            font-weight: 600;
            text-decoration: none;
        }
        .mst-auth-switch a: hover, .mst-auth-forgot a:hover {
            text-decoration: underline;
        }
        .mst-auth-logged-in {
            text-align: center;
            padding: 40px;
        }
        .mst-auth-logged-in a {
            color: #9952E0;
            font-weight: 600;
        }
        @media (max-width: 480px) {
            .mst-auth-card {
                padding: 32px 24px;
                margin: 16px;
            }
        }
        </style>
        
        <script>
        (function() {
            var wrapper = document.getElementById('<?php echo $uid; ?>');
            if (! wrapper) return;
            
            // Toggle password visibility
            wrapper.querySelectorAll('.mst-auth-toggle-password').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    var input = this.previousElementSibling;
                    input.type = input.type === 'password' ? 'text' : 'password';
                });
            });
            
            // Switch between login/register
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
                    var action = form.getAttribute('data-action');
                    var formData = new FormData(form);
                    
                    btn.disabled = true;
                    btn.textContent = 'Загрузка...';
                    errorDiv.style.display = 'none';
                    
                    formData.append('action', 'mst_auth_' + action);
                    formData.append('nonce', '<?php echo wp_create_nonce("mst_auth_nonce"); ?>');
                    
                    fetch('<?php echo admin_url("admin-ajax.php"); ?>', {
                        method: 'POST',
                        body: formData
                    })
                    .then(function(r) { return r.json(); })
                    .then(function(data) {
                        if (data.success) {
                            window.location.href = data.data.redirect || '<?php echo esc_url($redirect); ?>';
                        } else {
                            errorDiv.textContent = data.data || 'Ошибка';
                            errorDiv.style.display = 'block';
                            btn.disabled = false;
                            btn.textContent = action === 'login' ? 'Войти' : 'Зарегистрироваться';
                        }
                    })
                    .catch(function() {
                        errorDiv.textContent = 'Ошибка сети';
                        errorDiv.style.display = 'block';
                        btn.disabled = false;
                        btn.textContent = action === 'login' ? 'Войти' : 'Зарегистрироваться';
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
            case 'custom':
                if (! empty($s['custom_icon']['value'])) {
                    ob_start();
                    \Elementor\Icons_Manager::render_icon($s['custom_icon'], ['aria-hidden' => 'true']);
                    return ob_get_clean();
                }
                return '';
            default:
                return '';
        }
    }

    private function is_editor() {
        return \Elementor\Plugin::$instance->editor->is_edit_mode() || \Elementor\Plugin::$instance->preview->is_preview_mode();
    }
}