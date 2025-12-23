/**
 * MySuperTour Filters Auto Submit
 * Author: Telegram @l1ghtsun
 * Author URI: https://t.me/l1ghtsun
 */

jQuery(function($) {
    'use strict';

    // Автоматическая отправка формы при изменении фильтров
    $('.widget_layered_nav input[type="checkbox"]').on('change', function() {
        const form = $(this).closest('form');
        if (form.length) {
            // Показываем загрузку
            form.addClass('mst-filter-loading');
            
            // Отправляем форму
            setTimeout(function() {
                form.submit();
            }, 300);
        }
    });

    // Убираем класс загрузки после перехода
    $(window).on('beforeunload', function() {
        $('.mst-filter-loading').removeClass('mst-filter-loading');
    });
});