<?php
/**
 * Plugin Name: MySuperTour Quiz
 * Description: Квиз/Опросник по городам с процентами прогресса
 * Version: 1.0.0
 * Author: Telegram @l1ghtsun
 * Author URI: https://t.me/l1ghtsun
 */

if(!defined('ABSPATH')) exit;

define('MST_QUIZ_VERSION', '1.0.0');
define('MST_QUIZ_PATH', plugin_dir_path(__FILE__));
define('MST_QUIZ_URL', plugin_dir_url(__FILE__));

class MySuperTour_Quiz {
    private static $instance = null;
    
    public static function instance() {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        add_action('admin_menu', [$this, 'add_admin_menu']);
        add_shortcode('mst_quiz', [$this, 'render_quiz']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
        add_action('wp_ajax_mst_save_quiz_result', [$this, 'save_quiz_result']);
        add_action('wp_ajax_nopriv_mst_save_quiz_result', [$this, 'save_quiz_result']);
        
        register_activation_hook(__FILE__, [$this, 'create_test_quizzes']);
    }
    
    public function add_admin_menu() {
        add_submenu_page(
            'mysupertour-hub',
            'Квизы',
            '📝 Квизы',
            'manage_options',
            'mysupertour-quiz',
            [$this, 'render_admin_page']
        );
    }
    
    public function enqueue_assets() {
        wp_enqueue_style('mst-quiz', MST_QUIZ_URL . 'assets/css/quiz.css', [], MST_QUIZ_VERSION);
        wp_enqueue_script('mst-quiz', MST_QUIZ_URL . 'assets/js/quiz.js', ['jquery'], MST_QUIZ_VERSION, true);
        
        wp_localize_script('mst-quiz', 'mstQuiz', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('mst_quiz_nonce')
        ]);
    }
    
    public function render_admin_page() {
    $cities = ['paris' => 'Париж', 'amsterdam' => 'Амстердам', 'prague' => 'Прага', 'brussels' => 'Брюссель'];
    
    // Сохранение настроек текстов
    if (isset($_POST['mst_save_quiz_texts']) && check_admin_referer('mst_quiz_texts', 'mst_quiz_texts_nonce')) {
        $texts = [
            'result_line1' => sanitize_text_field($_POST['result_line1']),
            'result_line2' => sanitize_text_field($_POST['result_line2']),
            'result_line3' => sanitize_text_field($_POST['result_line3']),
            'restart_button' => sanitize_text_field($_POST['restart_button'])
        ];
        update_option('mst_quiz_texts', $texts);
        echo '<div class="notice notice-success"><p>✅ Тексты сохранены!</p></div>';
    }
    
    // Сохранение квиза
    if (isset($_POST['mst_save_quiz']) && check_admin_referer('mst_quiz_save', 'mst_quiz_nonce')) {
        $city = sanitize_text_field($_POST['city']);
        $questions = [];
        
        if (isset($_POST['questions'])) {
            foreach ($_POST['questions'] as $q) {
                $questions[] = [
                    'question' => sanitize_text_field($q['question']),
                    'answers' => array_map('sanitize_text_field', $q['answers']),
                    'correct' => intval($q['correct'])
                ];
            }
        }
        
        update_option('mst_quiz_' . $city, $questions);
        echo '<div class="notice notice-success"><p>✅ Квиз сохранён!</p></div>';
    }
    
    $current_city = isset($_GET['city']) ? sanitize_text_field($_GET['city']) : 'paris';
    $quiz = get_option('mst_quiz_' . $current_city, []);
    
    $texts = get_option('mst_quiz_texts', [
        'result_line1' => 'Ваш результат',
        'result_line2' => 'Правильных ответов: {score} из {total}',
        'result_line3' => '',
        'restart_button' => 'Пройти заново'
    ]);
    
    include MST_QUIZ_PATH . 'templates/admin-page.php';
	}
    
    public function render_quiz($atts) {
        $atts = shortcode_atts(['city' => 'paris'], $atts);
        $quiz = get_option('mst_quiz_' . $atts['city'], []);
        
        if (empty($quiz)) {
            return '<p>Квиз для этого города ещё не создан.</p>';
        }
        
        ob_start();
        include MST_QUIZ_PATH . 'templates/quiz-frontend.php';
        return ob_get_clean();
    }
    
    public function save_quiz_result() {
        check_ajax_referer('mst_quiz_nonce', 'nonce');
        
        $city = sanitize_text_field($_POST['city']);
        $score = intval($_POST['score']);
        $total = intval($_POST['total']);
        $percentage = round(($score / $total) * 100);
        
        $stats = get_option('mst_quiz_stats_' . $city, []);
        $stats[] = $percentage;
        update_option('mst_quiz_stats_' . $city, $stats);
        
        $better_than = 0;
        foreach ($stats as $s) {
            if ($percentage > $s) $better_than++;
        }
        
        $better_percentage = count($stats) > 1 ? round(($better_than / (count($stats) - 1)) * 100) : 50;
        
        // Получаем настроенные тексты
	$texts = get_option('mst_quiz_texts', [
    'result_line1' => 'Ваш результат',
    'result_line2' => 'Правильных ответов: {score} из {total}',
    'result_line3' => '',
    'restart_button' => 'Пройти заново'
	]);
        
        wp_send_json_success([
            'score' => $score,
            'total' => $total,
            'percentage' => $percentage,
            'better_than' => $better_percentage,
            'texts' => $texts
        ]);
    }
    
    public function create_test_quizzes() {
        $quizzes = [
            'paris' => [
                ['question' => 'В каком году была построена Эйфелева башня?', 'answers' => ['1889', '1900', '1875', '1910'], 'correct' => 0],
                ['question' => 'Какая река протекает через Париж?', 'answers' => ['Темза', 'Сена', 'Рейн', 'Дунай'], 'correct' => 1],
            ],
        ];
        
        foreach ($quizzes as $city => $quiz) {
            if (!get_option('mst_quiz_' . $city)) {
                update_option('mst_quiz_' . $city, $quiz);
            }
        }
    }
}

add_action('plugins_loaded', function() {
    MySuperTour_Quiz::instance();
}, 1);