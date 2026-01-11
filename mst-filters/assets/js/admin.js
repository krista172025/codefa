/**
 * MST Filters Admin JavaScript
 * v2.2.0 - With WordPress Media Library support
 */

(function($) {
    'use strict';

    $(document).ready(function() {
        initMSTFiltersAdmin();
    });

    function initMSTFiltersAdmin() {
        // Tabs functionality is handled inline in the admin page
        // This file is for additional admin features
        
        // City slug auto-generate
        $('.mst-cities-table input[name*="[url_slug]"]').each(function() {
            var $input = $(this);
            var $row = $input.closest('tr');
            var cityName = $row.find('td:eq(1) strong').text();
            
            if (!$input.val()) {
                $input.attr('placeholder', transliterate(cityName));
            }
        });
        
        // Category slug auto-generate
        $('input[name*="category_settings"][name*="[url_slug]"]').each(function() {
            var $input = $(this);
            if (!$input.val()) {
                var placeholder = $input.attr('placeholder');
                // Keep placeholder as-is
            }
        });
        
        // Toggle all checkboxes
        $('.mst-toggle-all').on('click', function(e) {
            e.preventDefault();
            var $table = $(this).closest('.mst-admin-card').find('table');
            var $checkboxes = $table.find('input[type="checkbox"]');
            var allChecked = $checkboxes.filter(':checked').length === $checkboxes.length;
            $checkboxes.prop('checked', !allChecked);
        });
        
        // URL preview update
        $('input[name*="[url_slug]"]').on('input', function() {
            updateURLPreview();
        });
        
        // Form validation
        $('form').on('submit', function() {
            var $btn = $(this).find('.button-hero');
            $btn.prop('disabled', true).text('⏳ Сохранение...');
        });
        
        // Icon type switcher
        initIconTypeSwitcher();
        
        // WordPress Media Library upload
        initMediaLibraryUpload();
    }
    
    /**
     * Initialize icon type switcher (emoji/image)
     */
    function initIconTypeSwitcher() {
        $(document).on('change', '.mst-icon-type-radio', function() {
            var $item = $(this).closest('.mst-icon-item');
            var type = $(this).val();
            
            if (type === 'emoji') {
                $item.find('.mst-icon-emoji-wrap').show();
                $item.find('.mst-icon-image-wrap').hide();
            } else {
                $item.find('.mst-icon-emoji-wrap').hide();
                $item.find('.mst-icon-image-wrap').show();
            }
        });
    }
    
    /**
     * Initialize WordPress Media Library upload
     */
    function initMediaLibraryUpload() {
        // Upload button click - create new uploader each time
        $(document).on('click', '.mst-upload-image-btn', function(e) {
            e.preventDefault();
            
            var $button = $(this);
            var $wrap = $button.closest('.mst-icon-image-wrap');
            var $input = $wrap.find('.mst-image-url-input');
            var $preview = $wrap.find('.mst-image-preview');
            var $removeBtn = $wrap.find('.mst-remove-image-btn');
            
            // Check if wp.media is available
            if (typeof wp === 'undefined' || typeof wp.media === 'undefined') {
                alert('Медиатека WordPress недоступна. Обновите страницу.');
                return;
            }
            
            // Create NEW media uploader each time for correct binding
            var mediaUploader = wp.media({
                title: 'Выберите иконку',
                button: {
                    text: 'Использовать эту иконку'
                },
                library: {
                    type: 'image'
                },
                multiple: false
            });
            
            // When an image is selected, run a callback
            mediaUploader.on('select', function() {
                var attachment = mediaUploader.state().get('selection').first().toJSON();
                
                // Get URL (prefer thumbnail or full size)
                var url = attachment.sizes && attachment.sizes.thumbnail 
                    ? attachment.sizes.thumbnail.url 
                    : attachment.url;
                
                $input.val(url);
                $preview.html('<img src="' + url + '" alt="">');
                $removeBtn.show();
            });
            
            // Open the uploader
            mediaUploader.open();
        });
        
        // Remove image button
        $(document).on('click', '.mst-remove-image-btn', function(e) {
            e.preventDefault();
            
            var $wrap = $(this).closest('.mst-icon-image-wrap');
            var $input = $wrap.find('.mst-image-url-input');
            var $preview = $wrap.find('.mst-image-preview');
            
            $input.val('');
            $preview.html('<span class="mst-no-image">Нет изображения</span>');
            $(this).hide();
        });
    }
    
    /**
     * Simple transliteration for URL slugs
     */
    function transliterate(text) {
        var ru = {
            'а': 'a', 'б': 'b', 'в': 'v', 'г': 'g', 'д': 'd',
            'е': 'e', 'ё': 'yo', 'ж': 'zh', 'з': 'z', 'и': 'i',
            'й': 'y', 'к': 'k', 'л': 'l', 'м': 'm', 'н': 'n',
            'о': 'o', 'п': 'p', 'р': 'r', 'с': 's', 'т': 't',
            'у': 'u', 'ф': 'f', 'х': 'kh', 'ц': 'ts', 'ч': 'ch',
            'ш': 'sh', 'щ': 'sch', 'ъ': '', 'ы': 'y', 'ь': '',
            'э': 'e', 'ю': 'yu', 'я': 'ya', ' ': '-'
        };
        
        return text.toLowerCase().split('').map(function(char) {
            return ru[char] || char;
        }).join('').replace(/[^a-z0-9-]/g, '').replace(/-+/g, '-');
    }
    
    /**
     * Update URL preview on the page
     */
    function updateURLPreview() {
        // This could update a live preview of URLs
        // For now, the examples are static
    }

})(jQuery);
