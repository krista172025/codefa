/**
 * MST Auth + LK Unified Script
 * FIXED: Photo preview logic, compact toast, reset after submit
 * Author: Telegram @l1ghtsun
 */
(function($) {
    'use strict';
    
    var mstLK = window.mstAuthLK || window.mstLK || {};

    // =============================
    // Auth state sync (обход кэша гостя)
    // =============================
    (function() {
        function dispatchAuthEvent(isLoggedIn, userId) {
            try {
                window.dispatchEvent(
                    new CustomEvent('mst:auth', {
                        detail: { isLoggedIn: !!isLoggedIn, userId: userId || 0 },
                    })
                );
            } catch (e) {}
        }

        // Экспортируем хелпер для сторонних скриптов (если нужно)
        window.mstAuthLKDispatchAuthEvent = dispatchAuthEvent;

        function applyAuthState(isLoggedIn, userId) {
            try {
                var nextIsLoggedIn = !!isLoggedIn;
                var nextUserId = nextIsLoggedIn
                    ? (userId || (window.mstShopGrid && window.mstShopGrid.userId) || 1)
                    : 0;

                if (window.mstAuthLK) window.mstAuthLK.is_logged_in = nextIsLoggedIn;
                mstLK.is_logged_in = nextIsLoggedIn;

                if (document.body) {
                    document.body.classList.toggle('logged-in', nextIsLoggedIn);
                    document.body.classList.toggle('logged-out', !nextIsLoggedIn);
                }

                if (window.mstShopGrid) {
                    window.mstShopGrid.userId = nextUserId;
                }

                dispatchAuthEvent(nextIsLoggedIn, nextUserId);
            } catch (e) {}
        }

        function refreshAuthState() {
            if (!mstLK.ajax_url) return Promise.resolve();

            return fetch(mstLK.ajax_url, {
                method: 'POST',
                credentials: 'same-origin',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' },
                body: new URLSearchParams({ action: 'mst_auth_status' }),
            })
                .then(function(r) {
                    return r.json();
                })
                .then(function(res) {
                    if (res && res.success && res.data) {
                        applyAuthState(!!res.data.is_logged_in, res.data.user_id || 0);
                    }
                })
                .catch(function() {});
        }

        window.mstAuthLKRefreshAuthState = refreshAuthState;

        function initOnce() {
            // ВСЕГДА проверяем реальный статус при загрузке (обход кэша гостя)
            refreshAuthState();
        }

        if (document.readyState === 'complete' || document.readyState === 'interactive') {
            initOnce();
        } else {
            document.addEventListener('DOMContentLoaded', initOnce);
        }

        window.addEventListener(
            'focus',
            function() {
                if (!mstLK.is_logged_in) refreshAuthState();
            },
            { passive: true }
        );

        // Logout: шлем единое событие сразу при клике по ссылке выхода (до редиректа)
        document.addEventListener(
            'click',
            function(e) {
                var target = e.target;
                if (!target) return;

                var link = target.closest ? target.closest('a') : null;
                if (!link) return;

                var href = link.getAttribute('href') || '';
                if (!href) return;

                if (
                    href.indexOf('customer-logout') !== -1 ||
                    href.indexOf('wp-login.php?action=logout') !== -1 ||
                    href.indexOf('action=logout') !== -1
                ) {
                    applyAuthState(false, 0);
                }
            },
            true
        );
    })();

    // Store selected files for incremental upload
    var reviewPhotosFiles = [];
    
    $(document).ready(function() {
        
        // =============================================
        // CRITICAL FIX: Prevent input focus from reloading page
        // =============================================
        $(document).on('click focus mousedown', '.mst-auth-input, .mst-form-control, .mst-otp-digit, input[type="email"], input[type="password"], input[type="text"], input[type="tel"]', function(e) {
            e.stopPropagation();
        });
        
        // =============================================
        // НАВИГАЦИЯ ЛК
        // =============================================
        function switchSection(sectionId) {
            $('.mst-lk-nav-item').removeClass('active');
            $('.mst-lk-section').removeClass('active');
            
            $('[data-section="' + sectionId + '"]').addClass('active');
            $('[data-section-id="' + sectionId + '"]').addClass('active');
            
            try {
                localStorage.setItem('mst_lk_active', sectionId);
            } catch(e) {}
        }
        
        $('.mst-lk-nav-item, .mst-lk-nav-item-trigger').on('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const section = $(this).data('section');
            switchSection(section);
            
            if ($(window).width() < 1024) {
                $('html, body').animate({ scrollTop: $('.mst-lk-content').offset().top - 100 }, 300);
            }
            
            return false;
        });
        
        // Восстановление активной секции
        let activeSection = localStorage.getItem('mst_lk_active');
        if (!activeSection || !$('[data-section="' + activeSection + '"]').length) {
            activeSection = $('.mst-lk-nav-item:first').data('section');
        }
        if (activeSection) {
            switchSection(activeSection);
        }
        
        // =============================================
        // ЗАГРУЗКА АВАТАРА
        // =============================================
        $('#mst-avatar-input').on('change', function(e) {
            e.stopPropagation();
            
            const file = e.target.files[0];
            if (!file) return;
            
            if (!file.type.match('image.*')) {
                showToast('Пожалуйста, выберите изображение', 'error');
                return;
            }
            
            if (file.size > 5 * 1024 * 1024) {
                showToast('Файл слишком большой (макс 5MB)', 'error');
                return;
            }
            
            const formData = new FormData();
            formData.append('action', 'mst_lk_update_avatar');
            formData.append('avatar', file);
            formData.append('nonce', mstLK.nonce);
            
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#mst-user-avatar').attr('src', e.target.result);
            };
            reader.readAsDataURL(file);
            
            $.ajax({
                url: mstLK.ajax_url,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        $('#mst-user-avatar').attr('src', response.data.url);
                        showToast('Аватар обновлен!', 'success');
                    } else {
                        showToast(response.data, 'error');
                    }
                },
                error: function() {
                    showToast('Ошибка загрузки', 'error');
                }
            });
        });
        
        // =============================================
        // ОБНОВЛЕНИЕ ПРОФИЛЯ
        // =============================================
        $('#mst-profile-form').on('submit', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const firstName = $('[name="first_name"]').val();
            const lastName = $('[name="last_name"]').val();
            const userEmail = $('[name="user_email"]').val();
            const billingPhone = $('[name="billing_phone"]').val();
            const newPassword = $('[name="new_password"]').val();
            const confirmPassword = $('[name="confirm_password"]').val();
            
            if (!firstName || !userEmail) {
                showToast('Заполните все обязательные поля', 'error');
                return false;
            }
            
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(userEmail)) {
                showToast('Введите корректный email', 'error');
                return false;
            }
            
            if (newPassword && newPassword !== confirmPassword) {
                showToast('Пароли не совпадают', 'error');
                return false;
            }
            
            const submitBtn = $(this).find('button[type="submit"]');
            submitBtn.prop('disabled', true).text('⏳ Сохранение...');
            
            $.ajax({
                url: mstLK.ajax_url,
                type: 'POST',
                data: {
                    action: 'mst_lk_update_profile',
                    nonce: mstLK.nonce,
                    first_name: firstName,
                    last_name: lastName,
                    user_email: userEmail,
                    billing_phone: billingPhone,
                    new_password: newPassword
                },
                success: function(response) {
                    if (response.success) {
                        showToast('Профиль обновлен!', 'success');
                    } else {
                        showToast(response.data, 'error');
                    }
                    submitBtn.prop('disabled', false).text('💾 Сохранить изменения');
                },
                error: function() {
                    showToast('Ошибка сохранения', 'error');
                    submitBtn.prop('disabled', false).text('💾 Сохранить изменения');
                }
            });
            
            return false;
        });
        
        // =============================================
        // ПРОСМОТР ЗАКАЗА
        // =============================================
        $(document).on('click', '.mst-lk-view-order', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const orderId = $(this).data('order-id');
            const modal = $('#mst-lk-order-modal');
            const modalBody = modal.find('.mst-lk-modal-body');
            
            modalBody.html('<div class="mst-loading"><div class="spinner"></div><p>Загрузка...</p></div>');
            modal.addClass('active');
            
            $.ajax({
                url: mstLK.ajax_url,
                type: 'POST',
                data: {
                    action: 'mst_lk_get_order_details',
                    nonce: mstLK.nonce,
                    order_id: orderId
                },
                success: function(response) {
                    if (response.success) {
                        modalBody.html(response.data.html);
                    } else {
                        modalBody.html('<div class="mst-error">❌ ' + response.data + '</div>');
                    }
                },
                error: function() {
                    modalBody.html('<div class="mst-error">❌ Ошибка загрузки</div>');
                }
            });
            
            return false;
        });
        
        // =============================================
        // БИЛЕТ
        // =============================================
        $(document).on('click', '.mst-lk-view-ticket', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const orderId = $(this).data('order-id');
            const modal = $('#mst-lk-ticket-modal');
            const modalBody = modal.find('.mst-lk-modal-body');
            
            modalBody.html('<div class="mst-loading"><div class="spinner"></div><p>Загрузка билета...</p></div>');
            modal.addClass('active');
            
            $.ajax({
                url: mstLK.ajax_url,
                type: 'POST',
                data: {
                    action: 'mst_lk_get_ticket',
                    nonce: mstLK.nonce,
                    order_id: orderId
                },
                success: function(response) {
                    if (response.success) {
                        modalBody.html(response.data.html);
                    } else {
                        modalBody.html('<div class="mst-error">❌ ' + response.data + '</div>');
                    }
                },
                error: function() {
                    modalBody.html('<div class="mst-error">❌ Ошибка загрузки билета</div>');
                }
            });
            
            return false;
        });
        
        // =============================================
        // БРОНИРОВАНИЯ LATEPOINT
        // =============================================
        $(document).on('click', '.mst-lk-view-latepoint-booking', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const bookingId = $(this).data('booking-id');
            const modal = $('#mst-lk-latepoint-modal');
            const modalBody = modal.find('.mst-lk-modal-body');
            
            modalBody.html('<div class="mst-loading"><div class="spinner"></div><p>Загрузка...</p></div>');
            modal.addClass('active');
            
            $.ajax({
                url: mstLK.ajax_url,
                type: 'POST',
                data: {
                    action: 'mst_lk_get_latepoint_booking',
                    nonce: mstLK.nonce,
                    booking_id: bookingId
                },
                success: function(response) {
                    if (response.success) {
                        modalBody.html(response.data.html);
                    } else {
                        modalBody.html('<div class="mst-error">❌ ' + response.data + '</div>');
                    }
                },
                error: function() {
                    modalBody.html('<div class="mst-error">❌ Ошибка загрузки</div>');
                }
            });
            
            return false;
        });
        
        // =============================================
        // ЗАКРЫТИЕ МОДАЛЬНЫХ ОКОН
        // =============================================
        $(document).on('click', '.mst-lk-modal-close', function(e) {
            e.preventDefault();
            e.stopPropagation();
            $(this).closest('.mst-lk-modal').removeClass('active');
            return false;
        });
        
        $('.mst-lk-modal').on('click', function(e) {
            if ($(e.target).is('.mst-lk-modal')) {
                $(this).removeClass('active');
            }
        });
        
        $(document).on('keydown', function(e) {
            if (e.key === 'Escape') {
                $('.mst-lk-modal.active').removeClass('active');
            }
        });
        
        // =============================================
        // УДАЛЕНИЕ ИЗ ИЗБРАННОГО
        // =============================================
        $(document).on('click', '.mst-remove-from-wishlist', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const productId = $(this).data('product-id');
            const item = $(this).closest('.mst-shop-grid-card, .xstore-wishlist-item');
            const btn = $(this);
            
            if (!confirm('Удалить товар из избранного?')) {
                return false;
            }
            
            btn.prop('disabled', true).css('opacity', '0.5');
            
            $.ajax({
                url: mstLK.ajax_url,
                type: 'POST',
                data: {
                    action: 'mst_lk_remove_from_wishlist',
                    nonce: mstLK.nonce,
                    product_id: productId
                },
                success: function(response) {
                    if (response.success) {
                        item.fadeOut(300, function() {
                            $(this).remove();
                            var remainingItems = $('.mst-shop-grid-card, .xstore-wishlist-item').length;
                            if (remainingItems === 0) {
                                $('.mst-wishlist-grid, .mst-shop-grid').html(
                                    '<div class="mst-lk-empty-state" style="grid-column: 1 / -1;">' +
                                    '<div class="mst-lk-empty-icon">❤️</div>' +
                                    '<p>Ваш список желаний пуст</p>' +
                                    '</div>'
                                );
                            }
                        });
                        showToast('Товар удален из избранного', 'success');
                    } else {
                        showToast(response.data, 'error');
                        btn.prop('disabled', false).css('opacity', '1');
                    }
                },
                error: function() {
                    showToast('Ошибка удаления', 'error');
                    btn.prop('disabled', false).css('opacity', '1');
                }
            });
            
            return false;
        });
        
        // =============================================
        // ОТЗЫВЫ - ОТКРЫТИЕ МОДАЛЬНОГО ОКНА
        // =============================================
        $(document).on('click', '.mst-lk-open-review', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const productId = $(this).data('product-id');
            const orderId = $(this).data('order-id');
            
            if (!productId || productId == 0) {
                showToast('Не удалось определить товар', 'error');
                return false;
            }
            
            // Reset form and photos
            $('#mst-review-form')[0].reset();
            $('#review-photos-preview').html('');
            reviewPhotosFiles = [];
            
            $('#review-product-id').val(productId);
            $('#review-order-id').val(orderId || '');
            
            loadGuideForProduct(productId);
            
            $('#mst-lk-review-modal').addClass('active');
            
            return false;
        });
        
        // Загрузка данных гида
        function loadGuideForProduct(productId) {
            $.ajax({
                url: mstLK.ajax_url,
                type: 'POST',
                data: {
                    action: 'mst_lk_get_product_guide',
                    nonce: mstLK.nonce,
                    product_id: productId
                },
                success: function(response) {
                    if (response.success && response.data.guide) {
                        const guide = response.data.guide;
                        $('#review-guide-id').val(guide.id);
                        $('#review-guide-info').html(
                            '<div class="mst-review-guide-preview">' +
                            '<img src="' + guide.avatar + '" alt="' + guide.name + '">' +
                            '<div><strong>Выбранный гид: ' + guide.name + '</strong>' +
                            (guide.city ? '<br><small>' + guide.city + '</small>' : '') + '</div>' +
                            '</div>'
                        );
                    } else {
                        $('#review-guide-id').val('');
                        $('#review-guide-info').html('');
                    }
                }
            });
        }
        
        // =============================================
        // ФОТО ОТЗЫВА - INCREMENTAL UPLOAD
        // =============================================
        $('#review-photos-input').on('change', function(e) {
            e.stopPropagation();
            
            const files = Array.from(this.files);
            const preview = $('#review-photos-preview');
            const maxPhotos = 5;
            
            files.forEach(function(file) {
                if (reviewPhotosFiles.length >= maxPhotos) {
                    showToast('Максимум ' + maxPhotos + ' фото', 'error');
                    return;
                }
                
                if (!file.type.match('image.*')) return;
                
                reviewPhotosFiles.push(file);
                const index = reviewPhotosFiles.length - 1;
                
                const reader = new FileReader();
                reader.onload = function(e) {
                    const photoItem = $(
                        '<div class="mst-review-photo-item" data-index="' + index + '">' +
                        '<img src="' + e.target.result + '" alt="">' +
                        '<button type="button" class="mst-review-photo-remove" data-index="' + index + '">×</button>' +
                        '</div>'
                    );
                    preview.append(photoItem);
                };
                reader.readAsDataURL(file);
            });
            
            // Clear input to allow re-selecting same files
            this.value = '';
        });
        
        // Remove photo from preview
        $(document).on('click', '.mst-review-photo-remove', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const index = parseInt($(this).data('index'));
            $(this).closest('.mst-review-photo-item').remove();
            reviewPhotosFiles[index] = null;
            
            return false;
        });
        
        // =============================================
        // ОТПРАВКА ОТЗЫВА
        // =============================================
        $('#mst-review-form').on('submit', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const formData = new FormData(this);
            formData.append('action', 'mst_lk_submit_review');
            formData.append('nonce', mstLK.nonce);
            
            // Remove old photos and add from our array
            formData.delete('review_photos[]');
            reviewPhotosFiles.forEach(function(file) {
                if (file) formData.append('review_photos[]', file);
            });
            
            const submitBtn = $(this).find('button[type="submit"]');
            submitBtn.prop('disabled', true).text('⏳ Отправка...');
            
            $.ajax({
                url: mstLK.ajax_url,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        showToast('Отзыв отправлен на модерацию', 'success');
                        $('#mst-lk-review-modal').removeClass('active');
                        
                        // Full reset
                        $('#mst-review-form')[0].reset();
                        $('#review-guide-info').html('');
                        $('#review-photos-preview').html('');
                        reviewPhotosFiles = [];
                    } else {
                        showToast(response.data, 'error');
                    }
                    submitBtn.prop('disabled', false).text('Отправить отзыв');
                },
                error: function() {
                    showToast('Ошибка отправки отзыва', 'error');
                    submitBtn.prop('disabled', false).text('Отправить отзыв');
                }
            });
            
            return false;
        });
        
        // =============================================
        // СКАЧАТЬ ПОДАРОК
        // =============================================
        $(document).on('click', '.mst-lk-download-gift', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const orderId = $(this).data('order-id');
            const btn = $(this);
            
            btn.prop('disabled', true).text('⏳ Загрузка...');
            
            $.ajax({
                url: mstLK.ajax_url,
                type: 'POST',
                data: {
                    action: 'mst_lk_download_gift',
                    nonce: mstLK.nonce,
                    order_id: orderId
                },
                success: function(response) {
                    if (response.success) {
                        const link = document.createElement('a');
                        link.href = response.data.url;
                        link.download = response.data.filename;
                        document.body.appendChild(link);
                        link.click();
                        document.body.removeChild(link);
                        showToast('Подарок скачан!', 'success');
                    } else {
                        showToast(response.data, 'error');
                    }
                    btn.prop('disabled', false).html('💝 Скачать подарок');
                },
                error: function() {
                    showToast('Ошибка скачивания', 'error');
                    btn.prop('disabled', false).html('💝 Скачать подарок');
                }
            });
            
            return false;
        });
        
        // =============================================
        // TOAST УВЕДОМЛЕНИЯ - COMPACT VERSION
        // =============================================
        function showToast(message, type) {
            type = type || 'info';
            
            $('.mst-toast').remove();
            
            var bgColor = type === 'success' ? '#10b981' : type === 'error' ? '#ef4444' : '#3b82f6';
            var icon = type === 'success' ? '✓' : type === 'error' ? '✕' : 'ℹ';
            
            var toast = $('<div class="mst-toast" style="' +
                'position: fixed; bottom: 20px; left: 50%; transform: translateX(-50%); ' +
                'background: ' + bgColor + '; color: #fff; padding: 10px 16px; ' +
                'border-radius: 8px; font-weight: 500; z-index: 999999; ' +
                'font-size: 14px; max-width: 300px; text-align: center; ' +
                'box-shadow: 0 4px 12px rgba(0,0,0,0.15); animation: mstToastIn 0.3s ease;' +
                '"><span style="margin-right: 6px;">' + icon + '</span>' + message + '</div>');
            
            $('body').append(toast);
            
            setTimeout(function() {
                toast.fadeOut(300, function() {
                    $(this).remove();
                });
            }, 3000);
        }
        
        window.showToast = showToast;
        
        // Добавляем CSS для анимации toast
        if (!$('#mst-toast-styles').length) {
            $('head').append('<style id="mst-toast-styles">' +
                '@keyframes mstToastIn { from { opacity: 0; transform: translateX(-50%) translateY(20px); } to { opacity: 1; transform: translateX(-50%) translateY(0); } }' +
                '.mst-loading { text-align: center; padding: 40px; }' +
                '.mst-loading .spinner { width: 40px; height: 40px; border: 3px solid #e5e7eb; border-top-color: #8b5cf6; border-radius: 50%; animation: spin 1s linear infinite; margin: 0 auto 16px; }' +
                '@keyframes spin { to { transform: rotate(360deg); } }' +
                '.mst-error { text-align: center; padding: 40px; color: #ef4444; }' +
            '</style>');
        }
        
        // =============================================
        // МАСКА ТЕЛЕФОНА
        // =============================================
        if (typeof IMask !== 'undefined') {
            const phoneInput = document.getElementById('mst-phone-input');
            if (phoneInput) {
                IMask(phoneInput, {
                    mask: '+{7} (000) 000-00-00',
                    lazy: false,
                    placeholderChar: '_'
                });
            }
        }
        
        // =============================================
        // OTP TOGGLE
        // =============================================
        $('#mst-otp-toggle').on('change', function() {
            const enabled = $(this).is(':checked');
            
            $.ajax({
                url: mstLK.ajax_url,
                type: 'POST',
                data: {
                    action: 'mst_lk_toggle_otp',
                    nonce: mstLK.nonce,
                    enabled: enabled ? 1 : 0
                },
                success: function(response) {
                    if (response.success) {
                        showToast(response.data.message, 'success');
                    } else {
                        showToast(response.data, 'error');
                    }
                },
                error: function() {
                    showToast('Ошибка сохранения', 'error');
                }
            });
        });
        
        // Сброс доверенных устройств
        $(document).on('click', '.mst-clear-trusted-ips', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            if (!confirm('Вы уверены? После этого при следующем входе потребуется код подтверждения.')) {
                return false;
            }
            
            const btn = $(this);
            btn.prop('disabled', true).text('⏳ Сброс...');
            
            $.ajax({
                url: mstLK.ajax_url,
                type: 'POST',
                data: {
                    action: 'mst_lk_clear_trusted_ips',
                    nonce: mstLK.nonce
                },
                success: function(response) {
                    if (response.success) {
                        showToast('Доверенные устройства сброшены', 'success');
                        btn.closest('.mst-trusted-devices').fadeOut();
                    } else {
                        showToast(response.data, 'error');
                    }
                    btn.prop('disabled', false).text('🗑️ Сбросить все доверенные устройства');
                },
                error: function() {
                    showToast('Ошибка', 'error');
                    btn.prop('disabled', false).text('🗑️ Сбросить все доверенные устройства');
                }
            });
            
            return false;
        });
        
        // Добавляем стили для toggle switch
        if (!$('#mst-toggle-styles').length) {
            $('head').append('<style id="mst-toggle-styles">' +
                '.mst-security-option { display: flex; align-items: center; justify-content: space-between; padding: 16px; background: #f9fafb; border-radius: 12px; }' +
                '.mst-security-option-info { flex: 1; }' +
                '.mst-toggle-switch { position: relative; display: inline-block; width: 52px; height: 28px; }' +
                '.mst-toggle-switch input { opacity: 0; width: 0; height: 0; }' +
                '.mst-toggle-slider { position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #d1d5db; transition: .3s; border-radius: 28px; }' +
                '.mst-toggle-slider:before { position: absolute; content: ""; height: 22px; width: 22px; left: 3px; bottom: 3px; background-color: white; transition: .3s; border-radius: 50%; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }' +
                '.mst-toggle-switch input:checked + .mst-toggle-slider { background-color: #8b5cf6; }' +
                '.mst-toggle-switch input:checked + .mst-toggle-slider:before { transform: translateX(24px); }' +
            '</style>');
        }
    });
})(jQuery);
