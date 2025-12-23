/**
 * Admin JS для карты
 * 
 * @package MySuperTour_Map
 * @author Telegram @l1ghtsun
 * @link https://t.me/l1ghtsun
 */

jQuery(document).ready(function($) {
    'use strict';
    
    // Инициализация Google Maps в админке
    if (typeof google !== 'undefined' && $('#mst-admin-map').length) {
        initAdminMap();
    }
    
    // Поиск по адресу (геокодинг)
    $('.mst-geocode-button').on('click', function(e) {
        e.preventDefault();
        var address = $(this).closest('.mst-form-group').find('input[type="text"]').val();
        geocodeAddress(address);
    });
    
    function initAdminMap() {
        var mapElement = document.getElementById('mst-admin-map');
        var map = new google.maps.Map(mapElement, {
            center: {lat: 48.8566, lng: 2.3522}, // Париж по умолчанию
            zoom: 6,
            styles: getMapStyles()
        });
        
        // Добавляем маркеры всех товаров
        if (typeof mstMapAdminData !== 'undefined' && mstMapAdminData.products) {
            mstMapAdminData.products.forEach(function(product) {
                addAdminMarker(map, product);
            });
        }
    }
    
    function addAdminMarker(map, product) {
        var marker = new google.maps.Marker({
            position: {lat: parseFloat(product.lat), lng: parseFloat(product.lng)},
            map: map,
            title: product.title,
            draggable: true
        });
        
        var infoWindow = new google.maps.InfoWindow({
            content: '<div style="padding:10px;"><strong>' + product.title + '</strong><br>Город: ' + product.city + '</div>'
        });
        
        marker.addListener('click', function() {
            infoWindow.open(map, marker);
        });
        
        marker.addListener('dragend', function(event) {
            updateProductCoordinates(product.id, event.latLng.lat(), event.latLng.lng());
        });
    }
    
    function geocodeAddress(address) {
        $.ajax({
            url: mstMapAdmin.ajax_url,
            type: 'POST',
            data: {
                action: 'mst_map_geocode_address',
                nonce: mstMapAdmin.nonce,
                address: address
            },
            success: function(response) {
                if (response.success) {
                    alert('Координаты найдены:\nШирота: ' + response.data.lat + '\nДолгота: ' + response.data.lng);
                } else {
                    alert('Ошибка: ' + response.data);
                }
            }
        });
    }
    
    function updateProductCoordinates(productId, lat, lng) {
        $.ajax({
            url: mstMapAdmin.ajax_url,
            type: 'POST',
            data: {
                action: 'mst_map_update_coordinates',
                nonce: mstMapAdmin.nonce,
                product_id: productId,
                lat: lat,
                lng: lng
            },
            success: function(response) {
                if (response.success) {
                    console.log('Координаты обновлены для товара #' + productId);
                }
            }
        });
    }
    
    function getMapStyles() {
        return [
            {
                featureType: 'poi',
                elementType: 'labels',
                stylers: [{visibility: 'off'}]
            }
        ];
    }
});