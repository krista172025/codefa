<?php
/**
 * Админка квизов
 * Author: Telegram @l1ghtsun
 * Author URI: https://t.me/l1ghtsun
 */
if(!defined('ABSPATH')) exit;
?>

<div class="wrap mst-hub-wrap">
    <div class="mst-hub-header">
        <h1 class="mst-hub-title">📝 Управление Квизами</h1>
    </div>
    
    <!-- ВКЛАДКИ -->
    <h2 class="nav-tab-wrapper">
        <a href="?page=mysupertour-quiz&tab=texts" class="nav-tab <?php echo (!isset($_GET['tab']) || $_GET['tab'] === 'texts') ? 'nav-tab-active' : ''; ?>">📝 Тексты результатов</a>
        <a href="?page=mysupertour-quiz&tab=quiz" class="nav-tab <?php echo (isset($_GET['tab']) && $_GET['tab'] === 'quiz') ? 'nav-tab-active' : ''; ?>">🎮 Квизы</a>
    </h2>
    
    <?php if (!isset($_GET['tab']) || $_GET['tab'] === 'texts'): ?>
        <!-- ВКЛАДКА: ТЕКСТЫ -->
        <div class="mst-panel" style="margin-top:20px;">
            <h2>📝 Настройка текстов результатов</h2>
            <p style="color:#666;">Здесь вы можете настроить тексты, которые видят пользователи после прохождения квиза.</p>
            
            <form method="post">
                <?php wp_nonce_field('mst_quiz_texts', 'mst_quiz_texts_nonce'); ?>
                
                <div class="mst-form-group">
                    <label class="mst-form-label">Строка 1 (результат)</label>
                    <input type="text" name="result_line1" class="mst-form-control" value="<?php echo esc_attr($texts['result_line1']); ?>" placeholder="Ваш результат" required>
                    <p class="description">Например: "Ваш результат" или "Поздравляем!"</p>
                </div>
                
                <div class="mst-form-group">
                    <label class="mst-form-label">Строка 2 (детали)</label>
                    <input type="text" name="result_line2" class="mst-form-control" value="<?php echo esc_attr($texts['result_line2']); ?>" placeholder="Правильных ответов: {score} из {total}" required>
                    <p class="description">Используйте {score} и {total}. Например: "Правильных ответов: {score} из {total}"</p>
                </div>
                
                <div class="mst-form-group">
                    <label class="mst-form-label">Строка 3 (дополнительно)</label>
                    <input type="text" name="result_line3" class="mst-form-control" value="<?php echo esc_attr($texts['result_line3']); ?>" placeholder="Отличный результат!">
                    <p class="description">Любой дополнительный текст (необязательно)</p>
                </div>
                
                <div class="mst-form-group">
                    <label class="mst-form-label">Текст кнопки</label>
                    <input type="text" name="restart_button" class="mst-form-control" value="<?php echo esc_attr($texts['restart_button']); ?>" placeholder="Пройти заново" required>
                </div>
                
                <button type="submit" name="mst_save_quiz_texts" class="mst-btn mst-btn-primary">💾 Сохранить тексты</button>
            </form>
        </div>
        
    <?php else: ?>
        <!-- ВКЛАДКА: КВИЗЫ -->
        <!-- Выбор города -->
        <div style="background:#fff;padding:20px;border-radius:12px;margin:20px 0;">
            <h3>Выберите город</h3>
            <div style="display:flex;gap:10px;flex-wrap:wrap;">
                <?php foreach ($cities as $slug => $name): ?>
                    <a href="<?php echo admin_url('admin.php?page=mysupertour-quiz&tab=quiz&city=' . $slug); ?>" 
                       class="mst-btn <?php echo $current_city === $slug ? 'mst-btn-primary' : 'mst-btn-secondary'; ?>">
                        <?php echo $name; ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
        
        <div class="mst-panel">
            <h2>📋 Вопросы для города: <?php echo $cities[$current_city]; ?></h2>
            
            <form method="post" action="" id="quiz-form">
                <?php wp_nonce_field('mst_quiz_save', 'mst_quiz_nonce'); ?>
                <input type="hidden" name="city" value="<?php echo esc_attr($current_city); ?>">
                
                <div id="quiz-questions">
                    <?php if (!empty($quiz)): ?>
                        <?php foreach ($quiz as $index => $q): ?>
                            <div class="quiz-question-block" data-index="<?php echo $index; ?>" style="background:#f9f9f9;padding:20px;border-radius:12px;margin-bottom:20px;border:2px solid #e0e0e0;">
                                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;">
                                    <h4 style="margin:0;">Вопрос <?php echo $index + 1; ?></h4>
                                    <button type="button" class="mst-btn mst-btn-danger remove-question" style="padding:6px 12px;font-size:12px;">🗑️ Удалить</button>
                                </div>
                                
                                <div class="mst-form-group">
                                    <label class="mst-form-label">Текст вопроса</label>
                                    <input type="text" name="questions[<?php echo $index; ?>][question]" class="mst-form-control" value="<?php echo esc_attr($q['question']); ?>" required>
                                </div>
                                
                                <?php for ($i = 0; $i < 4; $i++): ?>
                                    <div class="mst-form-group" style="display:flex;gap:12px;align-items:center;">
                                        <label style="display:flex;align-items:center;gap:8px;margin:0;">
                                            <input type="radio" name="questions[<?php echo $index; ?>][correct]" value="<?php echo $i; ?>" <?php checked($q['correct'], $i); ?> required>
                                            <span>Ответ <?php echo $i + 1; ?>:</span>
                                        </label>
                                        <input type="text" name="questions[<?php echo $index; ?>][answers][<?php echo $i; ?>]" class="mst-form-control" value="<?php echo esc_attr($q['answers'][$i] ?? ''); ?>" placeholder="Введите вариант ответа" required style="flex:1;">
                                    </div>
                                <?php endfor; ?>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p style="color:#999;text-align:center;padding:40px;">Нет вопросов. Добавьте первый!</p>
                    <?php endif; ?>
                </div>
                
                <div style="display:flex;gap:12px;margin-top:20px;">
                    <button type="button" id="add-question" class="mst-btn mst-btn-secondary">➕ Добавить вопрос</button>
                    <button type="submit" name="mst_save_quiz" class="mst-btn mst-btn-primary">💾 Сохранить квиз</button>
                </div>
            </form>
            
            <div style="margin-top:20px;padding:16px;background:#e8f5f1;border-radius:12px;">
                <strong>📝 Шорткод для вставки:</strong><br>
                <code style="background:#fff;padding:8px 12px;border-radius:6px;display:inline-block;margin-top:8px;">[mst_quiz city="<?php echo $current_city; ?>"]</code>
            </div>
        </div>
    <?php endif; ?>
</div>

<script>
jQuery(document).ready(function($) {
    let questionIndex = <?php echo count($quiz); ?>;
    
    $('#add-question').on('click', function() {
        const html = `
            <div class="quiz-question-block" data-index="${questionIndex}" style="background:#f9f9f9;padding:20px;border-radius:12px;margin-bottom:20px;border:2px solid #e0e0e0;animation:fadeIn 0.3s;">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;">
                    <h4 style="margin:0;">Вопрос ${questionIndex + 1}</h4>
                    <button type="button" class="mst-btn mst-btn-danger remove-question" style="padding:6px 12px;font-size:12px;">🗑️ Удалить</button>
                </div>
                
                <div class="mst-form-group">
                    <label class="mst-form-label">Текст вопроса</label>
                    <input type="text" name="questions[${questionIndex}][question]" class="mst-form-control" placeholder="Введите вопрос" required>
                </div>
                
                ${[0,1,2,3].map(i => `
                    <div class="mst-form-group" style="display:flex;gap:12px;align-items:center;">
                        <label style="display:flex;align-items:center;gap:8px;margin:0;">
                            <input type="radio" name="questions[${questionIndex}][correct]" value="${i}" ${i === 0 ? 'checked' : ''} required>
                            <span>Ответ ${i + 1}:</span>
                        </label>
                        <input type="text" name="questions[${questionIndex}][answers][${i}]" class="mst-form-control" placeholder="Введите вариант ответа" required style="flex:1;">
                    </div>
                `).join('')}
            </div>
        `;
        
        $('#quiz-questions').append(html);
        questionIndex++;
        updateQuestionNumbers();
    });
    
    $(document).on('click', '.remove-question', function() {
        if (confirm('Удалить этот вопрос?')) {
            $(this).closest('.quiz-question-block').fadeOut(300, function() {
                $(this).remove();
                updateQuestionNumbers();
            });
        }
    });
    
    function updateQuestionNumbers() {
        $('.quiz-question-block').each(function(index) {
            $(this).find('h4').text('Вопрос ' + (index + 1));
        });
    }
});
</script>

<style>
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-20px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>