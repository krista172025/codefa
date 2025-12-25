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
        add_shortcode('mst_search_header',[$this,'render_header']);
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
    
    public function render_header($atts){
        $atts=shortcode_atts([],$atts,'mst_search_header');
        $ph=esc_attr($this->s['placeholder']);
        ob_start(); ?>
        
        <!-- ✅ HEADER SEARCH INPUT - LIQUID GLASS + ВИДИМЫЙ ТЕКСТ -->
        <div class="msts-hdr-search-box" onclick="mstsOpenModal()">
            <span class="msts-hdr-icon">🔍</span>
            <input type="text" class="msts-hdr-input" placeholder="<?php echo $ph; ?>" readonly>
        </div>
        
        <!-- MODAL -->
        <div id="msts-modal-v3" class="msts-modal-v3">
            <button class="msts-modal-v3-close" onclick="mstsCloseModal()">×</button>
            <div class="msts-modal-v3-inner">
                <?php echo do_shortcode('[mst_search noautofocus=true]'); ?>
            </div>
        </div>
        
        <style>
        /* ✅ HEADER SEARCH BOX - LIQUID GLASS С ВИДИМЫМ ТЕКСТОМ */
        .msts-hdr-search-box {
            display: flex !important;
            align-items: center !important;
            gap: 10px !important;
            padding: 8px 16px !important;
            height: 38px !important;
            max-width: 280px !important;
            background: rgba(255,255,255,0.15) !important;
            backdrop-filter: blur(40px) !important;
            -webkit-backdrop-filter: blur(40px) !important;
            border: 2px solid rgba(255,255,255,0.25) !important;
            border-radius: 24px !important;
            cursor: pointer !important;
            transition: all 0.3s ease !important;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1) !important;
        }
        
        .msts-hdr-search-box:hover {
            background: rgba(255,255,255,0.25) !important;
            border-color: rgba(255,255,255,0.4) !important;
            transform: translateY(-2px) !important;
            box-shadow: 0 6px 28px rgba(0,0,0,0.15) !important;
        }
        
        .msts-hdr-icon {
            font-size: 16px !important;
            line-height: 1 !important;
            flex-shrink: 0 !important;
            opacity: 0.8 !important;
        }
        
        /* ✅ INPUT С ТЕМНЫМ ТЕКСТОМ (ВИДИМЫЙ!) */
        .msts-hdr-input {
            flex: 1 !important;
            border: none !important;
            outline: none !important;
            background: transparent !important;
            font-size: 13px !important;
            font-weight: 500 !important;
            color: #333 !important;
            pointer-events: none !important;
        }
        
        .msts-hdr-input::placeholder {
            color: #666 !important;
            opacity: 1 !important;
        }
        
        /* ✅ MODAL OVERLAY */
        .msts-modal-v3 {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(0,0,0,0);
            backdrop-filter: blur(0px);
            -webkit-backdrop-filter: blur(0px);
            z-index: 999999;
            opacity: 0;
            pointer-events: none;
            transition: all 0.4s cubic-bezier(0.4,0,0.2,1);
            display: flex;
            align-items: flex-start;
            justify-content: center;
            padding: 80px 20px 20px;
            overflow-y: auto;
        }
        
        .msts-modal-v3.active {
            background: rgba(0,0,0,0.85);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            opacity: 1;
            pointer-events: all;
        }
        
        /* ✅ MODAL INNER */
        .msts-modal-v3-inner {
            max-width: 900px;
            width: 100%;
            position: relative;
            transform: scale(0.9) translateY(-20px);
            transition: all 0.4s cubic-bezier(0.4,0,0.2,1);
        }
        
        .msts-modal-v3.active .msts-modal-v3-inner {
            transform: scale(1) translateY(0);
        }
        
        /* ✅ КРЕСТИК ЗАКРЫТИЯ МОДАЛКИ */
        .msts-modal-v3-close {
            position: fixed !important;
            top: 30px !important;
            right: 30px !important;
            width: 56px !important;
            height: 56px !important;
            background: rgba(255,255,255,0.25) !important;
            backdrop-filter: blur(20px) !important;
            -webkit-backdrop-filter: blur(20px) !important;
            border: 2px solid rgba(255,255,255,0.35) !important;
            border-radius: 50% !important;
            color: #fff !important;
            font-size: 32px !important;
            line-height: 1 !important;
            font-weight: 300 !important;
            cursor: pointer !important;
            transition: all 0.3s ease !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            padding: 0 !important;
            z-index: 1000001 !important;
        }
        
        .msts-modal-v3-close:hover {
            background: rgba(255,255,255,0.4) !important;
            border-color: rgba(255,255,255,0.6) !important;
            transform: rotate(90deg) scale(1.15) !important;
        }
        
        /* ✅ ФОРМА ВНУТРИ MODAL */
        .msts-modal-v3 .msts-search-container {
            margin: 0 !important;
        }
        
        .msts-modal-v3 .msts-search-wrapper form {
            background: rgba(255,255,255,0.15) !important;
            backdrop-filter: blur(40px) !important;
            -webkit-backdrop-filter: blur(40px) !important;
            border: 2px solid rgba(255,255,255,0.25) !important;
            border-radius: 50px !important;
            padding: 8px !important;
        }
        
        .msts-modal-v3 .msts-search-input-wrap {
            border: 3px solid rgba(255,255,255,0.6) !important;
        }
        
        .msts-modal-v3 .msts-search-input {
            background: rgba(255,255,255,0.2) !important;
            backdrop-filter: blur(20px) !important;
            -webkit-backdrop-filter: blur(20px) !important;
            color: #fff !important;
        }
        
        .msts-modal-v3 .msts-search-input::placeholder {
            color: rgba(255,255,255,0.7) !important;
        }
        
        .msts-modal-v3 .msts-search-btn {
            background: rgba(255,255,255,0.25) !important;
            backdrop-filter: blur(20px) !important;
            -webkit-backdrop-filter: blur(20px) !important;
            border: 2px solid rgba(255,255,255,0.3) !important;
            color: #fff !important;
            font-weight: 700 !important;
            min-width: 120px !important;
        }
        
        /* ✅ КРЕСТИК ОЧИСТКИ */
        .msts-modal-v3 .msts-clear-btn-fixed {
            position: absolute !important;
            right: 140px !important;
            top: 50% !important;
            transform: translateY(-50%) !important;
            width: 32px !important;
            height: 32px !important;
            background: rgba(255,255,255,0.25) !important;
            backdrop-filter: blur(20px) !important;
            -webkit-backdrop-filter: blur(20px) !important;
            border: 2px solid rgba(255,255,255,0.3) !important;
            border-radius: 50% !important;
            color: #fff !important;
            font-size: 22px !important;
            display: none !important;
            z-index: 10 !important;
        }
        
        .msts-modal-v3 .msts-clear-btn-fixed.msts-show {
            display: flex !important;
        }
        
        .msts-modal-v3 .msts-clear-btn-fixed:hover {
            background: rgba(255,255,255,0.35) !important;
            transform: translateY(-50%) rotate(90deg) scale(1.1) !important;
        }
        
        .msts-modal-v3 .msts-suggestions {
            position: static !important;
            margin-top: 20px !important;
            background: rgba(255,255,255,0.95) !important;
            backdrop-filter: blur(40px) !important;
            -webkit-backdrop-filter: blur(40px) !important;
            max-height: 500px !important;
            overflow-y: auto !important;
            border-radius: 28px !important;
        }
        
        /* ✅ АДАПТИВ */
        @media (max-width: 768px) {
            .msts-hdr-search-box {
                max-width: 240px !important;
                padding: 6px 12px !important;
                height: 34px !important;
            }
            .msts-hdr-icon {
                font-size: 14px !important;
            }
            .msts-hdr-input {
                font-size: 12px !important;
            }
            .msts-modal-v3-close {
                width: 44px !important;
                height: 44px !important;
                top: 20px !important;
                right: 20px !important;
                font-size: 28px !important;
            }
        }
        </style>
        
        <script>
        function mstsOpenModal() {
            var modal = document.getElementById('msts-modal-v3');
            if (!modal) return;
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
            setTimeout(function(){
                var input = modal.querySelector('.msts-search-input');
                if (input) input.focus();
            }, 300);
        }
        
        function mstsCloseModal() {
            var modal = document.getElementById('msts-modal-v3');
            if (!modal) return;
            modal.classList.remove('active');
            document.body.style.overflow = '';
            
            var input = modal.querySelector('.msts-search-input');
            if (input) {
                input.value = '';
                input.blur();
            }
            var suggestions = modal.querySelector('.msts-suggestions');
            if (suggestions) {
                suggestions.style.display = 'none';
                suggestions.innerHTML = '';
            }
            var clearBtn = modal.querySelector('.msts-clear-btn-fixed');
            if (clearBtn) clearBtn.classList.remove('msts-show');
        }
        
        document.addEventListener('keydown', function(e){
            if (e.key === 'Escape') {
                mstsCloseModal();
            }
        });
        
        document.getElementById('msts-modal-v3')?.addEventListener('click', function(e){
            if (e.target === this) {
                mstsCloseModal();
            }
        });
        </script>
        <?php return ob_get_clean();
    }
}