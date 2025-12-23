/**
 * MySuperTour Hub Admin JavaScript
 * Author: Telegram @l1ghtsun
 */

jQuery(function($){
    // Переключение вкладок для Search
    $('.msts-tab-btn').on('click', function(e){
        e.preventDefault();
        var tabId = $(this).data('tab');
        
        $('.msts-tab-btn').removeClass('active');
        $('.msts-tab-content').removeClass('active');
        
        $(this).addClass('active');
        $('#' + tabId).addClass('active');
        
        console.log('Tab switched to:', tabId);
    });
    
    // Превью иконок
    function updatePreview(){
        const type = $('#mst-t').val();
        const top = parseInt($('#mst-top').val()) || 0;
        const left = parseInt($('#mst-left').val()) || 0;
        const right = parseInt($('#mst-right').val()) || 0;
        const bottom = parseInt($('#mst-bottom').val()) || 0;
        const size = parseInt($('#mst-size').val()) || 32;
        const radius = parseInt($('#mst-radius').val()) || 50;
        
        const css = {
            position: type,
            top: (right || bottom) ? 'auto' : top + 'px',
            left: right ? 'auto' : left + 'px',
            right: right ? right + 'px' : 'auto',
            bottom: bottom ? bottom + 'px' : 'auto'
        };
        
        $('#mst-prev, .mst-li').css(css);
        $('.mst-icon-demo, .mst-lii').css({
            width: size + 'px',
            height: size + 'px',
            'border-radius': radius + '%'
        });
        $('.mst-icon-demo').css('font-size', (size / 2) + 'px');
    }
    $('.mst-l').on('input change', updatePreview);
    updatePreview();
    
    // ✅ АККОРДЕОНЫ - ИСПРАВЛЕНО!
    $('.mst-accordion-header').on('click', function(e) {
        e.preventDefault();
        const $accordion = $(this).closest('.mst-accordion');
        
        if ($accordion.hasClass('active')) {
            $accordion.removeClass('active');
        } else {
            $accordion.addClass('active');
        }
        
        console.log('Accordion toggled:', $accordion.find('.mst-accordion-title span:last').text());
    });
});