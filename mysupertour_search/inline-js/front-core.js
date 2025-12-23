/**
 * Author: Telegram @l1ghtsun
 * Author URI: https://t.me/l1ghtsun
 */
/* front-core.js FINAL: улучшенная подсветка (не режет слова) + все города с рубриками */

(function($){
  if(typeof window.MSTS_CFG_DIAG==='undefined'){return;}
  var CFG=window.MSTS_CFG_DIAG, currentCity='', timer=null, LS_KEY='msts_last_query';

  function esc(s){return (s||'').replace(/[&<>"']/g,function(c){return {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c];});}
  
  // УЛУЧШЕННАЯ ПОДСВЕТКА: не режет слова, подсвечивает только префиксы/целые слова
  function hl(text,qs){
    if(!qs||!qs.length) return esc(text);
    var q=qs[0]; if(!q) return esc(text);
    var low=text.toLowerCase(), ql=q.toLowerCase();
    
    // Ищем вхождение в начале слова или целое слово (word boundary)
    var i=low.indexOf(ql);
    if(i===-1) return esc(text);
    
    // Проверяем: это начало слова? (i===0 или перед ним пробел/небуквенный символ)
    if(i>0 && /[a-zа-яё]/i.test(low[i-1])){
      // Это середина слова — НЕ подсвечиваем
      return esc(text);
    }
    
    return esc(text.slice(0,i))+'<span class="msts-hl">'+esc(text.substr(i,q.length))+'</span>'+esc(text.slice(i+q.length));
  }

  function formatItem(f){
    var meta = f.count ? '<div class="msts-meta">'+f.count+' предложений</div>' : '';
    return '<button type="button" class="msts-item msts-format" data-url="'+esc(f.url)+'">'+
           '<div class="msts-icon">'+esc(f.icon||'🟢')+'</div>'+
           '<div class="msts-title">'+esc(f.name)+meta+'</div></button>';
  }

  function bindInput($input){
    var $form=$input.closest('form');
    var $box=$form.find('.msts-suggestions');
    if(!$box.length) $box=$('<div class="msts-suggestions"></div>').appendTo($form);
    if(!$box.data('msts-appended')){$box.appendTo('body');$box.data('msts-appended',1);}

    var $clear=$form.find('.msts-clear-btn-fixed');

    function positionBox(){
      if(!$box.is(':visible')) return;
      var rect=$form[0].getBoundingClientRect();
      var offY=parseInt(getComputedStyle(document.documentElement).getPropertyValue('--msts-dd-offset'))||14;
      $box.css({left:(rect.left+window.scrollX)+'px',top:(rect.bottom+window.scrollY+offY)+'px',width:rect.width+'px'});
    }
    function show(){positionBox();$box.show();}
    function hide(){ 
      $box.hide().empty(); 
      currentCity=''; 
      toggleClear(false);
    }
    function loading(){
      positionBox();
      $box.html(head('Загрузка...')+'<div class="msts-empty">Ждите…</div>').show();
      bindClose();
    }

    $(window).on('scroll resize',positionBox);

    if(CFG.rememberQuery){
      var prev=localStorage.getItem(LS_KEY);
      if(prev) $input.val(prev);
    }

    // ИСПРАВЛЕНИЕ #1: НЕ АВТОФОКУС при загрузке главной страницы, только при HOVER
    var isHovered = false;
    
    $input.on('mouseenter', function(){
      isHovered = true;
      if(document.activeElement !== $input[0]) {
        $input[0].focus({preventScroll:true});
      }
    });

    $input.on('focus',function(){
      var v=$input.val().trim();
      // Показываем подсказки только при наведении или если есть текст
      if(isHovered || v !== '') {
        if(v===''){ fetch('',true); } else { fetch(v,false); }
      }
    });

    $input.on('input',function(){
      var v=$input.val();
      toggleClear(v.length>0);
      var trimmed=v.trim();
      if(CFG.rememberQuery) localStorage.setItem(LS_KEY,trimmed);
      clearTimeout(timer);
      timer=setTimeout(function(){ fetch(trimmed, trimmed===''); },150);
    });

    $clear.on('click',function(e){
      e.preventDefault();
      $input.val('');
      toggleClear(false);
      hide(); // ИСПРАВЛЕНИЕ #3: Закрываем окно при очистке
      $input.focus();
    });

    function toggleClear(show){
      if(show){ $clear.addClass('msts-show'); $input.addClass('has-value'); }
      else { $clear.removeClass('msts-show'); $input.removeClass('has-value'); }
    }

    $input.on('blur',function(){
      setTimeout(function(){ 
        if(!$box.is(':hover')) {
          hide(); 
        }
      },180);
    });

    function fetch(q,onlyPopular){
      loading();
      $.ajax({
        url:CFG.ajaxUrl,method:'GET',dataType:'json',
        data:{action:'msts_search_suggest',nonce:CFG.nonce,q:q,city:currentCity}
      }).done(function(resp){ render(resp,onlyPopular); })
        .fail(function(){ $box.html(head('Ошибка')+'<div class="msts-empty">Сбой запроса</div>'); bindClose(); });
    }

    function head(title){
      return '<div class="msts-head"><div class="msts-head-title">'+esc(title)+'</div>'+
             (CFG.showSectionTitles?'<button type="button" class="msts-close-btn" aria-label="Закрыть">×</button>':'')+
             '</div>';
    }

    function inlineCityBlock(city,rubrics,qs,isPopular){
      var meta=CFG.showCounts?'<div class="msts-meta">'+city.count+' предложений</div>':'';
      var out='<div class="msts-item msts-city msts-city-inline '+(isPopular?'msts-pop-city':'')+'" data-city="'+esc(city.slug)+'" data-url="'+esc(city.url)+'">'+
              '<div class="msts-icon">'+esc(city.icon||'😎')+'</div>'+
              '<div class="msts-title">'+hl(city.name,qs)+meta+'</div></div>';
      out+='<div class="msts-inline-rubrics">';
      rubrics.forEach(function(r){
        var rmeta=CFG.showCounts?' <span class="msts-meta">('+r.count+')</span>':'';
        out+='<div class="msts-inline-rubric" data-url="'+esc(r.url)+'" data-rub="'+esc(r.slug)+'">'+
             '<span class="msts-inline-dot">'+esc(r.icon||'😎')+'</span>'+
             '<span class="msts-inline-text">'+hl(r.name,qs)+rmeta+'</span></div>';
      });
      out+='</div>';
      return out;
    }

    function cityItem(slug,name,count,icon,url,qs,isPopular){
      var meta=CFG.showCounts?'<div class="msts-meta">'+count+' предложений</div>':'';
      return '<button type="button" class="msts-item msts-city '+(isPopular?'msts-pop-city':'')+'" data-city="'+esc(slug)+'" data-url="'+esc(url||'')+'">'+
             '<div class="msts-icon">'+esc(icon||'😎')+'</div>'+
             '<div class="msts-title">'+hl(name,qs)+meta+'</div></button>';
    }
    function rubricItem(slug,name,count,icon,url,qs){
      var meta=CFG.showCounts?'<div class="msts-meta">'+count+' предложений</div>':'';
      return '<button type="button" class="msts-item msts-rubric" data-rub="'+esc(slug)+'" data-url="'+esc(url||'')+'">'+
             '<div class="msts-icon">'+esc(icon||'😎')+'</div>'+
             '<div class="msts-title">'+hl(name,qs)+meta+'</div></button>';
    }
    function productItem(p,qs){
      var thumb=p.thumb?'<img class="msts-thumb" src="'+esc(p.thumb)+'" alt="">':'<div class="msts-icon">'+esc(p.fallback_icon||'😎')+'</div>';
      var price=p.price?'<div class="msts-price">от '+esc(p.price)+'</div>':'';
      return '<button type="button" class="msts-item msts-prod" data-url="'+esc(p.url)+'">'+thumb+'<div class="msts-title">'+hl(p.title,qs)+'</div>'+price+'</button>';
    }
    function infoItem(i,qs){
      var desc=i.desc||'';
      return '<button type="button" class="msts-item msts-info" data-url="'+esc(i.url)+'">'+
             '<div class="msts-icon">'+esc(i.fallback_icon||'😎')+'</div>'+
             '<div class="msts-title">'+hl(i.title,qs)+(desc?'<div class="msts-meta">'+esc(desc)+'</div>':'')+'</div></button>';
    }

    function render(data,onlyPopular){
      if(!data){hide();return;}
      var qs=data.expanded||[], html=head('Результаты');
      var isEmpty=!data.query;

      if(isEmpty && data.formats && data.formats.length){
        if(CFG.showSectionTitles) html+='<div class="msts-section-title">'+esc('Форматы тура')+'</div>';
        data.formats.forEach(function(f){ html+=formatItem(f); });
        html+='<div class="msts-divider"></div>';
      }

      if(!isEmpty && data.formats && data.formats.length){
        if(CFG.showSectionTitles) html+='<div class="msts-section-title">'+esc('Форматы тура')+'</div>';
        data.formats.forEach(function(f){ html+=formatItem(f); });
        html+='<div class="msts-divider"></div>';
      }

      if(data.popular && data.popular.length){
        if(CFG.showSectionTitles) html+='<div class="msts-section-title">'+esc(CFG.labels.popular)+'</div>';
        
        if(data.inline_rubrics && data.inline_rubrics.length && CFG.showPopularRubrics){
          data.inline_rubrics.forEach(function(block){
            html+=inlineCityBlock(block.city,block.rubrics,qs,true);
          });
        } else {
          data.popular.forEach(function(c){
            html+=cityItem(c.term,c.name,c.count,c.icon||'😎',c.url,qs,true);
          });
        }

        if(!isEmpty) html+='<div class="msts-divider"></div>';
      }

      var cities=data.cities||[];
      if(cities.length===1 && data.inline_rubrics && data.inline_rubrics.length){
        html+=inlineCityBlock(cities[0],data.inline_rubrics,qs,false);
        html+='<div class="msts-divider"></div>';
      } else if(cities.length){
        if(CFG.showSectionTitles) html+='<div class="msts-section-title">'+esc(CFG.labels.cities)+' ('+cities.length+')</div>';
        cities.forEach(function(c){
          html+=cityItem(c.slug,c.name,c.count,c.icon||'😎',c.url,qs,false);
        });
        html+='<div class="msts-divider"></div>';
      }

      if(data.rubrics && data.rubrics.length){
        if(CFG.showSectionTitles) html+='<div class="msts-section-title">'+esc(CFG.labels.rubrics)+' ('+data.rubrics.length+')</div>';
        data.rubrics.forEach(function(r){
          html+=rubricItem(r.slug,r.name,r.count,r.icon||'😎',r.url,qs);
        });
        html+='<div class="msts-divider"></div>';
      }

      if(CFG.grouping && !isEmpty){
        html+='<div class="msts-tabs">';
        var prodActive=data.counts.products>0;
        html+='<button type="button" class="msts-tab '+(prodActive?'msts-active':'')+'" data-tab="products">'+esc(CFG.labels.products)+' ('+data.counts.products+')</button>';
        html+='<button type="button" class="msts-tab '+(!prodActive?'msts-active':'')+'" data-tab="info">'+esc(CFG.labels.info)+' ('+data.counts.info+')</button>';
        html+='</div>';
        html+='<div class="msts-section-products" style="'+(prodActive?'':'display:none;')+'">';
        if(data.products && data.products.length){
          data.products.forEach(function(p){ html+=productItem(p,qs); });
        } else html+='<div class="msts-empty">Нет продуктов</div>';
        html+='</div>';
        html+='<div class="msts-section-info" style="'+(!prodActive?'':'display:none;')+'">';
        if(data.info && data.info.length){
          data.info.forEach(function(i){ html+=infoItem(i,qs); });
        } else html+='<div class="msts-empty">Нет материалов</div>';
        html+='</div>';
      }

      html+='<div class="msts-bottom-btn-wrap"><a class="msts-bottom-btn" href="'+esc(CFG.shopUrl||"/shop")+'">Посмотреть всю информацию</a></div>';

      $box.html(html);
      bindClose();
      bindActions($box,$input);
      show();

      if(isEmpty){
        $box.find('.msts-tabs,.msts-section-products,.msts-section-info').remove();
      }
    }

    function bindActions($box,$input){
      $box.find('.msts-city').on('click',function(e){
        e.preventDefault();
        var $btn=$(this);
        var slug=($btn.data('city')||'').toLowerCase();
        var url=$btn.data('url');
        if(!url){
          url=window.location.origin + '/product-category/' + slug + '/';
        }
        hide();
        window.location.href=url;
      });

      $box.find('.msts-inline-rubric,.msts-rubric,.msts-prod,.msts-info,.msts-format').on('click',function(e){
        e.preventDefault(); var url=$(this).data('url'); if(url){ hide(); window.location.href=url; }
      });

      $box.find('.msts-tab').on('click',function(){
        var tab=$(this).data('tab');
        $box.find('.msts-tab').removeClass('msts-active');
        $(this).addClass('msts-active');
        $box.find('.msts-section-products').toggle(tab==='products');
        $box.find('.msts-section-info').toggle(tab==='info');
      });
    }

    function bindClose(){
      $('.msts-close-btn').off('click').on('click',function(e){e.preventDefault();hide();});
    }
  }

  $(function(){
    $('.msts-search-wrapper form .msts-search-input').each(function(){bindInput($(this));});
  });

  window.addEventListener('beforeunload',function(){
    if(!CFG.rememberQuery) localStorage.removeItem(LS_KEY);
  });
})(jQuery);