/**
 * JavaScript для фильтров MySuperTour
 * Author: Telegram @l1ghtsun
 * Author URI: https://t.me/l1ghtsun
 */

jQuery(document).ready(function($) {
    
    // ✅ DROPDOWN ЦЕНЫ - ОТКРЫТИЕ/ЗАКРЫТИЕ
    $('#mst-price-toggle').on('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        var $content = $('#mst-price-content');
        var isVisible = $content.is(':visible');
        $('.mst-price-content').slideUp(200);
        $('.mst-price-toggle').removeClass('active');
        if (!isVisible) {
            $(this).addClass('active');
            $content.slideDown(200);
        }
    });

    $('#mst-price-content').on('click', function(e) {
        e.stopPropagation();
    });

    $(document).on('click', function(e) {
        if (!$(e.target).closest('.mst-price-dropdown').length) {
            $('#mst-price-content').slideUp(200);
            $('#mst-price-toggle').removeClass('active');
        }
    });

    // ✅ ОБНОВЛЕНИЕ ТРЕКА СЛАЙДЕРА
    function updateTrack() {
        var min = parseFloat($('#mst-slider-min').val());
        var max = parseFloat($('#mst-slider-max').val());
        var minL = parseFloat($('#mst-slider-min').attr('min'));
        var maxL = parseFloat($('#mst-slider-max').attr('max'));
        var minP = ((min - minL) / (maxL - minL)) * 100;
        var maxP = ((max - minL) / (maxL - minL)) * 100;
        $('#mst-track').css({left: minP + '%', width: (maxP - minP) + '%'});
    }

    // ✅ ОБРАБОТКА СЛАЙДЕРОВ И ИНПУТОВ
    $('#mst-slider-min, #mst-slider-max').on('input change', function() {
        var min = parseFloat($('#mst-slider-min').val());
        var max = parseFloat($('#mst-slider-max').val());
        var minL = parseFloat($('#mst-slider-min').attr('min'));
        var maxL = parseFloat($('#mst-slider-max').attr('max'));
        
        // Проверяем, чтобы слайдеры не пересекались
        if (min >= max - 1) {
            if ($(this).attr('id') === 'mst-slider-min') {
                min = max - 1;
                $('#mst-slider-min').val(min);
            } else {
                max = min + 1;
                $('#mst-slider-max').val(max);
            }
        }
        
        $('#mst-input-min').val(Math.round(min));
        $('#mst-input-max').val(Math.round(max));
        $('#mst-hidden-min').val(Math.round(min));
        $('#mst-hidden-max').val(Math.round(max));
        $('#mst-price-display-text').text(Math.round(min).toLocaleString('ru-RU') + ' — ' + Math.round(max).toLocaleString('ru-RU') + ' €');
        updateTrack();
    });

    // ✅ ОБРАБОТКА ТЕКСТОВЫХ ИНПУТОВ
    $('#mst-input-min, #mst-input-max').on('input change', function() {
        var min = parseFloat($('#mst-input-min').val()) || parseFloat($('#mst-slider-min').attr('min'));
        var max = parseFloat($('#mst-input-max').val()) || parseFloat($('#mst-slider-max').attr('max'));
        var minL = parseFloat($('#mst-slider-min').attr('min'));
        var maxL = parseFloat($('#mst-slider-max').attr('max'));
        
        if (min < minL) min = minL;
        if (max > maxL) max = maxL;
        if (min >= max - 1) min = max - 1;
        
        $('#mst-slider-min').val(min);
        $('#mst-slider-max').val(max);
        $('#mst-input-min').val(Math.round(min));
        $('#mst-input-max').val(Math.round(max));
        $('#mst-hidden-min').val(Math.round(min));
        $('#mst-hidden-max').val(Math.round(max));
        $('#mst-price-display-text').text(Math.round(min).toLocaleString('ru-RU') + ' — ' + Math.round(max).toLocaleString('ru-RU') + ' €');
        updateTrack();
    });

    updateTrack();
    
    // ✅ ВОССТАНОВЛЕНИЕ СОСТОЯНИЯ ИЗ URL ПРИ ЗАГРУЗКЕ
    function restoreFiltersFromURL() {
        const urlParams = new URLSearchParams(window.location.search);
        
        // ✅ Восстанавливаем формат
        let format = urlParams.get('format');
        if (!format) {
            const formatArray = urlParams.getAll('format[]');
            if (formatArray.length > 0) format = formatArray[0];
        }
        
        if (format) {
            const $formatInput = $('input[name="format"][value="' + format + '"]');
            if ($formatInput.length) {
                $formatInput.prop('checked', true);
                $formatInput.closest('label, .mst-checkbox-label, .mst-radio-label').addClass('active');
                
                // Обновляем dropdown для формата
                const $dropdownItem = $('.mst-dropdown-item input[value="' + format + '"]').closest('.mst-dropdown-item');
                $dropdownItem.addClass('selected');
                if (!$dropdownItem.find('.mst-item-check').length) {
                    $dropdownItem.append('<span class="mst-item-check">✓</span>');
                }
                
                // Обновляем текст dropdown
                const $dropdown = $dropdownItem.closest('.mst-custom-dropdown');
                const $toggle = $dropdown.find('.mst-dropdown-toggle');
                const icon = $dropdownItem.find('.mst-item-icon').text();
                const text = $dropdownItem.find('.mst-item-text').text();
                $toggle.find('.mst-dropdown-text').text(icon + ' ' + text);
            }
        }
        
        // ✅ Восстанавливаем транспорт
        const transports = urlParams.getAll('transport[]');
        if (transports.length === 0) {
            const singleTransport = urlParams.get('transport');
            if (singleTransport) transports.push(singleTransport);
        }
        
        transports.forEach(function(val) {
            const $input = $('input[name="transport[]"][value="' + val + '"]');
            if ($input.length) {
                $input.prop('checked', true);
                $input.closest('label, .mst-checkbox-label').addClass('active');
                
                const $dropdownItem = $('.mst-dropdown-item input[value="' + val + '"]').closest('.mst-dropdown-item');
                $dropdownItem.addClass('selected');
                if (!$dropdownItem.find('.mst-item-check').length) {
                    $dropdownItem.append('<span class="mst-item-check">✓</span>');
                }
            }
        });
        
        // ✅ Восстанавливаем параметры
        const attributes = urlParams.getAll('attributes[]');
        if (attributes.length === 0) {
            const singleAttr = urlParams.get('attributes');
            if (singleAttr) attributes.push(singleAttr);
        }
        
        attributes.forEach(function(val) {
            const $input = $('input[name="attributes[]"][value="' + val + '"]');
            if ($input.length) {
                $input.prop('checked', true);
                $input.closest('label, .mst-checkbox-label, .mst-chip').addClass('active');
            }
        });
        
        // Обновляем текст dropdown для транспорта
        updateDropdownText();
    }
    
    // ✅ ОБНОВЛЕНИЕ ТЕКСТА DROPDOWN
    function updateDropdownText() {
        $('.mst-custom-dropdown').each(function() {
            const $dropdown = $(this);
            const $toggle = $dropdown.find('.mst-dropdown-toggle');
            const isMultiple = $dropdown.data('multiple') === 'true' || $dropdown.data('multiple') === true;
            
            if (isMultiple) {
                const selectedCount = $dropdown.find('.mst-dropdown-item.selected').length;
                const text = selectedCount === 0 ? 'Выберите' : selectedCount + ' выбрано';
                $toggle.find('.mst-dropdown-text').text(text);
            } else {
                const $selected = $dropdown.find('.mst-dropdown-item.selected').first();
                if ($selected.length) {
                    const icon = $selected.find('.mst-item-icon').text();
                    const text = $selected.find('.mst-item-text').text();
                    $toggle.find('.mst-dropdown-text').text(icon + ' ' + text);
                }
            }
        });
    }
    
    // Вызываем восстановление при загрузке
    restoreFiltersFromURL();
    
    // ✅ КАСТОМНЫЙ DROPDOWN - ИСПРАВЛЕНО!
    $(document).on('click', '.mst-dropdown-toggle', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        const $toggle = $(this);
        const $menu = $('#' + $toggle.data('dropdown'));
        
        // Закрываем все другие дропдауны
        $('.mst-dropdown-menu').not($menu).slideUp(200);
        $('.mst-dropdown-toggle').not($toggle).removeClass('active');
        
        // Переключаем текущий
        $menu.slideToggle(200);
        $toggle.toggleClass('active');
    });
    
    // ✅ Клик по элементу dropdown - ИСПРАВЛЕНО!
    $(document).on('click', '.mst-dropdown-item', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        const $item = $(this);
        const $input = $item.find('input');
        const $dropdown = $item.closest('.mst-custom-dropdown');
        const $toggle = $dropdown.find('.mst-dropdown-toggle');
        const $menu = $item.closest('.mst-dropdown-menu');
        const isMultiple = $dropdown.data('multiple') === 'true' || $dropdown.data('multiple') === true;
        
        if (isMultiple) {
            // Checkbox mode
            $input.prop('checked', !$input.prop('checked'));
            $item.toggleClass('selected');
            
            const selectedCount = $dropdown.find('.mst-dropdown-item.selected').length;
            const text = selectedCount === 0 ? 'Выберите' : selectedCount + ' выбрано';
            $toggle.find('.mst-dropdown-text').text(text);
            
            if ($input.prop('checked')) {
                if (!$item.find('.mst-item-check').length) {
                    $item.append('<span class="mst-item-check">✓</span>');
                }
            } else {
                $item.find('.mst-item-check').remove();
            }
        } else {
            // Radio mode
            $menu.find('.mst-dropdown-item').removeClass('selected');
            $menu.find('.mst-item-check').remove();
            $menu.find('input').prop('checked', false);
            
            $input.prop('checked', true);
            $item.addClass('selected');
            $item.append('<span class="mst-item-check">✓</span>');
            
            const icon = $item.find('.mst-item-icon').text();
            const text = $item.find('.mst-item-text').text();
            $toggle.find('.mst-dropdown-text').text(icon + ' ' + text);
            
            $menu.slideUp(200);
            $toggle.removeClass('active');
        }
    });
    
    // Закрытие при клике вне
    $(document).on('click', function(e) {
        if (!$(e.target).closest('.mst-custom-dropdown').length) {
            $('.mst-dropdown-menu').slideUp(200);
            $('.mst-dropdown-toggle').removeClass('active');
        }
    });
    
    // ✅ КНОПКА "СБРОС"
    $(document).on('click', '.mst-btn-reset', function(e) {
        e.preventDefault();
        
        $('.mst-filters-form input[type="checkbox"]').prop('checked', false);
        $('.mst-filters-form input[type="radio"]').prop('checked', false);
        $('.mst-checkbox-label, .mst-radio-label, .mst-chip').removeClass('active');
        $('.mst-dropdown-item').removeClass('selected');
        $('.mst-item-check').remove();
        $('.mst-dropdown-text').text('Выберите');
        
        const shopUrl = $('.mst-filters-form').attr('action');
        window.location.href = shopUrl;
    });
    
    // ✅ ОБРАБОТКА ЧЕКБОКСОВ И ЧИПОВ
    $(document).on('click', '.mst-checkbox-label, .mst-chip', function(e) {
        if (!$(e.target).is('input')) {
            e.preventDefault();
            const $label = $(this);
            const $input = $label.find('input[type="checkbox"]');
            
            if ($input.length) {
                $input.prop('checked', !$input.prop('checked'));
                $label.toggleClass('active');
            }
        }
    });
    
    // ✅ ОБРАБОТКА RADIO BUTTONS
    $(document).on('click', '.mst-radio-label', function(e) {
        if (!$(e.target).is('input')) {
            e.preventDefault();
            const $label = $(this);
            const $input = $label.find('input[type="radio"]');
            
            if ($input.length) {
                const name = $input.attr('name');
                $('input[name="' + name + '"]').closest('.mst-radio-label').removeClass('active');
                $input.prop('checked', true);
                $label.addClass('active');
            }
        }
    });
    
    // ✅ AJAX ФИЛЬТРАЦИЯ ДЛЯ SHOP GRID
    $('.mst-filters-form').on('submit', function(e) {
        e.preventDefault();
        
        const $form = $(this);
        const $target = $($form.data('ajax-target'));
        
        // If no target found, use standard form submission
        if (!$target.length) {
            // Standard GET redirect for pages without Shop Grid
            const formData = new FormData(this);
            const params = new URLSearchParams(formData);
            const shopUrl = $form.attr('action') || '/shop';
            window.location.href = shopUrl + '?' + params.toString();
            return;
        }
        
        // AJAX filtering for Shop Grid widget
        const formData = new FormData(this);
        formData.append('action', 'mst_filter_products');
        formData.append('mst_ajax_filter', '1');
        
        // Show loading state
        $target.css('opacity', '0.5');
        $('.mst-btn-apply').prop('disabled', true).text('Загрузка...');
        
        fetch('/wp-admin/admin-ajax.php', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.success && data.data.html) {
                $target.html(data.data.html);
                
                // Reinitialize wishlist buttons if they exist
                if (typeof initWishlist === 'function') {
                    initWishlist();
                }
                
                // Scroll to results
                $('html, body').animate({
                    scrollTop: $target.offset().top - 100
                }, 500);
            } else {
                console.error('Filter error:', data);
                $target.html('<p style="text-align:center;padding:40px;">Ошибка загрузки товаров. Попробуйте обновить страницу.</p>');
            }
        })
        .catch(err => {
            console.error('Filter request failed:', err);
            $target.html('<p style="text-align:center;padding:40px;">Ошибка загрузки товаров. Попробуйте обновить страницу.</p>');
        })
        .finally(() => {
            $target.css('opacity', '1');
            $('.mst-btn-apply').prop('disabled', false).text('НАЙТИ');
        });
    });
});