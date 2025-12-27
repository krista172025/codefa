(function($) {
    'use strict';
    
    $(document).ready(function() {
        
        const $container = $('. mst-filters-container');
        if (!$container.length) return;
        
        const targetSelector = $container.data('target') || '.mst-shop-grid';
        const $grid = $(targetSelector);
        
        // Кнопка поиска
        $container.on('click', '.mst-btn-search', function() {
            applyFilters();
        });
        
        // Кнопка сброса
        $container.on('click', '.mst-btn-reset', function() {
            resetFilters();
        });
        
        // Обработка выбора цены
        $container. on('change', 'select[name="price_range"]', function() {
            const val = $(this).val();
            if (val) {
                const parts = val.split('-');
                $container.find('input[name="min_price"]').val(parts[0]);
                $container.find('input[name="max_price"]').val(parts[1]);
            }
        });
        
        function applyFilters() {
            const tourTypes = [];
            const categories = [];
            
            $container.find('input[name="tour_type[]"]:checked').each(function() {
                tourTypes.push($(this).val());
            });
            
            $container. find('input[name="categories[]"]: checked').each(function() {
                categories.push($(this).val());
            });
            
            const transport = $container.find('select[name="transport"]').val();
            const minPrice = $container. find('input[name="min_price"]').val();
            const maxPrice = $container.find('input[name="max_price"]').val();
            
            $grid.addClass('mst-loading');
            
            $. ajax({
                url: MST_FILTERS. ajax_url,
                type: 'POST',
                data: {
                    action: 'mst_filter_products',
                    nonce: MST_FILTERS.nonce,
                    tour_type: tourTypes,
                    transport: transport,
                    categories: categories,
                    min_price: minPrice,
                    max_price: maxPrice,
                },
                success: function(response) {
                    if (response.success) {
                        filterGridByIds(response.data.product_ids);
                    }
                    $grid.removeClass('mst-loading');
                },
                error:  function() {
                    $grid.removeClass('mst-loading');
                    console.error('MST Filters:  AJAX error');
                }
            });
        }
        
        function filterGridByIds(ids) {
            const $cards = $grid.find('.mst-shop-grid-card');
            
            if (ids.length === 0) {
                $cards.addClass('mst-hidden');
                return;
            }
            
            $cards. each(function() {
                const $card = $(this);
                const productId = $card.data('product-id') || 
                                  $card.find('[data-product-id]').data('product-id') ||
                                  extractProductId($card);
                
                if (ids.includes(productId)) {
                    $card. removeClass('mst-hidden');
                } else {
                    $card.addClass('mst-hidden');
                }
            });
        }
        
        function extractProductId($card) {
            // Пытаемся извлечь ID из разных мест
            const href = $card.find('a').first().attr('href');
            if (href) {
                const match = href.match(/\/product\/([^\/]+)\/? /);
                if (match) {
                    // Нужен ID, а не slug - используем data-атрибут
                }
            }
            
            // Fallback: ищем в wishlist кнопке
            const wishlistBtn = $card.find('. mst-wishlist-btn');
            if (wishlistBtn.length) {
                return parseInt(wishlistBtn. data('product-id'));
            }
            
            return 0;
        }
        
        function resetFilters() {
            $container.find('input[type="checkbox"]').prop('checked', false);
            $container.find('select').val('');
            $container.find('. mst-chip input').prop('checked', false);
            
            $grid.find('.mst-shop-grid-card').removeClass('mst-hidden');
        }
        
    });
    
})(jQuery);