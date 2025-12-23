<?php
/**
 * Icons Page
 * Author: Telegram @l1ghtsun
 * Author URI: https://t.me/l1ghtsun
 */
if(!defined('ABSPATH')) exit;

$settings = get_option('mst_icon_positioning',['type'=>'absolute','top'=>'10','left'=>'25','right'=>'','bottom'=>'','size'=>'32','radius'=>'50']);
$per_page = 10;
$paged = isset($_GET['paged']) ? max(1,intval($_GET['paged'])) : 1;
$products = MST_Hub_Core::get_products_with_icons($per_page,$paged);
$total = MST_Hub_Core::count_products_with_icons();
$total_pages = ceil($total/$per_page);

settings_errors('mst_messages');
?>
<div class="wrap mst-hub-wrap">
    <div class="mst-hub-header"><h1 class="mst-hub-title">🎨 Настройки Иконок</h1></div>
    
    <div class="mst-admin-layout">
        <div class="mst-panel">
            <h2><span class="mst-section-icon">⚙️</span> Позиционирование</h2>
            <form method="post" action="">
                <?php wp_nonce_field('mst_icon_settings','mst_icon_nonce'); ?>
                
                <div class="mst-form-group">
                    <label class="mst-form-label">Тип позиционирования</label>
                    <select name="mst_icon_positioning[type]" id="mst-t" class="mst-l mst-form-control">
                        <option value="absolute" <?php selected($settings['type'],'absolute'); ?>>Absolute</option>
                        <option value="relative" <?php selected($settings['type'],'relative'); ?>>Relative</option>
                    </select>
                </div>
                
                <div class="mst-form-group">
                    <label class="mst-form-label">Сверху (px)</label>
                    <input type="number" name="mst_icon_positioning[top]" id="mst-top" class="mst-l mst-form-control" value="<?php echo $settings['top']; ?>" min="0" max="500">
                </div>
                
                <div class="mst-form-group">
                    <label class="mst-form-label">Слева (px)</label>
                    <input type="number" name="mst_icon_positioning[left]" id="mst-left" class="mst-l mst-form-control" value="<?php echo $settings['left']; ?>" min="0" max="500">
                </div>
                
                <div class="mst-form-group">
                    <label class="mst-form-label">Справа (px)</label>
                    <input type="number" name="mst_icon_positioning[right]" id="mst-right" class="mst-l mst-form-control" value="<?php echo $settings['right']; ?>" min="0" max="500">
                </div>
                
                <div class="mst-form-group">
                    <label class="mst-form-label">Снизу (px)</label>
                    <input type="number" name="mst_icon_positioning[bottom]" id="mst-bottom" class="mst-l mst-form-control" value="<?php echo $settings['bottom']; ?>" min="0" max="500">
                </div>
                
                <div class="mst-form-group">
                    <label class="mst-form-label">Размер (px)</label>
                    <input type="number" name="mst_icon_positioning[size]" id="mst-size" class="mst-l mst-form-control" value="<?php echo $settings['size']; ?>" min="16" max="100">
                </div>
                
                <div class="mst-form-group">
                    <label class="mst-form-label">Радиус (%)</label>
                    <input type="number" name="mst_icon_positioning[radius]" id="mst-radius" class="mst-l mst-form-control" value="<?php echo $settings['radius']; ?>" min="0" max="100">
                </div>
                
                <button type="submit" name="mst_save_icon_settings" class="mst-btn mst-btn-primary" style="width:100%;">💾 Сохранить настройки</button>
            </form>
            
            <div class="mst-preview-box">
                <div class="mst-preview-title">👁️ Live Preview</div>
                <div class="mst-product-preview">
                    <img src="https://via.placeholder.com/300x200/667eea/ffffff?text=Экскурсия+по+городу" alt="Preview" style="width:100%;display:block;border-radius:12px;">
                    <div class="mst-pi-badge" id="mst-prev" style="position:absolute;background:rgba(0,0,0,.55);color:#fff;padding:6px 10px;border-radius:10px;font-size:12px;display:flex;gap:8px;">
                        <span style="font-weight:600;">Групповая</span>
                        <span style="display:inline-flex;align-items:center;gap:6px;">⏱<span>2:00 часа</span></span>
                        <span>🚗</span>
                    </div>
                </div>
                <p style="font-size:13px;color:#666;margin-top:12px;">Изменяйте настройки выше и смотрите как плашка перемещается!</p>
            </div>
        </div>
        
        <div class="mst-panel">
            <h2><span class="mst-section-icon">📦</span> Товары с иконками (<?php echo $total; ?>)</h2>
            <?php if(empty($products)): ?>
                <div class="mst-empty-state">
                    <div class="mst-empty-icon">📦</div>
                    <p>Нет товаров с иконками</p>
                </div>
            <?php else: ?>
                <div class="mst-products-list">
                    <?php foreach($products as $p): ?>
                        <div class="mst-product-item">
                            <div class="mst-product-thumb" style="position:relative;width:180px;height:180px;flex-shrink:0;border-radius:12px;overflow:hidden;">
                                <?php echo $p['thumbnail']; ?>
                                <?php if($p['format'] || $p['duration'] || $p['transport']): ?>
                                <div class="mst-pi-badge mst-live-preview" style="position:absolute;top:<?php echo $settings['top']; ?>px;left:<?php echo $settings['left']; ?>px;background:rgba(0,0,0,.55);color:#fff;padding:6px 10px;border-radius:10px;font-size:12px;display:flex;gap:8px;">
                                    <?php if($p['format']): ?>
                                        <?php 
                                        $formats = get_option('mst_formats', []);
                                        $format_name = isset($formats[$p['format']]) ? $formats[$p['format']]['name'] : $p['format'];
                                        ?>
                                        <span style="font-weight:600;"><?php echo esc_html($format_name); ?></span>
                                    <?php endif; ?>
                                    <?php if($p['duration']): ?>
                                        <span style="display:inline-flex;align-items:center;gap:4px;">⏱<span><?php echo esc_html($p['duration']); ?></span></span>
                                    <?php endif; ?>
                                    <?php if($p['transport']): ?>
                                        <?php 
                                        $transports = get_option('mst_transports', []);
                                        echo isset($transports[$p['transport']]) ? $transports[$p['transport']]['icon'] : '';
                                        ?>
                                    <?php endif; ?>
                                </div>
                                <?php endif; ?>
                            </div>
                            <div class="mst-product-info">
                                <h4><?php echo esc_html($p['title']); ?></h4>
                                <p class="mst-meta">ID:<?php echo $p['id']; ?> | <a href="<?php echo $p['edit_url']; ?>" target="_blank">Редактировать товар</a></p>
                                <div class="mst-badges">
                                    <?php if($p['format']): ?><span class="mst-badge"><?php echo esc_html($p['format']); ?></span><?php endif; ?>
                                    <?php if($p['transport']): ?><span class="mst-badge"><?php echo esc_html($p['transport']); ?></span><?php endif; ?>
                                    <?php if($p['duration']): ?><span class="mst-badge">⏱<?php echo esc_html($p['duration']); ?></span><?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php if($total_pages>1): ?>
                    <div class="mst-pagination">
                        <?php for($i=1;$i<=$total_pages;$i++): ?>
                            <a href="<?php echo add_query_arg('paged',$i); ?>" class="<?php echo $i===$paged?'active':''; ?>"><?php echo $i; ?></a>
                        <?php endfor; ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
.mst-product-preview{position:relative;width:100%;max-width:300px;margin:20px auto;border-radius:16px;overflow:hidden;box-shadow:0 4px 16px rgba(0,0,0,0.15)}
.mst-product-preview img{display:block;width:100%}
.mst-product-thumb img{width:100%;height:100%;object-fit:cover;display:block}
</style>

<script>
jQuery(function($){
    function updatePreview(){
        const type=$('#mst-t').val();
        const top=parseInt($('#mst-top').val())||0;
        const left=parseInt($('#mst-left').val())||0;
        const right=parseInt($('#mst-right').val())||0;
        const bottom=parseInt($('#mst-bottom').val())||0;
        const size=parseInt($('#mst-size').val())||32;
        const radius=parseInt($('#mst-radius').val())||50;
        
        const css={
            position:type,
            top:(right||bottom)?'auto':top+'px',
            left:right?'auto':left+'px',
            right:right?right+'px':'auto',
            bottom:bottom?bottom+'px':'auto'
        };
        
        $('#mst-prev').css(css);
        $('.mst-live-preview').css(css);
        console.log('Preview updated:',css);
    }
    
    $('.mst-l').on('input change',updatePreview);
    updatePreview();
});
</script>