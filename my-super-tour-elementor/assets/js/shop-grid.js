/**
 * Shop Grid - Wishlist Sync and Guide Links
 * FIXED: Auth state check with async verification
 * Author: Telegram @l1ghtsun
 */

(function($) {
    'use strict';

    // Флаг: статус авторизации уже проверен через AJAX
    var authStateVerified = false;
    var authStatePromise = null;

    // Единое событие авторизации: моментально синхронизируем состояние для shop-grid/виджетов
    window.addEventListener('mst:auth', function(ev) {
        try {
            var detail = (ev && ev.detail) ? ev.detail : {};
            var isLoggedIn = !!detail.isLoggedIn;
            var userId = detail.userId || 0;

            if (typeof mstAuthLK !== 'undefined') {
                mstAuthLK.is_logged_in = isLoggedIn;
            }

            if (typeof mstShopGrid !== 'undefined') {
                mstShopGrid.userId = isLoggedIn ? (userId || mstShopGrid.userId || 1) : 0;
            }

            if (document.body) {
                document.body.classList.toggle('logged-in', isLoggedIn);
                document.body.classList.toggle('logged-out', !isLoggedIn);
            }

            // Помечаем что статус уже синхронизирован
            authStateVerified = true;

            // Если тост висит, а пользователь уже залогинен — убираем
            if (isLoggedIn) {
                var toast = document.querySelector('.mst-wishlist-toast');
                if (toast) toast.remove();
            }
        } catch (e) {}
    });

    // Inject Liquid Glass Toast styles once
    function injectToastStyles() {
        if (document.getElementById('mst-wishlist-toast-styles')) return;
        
        var style = document.createElement('style');
        style.id = 'mst-wishlist-toast-styles';
        style.textContent = `
            .mst-wishlist-toast {
                position: fixed;
                top: 100px;
                right: 20px;
                z-index: 99999;
                background: rgba(255, 255, 255, 0.85);
                backdrop-filter: blur(20px);
                -webkit-backdrop-filter: blur(20px);
                border: 1px solid rgba(255, 255, 255, 0.4);
                border-radius: 16px;
                padding: 16px 20px;
                box-shadow: 0 8px 32px rgba(0, 0, 0, 0.12), inset 0 1px 2px rgba(255, 255, 255, 0.8);
                max-width: 340px;
                transform: translateX(120%);
                transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
                font-family: inherit;
            }
            .mst-wishlist-toast.show { transform: translateX(0); }

            .mst-wishlist-toast--error {
                background: rgba(255, 245, 245, 0.95);
                border-color: rgba(239, 68, 68, 0.35);
            }

            .mst-wishlist-toast-title {
                font-weight: 600;
                font-size: 14px;
                color: #1a1a1a;
                margin-bottom: 8px;
                display: flex;
                align-items: center;
                gap: 8px;
            }
            .mst-wishlist-toast-title svg { color: hsl(0, 80%, 60%); }
            .mst-wishlist-toast--error .mst-wishlist-toast-title svg { color: #ef4444; }

            .mst-wishlist-toast-text {
                font-size: 13px;
                color: #666;
                line-height: 1.4;
            }

            .mst-wishlist-toast-code {
                margin-top: 10px;
                font-size: 12px;
                color: #991b1b;
                font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
                word-break: break-word;
            }

            .mst-wishlist-toast-link {
                display: inline-block;
                margin-top: 12px;
                padding: 8px 16px;
                background: hsl(270, 70%, 60%);
                color: #fff !important;
                border-radius: 20px;
                text-decoration: none;
                font-size: 13px;
                font-weight: 500;
                transition: background 0.2s;
            }
            .mst-wishlist-toast-link:hover { background: hsl(270, 70%, 50%); color: #fff !important; }
            .mst-wishlist-toast-close {
                position: absolute;
                top: 8px;
                right: 8px;
                background: none;
                border: none;
                cursor: pointer;
                padding: 4px;
                color: #999;
            }
        `;
        document.head.appendChild(style);
    }

    // Show toast for unauthorized users
    function showWishlistToast() {
        var existingToast = document.querySelector('.mst-wishlist-toast');
        if (existingToast) existingToast.remove();

        var toast = document.createElement('div');
        toast.className = 'mst-wishlist-toast';
        toast.innerHTML = 
            '<button class="mst-wishlist-toast-close" onclick="this.parentElement.remove()">' +
                '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>' +
            '</button>' +
            '<div class="mst-wishlist-toast-title">' +
                '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="2"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/></svg>' +
                'Добавить в избранное' +
            '</div>' +
            '<div class="mst-wishlist-toast-text">' +
                'Для того чтобы добавлять товары в вишлист, зарегистрируйтесь или войдите в аккаунт.' +
            '</div>' +
            '<a href="/auth" class="mst-wishlist-toast-link">Войти / Регистрация</a>';
        
        document.body.appendChild(toast);

        requestAnimationFrame(function() {
            toast.classList.add('show');
        });

        setTimeout(function() {
            toast.classList.remove('show');
            setTimeout(function() { toast.remove(); }, 400);
        }, 5000);
    }

    function showWishlistErrorToast(title, message, code, context) {
        var existingToast = document.querySelector('.mst-wishlist-toast');
        if (existingToast) existingToast.remove();

        var toast = document.createElement('div');
        toast.className = 'mst-wishlist-toast mst-wishlist-toast--error';

        var ctxText = '';
        try {
            if (context) ctxText = JSON.stringify(context).slice(0, 500);
        } catch (e) {}

        toast.innerHTML =
            '<button class="mst-wishlist-toast-close" onclick="this.parentElement.remove()">' +
                '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>' +
            '</button>' +
            '<div class="mst-wishlist-toast-title">' +
                '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>' +
                (title || 'Ошибка вишлиста') +
            '</div>' +
            '<div class="mst-wishlist-toast-text">' + (message || 'Что-то пошло не так') + '</div>' +
            '<div class="mst-wishlist-toast-code">ID: ' + (code || 'E_UNKNOWN') + (ctxText ? ('\n' + ctxText) : '') + '</div>';

        document.body.appendChild(toast);

        requestAnimationFrame(function() {
            toast.classList.add('show');
        });

        setTimeout(function() {
            toast.classList.remove('show');
            setTimeout(function() { toast.remove(); }, 400);
        }, 8000);
    }

    function reportWishlistError(code, message, context) {
        try {
            console.error('[MST Wishlist]', code, message, context || {});
        } catch (e) {}
        showWishlistErrorToast('Не удалось обновить избранное', message, code, context);
    }

    // Fly heart animation to header
    function flyToHeaderWishlist($btn) {
        var headerWishlist = document.querySelector(
            '.mst-header-icon-btn[href*="wishlist"], ' +
            '.header-wishlist, ' +
            '.etheme-wishlist-widget, ' +
            '.et-wishlist, ' +
            '[class*="wishlist-icon"]'
        );
        if (!headerWishlist) return;

        var btnRect = $btn[0].getBoundingClientRect();
        var headerRect = headerWishlist.getBoundingClientRect();

        var flyingHeart = document.createElement('div');
        flyingHeart.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="hsl(0, 80%, 60%)" stroke="hsl(0, 80%, 50%)" stroke-width="2"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/></svg>';
        flyingHeart.style.cssText = 'position:fixed;z-index:99998;pointer-events:none;';
        flyingHeart.style.left = (btnRect.left + btnRect.width/2 - 9) + 'px';
        flyingHeart.style.top = (btnRect.top + btnRect.height/2 - 9) + 'px';
        document.body.appendChild(flyingHeart);

        var targetX = headerRect.left + headerRect.width/2 - 9;
        var targetY = headerRect.top + headerRect.height/2 - 9;
        var deltaX = targetX - btnRect.left - btnRect.width/2 + 9;
        var deltaY = targetY - btnRect.top - btnRect.height/2 + 9;
        
        flyingHeart.animate([
            { transform: 'scale(1)', opacity: 1 },
            { transform: 'scale(1.3)', opacity: 1, offset: 0.2 },
            { transform: 'translate(' + deltaX + 'px, ' + deltaY + 'px) scale(0.5)', opacity: 0.5 }
        ], { duration: 600, easing: 'cubic-bezier(0.4, 0, 0.2, 1)' }).onfinish = function() {
            flyingHeart.remove();
            headerWishlist.style.transform = 'scale(1.2)';
            setTimeout(function() { headerWishlist.style.transform = ''; }, 200);
        };
    }

// XStore Wishlist Sync
function initWishlistSync() {
    // Inject toast styles
    injectToastStyles();
    
    // Синхронная проверка авторизации (для быстрых случаев)
    function checkIsLoggedInSync() {
        return document.body.classList.contains('logged-in') || 
               (typeof mstShopGrid !== 'undefined' && mstShopGrid.userId > 0) ||
               (typeof mstAuthLK !== 'undefined' && mstAuthLK.is_logged_in);
    }

    // Асинхронная проверка авторизации через AJAX (обход кэша)
    function verifyAuthStateAsync() {
        // Если уже проверено через mst:auth событие — доверяем
        if (authStateVerified) {
            return Promise.resolve(checkIsLoggedInSync());
        }

        // Если ещё идёт проверка — ждём её
        if (authStatePromise) {
            return authStatePromise.then(function() {
                return checkIsLoggedInSync();
            });
        }

        // Запускаем свою проверку
        var ajaxUrl = (typeof mstShopGrid !== 'undefined' && mstShopGrid.ajaxUrl) ||
                      (typeof mstAuthLK !== 'undefined' && mstAuthLK.ajax_url) ||
                      (typeof mstLK !== 'undefined' && mstLK.ajax_url);

        if (!ajaxUrl) {
            return Promise.resolve(checkIsLoggedInSync());
        }

        authStatePromise = fetch(ajaxUrl, {
            method: 'POST',
            credentials: 'same-origin',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' },
            body: new URLSearchParams({ action: 'mst_auth_status' }),
        })
        .then(function(r) { return r.json(); })
        .then(function(res) {
            if (res && res.success && res.data) {
                var isLoggedIn = !!res.data.is_logged_in;
                var userId = res.data.user_id || 0;

                // Обновляем состояние
                if (typeof mstAuthLK !== 'undefined') {
                    mstAuthLK.is_logged_in = isLoggedIn;
                }
                if (typeof mstShopGrid !== 'undefined') {
                    mstShopGrid.userId = isLoggedIn ? (userId || 1) : 0;
                }
                if (document.body) {
                    document.body.classList.toggle('logged-in', isLoggedIn);
                    document.body.classList.toggle('logged-out', !isLoggedIn);
                }

                authStateVerified = true;

                // Убираем тост если залогинен
                if (isLoggedIn) {
                    var toast = document.querySelector('.mst-wishlist-toast');
                    if (toast) toast.remove();
                }

                return isLoggedIn;
            }
            return checkIsLoggedInSync();
        })
        .catch(function() {
            return checkIsLoggedInSync();
        });

        return authStatePromise;
    }

    $(document).on('click', '.mst-wishlist-btn', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        var $btn = $(this);
        var productId = $btn.data('product-id');
        var isActive = $btn.hasClass('mst-wishlist-active');
        
        // Prevent double clicks
        if ($btn.hasClass('mst-wishlist-loading')) {
            return;
        }

        // Быстрая синхронная проверка — если уже залогинен, продолжаем
        if (checkIsLoggedInSync()) {
            processWishlistClick($btn, productId, isActive);
            return;
        }

        // Если не залогинен по синхронной проверке — делаем асинхронную (обход кэша)
        $btn.addClass('mst-wishlist-loading');
        
        verifyAuthStateAsync().then(function(isLoggedIn) {
            $btn.removeClass('mst-wishlist-loading');
            
            if (isLoggedIn) {
                processWishlistClick($btn, productId, isActive);
            } else {
                showWishlistToast();
            }
        });
    });

    // Вынесли логику обработки клика в отдельную функцию
    function processWishlistClick($btn, productId, isActive) {
        // Базовые проверки (чтобы не "делать вид", что добавили)
        if (!productId) {
            reportWishlistError('E_DATA_001', 'Не найден product_id у кнопки', { productId: productId });
            return;
        }
        if (typeof mstShopGrid === 'undefined') {
            reportWishlistError('E_CFG_001', 'Не найден объект mstShopGrid (не подхватились настройки скрипта)', { productId: productId });
            return;
        }
        if (!mstShopGrid.ajaxUrl) {
            reportWishlistError('E_CFG_002', 'Не задан mstShopGrid.ajaxUrl', { productId: productId });
            return;
        }
        if (!mstShopGrid.nonce) {
            reportWishlistError('E_CFG_003', 'Не задан mstShopGrid.nonce', { productId: productId });
            return;
        }

        // Prevent double clicks
        if ($btn.hasClass('mst-wishlist-loading')) {
            return;
        }

        $btn.addClass('mst-wishlist-loading');

        // INSTANT ANIMATION - Update UI immediately before AJAX
        var $icon = $btn.find('.mst-heart-icon');
        var strokeColor = $icon.attr('stroke') || 'hsl(0, 80%, 60%)';
        var defaultFill = $btn.data('icon-fill') || 'none';

        function revertUi() {
            if (isActive) {
                $icon.attr('fill', strokeColor);
                $btn.addClass('mst-wishlist-active');
            } else {
                $icon.attr('fill', defaultFill);
                $btn.removeClass('mst-wishlist-active');
            }
        }

        // Add animation class for scale effect
        $btn.addClass('mst-wishlist-animating');

        if (isActive) {
            // Removing from wishlist
            $icon.attr('fill', defaultFill);
            $btn.removeClass('mst-wishlist-active');
        } else {
            // Adding to wishlist
            $icon.attr('fill', strokeColor);
            $btn.addClass('mst-wishlist-active');
            // Fly animation to header
            flyToHeaderWishlist($btn);
        }

        // Remove animation class after animation completes
        setTimeout(function() {
            $btn.removeClass('mst-wishlist-animating');
        }, 300);

        var action = isActive ? 'mst_remove_wishlist' : 'mst_add_wishlist';

        $.ajax({
            url: mstShopGrid.ajaxUrl,
            type: 'POST',
            data: {
                action: action,
                product_id: productId,
                nonce: mstShopGrid.nonce
            },
            success: function(response) {
                if (response && response.success) {
                    updateWishlistCounter(isActive ? -1 : 1);
                } else {
                    revertUi();
                    reportWishlistError('E_API_001', 'Сервер вернул ошибку при обновлении избранного', {
                        productId: productId,
                        action: action,
                        response: response
                    });
                }
            },
            error: function(xhr, status, error) {
                revertUi();
                reportWishlistError('E_NET_001', 'AJAX запрос не прошёл', {
                    productId: productId,
                    action: action,
                    status: status,
                    httpStatus: (xhr && typeof xhr.status !== 'undefined') ? xhr.status : undefined,
                    error: error,
                    responseText: (xhr && xhr.responseText) ? String(xhr.responseText).slice(0, 500) : ''
                });
            },
            complete: function() {
                $btn.removeClass('mst-wishlist-loading');
            }
        });
    }
}
    
    // Update XStore wishlist counter in header
    function updateWishlistCounter(delta) {
        // Try multiple selectors for XStore theme
        const selectors = [
            '.et-wishlist .et-wishlist-count',
            '.wishlist-count',
            '[data-wishlist-count]',
            '.header-wishlist .count'
        ];
        
        selectors.forEach(selector => {
            const $counter = $(selector);
            if ($counter.length) {
                let count = parseInt($counter.text()) || 0;
                count = Math.max(0, count + delta);
                $counter.text(count);
                
                // Update data attribute if exists
                if ($counter.is('[data-wishlist-count]')) {
                    $counter.attr('data-wishlist-count', count);
                }
            }
        });
    }
    
    // Load guide data and update links
    function initGuideLinks() {
        const $cards = $('.mst-shop-grid-card');
        if ($cards.length === 0) return;
        
        // Collect all product IDs
        const productIds = [];
        $cards.each(function() {
            const $guide = $(this).find('[data-product-id]').first();
            if ($guide.length) {
                productIds.push($guide.data('product-id'));
            }
        });
        
        if (productIds.length === 0) return;
        
        // URL encode product IDs and construct URL
        const encodedIds = productIds.map(id => encodeURIComponent(id)).join(',');
        
        // Fetch guide data from REST API
        $.ajax({
            url: mstShopGrid.restUrl + 'mst/v1/guides/' + encodedIds,
            type: 'GET',
            success: function(guides) {
                // Update each card with guide data
                $cards.each(function() {
                    const $card = $(this);
                    const $guide = $card.find('[data-product-id]').first();
                    const productId = $guide.data('product-id');
                    
                    if (guides[productId]) {
                        const guideData = guides[productId];
                        const $guideLink = $card.find('.mst-shop-grid-guide, .mst-shop-grid-guide-inside');
                        
                        if ($guideLink.length) {
                            // Update guide link URL
                            $guideLink.attr('href', guideData.url);
                            
                            // Update border color based on status
                            if (guideData.border) {
                                $guideLink.css('border-color', guideData.border);
                                $guideLink.data('hover-border', guideData.border);
                            }
                            
                            // Update avatar if needed
                            const $img = $guideLink.find('img');
                            if ($img.length && guideData.avatar) {
                                $img.attr('src', guideData.avatar);
                            }
                            
                            // Update title/tooltip
                            $guideLink.attr('title', guideData.name + ' - ' + guideData.rating + ' ★ (' + guideData.reviews + ')');
                        }
                    }
                });
            },
            error: function(xhr, status, error) {
                console.error('Guide data fetch error:', error);
            }
        });
    }
    
    // Check and mark active wishlist items on page load
    function checkWishlistStatus() {
        if (!mstShopGrid.userId) return;
        
        const $btns = $('.mst-wishlist-btn');
        const productIds = [];
        
        $btns.each(function() {
            productIds.push($(this).data('product-id'));
        });
        
        if (productIds.length === 0) return;
        
        $.ajax({
            url: mstShopGrid.ajaxUrl,
            type: 'POST',
            data: {
                action: 'mst_check_wishlist',
                product_ids: productIds,
                nonce: mstShopGrid.nonce
            },
            success: function(response) {
                if (response.success && response.data) {
                    response.data.forEach(function(productId) {
                        const $btn = $('.mst-wishlist-btn[data-product-id="' + productId + '"]');
                        $btn.addClass('mst-wishlist-active');
                        
                        // Update heart icon fill
                        const $icon = $btn.find('.mst-heart-icon');
                        const strokeColor = $icon.attr('stroke') || 'hsl(0, 80%, 60%)';
                        $icon.attr('fill', strokeColor);
                    });
                }
            }
        });
    }
    
    // Initialize all functionality
    function init() {
        initWishlistSync();
        initGuideLinks();
        checkWishlistStatus();
    }
    
    // Run on document ready
    $(document).ready(init);
    
    // Re-run on Elementor preview
    $(window).on('elementor/frontend/init', function() {
        if (typeof elementorFrontend !== 'undefined') {
            elementorFrontend.hooks.addAction('frontend/element_ready/widget', function($scope) {
                if ($scope.find('.mst-shop-grid').length) {
                    setTimeout(init, 100);
                }
            });
        }
    });

})(jQuery);
