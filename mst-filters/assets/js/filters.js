(function($) {
    'use strict';
    
    $(document).ready(function() {
        
        var $container = $('.mst-filters-container');
        if (!$container.length) {
            console.log('MST Filters: контейнер не найден');
            return;
        }
        
        console.log('MST Filters: инициализация');
        
        var targetSelector = $container.data('target') || '.mst-shop-grid';
        var $grid = $(targetSelector);
        
        console.log('MST Filters: target =', targetSelector, 'grid found =', $grid.length);
        
        // Кнопка поиска
        $container.on('click', '.mst-btn-search', function(e) {
            e.preventDefault();
            console.log('MST Filters: клик НАЙТИ');
            applyFilters();
        });
        
        // Кнопка сброса
        $container.on('click', '.mst-btn-reset', function(e) {
            e.preventDefault();
            console.log('MST Filters: клик СБРОС');
            resetFilters();
        });
        
        // Обработка выбора цены
        $container.on('change', 'select[name="price_range"]', function() {
            var val = $(this).val();
            if (val) {
                var parts = val.split('-');
                $container.find('input[name="min_price"]').val(parts[0]);
                $container.find('input[name="max_price"]').val(parts[1]);
            } else {
                var $min = $container.find('input[name="min_price"]');
                var $max = $container.find('input[name="max_price"]');
                $min.val($min.data('default') || 0);
                $max.val($max.data('default') || 999999);
            }
        });
        
        function applyFilters() {
            var tourTypes = [];
            var categories = [];
            
            $container.find('input[name="tour_type[]"]:checked').each(function() {
                tourTypes.push($(this).val());
            });
            
            $container.find('input[name="categories[]"]:checked').each(function() {
                categories.push($(this).val());
            });
            
            var transport = $container.find('select[name="transport"]').val() || '';
            var minPrice = $container.find('input[name="min_price"]').val() || 0;
            var maxPrice = $container.find('input[name="max_price"]').val() || 999999;
            
            console.log('MST Filters: запрос', {
                tour_type: tourTypes,
                transport: transport,
                categories: categories,
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
                    tour_type: tourTypes,
                    transport: transport,
                    categories: categories,
                    min_price: minPrice,
                    max_price: maxPrice
                },
                success: function(response) {
                    console.log('MST Filters: ответ', response);
                    if (response.success) {
                        filterGridByIds(response.data.product_ids);
                        console.log('MST Filters: найдено', response.data.found);
                    } else {
                        console.error('MST Filters: ошибка', response);
                    }
                    $grid.removeClass('mst-loading');
                },
                error: function(xhr, status, error) {
                    $grid.removeClass('mst-loading');
                    console.error('MST Filters: AJAX ошибка', status, error);
                }
            });
        }
        
        function filterGridByIds(ids) {
            var $cards = $grid.find('.mst-shop-grid-card');
            
            console.log('MST Filters: фильтрация, IDs:', ids, 'карточек:', $cards.length);
            
            // Удаляем предыдущее сообщение
            $grid.find('.mst-no-results').remove();
            
            if (! ids || ids.length === 0) {
                $cards.addClass('mst-hidden');
                $grid.append('<div class="mst-no-results" style="text-align: center;padding:40px;color:#666;">Товары не найдены</div>');
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
            var id = 0;
            
            // 1.data-product-id на карточке
            id = $card.data('product-id');
            if (id) return parseInt(id);
            
            // 2.data-product-id на вложенном элементе
            var $inner = $card.find('[data-product-id]');
            if ($inner.length) {
                id = $inner.data('product-id');
                if (id) return parseInt(id);
            }
            
            // 3.Wishlist кнопка
            var $wishlist = $card.find('.mst-wishlist-btn, .mst-shop-grid-wishlist, [data-product-id]');
            if ($wishlist.length) {
                id = $wishlist.first().data('product-id');
                if (id) return parseInt(id);
            }
            
            // 4.Класс post-123
            var classes = $card.attr('class') || '';
            var match = classes.match(/post-(\d+)/);
            if (match) return parseInt(match[1]);
            
            // 5.ID элемента
            var elemId = $card.attr('id') || '';
            match = elemId.match(/product-(\d+)/);
            if (match) return parseInt(match[1]);
            
            return 0;
        }
        
        function resetFilters() {
            $container.find('input[type="checkbox"]').prop('checked', false);
            $container.find('select').val('');
            
            var $min = $container.find('input[name="min_price"]');
            var $max = $container.find('input[name="max_price"]');
            $min.val($min.data('default') || 0);
            $max.val($max.data('default') || 999999);
            
            $grid.find('.mst-shop-grid-card').removeClass('mst-hidden');
            $grid.find('.mst-no-results').remove();
        }
        
    });
    
})(jQuery);