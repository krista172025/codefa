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
        
        console.log('MST Filters: инициализация v1.2');
        
        var targetSelector = $container.data('target') || '.mst-shop-grid';
        var $grid = $(targetSelector);
        
        console.log('MST Filters: target =', targetSelector, ', grid =', $grid.length);
        
        // Инициализация слайдера цены
        initPriceSlider($container);
        
        // Кнопка поиска
        $container.find('.mst-btn-search').off('click').on('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            console.log('MST Filters: клик НАЙТИ');
            applyFilters($container, $grid);
        });
        
        // Кнопка сброса
        $container.find('.mst-btn-reset').off('click').on('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            console.log('MST Filters: клик СБРОС');
            resetFilters($container, $grid);
        });
    }
    
    function initPriceSlider($container) {
        var $minInput = $container.find('input[name="min_price"]');
        var $maxInput = $container.find('input[name="max_price"]');
        var $range = $container.find('#mst-price-range');
        var $minVal = $container.find('#mst-price-min-val');
        var $maxVal = $container.find('#mst-price-max-val');
        var $bars = $container.find('.mst-price-bar');
        
        if (! $minInput.length || !$maxInput.length) return;
        
        var min = parseFloat($minInput.attr('min'));
        var max = parseFloat($maxInput.attr('max'));
        
        function updateSlider() {
            var minVal = parseFloat($minInput.val());
            var maxVal = parseFloat($maxInput.val());
            
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
        
        $minInput.on('input', updateSlider);
        $maxInput.on('input', updateSlider);
        updateSlider();
    }
    
    function applyFilters($container, $grid) {
        var tourTypes = [];
        var transports = [];
        var tags = [];
        
        $container.find('input[name="tour_type[]"]:checked').each(function() {
            tourTypes.push($(this).val());
        });
        
        $container.find('input[name="transport[]"]:checked').each(function() {
            transports.push($(this).val());
        });
        
        $container.find('input[name="tags[]"]:checked').each(function() {
            tags.push($(this).val());
        });
        
        // Dropdown fallback
        var transportSelect = $container.find('select[name="transport"]').val();
        if (transportSelect) {
            transports.push(transportSelect);
        }
        
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
            tour_type: tourTypes,
            transport: transports,
            tags: tags,
            min_price: minPrice,
            max_price: maxPrice
        });
        
        $grid.addClass('mst-loading');
        
        $ajax({
            url: MST_FILTERS.ajax_url,
            type: 'POST',
            data: {
                action: 'mst_filter_products',
                nonce: MST_FILTERS.nonce,
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
        
        $grid.find('.mst-no-results').remove();
        
        if (! ids || ids.length === 0) {
            $cards.addClass('mst-hidden');
            $grid.append('<div class="mst-no-results">Товары не найдены</div>');
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
        var id = $card.data('product-id');
        if (id) return parseInt(id);
        
        var $inner = $card.find('[data-product-id]');
        if ($inner.length) {
            id = $inner.data('product-id');
            if (id) return parseInt(id);
        }
        
        var $wishlist = $card.find('.mst-wishlist-btn, .mst-shop-grid-wishlist');
        if ($wishlist.length) {
            id = $wishlist.first().data('product-id');
            if (id) return parseInt(id);
        }
        
        var classes = $card.attr('class') || '';
        var match = classes.match(/post-(\d+)/);
        if (match) return parseInt(match[1]);
        
        return 0;
    }
    
    function resetFilters($container, $grid) {
        $container.find('input[type="checkbox"], input[type="radio"]').prop('checked', false);
        $container.find('select').val('');
        
        var $minInput = $container.find('input[name="min_price"]');
        var $maxInput = $container.find('input[name="max_price"]');
        
        $minInput.val($minInput.data('default') || $minInput.attr('min'));
        $maxInput.val($maxInput.data('default') || $maxInput.attr('max'));
        $minInput.trigger('input');
        
        $grid.find('.mst-shop-grid-card').removeClass('mst-hidden');
        $grid.find('.mst-no-results').remove();
    }
    
})(jQuery);