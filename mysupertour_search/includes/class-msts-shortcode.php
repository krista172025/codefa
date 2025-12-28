<?php
/**
 * Shortcode output
 * Version: 3.2.0-WORKING-VERSION
 * Author: Telegram @l1ghtsun
 * Author URI: https://t.me/l1ghtsun
 */
if(!defined('ABSPATH')) exit;

class MSTS_Shortcode {
    private static $inst; private $s;
    public static function instance(){return self::$inst?:self::$inst=new self();}
    private function __construct(){
        $this->s=MSTS_Settings::instance()->get();
        add_shortcode('mst_search',[$this,'render']);
        add_shortcode('mst_head',[$this,'render_head']);
    }
    
    public function render($atts){
        $atts=shortcode_atts(['noautofocus'=>false],$atts,'mst_search');
        $auto = $atts['noautofocus'] ? 0 : 1;
        $use_external=$this->s['use_external'];
        $ph=esc_attr($this->s['placeholder']); $btn=esc_html($this->s['button_text']); $scope=esc_attr($this->s['search_scope']);
        ob_start(); ?>
        <div class="msts-search-container">
            <div class="msts-search-wrapper">
                <?php if($use_external): echo do_shortcode($this->s['external_shortcode']); else: ?>
                    <form action="<?php echo esc_url(home_url('/'));?>" method="get" novalidate data-autofocus="<?php echo $auto;?>">
                        <div class="msts-search-input-wrap">
                            <input type="text" name="s" class="msts-search-input" placeholder="<?php echo $ph;?>" autocomplete="off">
                        </div>
                        <?php if($scope==='products'): ?><input type="hidden" name="post_type" value="product"><?php endif;?>
                        <?php if($scope==='posts'): ?><input type="hidden" name="post_type" value="post"><?php endif;?>
                        <button type="submit" class="msts-search-btn">
							<span class="msts-btn-icon">🔍</span>
							<span class="msts-btn-text"><?php echo $btn;?></span>
						</button>
                        <button type="button" class="msts-clear-btn-fixed" aria-label="Очистить">×</button>
                        <div class="msts-suggestions"></div>
                    </form>
                <?php endif; ?>
            </div>
        </div>
        <?php return ob_get_clean();
    }
    
    public function render_head($atts){
        $ph = esc_attr($this->s['placeholder']);
        $uid = 'mst-head-' .uniqid();
        ob_start(); ?>
        
        <div class="mst-head-trigger" id="<?php echo $uid; ?>-trigger" onclick="mstHeadOpen('<?php echo $uid; ?>')">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
            <span><?php echo $ph; ?></span>
            <button type="button">НАЙТИ</button>
        </div>
        
        <div class="mst-head-overlay" id="<?php echo $uid; ?>-overlay">
            <button type="button" class="mst-head-close" onclick="mstHeadClose('<?php echo $uid; ?>')" aria-label="Закрыть">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
            <div class="mst-head-modal">
                <?php echo do_shortcode('[mst_search noautofocus=true]'); ?>
            </div>
        </div>
        
        <style>
        <?php echo $uid; ?>-trigger {
            displayinline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 6px 6px 14px;
            height: 40px;
            min-width: 180px;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 25px;
            cursor: pointer;
            transition: all 0.2s;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
        <?php echo $uid; ?>-trigger: hover {
            border-color: #bbb;
            box-shadow: 0 4px 16px rgba(0,0,0,0.1);
        }
        <?php echo $uid; ?>-trigger svg {
            color: #666;
            flex-shrink: 0;
        }
        <?php echo $uid; ?>-trigger span {
            flex: 1;
            font-size: 13px;
            color: #888;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        <?php echo $uid; ?>-trigger button {
            flex-shrink: 0;
            padding: 6px 14px;
            background: linear-gradient(135deg, #FFD700, #FFA500);
            color: #1a1a1a;
            font-size: 11px;
            font-weight: 700;
            border: none;
            border-radius: 20px;
            cursor: pointer;
            letter-spacing: 0.5px;
        }
        
        <?php echo $uid; ?>-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0);
            backdrop-filter: blur(0);
            z-index: 999999;
            opacity: 0;
            pointer-events: none;
            transition: all 0.3s ease;
            display: flex;
            align-items: flex-start;
            justify-content: center;
            padding: 100px 20px 40px;
            overflow-y: auto;
        }
        <?php echo $uid; ?>-overlay.mst-active {
            background: rgba(0,0,0,0.75);
            backdrop-filter: blur(10px);
            opacity: 1;
            pointer-events: all;
        }
        
        <?php echo $uid; ?>-overlay .mst-head-modal {
            max-width: 800px;
            width: 100%;
            transform: scale(0.95) translateY(-20px);
            transition: all 0.3s ease;
        }
        <?php echo $uid; ?>-overlay.mst-active .mst-head-modal {
            transform: scale(1) translateY(0);
        }
        
        <?php echo $uid; ?>-overlay .mst-head-close {
            position: fixed;
            top: 24px;
            right: 24px;
            width: 48px;
            height: 48px;
            background: rgba(255,255,255,0.15);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.25);
            border-radius: 50%;
            color: #fff;
            cursor: pointer;
            transition: all 0.25s;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0;
            z-index: 1000001;
        }
        <?php echo $uid; ?>-overlay .mst-head-close:hover {
            background: rgba(255,255,255,0.3);
            transform: rotate(90deg) scale(1.1);
        }
        
        <?php echo $uid; ?>-overlay .msts-search-wrapper form {
            background: rgba(255,255,255,0.12);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,0.2);
            border-radius: 40px;
            padding: 6px;
        }
        <?php echo $uid; ?>-overlay .msts-search-input-wrap {
            border: none;
            background: transparent;
        }
        <?php echo $uid; ?>-overlay .msts-search-input {
            background: transparent;
            border: none;
            color: #fff;
            font-size: 16px;
        }
        <?php echo $uid; ?>-overlay .msts-search-input:: placeholder {
            color: rgba(255,255,255,0.6);
        }
        <?php echo $uid; ?>-overlay .msts-search-btn {
            background: linear-gradient(135deg, #FFD700, #FFA500);
            border: none;
            border-radius: 30px;
            color: #1a1a1a;
            font-weight: 700;
            padding: 12px 24px;
        }
        <?php echo $uid; ?>-overlay .msts-clear-btn-fixed {
            display: none;
        }
        <?php echo $uid; ?>-overlay .msts-suggestions {
            position: static;
            margin-top: 16px;
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.15);
            max-height: 450px;
            overflow-y: auto;
        }
        
        @media (max-width: 768px) {
            <?php echo $uid; ?>-trigger { min-width: 120px; height: 36px; }
            <?php echo $uid; ?>-trigger span { display: none; }
            <?php echo $uid; ?>-overlay { padding: 80px 12px 20px; }
            <?php echo $uid; ?>-overlay .mst-head-close { width: 40px; height: 40px; top: 16px; right: 16px; }
        }
        </style>
        
        <script>
        function mstHeadOpen(id) {
            var o = document.getElementById(id + '-overlay');
            if (! o) return;
            o.classList.add('mst-active');
            document.body.style.overflow = 'hidden';
            setTimeout(function(){ var i = o.querySelector('.msts-search-input'); if(i) i.focus(); }, 200);
        }
        function mstHeadClose(id) {
            var o = document.getElementById(id + '-overlay');
            if (!o) return;
            o.classList.remove('mst-active');
            document.body.style.overflow = '';
            var i = o.querySelector('.msts-search-input');
            if (i) { i.value = ''; i.blur(); }
            var s = o.querySelector('.msts-suggestions');
            if (s) { s.style.display = 'none'; s.innerHTML = ''; }
        }
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                document.querySelectorAll('.mst-head-overlay.mst-active').forEach(function(o) {
                    var id = o.id.replace('-overlay', '');
                    mstHeadClose(id);
                });
            }
        });
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('mst-head-overlay') && e.target.classList.contains('mst-active')) {
                var id = e.target.id.replace('-overlay', '');
                mstHeadClose(id);
            }
        });
        </script>
        <?php return ob_get_clean();
    }
}