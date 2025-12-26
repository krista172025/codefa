/**
 * Author: Telegram @l1ghtsun
 * Author URI: https://t.me/l1ghtsun
 */
(function($) {
    'use strict';
    
    $(document).ready(function() {
        // Навигация
        function switchSection(sectionId) {
            $('.mst-lk-nav-item').removeClass('active');
            $('.mst-lk-section').removeClass('active');
            
            $('[data-section="' + sectionId + '"]').addClass('active');
            $('[data-section-id="' + sectionId + '"]').addClass('active');
            
            // ИСПРАВЛЕНИЕ #1: НЕ добавляем hash в URL (убрали #undefined)
            try {
                localStorage.setItem('mst_lk_active', sectionId);
                // УБРАЛИ: window.location.hash = sectionId;
            } catch(e) {}
        }
        
        $('.mst-lk-nav-item, .mst-lk-nav-item-trigger').on('click', function(e) {
            e.preventDefault();
            const section = $(this).data('section');
            switchSection(section);
            
            if ($(window).width() < 1024) {
                $('html, body').animate({ scrollTop: $('.mst-lk-content').offset().top - 100 }, 300);
            }
        });
        
        // Восстановление активной секции (БЕЗ hash)
        let activeSection = localStorage.getItem('mst_lk_active');
        if (!activeSection || !$('[data-section="' + activeSection + '"]').length) {
            activeSection = $('.mst-lk-nav-item:first').data('section');
        }
        if (activeSection) {
            switchSection(activeSection);
        }
        
        // Загрузка аватара БЕЗ медиатеки
        $('#mst-avatar-input').on('change', function(e) {
            const file = e.target.files[0];
            if (!file) return;
            
            if (!file.type.match('image.*')) {
                alert('Пожалуйста, выберите изображение');
                return;
            }
            
            if (file.size > 5 * 1024 * 1024) {
                alert('Файл слишком большой (макс 5MB)');
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
                        alert('✅ Аватар обновлен!');
                    } else {
                        alert('❌ ' + response.data);
                    }
                },
                error: function() {
                    alert('❌ Ошибка загрузки');
                }
            });
        });
        
        // Обновление профиля
        $('#mst-profile-form').on('submit', function(e) {
            e.preventDefault();
            
            const firstName = $('[name="first_name"]').val();
            const lastName = $('[name="last_name"]').val();
            const userEmail = $('[name="user_email"]').val();
            const billingPhone = $('[name="billing_phone"]').val();
            const newPassword = $('[name="new_password"]').val();
            const confirmPassword = $('[name="confirm_password"]').val();
            
            if (!firstName || !userEmail) {
                alert('Заполните все обязательные поля');
                return;
            }
            
            if (newPassword && newPassword !== confirmPassword) {
                alert('Пароли не совпадают');
                return;
            }
            
            const fullName = firstName + (lastName ? ' ' + lastName : '');
            
            $.ajax({
                url: mstLK.ajax_url,
                type: 'POST',
                data: {
                    action: 'mst_lk_update_profile',
                    nonce: mstLK.nonce,
                    display_name: fullName,
                    user_email: userEmail,
                    new_password: newPassword
                },
                success: function(response) {
                    if (response.success) {
                        alert('✅ Профиль обновлен!');
                        location.reload();
                    } else {
                        alert('❌ ' + response.data);
                    }
                },
                error: function() {
                    alert('❌ Ошибка сохранения');
                }
            });
        });
        
        // Просмотр деталей заказа (кнопка "Подробнее")
        $(document).on('click', '.mst-lk-view-order', function() {
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
        });
        
        // Просмотр билета (кнопка "Открыть билет")
        $(document).on('click', '.mst-lk-view-ticket', function() {
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
        });
        
        // Просмотр бронирования LatePoint
        $(document).on('click', '.mst-lk-view-latepoint-booking', function() {
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
        });
        
        // Закрытие модального окна
        $(document).on('click', '.mst-lk-modal-close', function() {
            $(this).closest('.mst-lk-modal').removeClass('active');
        });
        
        // Закрытие по клику вне модалки
        $('.mst-lk-modal').on('click', function(e) {
            if ($(e.target).is('.mst-lk-modal')) {
                $(this).removeClass('active');
            }
        });
        
        // Удаление из избранного
        $(document).on('click', '.mst-remove-from-wishlist', function() {
            const productId = $(this).data('product-id');
            const item = $(this).closest('.mst-shop-grid-card, .xstore-wishlist-item');
            
            if (!confirm('Удалить товар из избранного?')) {
                return;
            }
            
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
                            if ($('.mst-shop-grid-card, .xstore-wishlist-item').length === 0) {
                                location.reload();
                            }
                        });
                    } else {
                        alert('❌ ' + response.data);
                    }
                },
                error: function() {
                    alert('❌ Ошибка удаления');
                }
            });
        });
        
        // Открытие модального окна отзыва
        $(document).on('click', '.mst-lk-open-review', function(e) {
            e.preventDefault();
            const productId = $(this).data('product-id');
            
            if (!productId || productId == 0) {
                alert('❌ Не удалось определить товар');
                return;
            }
            
            $('#review-product-id').val(productId);
            $('#mst-lk-review-modal').addClass('active');
        });
        
        // Отправка отзыва
        $('#mst-review-form').on('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            formData.append('action', 'mst_lk_submit_review');
            formData.append('nonce', mstLK.nonce);
            
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
                        alert('✅ ' + response.data.message);
                        $('#mst-lk-review-modal').removeClass('active');
                        $('#mst-review-form')[0].reset();
                    } else {
                        alert('❌ ' + response.data);
                    }
                    submitBtn.prop('disabled', false).text('Отправить отзыв');
                },
                error: function() {
                    alert('❌ Ошибка отправки отзыва');
                    submitBtn.prop('disabled', false).text('Отправить отзыв');
                }
            });
        });
        
        // Скачать подарок
        $(document).on('click', '.mst-lk-download-gift', function(e) {
            e.preventDefault();
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
                        // Скачиваем файл
                        const link = document.createElement('a');
                        link.href = response.data.url;
                        link.download = response.data.filename;
                        document.body.appendChild(link);
                        link.click();
                        document.body.removeChild(link);
                        
                        alert('✅ Подарок скачан!');
                    } else {
                        alert('❌ ' + response.data);
                    }
                    btn.prop('disabled', false).text('💝 Скачать подарок');
                },
                error: function() {
                    alert('❌ Ошибка скачивания');
                    btn.prop('disabled', false).text('💝 Скачать подарок');
                }
            });
        });
    });
})(jQuery);

// ВАЛИДАЦИЯ И ФОРМАТИРОВАНИЕ ТЕЛЕФОНА
(function($) {
    'use strict';
    
    $(document).ready(function() {
        // IMask для телефона
        if (typeof IMask !== 'undefined') {
            const phoneInput = document.getElementById('mst-phone-input');
            if (phoneInput) {
                const phoneMask = IMask(phoneInput, {
                    mask: '+{7} (000) 000-00-00',
                    lazy: false,
                    placeholderChar: '_'
                });
            }
        }
        
        // Обновленная отправка формы профиля (сохранение телефона)
        $('#mst-profile-form').on('submit', function(e) {
            e.preventDefault();
            
            const firstName = $('[name="first_name"]').val();
            const lastName = $('[name="last_name"]').val();
            const userEmail = $('[name="user_email"]').val();
            const billingPhone = $('[name="billing_phone"]').val();
            const newPassword = $('[name="new_password"]').val();
            const confirmPassword = $('[name="confirm_password"]').val();
            
            // Валидация
            if (!firstName || !userEmail) {
                alert('❌ Заполните все обязательные поля');
                return;
            }
            
            // Проверка email
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(userEmail)) {
                alert('❌ Введите корректный email');
                return;
            }
            
            // Проверка телефона (только цифры, 11 символов)
            if (billingPhone) {
                const phoneDigits = billingPhone.replace(/\D/g, '');
                if (phoneDigits.length !== 11) {
                    alert('❌ Введите полный номер телефона');
                    return;
                }
            }
            
            // Проверка паролей
            if (newPassword && newPassword !== confirmPassword) {
                alert('❌ Пароли не совпадают');
                return;
            }
            
            if (newPassword && newPassword.length < 6) {
                alert('❌ Пароль должен быть не менее 6 символов');
                return;
            }
            
            const fullName = firstName + (lastName ? ' ' + lastName : '');
            
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
                    display_name: fullName,
                    user_email: userEmail,
                    billing_phone: billingPhone, // ИСПРАВЛЕНО: теперь сохраняется
                    new_password: newPassword
                },
                success: function(response) {
                    if (response.success) {
                        alert('✅ Профиль успешно обновлен!');
                        location.reload();
                    } else {
                        alert('❌ Ошибка: ' + response.data);
                        submitBtn.prop('disabled', false).text('💾 Сохранить изменения');
                    }
                },
                error: function() {
                    alert('❌ Ошибка сохранения. Попробуйте еще раз');
                    submitBtn.prop('disabled', false).text('💾 Сохранить изменения');
                }
            });
        });
    });
})(jQuery);