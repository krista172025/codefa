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
        $uid = 'msth-' .uniqid();
        ob_start(); ?>
        
        <div class="mst-head-box" id="<?php echo $uid; ?>-box" onclick="mstHeadOpen('<?php echo $uid; ?>')">
            <svg class="mst-head-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
            <input type="text" class="mst-head-input" placeholder="<?php echo $ph; ?>" readonly>
        </div>
        
        <div class="mst-head-overlay" id="<?php echo $uid; ?>-overlay">
            <div class="mst-head-modal">
                <?php echo do_shortcode('[mst_search noautofocus=true]'); ?>
            </div>
            <button type="button" class="mst-head-close" onclick="mstHeadClose('<?php echo $uid; ?>')">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        
        <style>
        .mst-head-box {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 10px 18px;
            min-width: 200px;
            max-width: 280px;
            height: 44px;
            background: #fff;
            border: 1px solid #e5e5e5;
            border-radius: 28px;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        }
        .mst-head-box:hover {
            border-color: #ccc;
            box-shadow: 0 4px 16px rgba(0,0,0,0.08);
        }
        .mst-head-icon {
            flex-shrink: 0;
            color: #999;
        }
        .mst-head-input {
            flex: 1;
            border: none;
            outline: none;
            background: transparent;
            font-size: 14px;
            color: #333;
            cursor: pointer;
            pointer-events: none;
        }
        .mst-head-input:: placeholder {
            color: #999;
        }
        
        .mst-head-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0);
            backdrop-filter: blur(0);
            -webkit-backdrop-filter: blur(0);
            z-index: 999999;
            opacity: 0;
            pointer-events: none;
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 100px 20px 40px;
            overflow-y: auto;
        }
        .mst-head-overlay.mst-open {
            background: rgba(0,0,0,0.7);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            opacity: 1;
            pointer-events: all;
        }
        
        .mst-head-modal {
            max-width: 750px;
            width: 100%;
            transform: scale(0.95) translateY(-30px);
            transition: all 0.3s ease;
        }
        .mst-head-overlay.mst-open .mst-head-modal {
            transform: scale(1) translateY(0);
        }
        
        .mst-head-close {
            position: fixed;
            top: 28px;
            right: 28px;
            width: 44px;
            height: 44px;
            background: rgba(255,255,255,0.15);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.25);
            border-radius: 50%;
            color: #fff;
            cursor: pointer;
            transition: all 0.25s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0;
            z-index: 1000001;
        }
        .mst-head-close:hover {
            background: rgba(255,255,255,0.3);
            transform: rotate(90deg) scale(1.1);
        }
        
        .mst-head-overlay .msts-search-wrapper form {
            background: rgba(255,255,255,0.1);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,0.2);
            border-radius: 36px;
            padding: 6px;
        }
        .mst-head-overlay .msts-search-input-wrap {
            border: none;
            background: transparent;
        }
        .mst-head-overlay .msts-search-input {
            background: transparent;
            border: none;
            color: #fff;
            font-size: 16px;
            padding: 14px 20px;
        }
        .mst-head-overlay .msts-search-input::placeholder {
            color: rgba(255,255,255,0.6);
        }
        .mst-head-overlay .msts-search-btn {
            background: linear-gradient(135deg, #a855f7, #7c3aed);
            border: none;
            border-radius: 28px;
            color: #fff;
            font-weight: 600;
            padding: 14px 28px;
            font-size: 14px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .mst-head-overlay .msts-clear-btn-fixed {
            display: none ! important;
        }
        .mst-head-overlay .msts-suggestions {
            position: static;
            margin-top: 16px;
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 8px 40px rgba(0,0,0,0.2);
            max-height: 420px;
            overflow-y: auto;
        }
        
        @media (max-width: 768px) {
            .mst-head-box {
                min-width: 160px;
                max-width: 220px;
                height: 40px;
                padding: 8px 14px;
            }
            .mst-head-input { font-size: 13px; }
            .mst-head-overlay { padding: 80px 12px 20px; }
            .mst-head-close { width: 38px; height: 38px; top: 16px; right: 16px; }
        }
        </style>
        
        <script>
        function mstHeadOpen(id) {
            var o = document.getElementById(id + '-overlay');
            if (! o) return;
            o.classList.add('mst-open');
            document.body.style.overflow = 'hidden';
            setTimeout(function(){
                var inp = o.querySelector('.msts-search-input');
                if (inp) inp.focus();
            }, 200);
        }
        function mstHeadClose(id) {
            var o = document.getElementById(id + '-overlay');
            if (!o) return;
            o.classList.remove('mst-open');
            document.body.style.overflow = '';
            var inp = o.querySelector('.msts-search-input');
            if (inp) { inp.value = ''; inp.blur(); }
            var sug = o.querySelector('.msts-suggestions');
            if (sug) { sug.style.display = 'none'; sug.innerHTML = ''; }
        }
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                document.querySelectorAll('.mst-head-overlay.mst-open').forEach(function(o) {
                    mstHeadClose(o.id.replace('-overlay', ''));
                });
            }
        });
        document.addEventListener('click', function(e) {
            var o = e.target.closest('.mst-head-overlay');
            if (o && o.classList.contains('mst-open') && e.target === o) {
                mstHeadClose(o.id.replace('-overlay', ''));
            }
        });
        </script>
        <?php return ob_get_clean();
    }
}