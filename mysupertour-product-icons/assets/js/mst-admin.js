/**
 * MySuperTour Product Icons - Admin Script
 * Author: Telegram @l1ghtsun
 * Author URI: https://t.me/l1ghtsun
 */

jQuery(function($) {
    'use strict';

    let mediaUploader;

    // Загрузка иконок
    $('.mst-pi-btn-upload').on('click', function(e) {
        e.preventDefault();
        
        const button = $(this);
        const targetId = button.data('target');
        const previewRole = button.data('preview');
        const previewBox = $('[data-role="' + previewRole + '"]');

        if (mediaUploader) {
            mediaUploader.open();
            return;
        }

        mediaUploader = wp.media({
            title: 'Выберите иконку',
            button: { text: 'Использовать' },
            multiple: false,
            library: { type: 'image' }
        });

        mediaUploader.on('select', function() {
            const attachment = mediaUploader.state().get('selection').first().toJSON();
            $('#' + targetId).val(attachment.id);
            previewBox.html('<img src="' + attachment.url + '" alt="">');
        });

        mediaUploader.open();
    });

    // Удаление иконок
    $('.mst-pi-btn-remove').on('click', function(e) {
        e.preventDefault();
        
        const button = $(this);
        const targetId = button.data('target');
        const previewRole = button.data('preview');
        const previewBox = $('[data-role="' + previewRole + '"]');

        $('#' + targetId).val('');
        previewBox.empty();
    });

    // Кнопка сохранения (триггер стандартного Update)
    $('#mst-pi-save-meta').on('click', function(e) {
        e.preventDefault();
        $('#publish, #save-post').click();
    });
});