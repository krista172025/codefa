(function($) {
    'use strict';
    
    $(document).ready(function() {
        
        const $container = $('.mst-filters-container');
        if (!$container.length) {
            console.log('MST Filters:  контейнер не найден');
            return;
        }
        
        console.log('MST Filters: инициализация');
        
        const targetSelector = $container.data('target') || '.mst-shop-grid';
        const $grid = $(targetSelector);
        
        console.log('MST Filters: target =', targetSelector, ', grid found =', $grid.length);
        
        // Кнопка поиска
        $container.on('click', '.mst-btn-search', function(e) {
            e.preventDefault();
            console.log('MST Filters: клик по кнопке НАЙТИ');
            applyFilters();
        });
        
        // Кнопка сброса
        $container.on('click', '.mst-btn-reset', function(e) {
            e.preventDefault();
            console.log('MST Filters:  клик по кнопке СБРОС');
            resetFilters();
        });
        
        // Обработка выбора цены
        $container.on('change', 'select[name="price_range"]', function() {
            const val = $(this).val();
            if (val) {
                const parts = val.split('-');
                $container.find('input[name="min_price"]').val(parts[0]);
                $container.find('input[name="max_price"]').val(parts[1]);
            } else {
                // Сброс на дефолтные значения
                $container.find('input[name="min_price"]').val($container.find('input[name="min_price"]').data('default') || 0);
                $container.find('input[name="max_price"]').val($container.find('input[name="max_price"]').data('default') || 999999);
            }
        });
        
        function applyFilters() {
            const tourTypes = [];
            const categories = [];
            
            $container.find('input[name="tour_type[]"]:checked').each(function() {
                tourTypes.push($(this).val());
            });
            
            $container.find('input[name="categories[]"]: checked').each(function() {
                categories.push($(this).val());
            });
            
            const transport = $container.find('select[name="transport"]').val();
            const minPrice = $container.find('input[name="min_price"]').val() || 0;
            const maxPrice = $container.find('input[name="max_price"]').val() || 999999;
            
            console.log('MST Filters: отправка запроса', {
                tour_type: tourTypes,
                transport: transport,
                categories: categories,
                min_price:  minPrice,
                max_price: maxPrice
            });
            
            $grid.addClass('mst-loading');
            
            $.ajax({
                url: MST_FILTERS.ajax_url,
                type: 'POST',
                data: {
                    action: 'mst_filter_products',
                    nonce: MST_FILTERS.nonce,
                    tour_type:  tourTypes,
                    transport: transport,
                    categories:  categories,
                    min_price: minPrice,
                    max_price: maxPrice,
                },
                success: function(response) {
                    console.log('MST Filters: ответ сервера', response);
                    if (response.success) {
                        filterGridByIds(response.data.product_ids);
                        console.log('MST Filters: найдено товаров =', response.data.found);
                    } else {
                        console.error('MST Filters:  ошибка в ответе', response);
                    }
                    $grid.removeClass('mst-loading');
                },
                error: function(xhr, status, error) {
                    $grid.removeClass('mst-loading');
                    console.error('MST Filters:  AJAX ошибка', status, error);
                }
            });
        }
        
        function filterGridByIds(ids) {
            const $cards = $grid.find('.mst-shop-grid-card');
            
            console.log('MST Filters: фильтрация карточек, IDs =', ids, ', карточек =', $cards.length);
            
            if (ids.length === 0) {
                $cards.addClass('mst-hidden');
                // Показать сообщение "ничего не найдено"
                if (! $grid.find('.mst-no-results').length) {
                    $grid.append('<div class="mst-no-results">Товары не найдены</div>');
                }
                return;
            }
            
            // Убираем сообщение если было
            $grid.find('.mst-no-results').remove();
            
            $cards.each(function() {
                const $card = $(this);
                const productId = getProductId($card);
                
                console.log('MST Filters:  карточка ID =', productId);
                
                if (ids.includes(productId)) {
                    $card.removeClass('mst-hidden');
                } else {
                    $card.addClass('mst-hidden');
                }
            });
        }
        
        function getProductId($card) {
            // 1.data-product-id на карточке
            let id = $card.data('product-id');
            if (id) return parseInt(id);
            
            // 2.data-product-id на вложенном элементе
            const $inner = $card.find('[data-product-id]');
            if ($inner.length) {
                id = $inner.data('product-id');
                if (id) return parseInt(id);
            }
            
            // 3.Wishlist кнопка
            const $wishlist = $card.find('.mst-wishlist-btn, .mst-shop-grid-wishlist');
            if ($wishlist.length) {
                id = $wishlist.data('product-id');
                if (id) return parseInt(id);
            }
            
            // 4.Класс с ID (post-123)
            const classes = $card.attr('class') || '';
            const match = classes.match(/post-(\d+)/);
            if (match) return parseInt(match[1]);
            
            return 0;
        }
        
        function resetFilters() {
            $container.find('input[type="checkbox"]').prop('checked', false);
            $container.find('select').val('');
            
            // Сброс цен на дефолт
            const $minPrice = $container.find('input[name="min_price"]');
            const $maxPrice = $container.find('input[name="max_price"]');
            $minPrice.val($minPrice.data('default') || 0);
            $maxPrice.val($maxPrice.data('default') || 999999);
            
            // Показать все карточки
            $grid.find('.mst-shop-grid-card').removeClass('mst-hidden');
            $grid.find('.mst-no-results').remove();
        }
        
    });
    
})(jQuery);