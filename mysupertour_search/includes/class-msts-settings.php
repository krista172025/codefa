<?php
/**
 * Settings + Frontend generator
 * Version: 2.6.5-FINAL-COMPLETE-6-TABS-FIXED
 * Author: Telegram @l1ghtsun
 */

if(!defined('ABSPATH')) exit;

class MSTS_Settings {
    private static $inst;
    private $key='mysupertour_search_settings';

    private $defaults=[
        'theme'=>'emerald','bg_color'=>'#2b2b2d','bg_color2'=>'#1f1f20','btn_bg'=>'#404042','btn_text_color'=>'#ffffff','text_color'=>'#eaeaea','placeholder_color'=>'#9b9fa5',
        'form_height'=>56,'outer_radius'=>46,'inner_radius'=>36,'round_mode'=>'large','max_width'=>896,'percent_width'=>100,'center_align'=>1,'padding_x'=>20,
        'dropdown_offset'=>14,'dropdown_shadow'=>'medium','dropdown_border'=>1,'z_index'=>999999,
        'font_base'=>16,'font_item'=>14,'font_meta'=>11,'font_section'=>12,'icon_size'=>28,'icon_radius'=>14,'density'=>'normal','item_pad_y'=>10,'item_gap'=>14,
        'placeholder'=>'Куда вы собираетесь?','button_text'=>'Найти','show_button'=>1,
        'match_mode'=>'strict','show_rubrics_inline'=>1,'show_popular_rubrics'=>1,'disable_popular_min_len'=>2,
        'show_popular'=>1,'show_section_titles'=>1,'show_close_btn'=>1,'show_counts'=>1,'remember_last_query'=>0,
        'ajax_suggestions'=>1,'enable_grouping'=>1,'search_scope'=>'products',
        'city_taxonomy'=>'product_cat','exclude_city_slugs'=>'latepoint,groupovaya,individualnaya,mini-gruppa,personalnaya,butik-format',
        'cities_limit'=>12,'rubrics_limit'=>12,'popular_rubrics_limit'=>5,'products_limit'=>8,'info_limit'=>6,'popular_limit'=>8,'popular_cache_ttl'=>600,
        'popular_label'=>'Популярные города','cities_label'=>'Города','rubrics_label'=>'Рубрики','product_label'=>'Продукты','info_label'=>'Блог',
        'highlight_mode'=>'bg','highlight_color'=>'#ffe28f','enable_fuzzy'=>0,'fuzzy_max_distance'=>2,'latin_translit'=>0,'layout_fix'=>1,
        'city_alias_target'=>'париж','synonyms'=>'par=париж\npari=париж\nparis=париж','info_post_types'=>'post',
        'enable_term_icons'=>1,'icons_override'=>[],'show_icons'=>1,'show_price_range'=>0,
        'use_external'=>0,'external_shortcode'=>'','shop_button_url'=>'/shop','custom_css'=>'','city_map_custom'=>[]
    ];

    private $palettes=['emerald'=>['bg_color'=>'#2b2b2d','bg_color2'=>'#1f1f20','btn_bg'=>'#404042','btn_text_color'=>'#ffffff','text_color'=>'#eaeaea','placeholder_color'=>'#9b9fa5'],'graphite'=>['bg_color'=>'#303033','bg_color2'=>'#232325','btn_bg'=>'#4a4a4c','btn_text_color'=>'#ffffff','text_color'=>'#ececec','placeholder_color'=>'#9b9fa5']];

    public static function instance(){return self::$inst?:self::$inst=new self();}
    private function __construct(){add_action('admin_menu',[$this,'menu']);add_action('admin_init',[$this,'register']);add_action('admin_enqueue_scripts',[$this,'admin_assets']);add_action('wp_enqueue_scripts',[$this,'frontend']);}

    public function get(){$opt=get_option($this->key,[]);$m=wp_parse_args($opt,$this->defaults);if(isset($this->palettes[$m['theme']])){foreach($this->palettes[$m['theme']] as $k=>$v) $m[$k]=$v;}if($m['round_mode']==='large'){$m['outer_radius']=46;$m['inner_radius']=36;}$m['wrap_color']=$m['bg_color2'];if(!in_array($m['match_mode'],['strict','expanded'])) $m['match_mode']='strict';if(!is_array($m['icons_override'])) $m['icons_override']=[];if(!is_array($m['city_map_custom'])) $m['city_map_custom']=[];return $m;}

    public function menu(){add_menu_page('MySuperTour','MySuperTour','manage_options','mysupertour-search-settings',[$this,'render'],'dashicons-search',57);}
    public function register(){register_setting('msts_group',$this->key,[$this,'sanitize']);}
    private function clr($v){$c=sanitize_hex_color($v);return $c?$c:'#000000';}

    public function sanitize($in){$d=$this->defaults;$o=[];$o['theme']=$in['theme']??'emerald';foreach(['bg_color','bg_color2','btn_bg','btn_text_color','text_color','placeholder_color','highlight_color'] as $c){$o[$c]=$this->clr($in[$c]??$d[$c]);}foreach(['form_height','outer_radius','inner_radius','max_width','percent_width','padding_x','dropdown_offset','z_index','font_base','font_item','font_meta','font_section','icon_size','icon_radius','item_pad_y','item_gap','disable_popular_min_len','cities_limit','rubrics_limit','popular_rubrics_limit','products_limit','info_limit','popular_limit','popular_cache_ttl','fuzzy_max_distance'] as $n){$o[$n]=max(0,intval($in[$n]??$d[$n]));}foreach(['center_align','dropdown_border','show_icons','show_button','ajax_suggestions','enable_grouping','show_counts','show_price_range','show_popular','show_section_titles','show_close_btn','remember_last_query','enable_fuzzy','latin_translit','enable_term_icons','show_rubrics_inline','show_popular_rubrics','layout_fix','use_external'] as $flag){$o[$flag]=!empty($in[$flag])?1:0;}$o['round_mode']='large';$o['match_mode']='strict';$o['highlight_mode']='bg';foreach(['placeholder','button_text','popular_label','cities_label','rubrics_label','product_label','info_label','city_taxonomy','search_scope','exclude_city_slugs','info_post_types','shop_button_url','city_alias_target'] as $t){$o[$t]=sanitize_text_field($in[$t]??$d[$t]);}$o['synonyms']=wp_unslash($in['synonyms']??$d['synonyms']);$o['icons_override']=[];if(!empty($in['icons_override']) && is_array($in['icons_override'])){foreach($in['icons_override'] as $slug_raw=>$ic){$slug_clean=sanitize_title($slug_raw);if(!$slug_clean) $slug_clean=$slug_raw;$ic=trim(wp_unslash($ic));if($slug_clean!=='' && $ic!==''){$o['icons_override'][$slug_clean]=$ic;}}}$o['city_map_custom']=[];if(!empty($in['city_map_custom']) && is_array($in['city_map_custom'])){foreach($in['city_map_custom'] as $row){$canon=mb_strtolower(trim(sanitize_text_field($row['canonical']??'')));if($canon==='') continue;$prefixes=$this->list_clean($row['prefixes']??'');$shorts=$this->list_clean($row['shorts']??'');$translits=$this->list_clean($row['translits']??'');$o['city_map_custom'][]=['canonical'=>$canon,'prefixes_latin'=>$prefixes,'shorts'=>$shorts,'translits'=>$translits];}}$o['custom_css']=wp_unslash($in['custom_css']??'');$o['external_shortcode']=wp_kses_post($in['external_shortcode']??$d['external_shortcode']);return $o;}

    private function list_clean($txt){$parts=preg_split('/[,\\n]+/',wp_unslash($txt));$out=[];foreach($parts as $p){$p=mb_strtolower(trim($p));if($p!=='') $out[$p]=true;}return array_keys($out);}

    public function admin_assets($hook){if($hook!=='toplevel_page_mysupertour-search-settings') return;wp_enqueue_style('msts-admin','data:text/css,');$css='.wrap{background:linear-gradient(135deg,#f5f7fa,#e8ebef);min-height:100vh;padding:40px 20px}.msts-tabs-nav{display:flex;gap:6px;margin:30px 0 20px;background:rgba(255,255,255,0.7);backdrop-filter:blur(20px);border:1px solid rgba(255,255,255,0.6);border-radius:18px;padding:6px;box-shadow:0 8px 32px rgba(31,38,135,0.08)}.msts-tab-btn{flex:1;padding:12px 18px;border:none;background:transparent;border-radius:14px;font-weight:600;font-size:13px;cursor:pointer;transition:all 0.3s;color:#5a5f6b}.msts-tab-btn.active{background:rgba(255,255,255,0.95);color:#1d1d1f;box-shadow:0 4px 16px rgba(31,38,135,0.12)}.msts-tab-btn:hover:not(.active){background:rgba(255,255,255,0.5);color:#1d1d1f}.msts-tab-content{display:none}.msts-tab-content.active{display:block}.msts-panels{display:flex;flex-wrap:wrap;gap:20px;margin-top:20px}.msts-panel{flex:1 1 360px;background:rgba(255,255,255,0.75);backdrop-filter:blur(20px);border:1px solid rgba(255,255,255,0.6);border-radius:20px;padding:24px;min-width:340px;box-shadow:0 8px 32px rgba(31,38,135,0.1);transition:all 0.3s}.msts-panel:hover{box-shadow:0 12px 40px rgba(31,38,135,0.15);transform:translateY(-2px)}.msts-panel h2{margin:0 0 16px;font-size:18px;font-weight:700;color:#1d1d1f;border-bottom:1px solid rgba(0,0,0,0.06);padding-bottom:12px}.panel-body label{display:block;font-size:13px;font-weight:600;margin-bottom:10px;color:#3c4043}.panel-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(140px,1fr));gap:14px;margin-bottom:10px}textarea.msts-wide{width:100%;min-height:140px;border-radius:12px;border:1px solid rgba(0,0,0,0.12);padding:12px;font-size:13px;font-family:monospace;background:rgba(255,255,255,0.8);transition:all 0.2s}textarea.msts-wide:focus{border-color:#007aff;outline:none;background:rgba(255,255,255,0.95);box-shadow:0 0 0 3px rgba(0,122,255,0.1)}input[type=text],input[type=number],select{width:100%;padding:10px 12px;border:1px solid rgba(0,0,0,0.12);border-radius:10px;background:rgba(255,255,255,0.8);font-size:13px;transition:all 0.2s}input[type=text]:focus,input[type=number]:focus,select:focus{border-color:#007aff;outline:none;background:rgba(255,255,255,0.95);box-shadow:0 0 0 3px rgba(0,122,255,0.1)}input[type=checkbox]{transform:scale(1.15);margin-right:8px;cursor:pointer;accent-color:#007aff}.city-map-table{border:1px solid rgba(0,0,0,0.08);padding:16px;border-radius:16px;background:rgba(248,249,250,0.5);margin-bottom:16px}.city-map-row{display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:12px;margin-bottom:14px;background:rgba(255,255,255,0.9);border:1px solid rgba(0,0,0,0.06);padding:14px;border-radius:14px;position:relative;transition:all 0.2s}.city-map-row:hover{box-shadow:0 4px 12px rgba(31,38,135,0.1);border-color:rgba(0,122,255,0.2)}.city-map-row button.remove-city{position:absolute;top:8px;right:8px;background:#ff3b30;border:none;color:#fff;padding:6px 12px;font-size:12px;font-weight:700;border-radius:10px;cursor:pointer;transition:all 0.2s}.city-map-row button.remove-city:hover{background:#ff2d20;transform:scale(1.05)}.add-city-btn{background:#007aff;color:#fff;padding:12px 24px;border:none;border-radius:12px;cursor:pointer;font-size:14px;font-weight:600;box-shadow:0 4px 12px rgba(0,122,255,0.3);transition:all 0.2s}.add-city-btn:hover{background:#0051d5;transform:translateY(-1px);box-shadow:0 6px 16px rgba(0,122,255,0.4)}.submit input[type=submit]{background:#007aff !important;border:none !important;color:#fff !important;font-size:16px !important;font-weight:600 !important;padding:14px 32px !important;border-radius:12px !important;box-shadow:0 4px 12px rgba(0,122,255,0.3) !important;cursor:pointer !important;transition:all 0.2s !important}.submit input[type=submit]:hover{background:#0051d5 !important;transform:translateY(-1px) !important;box-shadow:0 6px 16px rgba(0,122,255,0.4) !important}';wp_add_inline_style('msts-admin',$css);wp_enqueue_script('msts-admin-js','data:text/javascript,',[],false,true);$js='document.addEventListener("DOMContentLoaded",function(){var tabs=document.querySelectorAll(".msts-tab-btn");var contents=document.querySelectorAll(".msts-tab-content");tabs.forEach(function(tab){tab.addEventListener("click",function(){tabs.forEach(function(t){t.classList.remove("active");});contents.forEach(function(c){c.classList.remove("active");});tab.classList.add("active");var target=tab.dataset.tab;document.getElementById(target).classList.add("active");});});var addBtn=document.getElementById("msts-add-city");if(addBtn){addBtn.addEventListener("click",function(e){e.preventDefault();var box=document.getElementById("msts-city-map-box");var idx=box.querySelectorAll(".city-map-row").length;var html=\'<div class="city-map-row">\'+\'<label>Каноническое<input type="text" name="\'+addBtn.dataset.opt+\'[city_map_custom][\'+idx+\'][canonical]" placeholder="лондон"></label>\'+\'<label>Latin prefix<input type="text" name="\'+addBtn.dataset.opt+\'[city_map_custom][\'+idx+\'][prefixes]" placeholder="lon,lond,london"></label>\'+\'<label>Русские сокращения<input type="text" name="\'+addBtn.dataset.opt+\'[city_map_custom][\'+idx+\'][shorts]" placeholder="лонд,лондон"></label>\'+\'<label>Транслиты<input type="text" name="\'+addBtn.dataset.opt+\'[city_map_custom][\'+idx+\'][translits]" placeholder="london"></label>\'+\'<button type="button" class="remove-city">×</button></div>\';var div=document.createElement("div");div.innerHTML=html;box.appendChild(div.firstElementChild);});}document.addEventListener("click",function(e){if(e.target.classList.contains("remove-city")){e.preventDefault();var row=e.target.closest(".city-map-row");row.parentElement.removeChild(row);}});});';wp_add_inline_script('msts-admin-js',$js);}

    public function render(){$s=$this->get();$export=json_encode($s,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);$tax=$s['city_taxonomy'];$cities=[];$rubrics=[];if(taxonomy_exists($tax)){$cities=get_terms(['taxonomy'=>$tax,'parent'=>0,'hide_empty'=>false,'number'=>0]);if(!is_wp_error($cities)){foreach($cities as $c){$kids=get_terms(['taxonomy'=>$tax,'parent'=>$c->term_id,'hide_empty'=>false,'number'=>0]);if(!is_wp_error($kids)) $rubrics[$c->slug]=$kids;}}}?>
        <div class="wrap"><h1 style="color:#1d1d1f;font-size:32px;margin-bottom:10px;font-weight:700;">🔍 MySuperTour Search</h1><p style="font-size:14px;color:#6e6e73;margin-bottom:20px;">v2.6.5-FINAL | Telegram: @l1ghtsun</p><div class="msts-tabs-nav"><button class="msts-tab-btn active" data-tab="tab-design">🎨 Дизайн</button><button class="msts-tab-btn" data-tab="tab-behavior">⚙️ Поведение</button><button class="msts-tab-btn" data-tab="tab-limits">📊 Источники</button><button class="msts-tab-btn" data-tab="tab-cities">🌍 Города</button><button class="msts-tab-btn" data-tab="tab-icons">🎭 Иконки</button><button class="msts-tab-btn" data-tab="tab-export">💾 Экспорт</button></div><form method="post" action="options.php"><?php settings_fields('msts_group');?><div id="tab-design" class="msts-tab-content active"><div class="msts-panels"><?php $this->panel_appearance($s);?></div></div><div id="tab-behavior" class="msts-tab-content"><div class="msts-panels"><?php $this->panel_behavior($s);?></div></div><div id="tab-limits" class="msts-tab-content"><div class="msts-panels"><?php $this->panel_limits($s);?></div></div><div id="tab-cities" class="msts-tab-content"><div class="msts-panels"><?php $this->panel_city_map($s);?></div></div><div id="tab-icons" class="msts-tab-content"><div class="msts-panels"><?php $this->panel_icons($s,$cities,$rubrics);?></div></div><div id="tab-export" class="msts-tab-content"><div class="msts-panels"><?php $this->panel_export($s,$export);?></div></div><?php submit_button('💾 Сохранить все настройки');?></form></div><?php }

    private function panel_appearance($s){?><div class="msts-panel"><h2>🎨 Внешний вид</h2><div class="panel-body"><label>Тема <select name="<?php echo $this->key;?>[theme]"><?php foreach($this->palettes as $k=>$v):?><option value="<?php echo esc_attr($k);?>" <?php selected($s['theme'],$k);?>><?php echo esc_html(ucfirst($k));?></option><?php endforeach;?></select></label><div class="panel-grid"><label>BG1 <input type="text" name="<?php echo $this->key;?>[bg_color]" value="<?php echo esc_attr($s['bg_color']);?>"></label><label>BG2 <input type="text" name="<?php echo $this->key;?>[bg_color2]" value="<?php echo esc_attr($s['bg_color2']);?>"></label><label>Btn BG <input type="text" name="<?php echo $this->key;?>[btn_bg]" value="<?php echo esc_attr($s['btn_bg']);?>"></label></div><label><input type="checkbox" name="<?php echo $this->key;?>[show_icons]" value="1" <?php checked($s['show_icons'],1);?>> Показывать иконки</label><label><input type="checkbox" name="<?php echo $this->key;?>[center_align]" value="1" <?php checked($s['center_align'],1);?>> Центрировать</label><hr><label>Доп CSS <textarea name="<?php echo $this->key;?>[custom_css]" class="msts-wide" placeholder="/* Ваш CSS */"><?php echo esc_textarea($s['custom_css']);?></textarea></label></div></div><?php }

    private function panel_behavior($s){?><div class="msts-panel"><h2>⚙️ Поведение</h2><div class="panel-body"><label><input type="checkbox" name="<?php echo $this->key;?>[layout_fix]" value="1" <?php checked($s['layout_fix'],1);?>> Исправлять раскладку</label><label><input type="checkbox" name="<?php echo $this->key;?>[show_rubrics_inline]" value="1" <?php checked($s['show_rubrics_inline'],1);?>> Inline рубрики (1 город)</label><label><input type="checkbox" name="<?php echo $this->key;?>[show_popular_rubrics]" value="1" <?php checked($s['show_popular_rubrics'],1);?>> Рубрики у популярных</label><label><input type="checkbox" name="<?php echo $this->key;?>[show_popular]" value="1" <?php checked($s['show_popular'],1);?>> Популярные города</label><label><input type="checkbox" name="<?php echo $this->key;?>[show_section_titles]" value="1" <?php checked($s['show_section_titles'],1);?>> Заголовки секций</label><label><input type="checkbox" name="<?php echo $this->key;?>[show_close_btn]" value="1" <?php checked($s['show_close_btn'],1);?>> Кнопка закрытия</label><label><input type="checkbox" name="<?php echo $this->key;?>[show_counts]" value="1" <?php checked($s['show_counts'],1);?>> Показывать количество</label><hr><label>Placeholder <input type="text" name="<?php echo $this->key;?>[placeholder]" value="<?php echo esc_attr($s['placeholder']);?>"></label><label>Текст кнопки <input type="text" name="<?php echo $this->key;?>[button_text]" value="<?php echo esc_attr($s['button_text']);?>"></label><label><input type="checkbox" name="<?php echo $this->key;?>[show_button]" value="1" <?php checked($s['show_button'],1);?>> Показывать кнопку</label><label>Shop URL <input type="text" name="<?php echo $this->key;?>[shop_button_url]" value="<?php echo esc_attr($s['shop_button_url']);?>"></label></div></div><?php }

    private function panel_limits($s){?><div class="msts-panel"><h2>📊 Источники / Лимиты</h2><div class="panel-body"><div class="panel-grid"><label>Города <input type="number" name="<?php echo $this->key;?>[cities_limit]" value="<?php echo esc_attr($s['cities_limit']);?>"></label><label>Рубрики <input type="number" name="<?php echo $this->key;?>[rubrics_limit]" value="<?php echo esc_attr($s['rubrics_limit']);?>"></label></div><hr><label>Таксономия <input type="text" name="<?php echo $this->key;?>[city_taxonomy]" value="<?php echo esc_attr($s['city_taxonomy']);?>"></label><label>Исключить slugs <input type="text" name="<?php echo $this->key;?>[exclude_city_slugs]" value="<?php echo esc_attr($s['exclude_city_slugs']);?>"></label><label><input type="checkbox" name="<?php echo $this->key;?>[ajax_suggestions]" value="1" <?php checked($s['ajax_suggestions'],1);?>> AJAX подсказки</label><label><input type="checkbox" name="<?php echo $this->key;?>[enable_grouping]" value="1" <?php checked($s['enable_grouping'],1);?>> Группировать Prod/Info</label><hr><label>Синонимы <textarea name="<?php echo $this->key;?>[synonyms]" class="msts-wide" placeholder="par=париж"><?php echo esc_textarea($s['synonyms']);?></textarea></label></div></div><?php }

    private function panel_city_map($s){?><div class="msts-panel"><h2>🌍 Города (aliases)</h2><div class="panel-body"><p style="font-size:13px;color:#6e6e73;margin-top:0;">Базовые города (Париж, Амстердам, Брюссель, Прага) встроены. Добавь свои ниже.</p><div id="msts-city-map-box" class="city-map-table"><?php if(!empty($s['city_map_custom'])): foreach($s['city_map_custom'] as $idx=>$row):?><div class="city-map-row"><label>Каноническое<input type="text" name="<?php echo $this->key;?>[city_map_custom][<?php echo $idx;?>][canonical]" value="<?php echo esc_attr($row['canonical']);?>"></label><label>Latin prefix<input type="text" name="<?php echo $this->key;?>[city_map_custom][<?php echo $idx;?>][prefixes]" value="<?php echo esc_attr(implode(',',$row['prefixes_latin']));?>"></label><label>Русские сокращения<input type="text" name="<?php echo $this->key;?>[city_map_custom][<?php echo $idx;?>][shorts]" value="<?php echo esc_attr(implode(',',$row['shorts']));?>"></label><label>Транслиты<input type="text" name="<?php echo $this->key;?>[city_map_custom][<?php echo $idx;?>][translits]" value="<?php echo esc_attr(implode(',',$row['translits']));?>"></label><button type="button" class="remove-city">×</button></div><?php endforeach; endif;?></div><button id="msts-add-city" data-opt="<?php echo $this->key;?>" class="add-city-btn" type="button">+ Добавить город</button></div></div><?php }

    private function panel_icons($s,$cities,$rubrics){?><div class="msts-panel"><h2>🎭 Иконки</h2><div class="panel-body"><div class="msts-icons-box"><?php if($cities && !is_wp_error($cities)){foreach($cities as $c){$slug_raw=$c->slug;$slug_clean=sanitize_title($slug_raw);$cur=$s['icons_override'][$slug_clean]??get_term_meta($c->term_id,'msts_icon',true);echo '<div style="margin-bottom:8px;display:flex;align-items:center;gap:10px;"><strong style="flex:0 0 140px;">'.esc_html($c->name).'</strong><input style="width:120px;padding:8px;border:1px solid rgba(0,0,0,0.12);border-radius:10px" type="text" name="'.$this->key.'[icons_override]['.esc_attr($slug_raw).']" value="'.esc_attr($cur).'" placeholder="😎"></div>';if(isset($rubrics[$slug_raw])){foreach($rubrics[$slug_raw] as $r){$rslug_raw=$r->slug;$rslug_clean=sanitize_title($rslug_raw);$rcur=$s['icons_override'][$rslug_clean]??get_term_meta($r->term_id,'msts_icon',true);echo '<div style="margin-left:30px;margin-bottom:6px;display:flex;align-items:center;gap:10px;"><span style="flex:0 0 130px;color:#6e6e73">↳ '.esc_html($r->name).'</span><input style="width:120px;padding:8px;border:1px solid rgba(0,0,0,0.12);border-radius:10px" type="text" name="'.$this->key.'[icons_override]['.esc_attr($rslug_raw).']" value="'.esc_attr($rcur).'" placeholder="😎"></div>';}}}}else{echo '<em style="font-size:13px;color:#86868b;">Нет терминов в таксономии.</em>';}?></div><label style="margin-top:12px;"><input type="checkbox" name="<?php echo $this->key;?>[enable_term_icons]" value="1" <?php checked($s['enable_term_icons'],1);?>> Читать term meta</label></div></div><?php }

    private function panel_export($s,$export){?><div class="msts-panel"><h2>💾 Экспорт / Импорт</h2><div class="panel-body"><label>Лейбл "Популярные" <input type="text" name="<?php echo $this->key;?>[popular_label]" value="<?php echo esc_attr($s['popular_label']);?>"></label><label>Лейбл "Города" <input type="text" name="<?php echo $this->key;?>[cities_label]" value="<?php echo esc_attr($s['cities_label']);?>"></label><hr><strong style="display:block;margin-bottom:8px;color:#1d1d1f;">Экспорт (JSON)</strong><div style="font-family:monospace;font-size:12px;white-space:pre;max-height:200px;overflow:auto;border:1px solid rgba(0,0,0,0.12);background:rgba(248,249,250,0.8);padding:14px;border-radius:12px;"><?php echo esc_html($export);?></div></div></div><?php }
    
	public function frontend(){
    $s=$this->get();
    
    $css=':root{
        --msts-bg:'.$s['bg_color'].';
        --msts-bg2:'.$s['bg_color2'].';
        --msts-btn-bg:'.$s['btn_bg'].';
        --msts-btn-text:'.$s['btn_text_color'].';
        --msts-text:'.$s['text_color'].';
        --msts-ph:'.$s['placeholder_color'].';
        --msts-h:'.$s['form_height'].'px;
        --msts-r:'.$s['outer_radius'].'px;
        --msts-ri:'.$s['inner_radius'].'px;
        --msts-maxw:'.$s['max_width'].'px;
        --msts-pct:'.$s['percent_width'].'%;
        --msts-padx:'.$s['padding_x'].'px;
        --msts-z:'.$s['z_index'].';
    }
    
    .msts-search-container{
     width:var(--msts-pct);
     max-width:var(--msts-maxw);
     margin:0 auto;
     '.($s['center_align']?'':'margin-left:0;').'
     padding:0 18px;
     position:relative;
    }
    
    .msts-search-wrapper form{
    display:flex;
    align-items:center;
    gap:8px;
    background:transparent;
    padding:10px;
    border-radius:32px;
    }

    .msts-search-input-wrap{
    position:relative;
    flex:1;
    border-radius:24px;
    overflow:visible;
    border:none;
    box-sizing:border-box;
    }
	
      .msts-search-input{
    width:100%;
    height:var(--msts-h);
    padding:0 var(--msts-padx);
    font-size:18px;
    border:none;
    border-radius:24px;
    background:rgba(255,255,255,0.3);
    backdrop-filter:blur(8px);
    color:var(--msts-text);
    outline:none;
    transition:. 15s;
    box-shadow:none;
    }

    .msts-search-input:focus{
    border-color:#555;
    }

    .msts-search-input::placeholder{
    color:var(--msts-ph);
    }

    .msts-search-btn{
    height:var(--msts-h);
    padding:0 32px;
    border:none;
    border-radius:24px;
    background:var(--msts-btn-bg);
    color:var(--msts-btn-text);
    font-weight:700;
    font-size:15px;
    cursor:pointer;
    display:'.($s['show_button']?'flex':'none').';
    align-items:center;
    justify-content:center;
    transition:all 0.3s ease;
    flex-shrink:0;
    box-shadow:0 4px 12px rgba(0,0,0,0.15);
    }

    .msts-search-btn:hover{
    filter:brightness(1.08);
    }
	    
    .msts-btn-icon{
    display:none;
    font-size:20px;
    }
    
    .msts-btn-text{
    display:inline;
    }

    .msts-clear-btn-fixed{
    height:var(--msts-h);
    width:var(--msts-h);
    border:none;
    border-radius:50%;
    background:#d8dadc;
    color:#333;
    font-size:20px;
    font-weight:600;
    cursor:pointer;
    display:none;
    align-items:center;
    justify-content:center;
    transition:.15s;
    flex-shrink:0;
    }

    .msts-clear-btn-fixed.msts-show{
    display:flex;
    }

    .msts-clear-btn-fixed:hover{
    background:#c2c4c6;
    transform:rotate(90deg) scale(1.1);
    }

    /* ГЛАВНАЯ СТРАНИЦА - LIQUID GLASS */
    .home .msts-search-wrapper form,
    body.home .msts-search-wrapper form{
    background:rgba(255,255,255,0.15) !important;
    backdrop-filter:blur(40px) !important;
    -webkit-backdrop-filter:blur(40px) !important;
    border:2px solid rgba(255,255,255,0.25) !important;
    border-radius:50px ! important;
    padding:10px !important;
    box-shadow:0 20px 60px -15px rgba(0,0,0,.5) !important;
    transition:all 0.3s ease !important;
    }

    .home .msts-search-wrapper form:hover,
    body.home .msts-search-wrapper form:hover{
    background:rgba(255,255,255,0.25) !important;
    border-color:rgba(255,255,255,0.4) !important;
    }

    .home . msts-search-input,
    body.home .msts-search-input{
    background:#fff !important;
    border:none !important;
    color:#333 !important;
    box-sizing:border-box !important;
    box-shadow:none !important;
    border-radius:24px !important;
    }
	
    .home .msts-search-input-wrap,
    body.home .msts-search-input-wrap{
    border:none !important;
    box-sizing:border-box !important;
    overflow:visible !important;
    }

    .home .msts-search-input::placeholder,
    body.home .msts-search-input::placeholder{
    color:#999 !important;
    }

    .home .msts-search-btn,
    body.home .msts-search-btn{
    padding:0 32px !important;
    border-radius:24px !important;
    background:linear-gradient(135deg,#667eea 0%,#764ba2 100%) !important;
    border:none !important;
    color:#fff !important;
    font-weight:700 !important;
    box-shadow:0 4px 16px rgba(102,126,234,0.4) !important;
    transition:all 0.3s ease !important;
    }
    
    .home .msts-search-btn:hover,
    body.home .msts-search-btn:hover{
    transform:translateY(-2px) scale(1.05) !important;
    box-shadow:0 8px 24px rgba(102,126,234,0.6) !important;
    filter:none !important;
    }
    
    .home .msts-btn-icon,
    body.home .msts-btn-icon{
    display:inline ! important;
    margin-right:8px !important;
    }
    
    .home .msts-btn-text,
    body. home .msts-btn-text{
    display:inline !important;
    }

    .home .msts-clear-btn-fixed,
    body.home .msts-clear-btn-fixed{
    background:rgba(255,255,255,0.25) !important;
    backdrop-filter:blur(20px) !important;
    -webkit-backdrop-filter:blur(20px) !important;
    border:2px solid rgba(255,255,255,0.3) !important;
    color:#fff !important;
    }

    .home .msts-clear-btn-fixed:hover,
        body.home .msts-clear-btn-fixed:hover{
    background:rgba(255,255,255,0.35) !important;
    transform:rotate(90deg) scale(1.1) !important;
    }

    /* ХЕДЕР - КОМПАКТНАЯ КНОПКА */
    .msts-header-trigger{
    display:inline-flex;
    align-items:center;
    gap:8px;
    padding:10px 20px;
    background:rgba(255,255,255,0.15);
    backdrop-filter:blur(20px);
    -webkit-backdrop-filter:blur(20px);
    border:2px solid rgba(255,255,255,0.25);
    border-radius:50px;
    cursor:pointer;
    transition:all 0.3s ease;
    }

    .msts-header-trigger:hover{
    background:rgba(255,255,255,0.25);
    border-color:rgba(255,255,255,0.4);
    transform:translateY(-2px);
    }

    .msts-header-icon{
    font-size:18px;
    }

    .msts-header-text{
    font-size:14px;
    font-weight:600;
    color:#fff;
    }

    /* МОДАЛЬНОЕ ОКНО ПОИСКА */
    .msts-modal-overlay{
    position:fixed;
    top:0;
    left:0;
    width:100vw;
    height:100vh;
    background:rgba(0,0,0,0);
    backdrop-filter:blur(0px);
    -webkit-backdrop-filter:blur(0px);
    z-index:999999;
    opacity:0;
    pointer-events:none;
    transition:all 0.4s cubic-bezier(0.4,0,0.2,1);
    overflow-y:auto;
    }

    .msts-modal-overlay.msts-modal-active{
    background:rgba(0,0,0,0.85);
    backdrop-filter:blur(20px);
    -webkit-backdrop-filter:blur(20px);
    opacity:1;
    pointer-events:all;
    }

    .msts-modal-content{
    max-width:900px;
    width:90%;
    padding:20px;
    position:absolute;
    top:80px;
    left:50%;
    transform:translateX(-50%) scale(0.9) translateY(-20px);
    transition:all 0.4s cubic-bezier(0.4,0,0.2,1);
    }

    .msts-modal-overlay.msts-modal-active .msts-modal-content{
    transform:translateX(-50%) scale(1) translateY(0);
    }

    .msts-modal-close{
    position:fixed;
    top:30px;
    right:30px;
    width:56px;
    height:56px;
    background:rgba(255,255,255,0.25);
    backdrop-filter:blur(20px);
    -webkit-backdrop-filter:blur(20px);
    border:2px solid rgba(255,255,255,0.35);
    border-radius:50%;
    color:#fff;
    font-size:24px;
    line-height:1;
    font-weight:300;
    cursor:pointer;
    transition:all 0.3s ease;
    display:flex;
    align-items:center;
    justify-content:center;
    padding:0;
    z-index:1000000;
    }

    .msts-modal-close:hover{
    background:rgba(255,255,255,0.4);
    border-color:rgba(255,255,255,0.6);
    transform:rotate(90deg) scale(1.15);
    }

    /* МОДАЛЬНОЕ ОКНО - ФОРМА ПОИСКА */
    .msts-modal-overlay .msts-search-container{
    margin:0;
    }

    .msts-modal-overlay .msts-search-wrapper form{
    background:rgba(255,255,255,0.15) !important;
    backdrop-filter:blur(40px) !important;
    -webkit-backdrop-filter:blur(40px) !important;
    border:2px solid rgba(255,255,255,0.25) !important;
    }

    .msts-modal-overlay .msts-suggestions{
    position:static !important;
    margin-top:20px !important;
    background:rgba(255,255,255,0.95) !important;
    backdrop-filter:blur(40px) !important;
    -webkit-backdrop-filter:blur(40px) !important;
    max-height:500px !important;
    overflow-y:auto !important;
    }

    .msts-modal-overlay .msts-search-btn,
    .msts-modal-overlay .msts-clear-btn-fixed{
    background:rgba(255,255,255,0.25) !important;
    backdrop-filter:blur(20px) !important;
    -webkit-backdrop-filter:blur(20px) !important;
    border:2px solid rgba(255,255,255,0.3) !important;
    color:#fff !important;
    }
   
    /* HEADER WIDGET СКРУГЛЕНИЕ */
    .header-search .msts-search-wrapper form,
    .et_b_header-search .msts-search-wrapper form,
    .header-wrapper .msts-search-wrapper form,
    .elementor-search-form .msts-search-wrapper form,
    .widget_search .msts-search-wrapper form{
        border-radius:50px !important;
    }
    
    .header-search .msts-search-input,
    .et_b_header-search .msts-search-input,
    .header-wrapper .msts-search-input,
    .elementor-search-form .msts-search-input,
    .widget_search .msts-search-input{
        border-radius:50px !important;
        padding-left:24px !important;
        padding-right:80px !important;
    }
    
    .header-search .msts-search-btn,
    .et_b_header-search .msts-search-btn,
    .header-wrapper .msts-search-btn,
    .elementor-search-form .msts-search-btn,
    .widget_search .msts-search-btn{
        border-radius:50px !important;
    }
	
    /* ХЕДЕР - ВСПЛЫВАЮЩЕЕ ОКНО */
    .header-search .msts-suggestions,
    .et_b_header-search .msts-suggestions,
    .header-wrapper .msts-suggestions{
    position:fixed !important;
    top:0 !important;
    left:0 !important;
    width:100vw !important;
    height:100vh !important;
    background:rgba(0,0,0,0.9) !important;
    backdrop-filter:blur(20px) !important;
    -webkit-backdrop-filter:blur(20px) !important;
    padding:60px 20px !important;
    z-index:999999 !important;
    }
    
    /* DROPDOWN */
    body > .msts-suggestions{
        position:absolute;
        background:#fff;
        border-radius:28px;
        box-shadow:0 16px 46px -12px rgba(0,0,0,.28);
        '.($s['dropdown_border']?'border:1px solid #d5d7da;':'border:none;').'
        display:none;
        font-size:14px;
        z-index:var(--msts-z);
        max-height:calc(100vh - 120px);
        overflow-y:auto;
    }
    
    .msts-head{
        display:flex;
        align-items:center;
        justify-content:space-between;
        padding:14px 26px 10px;
        border-bottom:1px solid #eceeef;
        background:#fff;
        position:sticky;
        top:0;
    }
    
    .msts-head-title{
        font-weight:600;
        font-size:14px;
    }
    
    .msts-close-btn{
        background:#f1f2f3;
        border:1px solid #dadcde;
        padding:6px 12px;
        border-radius:14px;
        cursor:pointer;
        font-size:16px;
        line-height:1;
    }
    
    '.(!$s['show_close_btn']?'.msts-close-btn{display:none;}':'').'
    
    .msts-section-title{
        font-weight:600;
        padding:10px 26px 4px;
        font-size:12px;
        color:#444;
    }
    
    '.(!$s['show_section_titles']?'.msts-section-title{display:none;}':'').'
    
    .msts-item{
        display:flex;
        gap:10px;
        padding:8px 24px;
        align-items:center;
        background:#fff;
        border:0;
        width:100%;
        cursor:pointer;
        text-align:left;
        transition:.15s;
    }
    
    .msts-item:hover{
        background:#f4f6f8;
    }
    
    .msts-icon{
        width:28px;
        height:28px;
        border-radius:14px;
        background:transparent;
        display:flex;
        align-items:center;
        justify-content:center;
        font-size:16px;
        flex-shrink:0;
        '.($s['show_icons']?'':'display:none;').'
    }
    
    .msts-title{
        flex:1;
        font-size:14px;
        font-weight:500;
        line-height:1.25;
        color:#1d2125;
    }
    
    .msts-meta{
        font-size:11px;
        opacity:.65;
        margin-top:4px;
    }
    
    .msts-inline-rubrics{
        padding:6px 26px 10px 70px;
        background:#fff;
    }
    
    .msts-inline-rubric{
        display:flex;
        align-items:center;
        gap:12px;
        padding:6px 10px;
        border-radius:14px;
        cursor:pointer;
        transition:.15s;
    }
    
    .msts-inline-rubric:hover{
        background:#f4f6f8;
    }
    
    .msts-inline-dot{
        width:18px;
        height:18px;
        display:flex;
        align-items:center;
        justify-content:center;
        font-size:15px;
    }
    
    .msts-inline-text{
        font-weight:500;
    }
    
    .msts-divider{
        height:1px;
        background:#edeff1;
        margin:6px 0;
    }
    
    .msts-tabs{
        display:flex;
        margin:10px 26px 12px;
        background:#f1f2f3;
        border:1px solid #e1e2e5;
        border-radius:18px;
        overflow:hidden;
    }
    
    .msts-tab{
        flex:1;
        border:0;
        background:transparent;
        padding:12px 14px;
        font-size:13px;
        font-weight:600;
        cursor:pointer;
        color:#444;
        text-align:center;
    }
    
    .msts-tab.msts-active{
        background:#e2e4e5;
    }
    
    .msts-thumb{
        width:56px;
        height:56px;
        object-fit:cover;
        border-radius:16px;
        flex-shrink:0;
        box-shadow:0 4px 10px -3px rgba(0,0,0,.25);
    }
    
    .msts-empty{
        padding:14px 26px;
        font-size:13px;
        color:#666;
    }
    
    .msts-price{
        font-size:13px;
        font-weight:600;
        color:#173b29;
        white-space:nowrap;
    }
    
    .msts-bottom-btn-wrap{
        padding:16px 26px;
        background:#fff;
        position:sticky;
        bottom:0;
        border-top:1px solid #eceeef;
    }
    
    .msts-bottom-btn{
    display:block;
    width:100%;
    text-align:center;
    background:rgba(180,180,180,0.85) !important;
    backdrop-filter:blur(20px) !important;
    -webkit-backdrop-filter:blur(20px) !important;
    border:2px solid rgba(200,200,200,0.6) !important;
    color:#333 !important;
    font-weight:700 !important;
    font-size:14px;
    padding:14px 18px;
    border-radius:20px;
    text-decoration:none;
    transition:all 0.3s ease !important;
}

.msts-bottom-btn:hover{
    background:rgba(200,200,200,0.95) !important;
    transform:translateY(-2px) !important;
}
    
    .msts-hl{
        background:#ffe28f;
        color:#202020;
        padding:2px 4px;
        border-radius:6px;
        display:inline-block;
    }
    
    @media(max-width:760px){
        .msts-search-container{
            padding:0 12px;
        }
        .msts-search-wrapper form{
            padding:4px 8px;
        }
        .msts-inline-rubrics{
            padding-left:60px;
        }
        .msts-item{
            padding:8px 18px;
        }
        body > .msts-suggestions{
            max-height:calc(100vh - 100px);
        }
    }';
    
    if(!empty($s['custom_css'])) $css.=$s['custom_css'];
    
    wp_register_style('msts-inline','');
    wp_enqueue_style('msts-inline');
    wp_add_inline_style('msts-inline',$css);
    
    wp_register_script('msts-inline-js','data:text/javascript;base64,',[],MSTS_VERSION,true);
    wp_enqueue_script('msts-inline-js');
    
    if($s['ajax_suggestions'] && !$s['use_external']){
        $cfg=['ajaxUrl'=>admin_url('admin-ajax.php'),'nonce'=>wp_create_nonce('msts_search_nonce'),'labels'=>['popular'=>$s['popular_label'],'cities'=>$s['cities_label'],'rubrics'=>$s['rubrics_label'],'products'=>$s['product_label'],'info'=>$s['info_label']],'grouping'=>(int)$s['enable_grouping'],'showCounts'=>(int)$s['show_counts'],'rememberQuery'=>(int)$s['remember_last_query'],'enableTermIcons'=>(int)$s['enable_term_icons'],'showPopular'=>(int)$s['show_popular'],'showSectionTitles'=>(int)$s['show_section_titles'],'shopUrl'=>$s['shop_button_url'],'showPopularRubrics'=>(int)$s['show_popular_rubrics'],'autoFocus'=>1];
        $inline='window.MSTS_CFG_DIAG='.wp_json_encode($cfg).';';
        if(file_exists(MSTS_PATH.'inline-js/front-core.js')){
            $inline.=file_get_contents(MSTS_PATH.'inline-js/front-core.js');
        }
        wp_add_inline_script('msts-inline-js',$inline);
    }
}
}