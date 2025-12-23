/**
 * Frontend карта MySuperTour (LEAFLET - БЕЗ GOOGLE API)
 * Author: Telegram @l1ghtsun
 * Author URI: https://t.me/l1ghtsun
 */

(function($) {
    'use strict';

    let map;
    let markers = [];

    function initMap() {
        const container = $('.mst-map-container');
        if (!container.length) return;

        const productsData = container.attr('data-products');
        if (!productsData) {
            console.error('No products data found');
            return;
        }

        let products;
        try {
            products = JSON.parse(productsData);
        } catch(e) {
            console.error('Failed to parse products data:', e);
            return;
        }

        if (!products || !products.length) {
            $('#mst-google-map').html('<div class="mst-map-empty-state"><h3>Нет экскурсий для отображения</h3></div>');
            return;
        }

        console.log('🗺️ Initializing map with', products.length, 'products');

        // Вычисляем центр карты
        const avgLat = products.reduce((sum, p) => sum + parseFloat(p.lat), 0) / products.length;
        const avgLng = products.reduce((sum, p) => sum + parseFloat(p.lng), 0) / products.length;

        // ✅ Создаём LEAFLET карту (бесплатно!)
        map = L.map('mst-google-map').setView([avgLat, avgLng], 5);

        // Добавляем OpenStreetMap тайлы
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors',
            maxZoom: 19
        }).addTo(map);

        // Добавляем маркеры
        products.forEach((product, index) => {
            const lat = parseFloat(product.lat);
            const lng = parseFloat(product.lng);

            if (isNaN(lat) || isNaN(lng)) {
                console.warn('Invalid coordinates for product:', product);
                return;
            }

            // Кастомная иконка
            const customIcon = L.divIcon({
                className: 'mst-custom-marker',
                html: '<div style="background:#00c896;width:20px;height:20px;border-radius:50%;border:3px solid #fff;box-shadow:0 2px 8px rgba(0,200,150,0.5);"></div>',
                iconSize: [26, 26],
                iconAnchor: [13, 13]
            });

            const marker = L.marker([lat, lng], { icon: customIcon }).addTo(map);

            // Popup
            const priceHtml = product.price_html || `€${product.price}`;
            const popupContent = `
                <div style="padding:12px;min-width:200px;">
                    <h4 style="margin:0 0 8px;font-size:16px;font-weight:700;">${product.title}</h4>
                    ${product.city ? `<p style="margin:0 0 8px;color:#666;font-size:13px;">📍 ${product.city}</p>` : ''}
                    <p style="margin:0 0 8px;font-weight:700;color:#00c896;font-size:18px;">${priceHtml}</p>
                    ${product.duration ? `<p style="margin:0 0 8px;color:#666;font-size:13px;">⏱ ${product.duration}</p>` : ''}
                    <a href="${product.url}" style="display:inline-block;background:linear-gradient(135deg,#00c896 0%,#00a87a 100%);color:#fff;padding:8px 16px;border-radius:8px;text-decoration:none;font-size:13px;font-weight:600;margin-top:8px;">Подробнее →</a>
                </div>
            `;

            marker.bindPopup(popupContent);

            // Клик на маркер
            marker.on('click', function() {
                map.setView([lat, lng], 13);
                $('.mst-map-product-card').removeClass('active');
                $(`.mst-map-product-card[data-product-id="${product.id}"]`).addClass('active');
            });

            markers.push(marker);
        });

        console.log('✅ Map initialized with', markers.length, 'markers');
    }

    // Фильтр по городам
    $('#mst-map-city-filter').on('change', function() {
        const selectedCity = $(this).val();
        
        $('.mst-map-product-card').each(function() {
            const city = $(this).data('city');
            if (!selectedCity || city === selectedCity) {
                $(this).fadeIn(300);
            } else {
                $(this).fadeOut(300);
            }
        });

        // Обновляем видимость маркеров
        const container = $('.mst-map-container');
        const products = JSON.parse(container.attr('data-products') || '[]');
        
        if (selectedCity) {
            const visibleProducts = products.filter(p => p.city === selectedCity);
            if (visibleProducts.length > 0) {
                const avgLat = visibleProducts.reduce((sum, p) => sum + parseFloat(p.lat), 0) / visibleProducts.length;
                const avgLng = visibleProducts.reduce((sum, p) => sum + parseFloat(p.lng), 0) / visibleProducts.length;
                map.setView([avgLat, avgLng], 12);
            }
        } else {
            const avgLat = products.reduce((sum, p) => sum + parseFloat(p.lat), 0) / products.length;
            const avgLng = products.reduce((sum, p) => sum + parseFloat(p.lng), 0) / products.length;
            map.setView([avgLat, avgLng], 5);
        }
    });

    // Клик по карточке товара
    $(document).on('click', '.mst-map-product-card', function(e) {
        if ($(e.target).is('a')) return;

        const productId = $(this).data('product-id');
        const container = $('.mst-map-container');
        const products = JSON.parse(container.attr('data-products') || '[]');
        const product = products.find(p => p.id == productId);
        
        if (product) {
            const lat = parseFloat(product.lat);
            const lng = parseFloat(product.lng);
            map.setView([lat, lng], 15);

            // Открываем popup
            const markerIndex = products.indexOf(product);
            if (markers[markerIndex]) {
                markers[markerIndex].openPopup();
            }

            // Скроллим к карте
            $('html, body').animate({
                scrollTop: $('.mst-map-wrapper').offset().top - 100
            }, 500);
        }
    });

    // Инициализация
    $(document).ready(function() {
        if (typeof L !== 'undefined') {
            initMap();
        } else {
            console.error('❌ Leaflet не загружен');
        }
    });

})(jQuery);