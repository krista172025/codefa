/**
 * MST Filters Admin JavaScript
 * v2.1.0
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
        $('.mst-cities-table input[name*=\\"[url_slug]\\\"]').each(function() {
            var $input = $(this);
            var $row = $input.closest('tr');
            var cityName = $row.find('td:eq(1) strong').text();
            
            if (!$input.val()) {
                $input.attr('placeholder', transliterate(cityName));
            }
        });
        
        // Category slug auto-generate
        $('input[name*=\\"category_settings\\\"][name*=\\"[url_slug]\\\"]').each(function() {
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
            var $checkboxes = $table.find('input[type=\\"checkbox\\\"]');
            var allChecked = $checkboxes.filter(':checked').length === $checkboxes.length;
            $checkboxes.prop('checked', !allChecked);
        });
        
        // URL preview update
        $('input[name*=\\"[url_slug]\\\"]').on('input', function() {
            updateURLPreview();
        });
        
        // Form validation
        $('form').on('submit', function() {
            var $btn = $(this).find('.button-hero');
            $btn.prop('disabled', true).text('⏳ Сохранение...');
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
