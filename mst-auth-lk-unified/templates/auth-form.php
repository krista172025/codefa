<?php
/**
 * Auth Form Template с OTP + OAuth
 * ИСПРАВЛЕННАЯ ВЕРСИЯ - Fixed bugs:
 * 1. href="#" replaced with javascript:void(0) to prevent /#
 * 2. Added return false to prevent any navigation
 * Author: Telegram @l1ghtsun
 */
if (!defined('ABSPATH')) exit;

$oauth_enabled = get_option('mst_oauth_enabled', [
    'google' => false,
    'vk' => false,
    'yandex' => false
]);
?>

<div class="mst-auth-wrapper" id="<?php echo esc_attr($uid); ?>">
    <div class="mst-auth-card">
        
        <div class="mst-auth-icon">
            <svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 0L14.59 9.41L24 12L14.59 14.59L12 24L9.41 14.59L0 12L9.41 9.41L12 0Z"/></svg>
        </div>
        
        <!-- LOGIN FORM -->
        <div class="mst-auth-form-container" data-form="login" <?php echo $form_type === 'register' ? 'style="display:none;"' : ''; ?>>
            <h2 class="mst-auth-title"><?php _e('С возвращением!', 'mst-auth-lk'); ?></h2>
            <p class="mst-auth-subtitle"><?php _e('Войдите, чтобы продолжить', 'mst-auth-lk'); ?></p>
            
            <!-- OAuth Buttons -->
            <?php if ($oauth_enabled['google'] || $oauth_enabled['vk'] || $oauth_enabled['yandex']): ?>
            <div class="mst-oauth-buttons">
                <?php if ($oauth_enabled['google']): ?>
                <a href="<?php echo esc_url(add_query_arg(['mst_oauth' => 'google', 'redirect' => urlencode($redirect)], home_url())); ?>" class="mst-oauth-btn mst-oauth-google">
                    <svg viewBox="0 0 24 24" width="20" height="20"><path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/><path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/><path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/><path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/></svg>
                    Google
                </a>
                <?php endif; ?>
                
                <?php if ($oauth_enabled['vk']): ?>
                <a href="<?php echo esc_url(add_query_arg(['mst_oauth' => 'vk', 'redirect' => urlencode($redirect)], home_url())); ?>" class="mst-oauth-btn mst-oauth-vk">
                    <svg viewBox="0 0 24 24" width="20" height="20" fill="#4C75A3"><path d="M15.684 0H8.316C1.592 0 0 1.592 0 8.316v7.368C0 22.408 1.592 24 8.316 24h7.368C22.408 24 24 22.408 24 15.684V8.316C24 1.592 22.408 0 15.684 0zm3.692 17.123h-1.744c-.66 0-.864-.523-2.052-1.727-1.032-1.008-1.488-1.143-1.744-1.143-.36 0-.456.096-.456.552v1.584c0 .396-.12.624-1.128.624-1.668 0-3.516-.996-4.812-2.868-1.956-2.76-2.496-4.836-2.496-5.268 0-.252.096-.492.552-.492h1.752c.408 0 .564.18.72.612.792 2.292 2.112 4.296 2.652 4.296.204 0 .3-.096.3-.612V9.123c-.072-1.2-.696-1.296-.696-1.728 0-.18.144-.36.384-.36h2.748c.324 0 .444.18.444.576v3.108c0 .324.144.444.24.444.204 0 .372-.12.744-.492 1.152-1.284 1.968-3.264 1.968-3.264.108-.228.3-.444.696-.444h1.752c.528 0 .648.276.528.66-.216 1.02-2.352 3.99-2.352 3.99-.18.3-.252.432 0 .768.18.252.792.768 1.2 1.236.756.864 1.32 1.584 1.476 2.076.156.48-.084.732-.564.732z"/></svg>
                    VK
                </a>
                <?php endif; ?>
                
                <?php if ($oauth_enabled['yandex']): ?>
                <a href="<?php echo esc_url(add_query_arg(['mst_oauth' => 'yandex', 'redirect' => urlencode($redirect)], home_url())); ?>" class="mst-oauth-btn mst-oauth-yandex">
                    <svg viewBox="0 0 24 24" width="20" height="20"><path fill="#FC3F1D" d="M2.04 12c0-5.523 4.476-10 10-10 5.522 0 10 4.477 10 10s-4.478 10-10 10c-5.524 0-10-4.477-10-10zm6.627 6.325h2.008V6.625h-1.26c-2.334 0-3.566 1.256-3.566 3.067 0 1.515.602 2.402 1.848 3.319l-2.091 5.314h2.187l1.975-4.924.547.367c1.066.712 1.54 1.283 1.54 2.528v2.029h-.001zm-.16-8.437c0-1.022.596-1.643 1.52-1.643h.16v4.268l-.49-.327c-.802-.535-1.19-1.093-1.19-2.298z"/></svg>
                    Яндекс
                </a>
                <?php endif; ?>
            </div>
            
            <?php if ($oauth_enabled['google'] || $oauth_enabled['vk'] || $oauth_enabled['yandex']): ?>
            <div class="mst-oauth-divider">
                <span><?php _e('или', 'mst-auth-lk'); ?></span>
            </div>
            <?php endif; ?>
            <?php endif; ?>
            
            <form class="mst-auth-form" data-action="login">
                <div class="mst-auth-field">
                    <label for="<?php echo $uid; ?>-email">Email</label>
                    <input type="email" id="<?php echo $uid; ?>-email" name="email" class="mst-auth-input" placeholder="your@email.com" required autocomplete="email">
                </div>
                
                <div class="mst-auth-field">
                    <label for="<?php echo $uid; ?>-password"><?php _e('Пароль', 'mst-auth-lk'); ?></label>
                    <div class="mst-auth-password-wrap">
                        <input type="password" id="<?php echo $uid; ?>-password" name="password" class="mst-auth-input" placeholder="••••••••" required autocomplete="current-password">
                        <button type="button" class="mst-auth-toggle-password">👁</button>
                    </div>
                </div>
                
                <div class="mst-auth-error" style="display:none;"></div>
                
                <button type="submit" class="mst-auth-btn"><?php _e('Войти', 'mst-auth-lk'); ?></button>
                
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
        
        <!-- OTP VERIFICATION -->
        <div class="mst-auth-form-container" data-form="otp" style="display:none;">
            <h2 class="mst-auth-title"><?php _e('Подтверждение', 'mst-auth-lk'); ?></h2>
            <p class="mst-auth-subtitle"><?php _e('Введите код, отправленный на ваш email', 'mst-auth-lk'); ?></p>
            
            <form class="mst-auth-form" data-action="verify_otp">
                <input type="hidden" name="email" class="mst-otp-email" value="">
                <input type="hidden" name="password" class="mst-otp-password" value="">
                <input type="hidden" name="redirect" class="mst-otp-redirect" value="">
                
                <div class="mst-otp-inputs">
                    <input type="text" maxlength="1" class="mst-otp-digit" data-index="0" inputmode="numeric" autocomplete="one-time-code">
                    <input type="text" maxlength="1" class="mst-otp-digit" data-index="1" inputmode="numeric">
                    <input type="text" maxlength="1" class="mst-otp-digit" data-index="2" inputmode="numeric">
                    <input type="text" maxlength="1" class="mst-otp-digit" data-index="3" inputmode="numeric">
                    <input type="text" maxlength="1" class="mst-otp-digit" data-index="4" inputmode="numeric">
                    <input type="text" maxlength="1" class="mst-otp-digit" data-index="5" inputmode="numeric">
                </div>
                <input type="hidden" name="otp_code" class="mst-otp-code" value="">
                
                <div class="mst-auth-error" style="display:none;"></div>
                
                <button type="submit" class="mst-auth-btn"><?php _e('Подтвердить', 'mst-auth-lk'); ?></button>
                
                <div class="mst-otp-resend">
                    <span class="mst-otp-timer"><?php _e('Отправить повторно через', 'mst-auth-lk'); ?> <span class="mst-otp-countdown">60</span>с</span>
                    <a href="javascript:void(0)" class="mst-otp-resend-link" style="display:none;"><?php _e('Отправить код повторно', 'mst-auth-lk'); ?></a>
                </div>
                
                <div class="mst-otp-remember">
                    <label>
                        <input type="checkbox" name="remember_device" value="1" checked>
                        <?php _e('Запомнить это устройство', 'mst-auth-lk'); ?>
                    </label>
                </div>
            </form>
            
            <p class="mst-auth-switch">
                <a href="javascript:void(0)" class="mst-auth-switch-link" data-target="login">← <?php _e('Вернуться к входу', 'mst-auth-lk'); ?></a>
            </p>
        </div>
        
        <!-- FORGOT PASSWORD -->
        <div class="mst-auth-form-container" data-form="forgot" style="display:none;">
            <h2 class="mst-auth-title"><?php _e('Сброс пароля', 'mst-auth-lk'); ?></h2>
            <p class="mst-auth-subtitle"><?php _e('Введите email для получения инструкций', 'mst-auth-lk'); ?></p>
            
            <form class="mst-auth-form" data-action="forgot">
                <div class="mst-auth-field">
                    <label>Email</label>
                    <input type="email" name="email" class="mst-auth-input" placeholder="your@email.com" required autocomplete="email">
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
            <h2 class="mst-auth-title"><?php _e('Создать аккаунт', 'mst-auth-lk'); ?></h2>
            <p class="mst-auth-subtitle"><?php _e('Заполните форму для регистрации', 'mst-auth-lk'); ?></p>
            
            <!-- OAuth Buttons for Register too -->
            <?php if ($oauth_enabled['google'] || $oauth_enabled['vk'] || $oauth_enabled['yandex']): ?>
            <div class="mst-oauth-buttons">
                <?php if ($oauth_enabled['google']): ?>
                <a href="<?php echo esc_url(add_query_arg(['mst_oauth' => 'google', 'redirect' => urlencode($redirect)], home_url())); ?>" class="mst-oauth-btn mst-oauth-google">
                    <svg viewBox="0 0 24 24" width="20" height="20"><path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/><path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/><path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/><path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/></svg>
                    Google
                </a>
                <?php endif; ?>
                
                <?php if ($oauth_enabled['vk']): ?>
                <a href="<?php echo esc_url(add_query_arg(['mst_oauth' => 'vk', 'redirect' => urlencode($redirect)], home_url())); ?>" class="mst-oauth-btn mst-oauth-vk">
                    <svg viewBox="0 0 24 24" width="20" height="20" fill="#4C75A3"><path d="M15.684 0H8.316C1.592 0 0 1.592 0 8.316v7.368C0 22.408 1.592 24 8.316 24h7.368C22.408 24 24 22.408 24 15.684V8.316C24 1.592 22.408 0 15.684 0zm3.692 17.123h-1.744c-.66 0-.864-.523-2.052-1.727-1.032-1.008-1.488-1.143-1.744-1.143-.36 0-.456.096-.456.552v1.584c0 .396-.12.624-1.128.624-1.668 0-3.516-.996-4.812-2.868-1.956-2.76-2.496-4.836-2.496-5.268 0-.252.096-.492.552-.492h1.752c.408 0 .564.18.72.612.792 2.292 2.112 4.296 2.652 4.296.204 0 .3-.096.3-.612V9.123c-.072-1.2-.696-1.296-.696-1.728 0-.18.144-.36.384-.36h2.748c.324 0 .444.18.444.576v3.108c0 .324.144.444.24.444.204 0 .372-.12.744-.492 1.152-1.284 1.968-3.264 1.968-3.264.108-.228.3-.444.696-.444h1.752c.528 0 .648.276.528.66-.216 1.02-2.352 3.99-2.352 3.99-.18.3-.252.432 0 .768.18.252.792.768 1.2 1.236.756.864 1.32 1.584 1.476 2.076.156.48-.084.732-.564.732z"/></svg>
                    VK
                </a>
                <?php endif; ?>
                
                <?php if ($oauth_enabled['yandex']): ?>
                <a href="<?php echo esc_url(add_query_arg(['mst_oauth' => 'yandex', 'redirect' => urlencode($redirect)], home_url())); ?>" class="mst-oauth-btn mst-oauth-yandex">
                    <svg viewBox="0 0 24 24" width="20" height="20"><path fill="#FC3F1D" d="M2.04 12c0-5.523 4.476-10 10-10 5.522 0 10 4.477 10 10s-4.478 10-10 10c-5.524 0-10-4.477-10-10zm6.627 6.325h2.008V6.625h-1.26c-2.334 0-3.566 1.256-3.566 3.067 0 1.515.602 2.402 1.848 3.319l-2.091 5.314h2.187l1.975-4.924.547.367c1.066.712 1.54 1.283 1.54 2.528v2.029h-.001zm-.16-8.437c0-1.022.596-1.643 1.52-1.643h.16v4.268l-.49-.327c-.802-.535-1.19-1.093-1.19-2.298z"/></svg>
                    Яндекс
                </a>
                <?php endif; ?>
            </div>
            
            <?php if ($oauth_enabled['google'] || $oauth_enabled['vk'] || $oauth_enabled['yandex']): ?>
            <div class="mst-oauth-divider">
                <span><?php _e('или', 'mst-auth-lk'); ?></span>
            </div>
            <?php endif; ?>
            <?php endif; ?>
            
            <form class="mst-auth-form" data-action="register">
                <div class="mst-auth-field">
                    <label><?php _e('Имя', 'mst-auth-lk'); ?></label>
                    <input type="text" name="display_name" class="mst-auth-input" placeholder="<?php _e('Ваше имя', 'mst-auth-lk'); ?>" required autocomplete="name">
                </div>
                
                <div class="mst-auth-field">
                    <label>Email</label>
                    <input type="email" name="email" class="mst-auth-input" placeholder="your@email.com" required autocomplete="email">
                </div>
                
                <div class="mst-auth-field">
                    <label><?php _e('Пароль', 'mst-auth-lk'); ?></label>
                    <div class="mst-auth-password-wrap">
                        <input type="password" name="password" class="mst-auth-input" placeholder="<?php _e('Минимум 6 символов', 'mst-auth-lk'); ?>" required minlength="6" autocomplete="new-password">
                        <button type="button" class="mst-auth-toggle-password">👁</button>
                    </div>
                </div>
                
                <div class="mst-auth-error" style="display:none;"></div>
                
                <button type="submit" class="mst-auth-btn"><?php _e('Зарегистрироваться', 'mst-auth-lk'); ?></button>
                
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

<style>
/* OAuth Buttons */
.mst-oauth-buttons {
    display: flex;
    gap: 10px;
    margin-bottom: 20px;
}

.mst-oauth-btn {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 12px 16px;
    border: 1px solid #e5e7eb;
    border-radius: 10px;
    background: #fff;
    color: #374151;
    font-weight: 500;
    font-size: 14px;
    text-decoration: none;
    transition: all 0.2s;
}

.mst-oauth-btn:hover {
    background: #f9fafb;
    border-color: #d1d5db;
    transform: translateY(-1px);
}

.mst-oauth-google:hover { border-color: #4285F4; }
.mst-oauth-vk:hover { border-color: #4C75A3; }
.mst-oauth-yandex:hover { border-color: #FC3F1D; }

.mst-oauth-divider {
    display: flex;
    align-items: center;
    margin: 20px 0;
    color: #9ca3af;
    font-size: 13px;
}

.mst-oauth-divider::before,
.mst-oauth-divider::after {
    content: '';
    flex: 1;
    height: 1px;
    background: #e5e7eb;
}

.mst-oauth-divider span {
    padding: 0 16px;
}

/* OTP Inputs */
.mst-otp-inputs {
    display: flex;
    gap: 10px;
    justify-content: center;
    margin: 24px 0;
}

.mst-otp-digit {
    width: 48px;
    height: 56px;
    text-align: center;
    font-size: 24px;
    font-weight: 700;
    border: 2px solid #e5e7eb;
    border-radius: 12px;
    background: #fff;
    transition: all 0.2s;
}

.mst-otp-digit:focus {
    border-color: #9952E0;
    outline: none;
    box-shadow: 0 0 0 3px rgba(153, 82, 224, 0.1);
}

.mst-otp-digit:not(:placeholder-shown) {
    border-color: #9952E0;
    background: #F8F0FC;
}

.mst-otp-resend {
    text-align: center;
    margin-top: 16px;
    font-size: 14px;
    color: #6b7280;
}

.mst-otp-resend-link {
    color: #9952E0;
    text-decoration: none;
    font-weight: 500;
}

.mst-otp-resend-link:hover {
    text-decoration: underline;
}

.mst-otp-remember {
    margin-top: 16px;
    text-align: center;
    font-size: 14px;
    color: #6b7280;
}

.mst-otp-remember label {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
}

.mst-otp-remember input[type="checkbox"] {
    width: 16px;
    height: 16px;
    accent-color: #9952E0;
}

@media (max-width: 480px) {
    .mst-oauth-buttons {
        flex-direction: column;
    }
    
    .mst-otp-digit {
        width: 42px;
        height: 50px;
        font-size: 20px;
    }
}
</style>

<script>
(function() {
    var wrapper = document.getElementById('<?php echo esc_js($uid); ?>');
    if (!wrapper) return;
    
    // CRITICAL FIX: Prevent form reloading on input focus
    // This stops Elementor/xStore from interfering with form inputs
    wrapper.querySelectorAll('.mst-auth-input, .mst-otp-digit').forEach(function(input) {
        input.addEventListener('click', function(e) {
            e.stopPropagation();
        });
        input.addEventListener('focus', function(e) {
            e.stopPropagation();
        });
        input.addEventListener('mousedown', function(e) {
            e.stopPropagation();
        });
    });
    
    // Toggle password visibility
    wrapper.querySelectorAll('.mst-auth-toggle-password').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            var input = this.previousElementSibling;
            input.type = input.type === 'password' ? 'text' : 'password';
        });
    });
    
    // Switch between forms - FIXED: added stopPropagation and return false
    wrapper.querySelectorAll('.mst-auth-switch-link').forEach(function(link) {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            var target = this.getAttribute('data-target');
            wrapper.querySelectorAll('.mst-auth-form-container').forEach(function(form) {
                form.style.display = form.getAttribute('data-form') === target ? 'block' : 'none';
            });
            // Focus first input in the new form
            var newForm = wrapper.querySelector('.mst-auth-form-container[data-form="' + target + '"]');
            if (newForm) {
                var firstInput = newForm.querySelector('.mst-auth-input');
                if (firstInput) {
                    setTimeout(function() { firstInput.focus(); }, 100);
                }
            }
            return false;
        });
    });
    
    // OTP digit inputs
    var otpInputs = wrapper.querySelectorAll('.mst-otp-digit');
    otpInputs.forEach(function(input, index) {
        input.addEventListener('input', function(e) {
            var value = e.target.value.replace(/\D/g, '');
            e.target.value = value.slice(0, 1);
            
            if (value && index < otpInputs.length - 1) {
                otpInputs[index + 1].focus();
            }
            
            // Collect full OTP
            var otp = '';
            otpInputs.forEach(function(inp) {
                otp += inp.value;
            });
            wrapper.querySelector('.mst-otp-code').value = otp;
        });
        
        input.addEventListener('keydown', function(e) {
            if (e.key === 'Backspace' && !e.target.value && index > 0) {
                otpInputs[index - 1].focus();
            }
        });
        
        // Paste support
        input.addEventListener('paste', function(e) {
            e.preventDefault();
            var paste = (e.clipboardData || window.clipboardData).getData('text').replace(/\D/g, '');
            paste.split('').slice(0, 6).forEach(function(char, i) {
                if (otpInputs[i]) otpInputs[i].value = char;
            });
            wrapper.querySelector('.mst-otp-code').value = paste.slice(0, 6);
            if (paste.length >= 6) otpInputs[5].focus();
        });
    });
    
    // OTP countdown timer
    function startOtpCountdown() {
        var countdown = 60;
        var timerSpan = wrapper.querySelector('.mst-otp-countdown');
        var timerWrap = wrapper.querySelector('.mst-otp-timer');
        var resendLink = wrapper.querySelector('.mst-otp-resend-link');
        
        timerWrap.style.display = 'block';
        resendLink.style.display = 'none';
        
        var interval = setInterval(function() {
            countdown--;
            timerSpan.textContent = countdown;
            
            if (countdown <= 0) {
                clearInterval(interval);
                timerWrap.style.display = 'none';
                resendLink.style.display = 'inline';
            }
        }, 1000);
    }
    
    // Resend OTP
    var resendLinkEl = wrapper.querySelector('.mst-otp-resend-link');
    if (resendLinkEl) {
        resendLinkEl.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            var email = wrapper.querySelector('.mst-otp-email').value;
            
            fetch(mstAuthLK.ajax_url, {
                method: 'POST',
                body: new URLSearchParams({
                    action: 'mst_auth_resend_otp',
                    nonce: mstAuthLK.auth_nonce,
                    email: email
                }),
                credentials: 'same-origin'
            })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                if (data.success) {
                    startOtpCountdown();
                    alert('<?php _e('Код отправлен повторно!', 'mst-auth-lk'); ?>');
                }
            });
            return false;
        });
    }
    
    // Form submissions
    wrapper.querySelectorAll('.mst-auth-form').forEach(function(form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
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
                    } else if (data.data.require_otp) {
                        // Show OTP form
                        wrapper.querySelector('.mst-otp-email').value = formData.get('email');
                        wrapper.querySelector('.mst-otp-password').value = formData.get('password') || '';
                        wrapper.querySelector('.mst-otp-redirect').value = formData.get('redirect') || '';
                        
                        wrapper.querySelectorAll('.mst-auth-form-container').forEach(function(f) {
                            f.style.display = f.getAttribute('data-form') === 'otp' ? 'block' : 'none';
                        });
                        
                        startOtpCountdown();
                        wrapper.querySelector('.mst-otp-digit[data-index="0"]').focus();
                        
                        btn.disabled = false;
                        btn.textContent = action === 'login' ? '<?php _e('Войти', 'mst-auth-lk'); ?>' : '<?php _e('Зарегистрироваться', 'mst-auth-lk'); ?>';
                    } else {
                        // ✅ ИСПРАВЛЕНИЕ: Обновляем статус авторизации ПЕРЕД редиректом
                        document.body.classList.add('logged-in');
                        document.body.classList.remove('logged-out');
                        if (typeof mstAuthLK !== 'undefined') {
                            mstAuthLK.is_logged_in = true;
                        }
                        if (typeof mstShopGrid !== 'undefined') {
                            mstShopGrid.userId = 1;
                        }

                        // Единое событие для моментального обновления других виджетов
                        try {
                            window.dispatchEvent(new CustomEvent('mst:auth', { detail: { isLoggedIn: true, userId: 1 } }));
                        } catch (e) {}

                        window.location.href = data.data.redirect;
                    }
                } else {
                    errorDiv.textContent = data.data || '<?php _e('Ошибка', 'mst-auth-lk'); ?>';
                    errorDiv.style.display = 'block';
                    btn.disabled = false;
                    btn.textContent = action === 'login' ? '<?php _e('Войти', 'mst-auth-lk'); ?>' : 
                                      action === 'register' ? '<?php _e('Зарегистрироваться', 'mst-auth-lk'); ?>' : 
                                      action === 'verify_otp' ? '<?php _e('Подтвердить', 'mst-auth-lk'); ?>' :
                                      '<?php _e('Отправить', 'mst-auth-lk'); ?>';
                }
            })
            .catch(function(err) {
                console.error('Auth error:', err);
                errorDiv.textContent = '<?php _e('Ошибка сети. Попробуйте снова.', 'mst-auth-lk'); ?>';
                errorDiv.style.display = 'block';
                btn.disabled = false;
            });
            
            return false;
        });
    });
})();
</script>
