/**
 * MST Filters - Smart Filtering for WooCommerce
 * v2.2.0 - With custom dropdown and smart price slider
 */

(function($) {
    'use strict';
    
    $(document).ready(function() {
        initMSTFilters();
    });
    
    function initMSTFilters() {
        var $container = $('.mst-filters-container');
        if (!$container.length) {
            console.log('MST Filters: контейнер не найден');
            return;
        }
        
        console.log('MST Filters: инициализация v2.2');
        
        var targetSelector = $container.data('target') || '.mst-shop-grid';
        var $grid = $(targetSelector);
        var currentCategory = MST_FILTERS.current_category || '';
        
        console.log('MST Filters: target =', targetSelector, ', grid =', $grid.length, ', category =', currentCategory);
        
        // Initialize smart price slider
        initSmartPriceSlider($container);
        
        // Initialize custom dropdowns (replaces native select multiple)
        initCustomDropdowns($container);
        
        // Load smart filters if we have a category
        if (currentCategory && MST_FILTERS.current_category_id) {
            loadSmartFilters($container, currentCategory);
        }
        
        // Search button click
        $container.find('.mst-btn-search').off('click').on('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            console.log('MST Filters: клик НАЙТИ');
            applyFilters($container, $grid, currentCategory);
        });
        
        // Reset button click
        $container.find('.mst-btn-reset').off('click').on('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            console.log('MST Filters: клик СБРОС');
            resetFilters($container, $grid);
        });
    }
    
    /**
     * Initialize custom dropdown components (Liquid Glass style)
     * Replaces native <select multiple> with beautiful dropdown
     */
    function initCustomDropdowns($container) {
        $container.find('.mst-dropdown-wrapper').each(function() {
            var $wrapper = $(this);
            var $trigger = $wrapper.find('.mst-dropdown-trigger');
            var $menu = $wrapper.find('.mst-dropdown-menu');
            var $text = $wrapper.find('.mst-dropdown-text');
            var $hiddenSelect = $wrapper.find('.mst-select-hidden');
            var isMultiple = $wrapper.data('multiple') === true || $wrapper.data('multiple') === 'true';
            
            // Toggle dropdown on trigger click
            $trigger.on('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                // Close all other dropdowns
                $('.mst-dropdown-wrapper').not($wrapper).removeClass('mst-dropdown-open');
                
                $wrapper.toggleClass('mst-dropdown-open');
            });
            
            // Handle item selection
            $menu.find('.mst-dropdown-item input').on('change', function() {
                var $input = $(this);
                var value = $input.val();
                var checked = $input.is(':checked');
                
                if (!isMultiple) {
                    // Single select - uncheck others
                    $menu.find('.mst-dropdown-item input').not($input).prop('checked', false);
                    $wrapper.removeClass('mst-dropdown-open');
                }
                
                // Update hidden select
                syncHiddenSelect($wrapper);
                
                // Update trigger text
                updateDropdownText($wrapper);
            });
            
            // Initial text update
            updateDropdownText($wrapper);
        });
        
        // Close dropdowns on outside click
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.mst-dropdown-wrapper').length) {
                $('.mst-dropdown-wrapper').removeClass('mst-dropdown-open');
            }
        });
    }
    
    /**
     * Sync hidden select with custom dropdown state
     */
    function syncHiddenSelect($wrapper) {
        var $hiddenSelect = $wrapper.find('.mst-select-hidden');
        var $menu = $wrapper.find('.mst-dropdown-menu');
        
        // Clear current selection
        $hiddenSelect.find('option').prop('selected', false);
        
        // Set selected based on checkboxes
        $menu.find('.mst-dropdown-item input:checked').each(function() {
            var value = $(this).val();
            $hiddenSelect.find('option[value="' + value + '"]').prop('selected', true);
        });
    }
    
    /**
     * Update dropdown trigger text based on selection
     */
    function updateDropdownText($wrapper) {
        var $text = $wrapper.find('.mst-dropdown-text');
        var $menu = $wrapper.find('.mst-dropdown-menu');
        var selected = [];
        
        $menu.find('.mst-dropdown-item input:checked').each(function() {
            selected.push($(this).siblings('span').text());
        });
        
        if (selected.length === 0) {
            $text.text('Выберите');
            $wrapper.removeClass('mst-dropdown-has-value');
        } else if (selected.length === 1) {
            $text.text(selected[0]);
            $wrapper.addClass('mst-dropdown-has-value');
        } else {
            $text.text(selected.length + ' выбрано');
            $wrapper.addClass('mst-dropdown-has-value');
        }
    }
    
    /**
     * Initialize smart price slider - click anywhere to move thumb
     */
    function initSmartPriceSlider($container) {
        var $minInput = $container.find('input[name="min_price"]');
        var $maxInput = $container.find('input[name="max_price"]');
        var $range = $container.find('#mst-price-range');
        var $minVal = $container.find('#mst-price-min-val');
        var $maxVal = $container.find('#mst-price-max-val');
        var $bars = $container.find('.mst-price-bar');
        var $slider = $container.find('.mst-price-slider');
        var $track = $container.find('.mst-price-track');
        
        if (!$minInput.length || !$maxInput.length) return;
        
        var min = parseFloat($minInput.attr('min'));
        var max = parseFloat($maxInput.attr('max'));
        
        // Store which thumb is being dragged
        var draggingThumb = null;
        
        function updateSlider() {
            var minVal = parseFloat($minInput.val());
            var maxVal = parseFloat($maxInput.val());
            
            // Ensure min doesn't exceed max
            if (minVal > maxVal - 5) {
                minVal = maxVal - 5;
                $minInput.val(minVal);
            }
            
            var leftPercent = ((minVal - min) / (max - min)) * 100;
            var rightPercent = ((maxVal - min) / (max - min)) * 100;
            
            $range.css({
                left: leftPercent + '%',
                width: (rightPercent - leftPercent) + '%'
            });
            
            $minVal.text(Math.round(minVal) + ' €');
            $maxVal.text(Math.round(maxVal) + ' €');
            
            // Update histogram bars
            $bars.each(function(i) {
                var barMin = min + (i / 10) * (max - min);
                var barMax = min + ((i + 1) / 10) * (max - min);
                
                if (barMax >= minVal && barMin <= maxVal) {
                    $(this).addClass('active');
                } else {
                    $(this).removeClass('active');
                }
            });
        }
        
        /**
         * Calculate value from click position
         */
        function getValueFromPosition(e, $element) {
            var rect = $element[0].getBoundingClientRect();
            var clickX = e.clientX - rect.left;
            var percent = clickX / rect.width;
            percent = Math.max(0, Math.min(1, percent));
            return min + (max - min) * percent;
        }
        
        /**
         * Determine which thumb should move based on click position
         */
        function getNearestThumb(clickValue) {
            var minVal = parseFloat($minInput.val());
            var maxVal = parseFloat($maxInput.val());
            
            var distToMin = Math.abs(clickValue - minVal);
            var distToMax = Math.abs(clickValue - maxVal);
            
            // If click is in the middle, decide based on which side
            if (clickValue < minVal) return 'min';
            if (clickValue > maxVal) return 'max';
            
            return distToMin <= distToMax ? 'min' : 'max';
        }
        
        /**
         * Smart click handler - click anywhere on track/slider to move nearest thumb
         */
        $slider.on('mousedown touchstart', function(e) {
            // Don't interfere with actual thumb dragging
            if ($(e.target).closest('input[type="range"]').length) return;
            
            e.preventDefault();
            
            var clickEvent = e.originalEvent.touches ? e.originalEvent.touches[0] : e;
            var clickValue = getValueFromPosition(clickEvent, $slider);
            var thumbToMove = getNearestThumb(clickValue);
            
            if (thumbToMove === 'min') {
                var newMin = Math.min(clickValue, parseFloat($maxInput.val()) - 5);
                $minInput.val(Math.round(newMin));
            } else {
                var newMax = Math.max(clickValue, parseFloat($minInput.val()) + 5);
                $maxInput.val(Math.round(newMax));
            }
            
            updateSlider();
            
            // Enable dragging from this point
            draggingThumb = thumbToMove;
            
            $(document).on('mousemove.mstslider touchmove.mstslider', function(moveEvent) {
                var moveE = moveEvent.originalEvent.touches ? moveEvent.originalEvent.touches[0] : moveEvent;
                var moveValue = getValueFromPosition(moveE, $slider);
                
                if (draggingThumb === 'min') {
                    var newMin = Math.min(moveValue, parseFloat($maxInput.val()) - 5);
                    newMin = Math.max(min, newMin);
                    $minInput.val(Math.round(newMin));
                } else {
                    var newMax = Math.max(moveValue, parseFloat($minInput.val()) + 5);
                    newMax = Math.min(max, newMax);
                    $maxInput.val(Math.round(newMax));
                }
                
                updateSlider();
            });
            
            $(document).on('mouseup.mstslider touchend.mstslider', function() {
                draggingThumb = null;
                $(document).off('.mstslider');
            });
        });
        
        // Click on histogram bars
        $container.find('.mst-price-histogram').on('click', function(e) {
            var clickValue = getValueFromPosition(e, $(this));
            var thumbToMove = getNearestThumb(clickValue);
            
            if (thumbToMove === 'min') {
                var newMin = Math.min(clickValue, parseFloat($maxInput.val()) - 5);
                $minInput.val(Math.round(newMin));
            } else {
                var newMax = Math.max(clickValue, parseFloat($minInput.val()) + 5);
                $maxInput.val(Math.round(newMax));
            }
            
            updateSlider();
        });
        
        // Standard input handlers
        $minInput.on('input', updateSlider);
        $maxInput.on('input', updateSlider);
        
        // Initial update
        updateSlider();
    }
    
    /**
     * Load smart filters based on current category
     */
    function loadSmartFilters($container, categorySlug) {
        $.ajax({
            url: MST_FILTERS.ajax_url,
            type: 'POST',
            data: {
                action: 'mst_get_category_filters',
                nonce: MST_FILTERS.nonce,
                category_slug: categorySlug
            },
            success: function(response) {
                if (response.success && response.data) {
                    updateFilterOptions($container, response.data);
                    console.log('MST Filters: умные фильтры загружены', response.data);
                }
            },
            error: function(xhr, status, error) {
                console.error('MST Filters: ошибка загрузки умных фильтров', error);
            }
        });
    }
    
    /**
     * Update filter options based on available data
     */
    function updateFilterOptions($container, data) {
        function applyAvailabilityToChips(fieldName, available) {
            var selector = 'input[name="' + fieldName + '[]"]';
            $container.find(selector).each(function() {
                var $input = $(this);
                var $chip = $input.closest('.mst-chip');
                var value = $input.val();

                var isAvailable = !available || available.length === 0 || available.indexOf(value) !== -1;

                if (!isAvailable) {
                    // убираем из UI (и снимаем выбор на всякий)
                    $input.prop('checked', false);
                    $input.prop('disabled', true);
                    $chip.addClass('mst-filter-hidden');
                } else {
                    $input.prop('disabled', false);
                    $chip.removeClass('mst-filter-hidden');
                }
            });
        }

        function applyAvailabilityToDropdown(fieldName, available) {
            $container
                .find('.mst-dropdown-wrapper')
                .has('input[name="' + fieldName + '[]"]')
                .each(function() {
                    var $wrapper = $(this);
                    var $menu = $wrapper.find('.mst-dropdown-menu');

                    $menu.find('input[name="' + fieldName + '[]"]').each(function() {
                        var $input = $(this);
                        var $item = $input.closest('.mst-dropdown-item');
                        var value = $input.val();

                        var isAvailable = !available || available.length === 0 || available.indexOf(value) !== -1;

                        if (!isAvailable) {
                            $input.prop('checked', false);
                            $input.prop('disabled', true);
                            $item.addClass('mst-filter-hidden');
                        } else {
                            $input.prop('disabled', false);
                            $item.removeClass('mst-filter-hidden');
                        }
                    });

                    // подчищаем скрытый select и текст
                    syncHiddenSelect($wrapper);
                    updateDropdownText($wrapper);
                });
        }

        // Update tour types
        if (data.tour_types) {
            var availableTourTypes = Object.keys(data.tour_types);
            applyAvailabilityToChips('tour_type', availableTourTypes);
            applyAvailabilityToDropdown('tour_type', availableTourTypes);
        }

        // Update transports
        if (data.transports) {
            var availableTransports = Object.keys(data.transports);
            applyAvailabilityToChips('transport', availableTransports);
            applyAvailabilityToDropdown('transport', availableTransports);
        }

        // Update tags
        if (data.tags) {
            var availableTags = Object.keys(data.tags);
            applyAvailabilityToChips('tags', availableTags);
            applyAvailabilityToDropdown('tags', availableTags);
        }

        // Update price range
        if (data.price_range) {
            var $minInput = $container.find('input[name="min_price"]');
            var $maxInput = $container.find('input[name="max_price"]');

            if ($minInput.length && $maxInput.length) {
                $minInput.attr('min', data.price_range.min);
                $minInput.attr('max', data.price_range.max);
                $minInput.val(data.price_range.min);

                $maxInput.attr('min', data.price_range.min);
                $maxInput.attr('max', data.price_range.max);
                $maxInput.val(data.price_range.max);

                $minInput.trigger('input');
            }
        }
    }
    
    function applyFilters($container, $grid, categorySlug) {
        var tourTypes = [];
        var transports = [];
        var tags = [];
        
        // Collect checked tour types (chips)
        $container.find('input[name="tour_type[]"]:checked:not(:disabled)').each(function() {
            tourTypes.push($(this).val());
        });
        
        // Collect checked transports (chips)
        $container.find('input[name="transport[]"]:checked:not(:disabled)').each(function() {
            transports.push($(this).val());
        });
        
        // Collect checked tags (chips)
        $container.find('input[name="tags[]"]:checked:not(:disabled)').each(function() {
            tags.push($(this).val());
        });
        
        // Dropdown values (from custom dropdown)
        $container.find('.mst-dropdown-wrapper').each(function() {
            var $wrapper = $(this);
            var $hiddenSelect = $wrapper.find('.mst-select-hidden');
            var name = $hiddenSelect.attr('name');
            
            if (name) {
                name = name.replace('[]', '');
                var selectedValues = $hiddenSelect.val();
                
                if (selectedValues) {
                    if (Array.isArray(selectedValues)) {
                        if (name === 'transport') transports = transports.concat(selectedValues);
                        if (name === 'tour_type') tourTypes = tourTypes.concat(selectedValues);
                        if (name === 'tags') tags = tags.concat(selectedValues);
                    } else {
                        if (name === 'transport') transports.push(selectedValues);
                        if (name === 'tour_type') tourTypes.push(selectedValues);
                        if (name === 'tags') tags.push(selectedValues);
                    }
                }
            }
        });
        
        // Price range
        var minPrice = $container.find('input[name="min_price"]').val() || 0;
        var maxPrice = $container.find('input[name="max_price"]').val() || 999999;
        
        // Dropdown price fallback
        var priceRange = $container.find('select[name="price_range"]').val();
        if (priceRange) {
            var parts = priceRange.split('-');
            minPrice = parts[0];
            maxPrice = parts[1];
        }
        
        console.log('MST Filters: отправка', {
            category_slug: categorySlug,
            tour_type: tourTypes,
            transport: transports,
            tags: tags,
            min_price: minPrice,
            max_price: maxPrice
        });
        
        $grid.addClass('mst-loading');
        
        $.ajax({
            url: MST_FILTERS.ajax_url,
            type: 'POST',
            data: {
                action: 'mst_filter_products',
                nonce: MST_FILTERS.nonce,
                category_slug: categorySlug,
                tour_type: tourTypes,
                transport: transports,
                tags: tags,
                min_price: minPrice,
                max_price: maxPrice
            },
            success: function(response) {
                console.log('MST Filters: ответ', response);
                if (response.success) {
                    filterGridByIds(response.data.product_ids, $grid);
                    console.log('MST Filters: найдено', response.data.found);
                } else {
                    console.error('MST Filters: ошибка', response);
                }
                $grid.removeClass('mst-loading');
            },
            error: function(xhr, status, error) {
                console.error('MST Filters: AJAX ошибка', status, error);
                $grid.removeClass('mst-loading');
            }
        });
    }
    
    function filterGridByIds(ids, $grid) {
        var $cards = $grid.find('.mst-shop-grid-card');
        
        console.log('MST Filters: фильтрация, IDs:', ids, ', карточек:', $cards.length);
        
        // Remove existing no-results message
        $grid.find('.mst-no-results').remove();
        
        if (!ids || ids.length === 0) {
            $cards.addClass('mst-hidden');
            $grid.append('<div class="mst-no-results">Товары не найдены. Попробуйте изменить параметры фильтрации.</div>');
            return;
        }
        
        $cards.each(function() {
            var $card = $(this);
            var productId = getProductId($card);
            
            if (ids.indexOf(productId) !== -1) {
                $card.removeClass('mst-hidden');
            } else {
                $card.addClass('mst-hidden');
            }
        });
    }
    
    function getProductId($card) {
        // Try data attribute first
        var id = $card.data('product-id');
        if (id) return parseInt(id);
        
        // Try inner elements
        var $inner = $card.find('[data-product-id]');
        if ($inner.length) {
            id = $inner.data('product-id');
            if (id) return parseInt(id);
        }
        
        // Try wishlist button
        var $wishlist = $card.find('.mst-wishlist-btn, .mst-shop-grid-wishlist');
        if ($wishlist.length) {
            id = $wishlist.first().data('product-id');
            if (id) return parseInt(id);
        }
        
        // Try class name pattern
        var classes = $card.attr('class') || '';
        var match = classes.match(/post-(\d+)/);
        if (match) return parseInt(match[1]);
        
        return 0;
    }
    
    function resetFilters($container, $grid) {
        // Uncheck all checkboxes and radios (chips and dropdowns)
        $container.find('input[type="checkbox"], input[type="radio"]').prop('checked', false);
        $container.find('select').val('');
        
        // Reset custom dropdowns
        $container.find('.mst-dropdown-wrapper').each(function() {
            var $wrapper = $(this);
            $wrapper.find('.mst-dropdown-item input').prop('checked', false);
            $wrapper.find('.mst-dropdown-text').text('Выберите');
            $wrapper.removeClass('mst-dropdown-has-value mst-dropdown-open');
            
            // Reset hidden select
            $wrapper.find('.mst-select-hidden option').prop('selected', false);
        });
        
        // Reset price slider
        var $minInput = $container.find('input[name="min_price"]');
        var $maxInput = $container.find('input[name="max_price"]');
        
        $minInput.val($minInput.data('default') || $minInput.attr('min'));
        $maxInput.val($maxInput.data('default') || $maxInput.attr('max'));
        $minInput.trigger('input');
        
        // Show all cards
        $grid.find('.mst-shop-grid-card').removeClass('mst-hidden');
        $grid.find('.mst-no-results').remove();
    }
    
})(jQuery);
