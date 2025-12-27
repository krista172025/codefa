(function($) {
    'use strict';
    
    $(document).ready(function() {
        
        var $container = $('.mst-filters-container');
        if (!$container.length) {
            return;
        }
        
        var targetSelector = $container.data('target') || '.mst-shop-grid';
        var $grid = $(targetSelector);
        
        // Инициализация слайдера цены
        initPriceSlider();
        
        // Кнопка поиска
        $container.on('click', '.mst-btn-search', function(e) {
            e.preventDefault();
            applyFilters();
        });
        
        // Кнопка сброса
        $container.on('click', '.mst-btn-reset', function(e) {
            e.preventDefault();
            resetFilters();
        });
        
        function initPriceSlider() {
            var $minInput = $container.find('input[name="min_price"]');
            var $maxInput = $container.find('input[name="max_price"]');
            var $range = $('#mst-price-range');
            var $minVal = $('#mst-price-min-val');
            var $maxVal = $('#mst-price-max-val');
            var $bars = $container.find('.mst-price-bar');
            
            if (!$minInput.length || !$maxInput.length) return;
            
            var min = parseFloat($minInput.attr('min'));
            var max = parseFloat($maxInput.attr('max'));
            
            function updateSlider() {
                var minVal = parseFloat($minInput.val());
                var maxVal = parseFloat($maxInput.val());
                
                // Не допускаем пересечения
                if (minVal > maxVal - 10) {
                    if ($(this).attr('name') === 'min_price') {
                        minVal = maxVal - 10;
                        $minInput.val(minVal);
                    } else {
                        maxVal = minVal + 10;
                        $maxInput.val(maxVal);
                    }
                }
                
                var leftPercent = ((minVal - min) / (max - min)) * 100;
                var rightPercent = ((maxVal - min) / (max - min)) * 100;
                
                $range.css({
                    left: leftPercent + '%',
                    width: (rightPercent - leftPercent) + '%'
                });
                
                $minVal.text(Math.round(minVal) + ' €');
                $maxVal.text(Math.round(maxVal) + ' €');
                
                // Обновляем гистограмму
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
            
            // Инициализация
            updateSlider();
        }
        
        function applyFilters() {
            var tourTypes = [];
            var transports = [];
            var tags = [];
            
            $container.find('input[name="tour_type[]"]:checked').each(function() {
                tourTypes.push($(this).val());
            });
            
            $container.find('input[name="transport[]"]: checked').each(function() {
                transports.push($(this).val());
            });
            
            $container.find('input[name="tags[]"]:checked').each(function() {
                tags.push($(this).val());
            });
            
            var minPrice = $container.find('input[name="min_price"]').val() || 0;
            var maxPrice = $container.find('input[name="max_price"]').val() || 999999;
            
            $grid.addClass('mst-loading');
            
            $.ajax({
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
                    if (response.success) {
                        filterGridByIds(response.data.product_ids);
                    }
                    $grid.removeClass('mst-loading');
                },
                error: function() {
                    $grid.removeClass('mst-loading');
                }
            });
        }
        
        function filterGridByIds(ids) {
            var $cards = $grid.find('.mst-shop-grid-card');
            
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
        
        function resetFilters() {
            $container.find('input[type="checkbox"]').prop('checked', false);
            
            var $minInput = $container.find('input[name="min_price"]');
            var $maxInput = $container.find('input[name="max_price"]');
            
            $minInput.val($minInput.data('default') || $minInput.attr('min'));
            $maxInput.val($maxInput.data('default') || $maxInput.attr('max'));
            
            // Перерисовываем слайдер
            $minInput.trigger('input');
            
            $grid.find('.mst-shop-grid-card').removeClass('mst-hidden');
            $grid.find('.mst-no-results').remove();
        }
        
    });
    
})(jQuery);