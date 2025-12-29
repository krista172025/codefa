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
        
        <!-- HEADER SEARCH INPUT -->
        <div class="mst-hsearch" id="<?php echo $uid; ?>-trigger" onclick="mstHopen('<?php echo $uid; ?>')">
            <svg class="mst-hsearch-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
            <span class="mst-hsearch-text"><?php echo $ph; ?></span>
        </div>
        
        <!-- FULLSCREEN MODAL -->
        <div class="mst-hmodal" id="<?php echo $uid; ?>-modal">
            <div class="mst-hmodal-bg" onclick="mstHclose('<?php echo $uid; ?>')"></div>
            <div class="mst-hmodal-content">
                <button type="button" class="mst-hmodal-close" onclick="mstHclose('<?php echo $uid; ?>')">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>
                <div class="mst-hmodal-form">
                    <?php echo do_shortcode('[mst_search noautofocus=true]'); ?>
                </div>
            </div>
        </div>
        
        <style>
        /* === FULLSCREEN MODAL === */
        .mst-hmodal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            z-index: 999999;
            opacity: 0;
            visibility: hidden;
            transition: all 0.35s ease;
        }
        .mst-hmodal.mst-open {
            opacity: 1;
            visibility: visible;
        }

        /* BACKGROUND OVERLAY */
        .mst-hmodal-bg {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.7);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
        }

        /* CONTENT - ПОДНЯТО К КРЕСТИКУ */
        .mst-hmodal-content {
            position: relative;
            z-index: 2;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            padding: 24px 24px 40px;
            width: 100%;
            height: 100%;
            overflow-y: auto;
            transform: translateY(-30px);
            transition: transform 0.35s ease;
        }
        .mst-hmodal.mst-open .mst-hmodal-content {
            transform: translateY(0);
        }

        /* CLOSE BUTTON */
        .mst-hmodal-close {
            position: absolute;
            top: 24px;
            right: 24px;
            width: 48px;
            height: 48px;
            background: rgba(255,255,255,0.15);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255,255,255,0.3);
            border-radius: 50%;
            color: #fff;
            cursor: pointer;
            transition: all 0.25s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0;
            z-index: 10;
        }
        .mst-hmodal-close:hover {
            background: rgba(255,255,255,0.3);
            transform: rotate(90deg) scale(1.1);
        }

        /* FORM CONTAINER - В ОДНУ ЛИНИЮ С КРЕСТИКОМ */
        .mst-hmodal-form {
            max-width: 720px;
            width: 100%;
            margin-top: 0;
            padding-right: 70px;
        }

        /* === СТИЛИ КАК У ОСНОВНОГО ПОИСКА === */
        .mst-hmodal .msts-search-wrapper {
            background: transparent ! important;
            backdrop-filter: none ! important;
            -webkit-backdrop-filter: none !important;
            border: none !important;
            padding: 0 ! important;
        }

        .mst-hmodal .msts-search-wrapper form {
            background: rgba(255,255,255,0.15) !important;
            backdrop-filter: blur(20px) !important;
            -webkit-backdrop-filter: blur(20px) !important;
            border: 1px solid rgba(255,255,255,0.2) !important;
            border-radius: 60px !important;
            padding: 8px ! important;
        }

        .mst-hmodal .msts-search-input-wrap {
            border: none !important;
            background: transparent !important;
        }

        .mst-hmodal .msts-search-input {
            background: #ffffff !important;
            border: none !important;
            border-radius: 50px !important;
            height: 56px !important;
            box-shadow: none !important;
            outline: none !important;
            color: #333 ! important;
            font-size: 16px !important;
            padding: 0 24px !important;
        }

        .mst-hmodal .msts-search-input:: placeholder {
            color: #888 !important;
        }

        .mst-hmodal .msts-search-btn {
            background: linear-gradient(135deg, #a855f7, #7c3aed) !important;
            border: none !important;
            border-radius: 50px !important;
            color: #fff !important;
            font-weight: 600 !important;
            padding: 16px 32px ! important;
            font-size: 15px !important;
            height: 56px ! important;
            cursor: pointer !important;
            display: flex !important;
            align-items: center !important;
            gap: 8px ! important;
            transition: all 0.2s ease !important;
        }

        .mst-hmodal .msts-search-btn:hover {
            transform: scale(1.02) !important;
            box-shadow: 0 4px 20px rgba(168,85,247,0.4) !important;
        }

        .mst-hmodal .msts-clear-btn-fixed {
            display: none !important;
        }

        .mst-hmodal .msts-suggestions {
            position  static !important;
            margin-top: 20px !important;
            background: rgba(255,255,255,0.95) !important;
            backdrop-filter: blur(20px) !important;
            -webkit-backdrop-filter: blur(20px) !important;
            border-radius: 24px !important;
            box-shadow: 0 12px 48px rgba(0,0,0,0.2) !important;
            max-height: 60vh ! important;
            overflow-y: auto ! important;
        }

        /* === HEADER SEARCH TRIGGER - БЕЗ РАМКИ === */
        .mst-hsearch {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            background: transparent;
            border: 1px solid #ddd;
            border-radius: 24px;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .mst-hsearch:hover {
            border-color: rgba(153, 82, 224, 1);
            background: rgba(0,0,0,0.02);
        }
        .mst-hsearch-icon {
            flex-shrink: 0;
            color: #000000ff;
            width: 16px;
            height: 16px;
        }
        .mst-hsearch-text {
            font-size: 14px;
            color: #000000ff;
            white-space: nowrap;
        }

        /* === MOBILE === */
        @media (max-width: 768px) {
            .mst-hsearch {
                padding: 8px;
                border: none;
            }
            .mst-hsearch-text {
                display: none;
            }
        }
        </style>
        
        <script>
        function mstHopen(id) {
            var m = document.getElementById(id + '-modal');
            if (!m) return;
            m.classList.add('mst-open');
            document.body.style.overflow = 'hidden';
            setTimeout(function(){
                var inp = m.querySelector('.msts-search-input');
                if (inp) inp.focus();
            }, 300);
        }
        function mstHclose(id) {
            var m = document.getElementById(id + '-modal');
            if (!m) return;
            m.classList.remove('mst-open');
            document.body.style.overflow = '';
            var inp = m.querySelector('.msts-search-input');
            if (inp) { inp.value = ''; inp.blur(); }
            var sug = m.querySelector('.msts-suggestions');
            if (sug) { sug.style.display = 'none'; sug.innerHTML = ''; }
        }
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                document.querySelectorAll('.mst-hmodal.mst-open').forEach(function(m) {
                    mstHclose(m.id.replace('-modal', ''));
                });
            }
        });
        </script>
        <?php return ob_get_clean();
    }
}   