(function($) {
    'use strict';
    
    const MSTFilters = {
        init: function() {
            this.bindEvents();
            this.findShopGrid();
        },
        
        findShopGrid: function() {
            // Search for Shop Grid on page
            this.$grid = $('.mst-shop-grid');
            this.$form = $('.mst-filters-form, #wcaf-filters-form');
        },
        
        bindEvents: function() {
            // Form submission
            $(document).on('submit', '.mst-filters-form, #wcaf-filters-form', this.handleSubmit.bind(this));
            
            // Auto-submit on checkbox change (optional)
            $(document).on('change', '.mst-filters-form input[type="checkbox"]', this.handleChange.bind(this));
            
            // Apply filters button
            $(document).on('click', '.mst-apply-filters', this.handleApplyClick.bind(this));
        },
        
        handleSubmit: function(e) {
            e.preventDefault();
            this.applyFilters($(e.target));
        },
        
        handleChange: function(e) {
            // Optional: auto-filter on change
            // Uncomment to enable auto-filtering
            // this.applyFilters($(e.target).closest('form'));
        },
        
        handleApplyClick: function(e) {
            e.preventDefault();
            const $form = $(e.target).closest('form');
            if (!$form.length) {
                $form = $('.mst-filters-form, #wcaf-filters-form').first();
            }
            if ($form.length) {
                this.applyFilters($form);
            }
        },
        
        applyFilters: function($form) {
            const self = this;
            const $grid = $('.mst-shop-grid');
            
            if (!$grid.length) {
                console.warn('Shop Grid not found on page');
                return;
            }
            
            // Show loader
            $grid.addClass('mst-loading');
            if (!$grid.find('.mst-loader').length) {
                $grid.append('<div class="mst-loader"><div class="mst-spinner"></div></div>');
            }
            
            // Get Elementor settings from data attribute
            const gridSettings = $grid.data('settings') || {};
            
            const formData = new FormData($form[0]);
            formData.append('action', 'mst_filter_shop_grid');
            formData.append('nonce', mstFiltersData.nonce);
            formData.append('elementor_settings', JSON.stringify(gridSettings));
            
            $.ajax({
                url: mstFiltersData.ajaxurl,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        $grid.html(response.data.html);
                        
                        // Update found products count if element exists
                        if (response.data.found && $('.mst-products-found').length) {
                            $('.mst-products-found').text(response.data.found);
                        }
                        
                        // Reinitialize all Shop Grid effects
                        if (typeof MSTShopGrid !== 'undefined' && MSTShopGrid.reinit) {
                            MSTShopGrid.reinit();
                        }
                        
                        // Reinitialize guides
                        if (typeof initShopGridGuides === 'function') {
                            initShopGridGuides();
                        }
                        
                        // Reinitialize wishlist
                        if (typeof initWishlistHover === 'function') {
                            initWishlistHover();
                        }
                        
                        // Reinitialize glow effects
                        if (typeof initFollowGlow === 'function') {
                            initFollowGlow();
                        }
                        
                        if (typeof initCursorGlow === 'function') {
                            initCursorGlow();
                        }
                        
                        // Reinitialize badge auto-positioning
                        if (typeof initBadgeAutoPosition === 'function') {
                            initBadgeAutoPosition();
                        }
                        
                        // Reinitialize liquid glass effects
                        if (typeof initLiquidGlass === 'function') {
                            initLiquidGlass();
                        }
                        
                        // Trigger custom event for other scripts to hook into (jQuery and native)
                        $(document).trigger('mst-shop-grid-updated', [$grid]);
                        document.dispatchEvent(new CustomEvent('mst_shop_grid_updated', { detail: { grid: $grid[0] } }));
                    } else {
                        console.error('Filter error:', response.data);
                    }
                    $grid.removeClass('mst-loading');
                    $grid.find('.mst-loader').remove();
                },
                error: function(xhr, status, error) {
                    $grid.removeClass('mst-loading');
                    $grid.find('.mst-loader').remove();
                    console.error('Filter AJAX error:', error);
                }
            });
        }
    };
    
    $(document).ready(function() {
        MSTFilters.init();
    });
    
})(jQuery);
