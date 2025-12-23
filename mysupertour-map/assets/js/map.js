jQuery(function($) {
    if (typeof mstMap === 'undefined' || !mstMap.settings) return;
    
    const settings = mstMap.settings;
    const products = mstMap.products || [];
    
    if (settings.map_type === 'yandex') {
        if (!settings.api_key) {
            $('#mst-map').html('<div style="padding:40px;text-align:center;color:#999;">API ключ не указан</div>');
            return;
        }
        
        if (typeof ymaps === 'undefined') {
            $('#mst-map').html('<div style="padding:40px;text-align:center;color:#999;">Яндекс Карты не загрузились</div>');
            return;
        }
        
        ymaps.ready(function() {
            const map = new ymaps.Map('mst-map', {
                center: [parseFloat(settings.center_lat), parseFloat(settings.center_lng)],
                zoom: parseInt(settings.zoom),
                controls: ['zoomControl', 'fullscreenControl']
            });
            
            if (products.length === 0) {
                const examplePlacemark = new ymaps.Placemark([
                    parseFloat(settings.center_lat), 
                    parseFloat(settings.center_lng)
                ], {
                    balloonContent: '<strong>📍 Добавьте координаты к товарам</strong>'
                }, {
                    preset: 'islands#blueDotIcon'
                });
                map.geoObjects.add(examplePlacemark);
                return;
            }
            
            const markerColor = settings.marker_color || '#FF385C';
            
            // Функция для декодирования HTML entities
            function decodeHtml(html) {
                const txt = document.createElement('textarea');
                txt.innerHTML = html;
                return txt.value;
            }
            
            // Функция для извлечения только цены (без старой цены)
            function extractPrice(priceHtml) {
                const decoded = decodeHtml(priceHtml);
                // Берём только первую цену (до первого пробела или переноса)
                const match = decoded.match(/[\d\s,\.]+\s*[€₽$£]/);
                return match ? match[0].trim() : decoded.split(/\s+/)[0];
            }
            
            // КЛАСТЕРИЗАТОР
            const clusterer = new ymaps.Clusterer({
                clusterDisableClickZoom: false,
                clusterOpenBalloonOnClick: true,
                clusterBalloonContentLayout: 'cluster#balloonCarousel',
                clusterBalloonPagerSize: 5,
                clusterBalloonItemContentLayout: ymaps.templateLayoutFactory.createClass(
                    '<div style="padding:10px;">' +
                        '<h3 style="margin:0 0 5px;font-size:14px;">$[properties.title]</h3>' +
                        '<p style="margin:0;font-size:13px;color:#666;">$[properties.priceText]</p>' +
                    '</div>'
                ),
                clusterIconLayout: ymaps.templateLayoutFactory.createClass(
                    '<div class="mst-cluster">{{ properties.geoObjects.length }}</div>'
                ),
                clusterIconShape: {
                    type: 'Circle',
                    coordinates: [0, 0],
                    radius: 25
                }
            });
            
            const placemarks = [];
            
            // Добавляем товары
            products.forEach(function(product, index) {
                if (isNaN(product.lat) || isNaN(product.lng)) return;
                
                const priceClean = extractPrice(product.price);
                
                // Кастомная иконка с ценой
                const customIcon = ymaps.templateLayoutFactory.createClass(
                    '<div class="mst-price-marker">{{ properties.priceText }}</div>'
                );
                
                // Контент балуна
                const balloonContent = 
                    '<div style="width:320px;font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Roboto,sans-serif;">' +
                        '<img src="' + product.image + '" style="width:100%;height:200px;object-fit:cover;border-radius:8px;margin-bottom:12px;">' +
                        '<h3 style="font-size:18px;font-weight:600;color:#222;margin:0 0 10px 0;">' + product.title + '</h3>' +
                        '<div style="font-size:20px;font-weight:700;color:' + markerColor + ';margin-bottom:12px;">' + priceClean + '</div>' +
                        '<a href="' + product.link + '" style="display:block;padding:12px;background:' + markerColor + ';color:#fff;text-align:center;border-radius:8px;text-decoration:none;font-weight:600;">Подробнее</a>' +
                    '</div>';
                
                const placemark = new ymaps.Placemark(
                    [product.lat, product.lng],
                    {
                        title: product.title,
                        priceText: priceClean,
                        balloonContent: balloonContent
                    },
                    {
                        iconLayout: customIcon,
                        iconShape: {
							type: 'Rectangle',
							coordinates: [[-60, -30], [60, 30]]
						}
                    }
                );
                
                placemarks.push(placemark);
            });
            
            // Добавляем плейсмарки в кластеризатор
            clusterer.add(placemarks);
            map.geoObjects.add(clusterer);
            
            // Автоцентрирование
            if (products.length > 0) {
                setTimeout(function() {
                    map.setBounds(map.geoObjects.getBounds(), {
                        checkZoomRange: true,
                        zoomMargin: 100
                    });
                }, 100);
            }
        });
    }
});