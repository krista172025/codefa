/**
 * Shop Grid - Wishlist Sync and Guide Links
 * Author: Telegram @l1ghtsun
 */

(function($) {
    'use strict';

    // XStore Wishlist Sync
    function initWishlistSync() {
        $(document).on('click', '.mst-wishlist-btn', function(e) {
            e.preventDefault();
            
            const $btn = $(this);
            const productId = $btn.data('product-id');
            const isActive = $btn.hasClass('mst-wishlist-active');
            
            // Prevent double clicks
            if ($btn.hasClass('mst-wishlist-loading')) {
                return;
            }
            
            $btn.addClass('mst-wishlist-loading');
            
            const action = isActive ? 'mst_remove_wishlist' : 'mst_add_wishlist';
            
            $.ajax({
                url: mstShopGrid.ajaxUrl,
                type: 'POST',
                data: {
                    action: action,
                    product_id: productId,
                    nonce: mstShopGrid.nonce
                },
                success: function(response) {
                    if (response.success) {
                        // Toggle active state
                        $btn.toggleClass('mst-wishlist-active');
                        
                        // Update heart icon fill
                        const $icon = $btn.find('.mst-heart-icon');
                        if ($btn.hasClass('mst-wishlist-active')) {
                            const strokeColor = $icon.attr('stroke') || 'hsl(0, 80%, 60%)';
                            $icon.attr('fill', strokeColor);
                        } else {
                            const defaultFill = $btn.data('icon-fill') || '#ffffff';
                            $icon.attr('fill', defaultFill);
                        }
                        
                        // Update XStore header counter if exists
                        updateWishlistCounter(isActive ? -1 : 1);
                    } else {
                        console.error('Wishlist error:', response.data);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX error:', error);
                },
                complete: function() {
                    $btn.removeClass('mst-wishlist-loading');
                }
            });
        });
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
