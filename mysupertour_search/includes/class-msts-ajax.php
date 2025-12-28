<?php
/**
 * AJAX handler for MySuperTour Search
 * Version: 2.6.5-FINAL-PRICE-RANGE-AUTO
 */

if(!defined('ABSPATH')) exit;

if(!function_exists('str_starts_with')){
    function str_starts_with($haystack,$needle){return $needle==='' || strpos($haystack,$needle)===0;}
}

class MSTS_Ajax {
    private static $inst;
    private $s;
    private $popular_key='msts_popular_v265_final';

    private $BASE_CITY_MAP=[
        ['canonical'=>'париж','prefixes_latin'=>['par','pari','paris','paree','parij','parizh','peris','perizh','frpar','frparis','fr-par','fr-paris','fr-pari'],'shorts'=>['пар','пари','парис','париж','пориж','периж','gfh','gfhb'],'translits'=>['paris','parizh','paree','parij','peris','perizh']],
        ['canonical'=>'амстердам','prefixes_latin'=>['ams','amst','amste','amster','amstr','amstd','amsterdam','adam'],'shorts'=>['амст','амстер','амстерд','амстердам','амс','амсдам'],'translits'=>['amsterdam','amster','amstr','amstd','adam']],
        ['canonical'=>'брюссель','prefixes_latin'=>['bru','brus','bruss','brusse','brussel','brussels','brux','bruxel','bruxell','bruxelles'],'shorts'=>['брю','брюс','брюсс','брюссе','брюссель'],'translits'=>['brussels','brussel','bruxelles','brux']],
        ['canonical'=>'прага','prefixes_latin'=>['pra','prag','praga','prague','prah','praha','prg'],'shorts'=>['праг','прага','пра'],'translits'=>['prague','praga','praha','prag']],
    ];

    private $forceParisQuery=false;
    private $info_allowed_category='blog';
    private $format_terms=['groupovaya','individualnaya'];

    public static function instance(){return self::$inst?:self::$inst=new self();}
    private function __construct(){
        $this->s=MSTS_Settings::instance()->get();
        add_action('wp_ajax_msts_search_suggest',[$this,'handle']);
        add_action('wp_ajax_nopriv_msts_search_suggest',[$this,'handle']);
    }

    public function handle(){
        if(!isset($_GET['nonce']) || !wp_verify_nonce($_GET['nonce'],'msts_search_nonce')){
            wp_send_json(['error'=>'bad_nonce']);
        }
        if(!$this->s['ajax_suggestions'] || $this->s['use_external']){
            wp_send_json(['error'=>'disabled']);
        }

        $raw=substr((string)($_GET['q']??''),0,160);
        $city_slug=sanitize_text_field($_GET['city']??'');
        $q=mb_strtolower(trim($raw));

        if($this->s['layout_fix'] && $q!==''){
            if(!preg_match('/^[a-z]{1,5}$/u',$raw)){
                $fixed=$this->layout_to_ru($q);
                if($fixed && $fixed!==$q) $q=$fixed;
            }
        }

        $popular=$this->popular($q);

        if($q==='' && !$city_slug){
            $popularInline=[];
            if($this->s['show_popular_rubrics'] && $popular){
                foreach($popular as $pop){
                    $term=get_term_by('slug',$pop['term'],$this->s['city_taxonomy']);
                    if(!$term || is_wp_error($term)) continue;
                    $rubrics=$this->rubrics_for_city($term,true);
                    $popularInline[]=['city'=>$this->fmt_city($term,false),'rubrics'=>$rubrics];
                }
            }
            $formats=$this->get_formats();
            wp_send_json($this->payload([],[],[],[],$raw,$popular,[],$popularInline,$formats));
        }

        $expanded=$this->expand_multi($q,$popular);
        $inline_rubrics=[]; $cities=[]; $rubrics=[]; $formats=[];
        $formatMatch=$this->match_format_query($q);
        if($formatMatch){$formats=$this->get_formats_filtered($formatMatch);}

        if($city_slug){
            $term=get_term_by('slug',$city_slug,$this->s['city_taxonomy']);
            if($term){$cities[]=$this->fmt_city($term,false);$inline_rubrics=$this->rubrics_for_city($term,true);}
        } else {
            $cities=$this->search_cities($expanded,$q);
            if(!$cities && $this->is_letter_query($q)){
                foreach($this->get_full_city_map() as $data){
                    if($this->matches_city($q,$data)){
                        if(!in_array($data['canonical'],$expanded,true)){$expanded[]=$data['canonical'];$cities=$this->search_cities($expanded,$q);}
                        break;
                    }
                }
            }
            if($this->forceParisQuery && in_array('париж',$expanded,true)){$cities=$this->filter_only_paris($cities);}
            if($this->s['show_rubrics_inline'] && count($cities)===1){
                $term=get_term_by('slug',$cities[0]['slug'],$this->s['city_taxonomy']);
                if($term){$inline_rubrics=$this->rubrics_for_city($term,true);}
            } else {$rubrics=$this->aggregate_rubrics($cities);}
        }

        $products=$this->find_products($expanded,$city_slug?[$city_slug]:wp_list_pluck($cities,'slug'));
        $info=$this->find_info($expanded,$q);

        wp_send_json($this->payload($cities,$rubrics,$products,$info,$raw,$popular,$expanded,$inline_rubrics,$formats));
    }

    private function get_formats(){
        $tax=$this->s['city_taxonomy'];$res=[];
        foreach($this->format_terms as $slug){
            $slugClean=sanitize_title($slug);if(!$slugClean) continue;
            $t=get_term_by('slug',$slugClean,$tax);if(!$t || is_wp_error($t)) continue;
            $icon=$this->s['icons_override'][$t->slug]??($this->s['enable_term_icons']?get_term_meta($t->term_id,'msts_icon',true):'');
            if($icon==='') $icon='🟢';
            $res[]=['slug'=>$t->slug,'name'=>$t->name,'count'=>$t->count,'url'=>get_term_link($t),'icon'=>$icon];
        }
        return $res;
    }

    private function match_format_query($q){if(preg_match('/групп/iu',$q)) return 'groupovaya';if(preg_match('/индивид/iu',$q)) return 'individualnaya';return null;}
    private function get_formats_filtered($slug){$tax=$this->s['city_taxonomy'];$t=get_term_by('slug',$slug,$tax);if(!$t || is_wp_error($t)) return [];$icon=$this->s['icons_override'][$t->slug]??($this->s['enable_term_icons']?get_term_meta($t->term_id,'msts_icon',true):'');if($icon==='') $icon='🟢';return [['slug'=>$t->slug,'name'=>$t->name,'count'=>$t->count,'url'=>get_term_link($t),'icon'=>$icon]];}
    private function is_par_query($q){return preg_match('/^(par|pari|paris)/iu',$q) || preg_match('/^пар/iu',$q);}
    private function allowed_paris_slugs(){$list=['par','pari','paris','parij','parizh','paree','peris','perizh','gfh','gfhb'];$lines=preg_split('/\r?\n/',(string)$this->s['synonyms']);foreach($lines as $l){$l=trim($l);if(!$l || strpos($l,'=')===false) continue;list($left,$right)=array_map('trim',explode('=',$l,2));if(mb_strtolower($right)==='париж'){$leftSlug=sanitize_title($left);if($leftSlug && !in_array($leftSlug,$list,true)) $list[]=$leftSlug;}}return array_unique($list);}
    private function filter_only_paris($cities){if(!$cities) return [];$allowed=$this->allowed_paris_slugs();$result=[];foreach($cities as $c){$nameNorm=mb_strtolower($c['name']);$slugNorm=mb_strtolower($c['slug']);if($nameNorm==='париж' || in_array($slugNorm,$allowed,true)){if($nameNorm==='париж'){ $result=[$c]; break; }$result[]=$c;}}if(!$result){$term=get_term_by('name','Париж',$this->s['city_taxonomy']);if($term){ $result[]=$this->fmt_city($term,false); }}if(count($result)>1){foreach($result as $rc){if(mb_strtolower($rc['name'])==='париж'){ return [$rc]; }}}return $result;}
    private function norm($s){return mb_strtolower(trim($s));}
    private function is_numeric_like($q){return (bool)preg_match('/^[0-9\\s\\.,\\-]+$/u',$q);}
    private function is_letter_query($q){return (bool)preg_match('/[a-zA-Zа-яА-ЯёЁ]/u',$q);}
    private function layout_to_ru($s){$map=['q'=>'й','w'=>'ц','e'=>'у','r'=>'к','t'=>'е','y'=>'н','u'=>'г','i'=>'ш','o'=>'щ','p'=>'з','['=>'х',']'=>'ъ','a'=>'ф','s'=>'ы','d'=>'в','f'=>'а','g'=>'п','h'=>'р','j'=>'о','k'=>'л','l'=>'д',';'=>'ж',"'"=>'э','z'=>'я','x'=>'ч','c'=>'с','v'=>'м','b'=>'и','n'=>'т','m'=>'ь',','=>'б','.'=>'ю','`'=>'ё'];$out='';$changed=false;foreach(preg_split('//u',$s,-1,PREG_SPLIT_NO_EMPTY) as $ch){$low=mb_strtolower($ch);if(isset($map[$low])){$out.=$map[$low];$changed=true;} else {$out.=$ch;}}return $changed?$this->norm($out):$s;}
    private function get_full_city_map(){$full=$this->BASE_CITY_MAP;if(is_array($this->s['city_map_custom']) && $this->s['city_map_custom']){foreach($this->s['city_map_custom'] as $row){if(isset($row['canonical'])){$full[]=['canonical'=>$this->norm($row['canonical']),'prefixes_latin'=>$row['prefixes_latin']??[],'shorts'=>$row['shorts']??[],'translits'=>$row['translits']??[]];}}}return $full;}
    private function matches_prefix($q,$pref){foreach($pref as $p){if(str_starts_with($q,$p) || str_starts_with($p,$q)) return true;if(strlen($q)>=3 && str_starts_with($p,$q)) return true;}return false;}
    private function matches_city($q,$data){if(!$this->is_letter_query($q) || $this->is_numeric_like($q)) return false;$len=mb_strlen($q);if($len<2 || $len>40) return false;if($this->matches_prefix($q,$data['prefixes_latin'])) return true;if(in_array($q,$data['shorts'],true)) return true;if(in_array($q,$data['translits'],true)) return true;return false;}
    private function expand_multi($q,$popular){$out=[];if($q!=='') $out[]=$q;$this->forceParisQuery=$this->is_par_query($q);if($this->forceParisQuery){if(!in_array('париж',$out,true)) $out[]='париж';$aliasTarget=$this->norm($this->s['city_alias_target']);if($aliasTarget && !in_array($aliasTarget,$out,true)) $out[]=$aliasTarget;foreach(['par','pari','paris'] as $lat){ if(!in_array($lat,$out,true)) $out[]=$lat; }}if($this->is_numeric_like($q)) return $out;foreach($this->parse_syn($q) as $syn){if(!in_array($syn,$out,true)) $out[]=$syn;}foreach($this->get_full_city_map() as $data){if($this->matches_city($q,$data) && !in_array($data['canonical'],$out,true)){$out[]=$data['canonical'];}foreach($data['prefixes_latin'] as $pref){if(str_starts_with($pref,$q) || str_starts_with($q,$pref)){if(!in_array($data['canonical'],$out,true)) $out[]=$data['canonical'];break;}}}if($this->s['match_mode']==='expanded'){if($this->s['latin_translit']){foreach($this->translits($q) as $t){if(!in_array($t,$out,true)) $out[]=$t;}}if($this->s['enable_fuzzy']){$max=$this->s['fuzzy_max_distance'];foreach($popular as $p){$n=$this->norm($p['name']);if($this->lev($q,$n)<= $max && !in_array($n,$out,true)) $out[]=$n;}}}return array_values(array_unique($out));}
    private function parse_syn($q){$lines=preg_split('/\r?\n/',(string)$this->s['synonyms']);$res=[];$low=$this->norm($q);foreach($lines as $l){$l=trim($l);if(!$l || strpos($l,'=')===false) continue;list($a,$b)=array_map('trim',explode('=',$l,2));if($this->norm($a)===$low) $res[]=$this->norm($b);}return $res;}
    private function translits($q){$map=['а'=>'a','б'=>'b','в'=>'v','г'=>'g','д'=>'d','е'=>'e','ё'=>'e','ж'=>'zh','з'=>'z','и'=>'i','й'=>'j','к'=>'k','л'=>'l','м'=>'m','н'=>'n','о'=>'o','п'=>'p','р'=>'r','с'=>'s','т'=>'t','у'=>'u','ф'=>'f','х'=>'kh','ц'=>'c','ч'=>'ch','ш'=>'sh','щ'=>'sh','ъ'=>'','ы'=>'y','ь'=>'','э'=>'e','ю'=>'yu','я'=>'ya'];$latin='';foreach(preg_split('//u',$q,-1,PREG_SPLIT_NO_EMPTY) as $ch){ $low=mb_strtolower($ch); $latin.=isset($map[$low])?$map[$low]:$ch; }$rev=['paris'=>'париж','parizh'=>'париж','amsterdam'=>'амстердам','amster'=>'амстердам','amstr'=>'амстердам','brussels'=>'брюссель','brussel'=>'брюссель','bruxelles'=>'брюссель','prague'=>'прага','praha'=>'прага','praga'=>'прага'];$out=[];if($latin!==$q) $out[]=$latin;if(isset($rev[$q])) $out[]=$rev[$q];return $out;}
    private function lev($a,$b){$la=mb_strlen($a);$lb=mb_strlen($b);if($la===0)return $lb;if($lb===0)return $la;$prev=range(0,$lb);for($i=1;$i<=$la;$i++){$cur=[$i];$ca=mb_substr($a,$i-1,1);for($j=1;$j<=$lb;$j++){$cb=mb_substr($b,$j-1,1);$cost=$ca===$cb?0:1;$cur[$j]=min($prev[$j]+1,$cur[$j-1]+1,$prev[$j-1]+$cost);}$prev=$cur;}return $prev[$lb];}
    private function popular($q){if(!$this->s['show_popular']) return [];if(mb_strlen($q) >= $this->s['disable_popular_min_len']) return [];$tax=$this->s['city_taxonomy'];if(!taxonomy_exists($tax)) return [];$cache=get_transient($this->popular_key);if(is_array($cache)) return $cache;$terms=get_terms(['taxonomy'=>$tax,'parent'=>0,'hide_empty'=>true,'number'=>0]);if(is_wp_error($terms)) return [];$exclude=array_map('trim',explode(',',$this->s['exclude_city_slugs']));$list=[];foreach($terms as $t){if(in_array($t->slug,$exclude,true)) continue;$icon=$this->s['icons_override'][$t->slug]??($this->s['enable_term_icons']?get_term_meta($t->term_id,'msts_icon',true):'');if($icon==='') $icon='😎';$list[]=['term'=>$t->slug,'name'=>$t->name,'count'=>$t->count,'icon'=>$icon,'url'=>get_term_link($t)];}usort($list,function($a,$b){return $b['count']<=>$a['count'];});$list=array_slice($list,0,$this->s['popular_limit']);set_transient($this->popular_key,$list,$this->s['popular_cache_ttl']);return $list;}
    private function search_cities($expanded,$raw){$tax=$this->s['city_taxonomy'];$terms=get_terms(['taxonomy'=>$tax,'hide_empty'=>false,'number'=>0]);if(is_wp_error($terms)) return [];$exclude=array_map('trim',explode(',',$this->s['exclude_city_slugs']));$expandedNorm=array_map([$this,'norm'],$expanded);$out=[];foreach($terms as $t){if(in_array($t->slug,$exclude,true)) continue;$nameNorm=$this->norm($t->name);$slugNorm=$this->norm($t->slug);$bestPos=9999;$hit=false;foreach($expandedNorm as $q){if($q==='') continue;$pos=mb_strpos($nameNorm,$q);$slugStart=str_starts_with($slugNorm,$q);if($pos!==false || $slugStart){if($this->s['match_mode']==='strict'){if($pos===0 || $slugStart){ $hit=true;$bestPos=0;break; }} else {$hit=true;if($slugStart){ $bestPos=0;break; }elseif($pos < $bestPos) $bestPos=$pos;}}}if($hit){$out[]=['term'=>$t,'pos'=>$bestPos];}}usort($out,function($a,$b){if($a['pos']==$b['pos']) return strcmp($a['term']->name,$b['term']->name);return $a['pos']<$b['pos']?-1:1;});if($this->s['match_mode']==='strict' && $out){$firstPos=$out[0]['pos'];$out=array_values(array_filter($out,function($x) use($firstPos){return $x['pos']===$firstPos;}));}$final=[];foreach($out as $o){$final[]=$this->fmt_city($o['term'],false);if(count($final)>=$this->s['cities_limit']) break;}return $final;}
    private function fmt_city($t,$forced){$icon=$this->s['icons_override'][$t->slug]??($this->s['enable_term_icons']?get_term_meta($t->term_id,'msts_icon',true):'');if($icon==='') $icon='😎';return ['id'=>$t->term_id,'name'=>$t->name,'slug'=>$t->slug,'count'=>(int)$t->count,'icon'=>$icon,'forced'=>$forced,'url'=>get_term_link($t)];}
    private function rubrics_for_city($city,$inline=false){$tax=$this->s['city_taxonomy'];$kids=get_terms(['taxonomy'=>$tax,'parent'=>$city->term_id,'hide_empty'=>false,'number'=>0]);if(is_wp_error($kids)) return [];$res=[];foreach($kids as $k){$icon=$this->s['icons_override'][$k->slug]??($this->s['enable_term_icons']?get_term_meta($k->term_id,'msts_icon',true):'');if($icon==='') $icon='😎';$cnt=$this->count_pair($city->slug,$k->slug);$res[]=['id'=>$k->term_id,'name'=>$k->name,'slug'=>$k->slug,'count'=>$cnt,'icon'=>$icon,'url'=>get_term_link($k),'inline'=>$inline?1:0];}usort($res,function($a,$b){return $b['count']<=>$a['count'];});return array_slice($res,0,$this->s['rubrics_limit']);}
    private function aggregate_rubrics($cities){if(!$cities || $this->s['show_rubrics_inline']) return [];$agg=[];foreach($cities as $c){$term=get_term_by('slug',$c['slug'],$this->s['city_taxonomy']);if(!$term) continue;foreach($this->rubrics_for_city($term,false) as $r){if(isset($agg[$r['slug']])) $agg[$r['slug']]['count']+=$r['count']; else $agg[$r['slug']]=$r;}}$list=array_values($agg);usort($list,function($a,$b){return $b['count']<=>$a['count'];});return array_slice($list,0,$this->s['rubrics_limit']);}
    private function count_pair($city,$rub){$tax=$this->s['city_taxonomy'];$ids=get_posts(['post_type'=>'product','post_status'=>'publish','fields'=>'ids','posts_per_page'=>-1,'tax_query'=>['relation'=>'AND',['taxonomy'=>$tax,'field'=>'slug','terms'=>[$city]],['taxonomy'=>$tax,'field'=>'slug','terms'=>[$rub]]]]);return count($ids);}

    private function find_products($expanded, $citySlugs){
        $limit = $this->s['products_limit'];
        if($limit < 1) return [];
        
        global $wpdb;
        $found = [];
        
        // Получаем ID товаров из категории latepoint для исключения
        $latepoint_ids = get_posts([
            'post_type' => 'product',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'fields' => 'ids',
            'tax_query' => [[
                'taxonomy' => 'product_cat',
                'field' => 'slug',
                'terms' => ['latepoint'],
            ]]
        ]);
        
        foreach($expanded as $q){
            if($q === '' || $this->is_numeric_like($q)) continue;
            if(mb_strlen($q) < 2) continue;
            
            // Поиск по LIKE %слово% в заголовке
            $like = '%' . $wpdb->esc_like($q) . '%';
            
            $sql = $wpdb->prepare(
                "SELECT ID FROM {$wpdb->posts} 
                WHERE post_type = 'product' 
                AND post_status = 'publish' 
                AND post_title LIKE %s
                ORDER BY post_title ASC
                LIMIT %d",
                $like,
                $limit * 2
            );
            
            $ids = $wpdb->get_col($sql);
            
            foreach($ids as $id){
                // Исключаем latepoint
                if(in_array($id, $latepoint_ids)) continue;
                // Исключаем товары без цены
                $price = get_post_meta($id, '_price', true);
                if($price === '' || $price === null) continue;
                
                if(! isset($found[$id])){
                    $found[$id] = $id;
                }
                if(count($found) >= $limit) break 2;
            }
        }
        
        $out = [];
        foreach($found as $id){
            $p = get_post($id);
            if(! $p) continue;
            
            // Формируем цену
            $price = '';
            $children = get_children([
                'post_parent' => $id,
                'post_type' => 'product',
                'post_status' => 'publish',
                'fields' => 'ids'
            ]);
            
            $vals = [];
            foreach($children as $cid){
                $cp = get_post_meta($cid, '_price', true);
                if($cp !== '' && $cp !== null) $vals[] = (float)$cp;
            }
            
            if($vals){
                $mn = min($vals);
                $mx = max($vals);
                $price = $mn == $mx ? msts_format_price($mn) : msts_format_price($mn) . ' – ' . msts_format_price($mx);
            } else {
                $pr = get_post_meta($id, '_price', true);
                if($pr !== '' && $pr !== null) $price = msts_format_price($pr);
            }
            
            $thumb = get_the_post_thumbnail_url($id, 'thumbnail');
            $out[] = [
                'id' => $id,
                'title' => mb_strimwidth(get_the_title($id), 0, 90, '…'),
                'url' => get_permalink($id),
                'price' => $price,
                'thumb' => $thumb ?: '',
                'fallback_icon' => '😎'
            ];
            
            if(count($out) >= $limit) break;
        }
        
        return $out;
    }

    private function find_info($expanded,$query){$query=trim($query);if($query==='') return [];$limit=$this->s['info_limit'];if($limit<1) return [];$types=array_filter(array_map('trim',explode(',',$this->s['info_post_types'])));if(!$types)$types=['post'];$tax_filter=[];if($this->info_allowed_category){$cat_obj=get_category_by_slug($this->info_allowed_category);if($cat_obj){$tax_filter=[['taxonomy'=>'category','field'=>'term_id','terms'=>[$cat_obj->term_id],'include_children'=>true]];}}$found=[];foreach($expanded as $q){$q=trim($q);if($q===''||$this->is_numeric_like($q)) continue;$args=['post_type'=>$types,'post_status'=>'publish','posts_per_page'=>-1,'fields'=>'ids'];if($tax_filter) $args['tax_query']=$tax_filter;$all_ids=get_posts($args);foreach($all_ids as $id){$title=mb_strtolower(get_the_title($id));if(mb_stripos($title,$q)===false) continue;if(isset($found[$id])) continue;$found[$id]=$id;if(count($found)>=$limit) break 2;}}$out=[];foreach($found as $id){$out[]=['id'=>$id,'title'=>mb_strimwidth(get_the_title($id),0,90,'…'),'url'=>get_permalink($id),'type'=>get_post_type($id),'desc'=>'Полезная информация если вы собираетесь в путешествие','fallback_icon'=>'😎'];if(count($out)>=$limit) break;}return $out;}
    private function payload($cities,$rubrics,$products,$info,$query,$popular,$expanded,$inline=[],$formats=[]){return ['query'=>$query,'expanded'=>$expanded,'popular'=>$popular,'cities'=>$cities,'rubrics'=>$rubrics,'products'=>$products,'info'=>$info,'inline_rubrics'=>$inline,'formats'=>$formats,'counts'=>['cities'=>count($cities),'rubrics'=>count($rubrics),'products'=>count($products),'info'=>count($info)]];}
}