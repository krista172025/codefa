<?php
/**
 * Frontend квиза
 * Author: Telegram @l1ghtsun
 * Author URI: https://t.me/l1ghtsun
 */
if(!defined('ABSPATH')) exit;
?>

<div class="mst-quiz-container" data-city="<?php echo esc_attr($atts['city']); ?>" data-quiz='<?php echo esc_attr(json_encode($quiz)); ?>'>
    <div class="mst-quiz-progress">
        <div class="mst-quiz-progress-bar" style="width:0%"></div>
    </div>
    
    <div class="mst-quiz-content">
        <div class="mst-quiz-question-wrapper" style="display:none;">
            <h3 class="mst-quiz-question"></h3>
            <div class="mst-quiz-answers"></div>
        </div>
        
        <div class="mst-quiz-start">
            <h2>Насколько хорошо вы знаете этот город?</h2>
            <p>Пройдите квиз из <?php echo count($quiz); ?> вопросов</p>
            <button class="mst-btn mst-btn-primary mst-quiz-start-btn">Начать квиз</button>
        </div>
        
        <div class="mst-quiz-result" style="display:none;">
            <h2>Результаты</h2>
            <div class="mst-quiz-score"></div>
            <div class="mst-quiz-comparison"></div>
            <button class="mst-btn mst-btn-secondary mst-quiz-restart-btn">Пройти заново</button>
        </div>
    </div>
</div>