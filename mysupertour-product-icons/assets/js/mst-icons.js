/**
 * MySuperTour Product Icons - Frontend Script (Динамическая загрузка)
 * Author: Telegram @l1ghtsun
 * Author URI: https://t.me/l1ghtsun
 */
(function(){
  'use strict';
  
  var DEBUG = false; // Выключен для продакшена
  function log(){ if(DEBUG) console.log.apply(console, arguments); }

  var META = (window.MST_PI_DATA && MST_PI_DATA.meta) ? MST_PI_DATA.meta : {};
  if(!META || !Object.keys(META).length){
    var inline=document.getElementById('mst-pi-json');
    if(inline){
      try{ META=JSON.parse(inline.textContent||'{}'); }catch(e){ log('[MST_PI] Ошибка JSON:', e); }
    }
  }
  
  // Кэш загруженных данных
  var CACHE = {};
  var LOADING = {}; // Трекинг запросов
  
  log('[MST_PI] Начальные данные:', Object.keys(META).length);

// ✅ УБИРАЕМ ХАРДКОД - берём название из meta
function getFormatName(meta){
    if(meta.format_name) return meta.format_name;
    
    // Fallback для старых слагов
    var oldFormats = {
        'group': 'Групповая',
        'individual': 'Индивидуальная',
        'butik': 'Бутик-формат',
        'mini': 'Мини-группа'
    };
    
    return oldFormats[meta.format] || meta.format || '';
}

// ✅ УБИРАЕМ ХАРДКОД - берём иконку из meta или fallback
function getTransportIcon(meta){
    if(meta.transport_icon) {
        return '<img src="'+escapeHtml(meta.transport_icon)+'" alt="">';
    }
    return meta.transport_icon_emoji || '🚶';
}

// ✅ ДОБАВЛЯЕМ ТЕКСТ ТРАНСПОРТА
function getTransportText(meta){
    if(meta.transport_name) return meta.transport_name;
    
    // Fallback для старых слагов
    var oldTransports = {
        'walk': 'Пешком',
        'car': 'Авто',
        'combined': 'Комбинированный'
    };
    
    return oldTransports[meta.transport] || '';
}

function escapeHtml(s){
    return (s==null?'':String(s)).replace(/[&<>"']/g,c=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));
}

function buildBadge(meta){
    var parts = [];
    
    // ✅ ФОРМАТ - берём из meta.format_name
    var formatName = getFormatName(meta);
    if(formatName) {
        parts.push('<span class="mst-pi-format">'+escapeHtml(formatName)+'</span>');
    }
    
    // ✅ ВРЕМЯ
    if(meta.duration || meta.time_icon){
        var ti = meta.time_icon ? '<img src="'+escapeHtml(meta.time_icon)+'" alt="">' : '⏱';
        parts.push('<span class="mst-pi-duration">'+ti+'<span class="mst-pi-duration-text">'+escapeHtml(meta.duration||'')+'</span></span>');
    }
    
    // ✅ ТРАНСПОРТ - показываем ИКОНКУ + ТЕКСТ
    if(meta.transport || meta.transport_icon){
        var transportIcon = getTransportIcon(meta);
        var transportText = getTransportText(meta);
        
        // Если есть текст - показываем иконку + текст
        if(transportText) {
            parts.push('<span class="mst-pi-transport">'+transportIcon+' <span class="mst-pi-transport-text">'+escapeHtml(transportText)+'</span></span>');
        } else {
            // Только иконка
            parts.push('<span class="mst-pi-transport">'+transportIcon+'</span>');
        }
    }
    
    return parts.length ? '<div class="mst-pi-badge">'+parts.join('')+'</div>' : '';
}

  function getId(el){
    if(!el) return 0;
    
    var classList = el.className || '';
    var matches = classList.match(/post-(\d+)|postid-(\d+)|product-(\d+)|item-(\d+)/);
    if(matches){
      for(var i=1; i<matches.length; i++){
        if(matches[i]) return parseInt(matches[i], 10);
      }
    }
    
    var dataId = el.getAttribute('data-product-id') || el.getAttribute('data-id') || el.getAttribute('data-post-id');
    if(dataId) return parseInt(dataId, 10);
    
    var parent = el.parentElement;
    if(parent && parent !== document.body){
      return getId(parent);
    }
    
    return 0;
  }

  // НОВАЯ ФУНКЦИЯ: Загрузка данных товара через REST API
  function loadProductMeta(productId, callback) {
    // Проверяем кэш
    if(CACHE[productId]) {
      log('[MST_PI] Взято из кэша:', productId);
      callback(CACHE[productId]);
      return;
    }
    
    // Проверяем, не загружается ли уже
    if(LOADING[productId]) {
      log('[MST_PI] Уже загружается:', productId);
      return;
    }
    
    LOADING[productId] = true;
    
    var restUrl = '/wp-json/mst/v1/product-meta/' + productId;
    
    log('[MST_PI] Загружаем данные для ID:', productId);
    
    fetch(restUrl)
      .then(function(response) {
        if (!response.ok) {
          throw new Error('HTTP ' + response.status);
        }
        return response.json();
      })
      .then(function(data) {
        log('[MST_PI] ✅ Загружены данные для ID:', productId, data);
        CACHE[productId] = data;
        META[productId] = data; // Добавляем в глобальный META
        delete LOADING[productId];
        callback(data);
      })
      .catch(function(error) {
        log('[MST_PI] ❌ Ошибка загрузки для ID:', productId, error);
        delete LOADING[productId];
      });
  }

  function addBadgeToProduct(productElement, productId, meta) {
    if(!meta || !(meta.format||meta.duration||meta.transport||meta.time_icon||meta.transport_icon)){
      log('[MST_PI] Нет данных для отображения, ID:', productId);
      return;
    }
    
    var containerSelectors = [
      '.etheme-product-grid-image',
      '.product-image-wrapper',
      '.product-image',
      '.product-thumbnail',
      '.product-content-image',
      '.content-product',
      'a.woocommerce-LoopProduct-link',
      'a[href*="product"]',
      'a',
      '.wp-post-image',
      'img.attachment-thumbnail'
    ];
    
    var container = null;
    for(var i=0; i<containerSelectors.length; i++){
      container = productElement.querySelector(containerSelectors[i]);
      if(container){
        log('[MST_PI] Контейнер:', containerSelectors[i]);
        break;
      }
    }
    
    if(!container){
      log('[MST_PI] ❌ Нет контейнера для ID:', productId);
      return;
    }
    
    if(container.tagName === 'IMG'){
      container = container.parentElement;
    }
    
    var pos = window.getComputedStyle(container).position;
    if(pos === 'static'){
      try{ container.style.position = 'relative'; }catch(e){}
    }
    
    var html = buildBadge(meta);
    if(!html) return;
    
    try{
      container.insertAdjacentHTML('afterbegin', html);
      log('[MST_PI] ✅ Плашка добавлена для ID:', productId);
    }catch(e){
      log('[MST_PI] ❌ Ошибка:', e);
    }
  }

  function process(){
    log('[MST_PI] Обработка товаров...');
    
    var selectors = [
      '.product:not(.footer-inner)',
      'li.product',
      'div.product:not(.footer-inner)',
      '.type-product',
      '.etheme-product',
      '.product-small',
      '.product-grid-item',
      '.etheme-product-grid-item'
    ];
    
    var nodes = document.querySelectorAll(selectors.join(','));
    log('[MST_PI] Найдено товаров:', nodes.length);
    
    nodes.forEach(function(n){
      if(n.classList.contains('footer-inner') || n.closest('.footer-inner')){
        return;
      }
      
      if(n.querySelector('.mst-pi-badge')){
        return;
      }
      
      var id = getId(n);
      if(!id) return;
      
      // Проверяем есть ли данные в META
      if(META[id]) {
        log('[MST_PI] Данные есть в META для ID:', id);
        addBadgeToProduct(n, id, META[id]);
      } else {
        // Загружаем данные через REST API
        log('[MST_PI] Загружаем данные для ID:', id);
        loadProductMeta(id, function(meta) {
          addBadgeToProduct(n, id, meta);
        });
      }
    });
  }

  function ready(fn){
    if(document.readyState!=='loading') fn();
    else document.addEventListener('DOMContentLoaded', fn);
  }

  ready(function(){
    log('[MST_PI] DOM готов');
    process();
    
    var tries = 0, intv = setInterval(function(){
      process();
      tries++;
      if(tries > 5) clearInterval(intv);
    }, 500);
    
    var obs = new MutationObserver(function(){
      process();
    });
    obs.observe(document.body, {childList:true, subtree:true});
  });
})();

jQuery(document).ready(function($) {
    // ✅ БЕЙДЖИК СКИДКИ
    $('.woocommerce-loop-product__link, .product-thumbnail').each(function() {
        var $link = $(this);
        var $product = $link.closest('li.product, .product-grid-item');
        
        if (!$product.length) return;
        
        // Ищем цены
        var $regular = $product.find('.price del .woocommerce-Price-amount, .regular-price .woocommerce-Price-amount').first();
        var $sale = $product.find('.price ins .woocommerce-Price-amount, .sale-price .woocommerce-Price-amount').first();
        
        if ($regular.length && $sale.length) {
            var regularPrice = parseFloat($regular.text().replace(/[^\d.,]/g, '').replace(',', '.'));
            var salePrice = parseFloat($sale.text().replace(/[^\d.,]/g, '').replace(',', '.'));
            
            if (salePrice < regularPrice) {
                var discount = Math.round(((regularPrice - salePrice) / regularPrice) * 100);
                
                // Создаём бейдж
                var $badge = $('<div class="mst-sale-badge">СКИДКА<br>' + discount + '%</div>');
                $badge.css({
                    'position': 'absolute',
                    'top': '10px',
                    'right': '10px',
                    'background': 'linear-gradient(135deg, #ff4444 0%, #cc0000 100%)',
                    'color': '#fff',
                    'padding': '8px 12px',
                    'border-radius': '8px 0 8px 0',
                    'font-weight': '700',
                    'font-size': '13px',
                    'line-height': '1.2',
                    'text-align': 'center',
                    'z-index': '10',
                    'box-shadow': '0 2px 8px rgba(255, 68, 68, 0.4)',
                    'pointer-events': 'none'
                });
                
                // Добавляем в контейнер картинки
                var $imgContainer = $link.find('.attachment-woocommerce_thumbnail').parent();
                if (!$imgContainer.length) $imgContainer = $link;
                
                $imgContainer.css('position', 'relative');
                $imgContainer.prepend($badge);
            }
        }
    });
});