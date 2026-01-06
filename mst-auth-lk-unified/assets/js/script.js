/**
 * MST Auth + LK Unified Script v4.1
 * UPDATED: AJAX load more for reviews & orders
 * Author: Telegram @l1ghtsun
 */
(function($) {
    'use strict';
    
    var mstLK = window.mstAuthLK || window.mstLK || {};
    var reviewPhotosFiles = [];
    
    $(document).ready(function() {
        
        // =============================================
        // NAVIGATION
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
        
        // Restore active section
        let activeSection = localStorage.getItem('mst_lk_active');
        if (!activeSection || !$('[data-section="' + activeSection + '"]').length) {
            activeSection = $('.mst-lk-nav-item:first').data('section');
        }
        if (activeSection) {
            switchSection(activeSection);
        }
        
        // =============================================
        // AVATAR UPLOAD
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
        // AJAX LOAD MORE ORDERS
        // =============================================
        var ordersPage = 1;
        var ordersPerPage = 4;
        var ordersLoading = false;
        
        $(document).on('click', '#mst-load-more-orders', function(e) {
            e.preventDefault();
            
            if (ordersLoading) return;
            
            var btn = $(this);
            ordersPage++;
            ordersLoading = true;
            
            btn.prop('disabled', true).html('<span class="spinner" style="width:16px;height:16px;border-width:2px;display:inline-block;margin-right:8px;"></span> Загрузка...');
            
            $.ajax({
                url: mstLK.ajax_url,
                type: 'POST',
                data: {
                    action: 'mst_lk_load_more_orders',
                    nonce: mstLK.nonce,
                    page: ordersPage,
                    per_page: ordersPerPage
                },
                success: function(response) {
                    ordersLoading = false;
                    
                    if (response.success && response.data.html) {
                        $('#mst-orders-list').append(response.data.html);
                        
                        if (!response.data.has_more) {
                            btn.parent().remove();
                        } else {
                            btn.prop('disabled', false).html('Загрузить ещё ↓');
                        }
                    } else {
                        btn.parent().remove();
                    }
                },
                error: function() {
                    ordersLoading = false;
                    btn.prop('disabled', false).html('Загрузить ещё ↓');
                    showToast('Ошибка загрузки', 'error');
                }
            });
        });
        
        // =============================================
        // VIEW ORDER DETAILS
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
        // VIEW TICKET
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
        // CLOSE MODALS
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
        // REMOVE FROM WISHLIST
        // =============================================
        $(document).on('click', '.mst-remove-from-wishlist', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const productId = $(this).data('product-id');
            const item = $(this).closest('.mst-wishlist-card, .mst-shop-grid-card');
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
                            var remainingItems = $('.mst-wishlist-card').length;
                            if (remainingItems === 0) {
                                $('.mst-wishlist-grid-new').html(
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
        // REVIEW PHOTOS
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
            
            this.value = '';
        });
        
        $(document).on('click', '.mst-review-photo-remove', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const index = parseInt($(this).data('index'));
            $(this).closest('.mst-review-photo-item').remove();
            reviewPhotosFiles[index] = null;
            
            return false;
        });
        
        // =============================================
        // SUBMIT REVIEW
        // =============================================
        $('#mst-review-form').on('submit', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const formData = new FormData(this);
            formData.append('action', 'mst_lk_submit_review');
            formData.append('nonce', mstLK.nonce);
            
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
        // OPEN REVIEW MODAL
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
            
            $('#mst-review-form')[0].reset();
            $('#review-photos-preview').html('');
            reviewPhotosFiles = [];
            
            $('#review-product-id').val(productId);
            $('#review-order-id').val(orderId || '');
            
            loadGuideForProduct(productId);
            
            $('#mst-lk-review-modal').addClass('active');
            
            return false;
        });
        
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
        // DOWNLOAD GIFT
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
        // TOAST NOTIFICATIONS
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
        
        // Toast animation CSS
        if (!$('#mst-toast-styles').length) {
            $('head').append('<style id="mst-toast-styles">' +
                '@keyframes mstToastIn { from { opacity: 0; transform: translateX(-50%) translateY(20px); } to { opacity: 1; transform: translateX(-50%) translateY(0); } }' +
                '.mst-loading { text-align: center; padding: 40px; }' +
                '.mst-loading .spinner { width: 40px; height: 40px; border: 3px solid #e5e7eb; border-top-color: #9952E0; border-radius: 50%; animation: mstSpin 1s linear infinite; margin: 0 auto 16px; }' +
                '@keyframes mstSpin { to { transform: rotate(360deg); } }' +
                '.mst-error { text-align: center; padding: 40px; color: #ef4444; }' +
            '</style>');
        }
        
        // =============================================
        // PROFILE FORM
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
        
    });
})(jQuery);
