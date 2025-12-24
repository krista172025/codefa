<?php
/**
 * Plugin Name: MST Preloader (exact)
 * Description: Мини‑плагин — вставляет MST map preloader (точно как в provided standalone HTML).
 * Version: 1.1.0
 * Author: MST
 * Text Domain: mst-preloader
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Load translations
add_action('plugins_loaded', function() {
    load_plugin_textdomain('mst-preloader', false, dirname(plugin_basename(__FILE__)) . '/languages');
});

/**
 * Проверка: показывали ли прелоадер ранее (на стороне сервера) — смотрим cookie 'mst_preloader_shown'
 * Если cookie установлена и равна '1' — считаем, что показывать не нужно.
 */
function mstp_preloader_already_shown() {
    return isset( $_COOKIE['mst_preloader_shown'] ) && $_COOKIE['mst_preloader_shown'] === '1';
}

/**
 * Enqueue assets (font, css, js) from plugin/assets/
 * Если cookie показывает, что уже было — не подключаем ассеты.
 */
function mstp_enqueue_assets() {
    if ( is_admin() || ( defined( 'REST_REQUEST' ) && REST_REQUEST ) || ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {
        return;
    }

    // Если уже показывали — не грузим
    if ( mstp_preloader_already_shown() ) {
        return;
    }

    $base_url  = plugin_dir_url( __FILE__ );
    $base_path = plugin_dir_path( __FILE__ );

    // Montserrat font (as in standalone)
    wp_enqueue_style( 'mst-preloader-montserrat', 'https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap', array(), null );

    // CSS
    $css_file = $base_path . 'assets/css/mst-preloader.css';
    if ( file_exists( $css_file ) ) {
        $ver = filemtime( $css_file );
        wp_enqueue_style( 'mst-preloader-style', $base_url . 'assets/css/mst-preloader.css', array(), $ver );
    }

    // JS (in footer)
    $js_file = $base_path . 'assets/js/mst-preloader.js';
    if ( file_exists( $js_file ) ) {
        $ver = filemtime( $js_file );
        wp_enqueue_script( 'mst-preloader-script', $base_url . 'assets/js/mst-preloader.js', array(), $ver, true );
    }
}
add_action( 'wp_enqueue_scripts', 'mstp_enqueue_assets', 10 );

/**
 * Insert exact preloader markup after <body> open.
 * Если cookie указывает, что уже показывали — не вставляем разметку.
 */
function mstp_insert_preloader_in_body() {
    if ( is_admin() || ( defined( 'REST_REQUEST' ) && REST_REQUEST ) ) {
        return;
    }

    // Если уже показывали — не вставляем
    if ( mstp_preloader_already_shown() ) {
        return;
    }

    // Выводим точную разметку (копия из standalone)
    ?>
    <div id="mst-preloader">
        <button class="skip-button" onclick="skipPreloader()"><?php _e('Пропустить', 'mst-preloader'); ?></button>
        
        <div class="map-svg-container">
            <!-- Mobile: Vertical route -->
            <svg viewBox="0 0 300 500" class="map-svg-mobile" xmlns="http://www.w3.org/2000/svg">
                <line x1="50" y1="80" x2="250" y2="80" stroke="#E5E7EB" stroke-width="1" opacity="0.5"/>
                <line x1="50" y1="150" x2="250" y2="150" stroke="#E5E7EB" stroke-width="1" opacity="0.5"/>
                <line x1="50" y1="220" x2="250" y2="220" stroke="#E5E7EB" stroke-width="1" opacity="0.5"/>
                <line x1="50" y1="290" x2="250" y2="290" stroke="#E5E7EB" stroke-width="1" opacity="0.5"/>
                <line x1="50" y1="360" x2="250" y2="360" stroke="#E5E7EB" stroke-width="1" opacity="0.5"/>
                <line x1="50" y1="430" x2="250" y2="430" stroke="#E5E7EB" stroke-width="1" opacity="0.5"/>
                
                <line x1="50" y1="80" x2="50" y2="450" stroke="#E5E7EB" stroke-width="1" opacity="0.5"/>
                <line x1="150" y1="80" x2="150" y2="450" stroke="#E5E7EB" stroke-width="1" opacity="0.5"/>
                <line x1="250" y1="80" x2="250" y2="450" stroke="#E5E7EB" stroke-width="1" opacity="0.5"/>

                <defs>
                    <linearGradient id="gradient-mobile" x1="0%" y1="0%" x2="0%" y2="100%">
                        <stop offset="0%" stop-color="#FF385C" stop-opacity="0.5"/>
                        <stop offset="50%" stop-color="#FF385C"/>
                        <stop offset="100%" stop-color="#FF385C" stop-opacity="0.5"/>
                    </linearGradient>
                </defs>

                <path id="route-path-mobile"
                      d="M 150 100 Q 120 150 140 200 Q 160 250 130 300 Q 110 350 140 400"
                      stroke="url(#gradient-mobile)"
                      stroke-width="3"
                      fill="none"
                      stroke-linecap="round"
                      stroke-dasharray="1500"
                      stroke-dashoffset="1500"/>

                <g class="animate-fade-in" style="animation-delay: 0s">
                    <circle cx="150" cy="100" r="25" fill="none" stroke="#FF385C" stroke-width="2" opacity="0" class="animate-ping" style="animation-delay: 0.5s"/>
                    <circle cx="150" cy="100" r="18" fill="none" stroke="#FF385C" stroke-width="1.5" opacity="0.3" class="animate-ping" style="animation-delay: 0.7s; animation-duration: 2s"/>
                    <circle cx="150" cy="100" r="8" fill="#FF385C"/>
                    <circle cx="150" cy="100" r="3" fill="white"/>
                    <text x="185" y="105" text-anchor="start" font-size="12" font-weight="500" fill="#374151">Париж</text>
                </g>

                <g class="animate-fade-in" style="animation-delay: 0.4s">
                    <circle cx="140" cy="200" r="25" fill="none" stroke="#FF385C" stroke-width="2" opacity="0" class="animate-ping" style="animation-delay: 0.9s"/>
                    <circle cx="140" cy="200" r="18" fill="none" stroke="#FF385C" stroke-width="1.5" opacity="0.3" class="animate-ping" style="animation-delay: 1.1s; animation-duration: 2s"/>
                    <circle cx="140" cy="200" r="8" fill="#FF385C"/>
                    <circle cx="140" cy="200" r="3" fill="white"/>
                    <text x="175" y="205" text-anchor="start" font-size="12" font-weight="500" fill="#374151">Брюссель</text>
                </g>

                <g class="animate-fade-in" style="animation-delay: 0.8s">
                    <circle cx="130" cy="300" r="25" fill="none" stroke="#FF385C" stroke-width="2" opacity="0" class="animate-ping" style="animation-delay: 1.3s"/>
                    <circle cx="130" cy="300" r="18" fill="none" stroke="#FF385C" stroke-width="1.5" opacity="0.3" class="animate-ping" style="animation-delay: 1.5s; animation-duration: 2s"/>
                    <circle cx="130" cy="300" r="8" fill="#FF385C"/>
                    <circle cx="130" cy="300" r="3" fill="white"/>
                    <text x="165" y="305" text-anchor="start" font-size="12" font-weight="500" fill="#374151">Прага</text>
                </g>

                <g class="animate-fade-in" style="animation-delay: 1.2s">
                    <circle cx="140" cy="400" r="25" fill="none" stroke="#FF385C" stroke-width="2" opacity="0" class="animate-ping" style="animation-delay: 1.7s"/>
                    <circle cx="140" cy="400" r="18" fill="none" stroke="#FF385C" stroke-width="1.5" opacity="0.3" class="animate-ping" style="animation-delay: 1.9s; animation-duration: 2s"/>
                    <circle cx="140" cy="400" r="8" fill="#FF385C"/>
                    <circle cx="140" cy="400" r="3" fill="white"/>
                    <text x="175" y="405" text-anchor="start" font-size="12" font-weight="500" fill="#374151">Амстердам</text>
                </g>

                <circle id="moving-dot-mobile" r="4" fill="#FF385C" style="filter: drop-shadow(0 4px 6px rgba(0,0,0,0.1))"/>
            </svg>

            <!-- Desktop: Horizontal route -->
            <svg viewBox="0 0 600 400" class="map-svg-desktop" xmlns="http://www.w3.org/2000/svg">
                <line x1="50" y1="80" x2="550" y2="80" stroke="#E5E7EB" stroke-width="1" opacity="0.5"/>
                <line x1="50" y1="140" x2="550" y2="140" stroke="#E5E7EB" stroke-width="1" opacity="0.5"/>
                <line x1="50" y1="200" x2="550" y2="200" stroke="#E5E7EB" stroke-width="1" opacity="0.5"/>
                <line x1="50" y1="260" x2="550" y2="260" stroke="#E5E7EB" stroke-width="1" opacity="0.5"/>
                <line x1="50" y1="320" x2="550" y2="320" stroke="#E5E7EB" stroke-width="1" opacity="0.5"/>
                
                <line x1="50" y1="80" x2="50" y2="320" stroke="#E5E7EB" stroke-width="1" opacity="0.5"/>
                <line x1="112.5" y1="80" x2="112.5" y2="320" stroke="#E5E7EB" stroke-width="1" opacity="0.5"/>
                <line x1="175" y1="80" x2="175" y2="320" stroke="#E5E7EB" stroke-width="1" opacity="0.5"/>
                <line x1="237.5" y1="80" x2="237.5" y2="320" stroke="#E5E7EB" stroke-width="1" opacity="0.5"/>
                <line x1="300" y1="80" x2="300" y2="320" stroke="#E5E7EB" stroke-width="1" opacity="0.5"/>
                <line x1="362.5" y1="80" x2="362.5" y2="320" stroke="#E5E7EB" stroke-width="1" opacity="0.5"/>
                <line x1="425" y1="80" x2="425" y2="320" stroke="#E5E7EB" stroke-width="1" opacity="0.5"/>
                <line x1="487.5" y1="80" x2="487.5" y2="320" stroke="#E5E7EB" stroke-width="1" opacity="0.5"/>
                <line x1="550" y1="80" x2="550" y2="320" stroke="#E5E7EB" stroke-width="1" opacity="0.5"/>

                <defs>
                    <linearGradient id="gradient-desktop" x1="0%" y1="0%" x2="100%" y2="0%">
                        <stop offset="0%" stop-color="#FF385C" stop-opacity="0.5"/>
                        <stop offset="50%" stop-color="#FF385C"/>
                        <stop offset="100%" stop-color="#FF385C" stop-opacity="0.5"/>
                    </linearGradient>
                </defs>

                <path id="route-path-desktop"
                      d="M 100 200 Q 180 160 250 180 Q 320 200 380 160 Q 450 140 520 170"
                      stroke="url(#gradient-desktop)"
                      stroke-width="3"
                      fill="none"
                      stroke-linecap="round"
                      stroke-dasharray="1800"
                      stroke-dashoffset="1800"/>

                <g class="animate-fade-in" style="animation-delay: 0s">
                    <circle cx="100" cy="200" r="30" fill="none" stroke="#FF385C" stroke-width="2" opacity="0" class="animate-ping" style="animation-delay: 0.5s"/>
                    <circle cx="100" cy="200" r="20" fill="none" stroke="#FF385C" stroke-width="1.5" opacity="0.3" class="animate-ping" style="animation-delay: 0.7s; animation-duration: 2s"/>
                    <circle cx="100" cy="200" r="10" fill="#FF385C"/>
                    <circle cx="100" cy="200" r="4" fill="white"/>
                    <text x="100" y="230" text-anchor="middle" font-size="14" font-weight="500" fill="#374151">Париж</text>
                </g>

                <g class="animate-fade-in" style="animation-delay: 0.4s">
                    <circle cx="250" cy="180" r="30" fill="none" stroke="#FF385C" stroke-width="2" opacity="0" class="animate-ping" style="animation-delay: 0.9s"/>
                    <circle cx="250" cy="180" r="20" fill="none" stroke="#FF385C" stroke-width="1.5" opacity="0.3" class="animate-ping" style="animation-delay: 1.1s; animation-duration: 2s"/>
                    <circle cx="250" cy="180" r="10" fill="#FF385C"/>
                    <circle cx="250" cy="180" r="4" fill="white"/>
                    <text x="250" y="210" text-anchor="middle" font-size="14" font-weight="500" fill="#374151">Брюссель</text>
                </g>

                <g class="animate-fade-in" style="animation-delay: 0.8s">
                    <circle cx="380" cy="160" r="30" fill="none" stroke="#FF385C" stroke-width="2" opacity="0" class="animate-ping" style="animation-delay: 1.3s"/>
                    <circle cx="380" cy="160" r="20" fill="none" stroke="#FF385C" stroke-width="1.5" opacity="0.3" class="animate-ping" style="animation-delay: 1.5s; animation-duration: 2s"/>
                    <circle cx="380" cy="160" r="10" fill="#FF385C"/>
                    <circle cx="380" cy="160" r="4" fill="white"/>
                    <text x="380" y="190" text-anchor="middle" font-size="14" font-weight="500" fill="#374151">Прага</text>
                </g>

                <g class="animate-fade-in" style="animation-delay: 1.2s">
                    <circle cx="520" cy="170" r="30" fill="none" stroke="#FF385C" stroke-width="2" opacity="0" class="animate-ping" style="animation-delay: 1.7s"/>
                    <circle cx="520" cy="170" r="20" fill="none" stroke="#FF385C" stroke-width="1.5" opacity="0.3" class="animate-ping" style="animation-delay: 1.9s; animation-duration: 2s"/>
                    <circle cx="520" cy="170" r="10" fill="#FF385C"/>
                    <circle cx="520" cy="170" r="4" fill="white"/>
                    <text x="520" y="200" text-anchor="middle" font-size="14" font-weight="500" fill="#374151">Амстердам</text>
                </g>

                <circle id="moving-dot-desktop" r="5" fill="#FF385C" style="filter: drop-shadow(0 4px 6px rgba(0,0,0,0.1))"/>
            </svg>
        </div>

        <div class="logo-section">
            <h1 class="logo-title">MST</h1>
            <p class="logo-text" id="dynamic-text"><?php _e('Находим лучшие направления', 'mst-preloader'); ?></p>
        </div>

        <div class="progress-container">
            <div class="progress-bar-wrapper">
                <div class="progress-bar" id="progress-bar"></div>
            </div>
            <div class="progress-text">
                <span><?php _e('Загрузка...', 'mst-preloader'); ?></span>
                <span><span id="progress-percent">0</span>%</span>
            </div>
        </div>
    </div>
    <?php
}
add_action( 'wp_body_open', 'mstp_insert_preloader_in_body', 10 );