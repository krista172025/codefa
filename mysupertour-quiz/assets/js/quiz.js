jQuery(function($) {
    const quizContainer = $('.mst-quiz-container');
    if (!quizContainer.length) return;

    let currentQuestion = 0;
    let score = 0;
    let quiz = [];
    const city = quizContainer.data('city');

    try {
        quiz = JSON.parse(quizContainer.attr('data-quiz'));
    } catch(e) {
        console.error('Ошибка загрузки квиза:', e);
        return;
    }

    const totalQuestions = quiz.length;

    $('.mst-quiz-start-btn').on('click', function() {
        $('.mst-quiz-start').hide();
        $('.mst-quiz-question-wrapper').show();
        loadQuestion();
    });

    $(document).on('click', '.mst-quiz-restart-btn', function() {
        location.reload();
    });

    function loadQuestion() {
        if (currentQuestion >= totalQuestions) {
            showResults();
            return;
        }

        const q = quiz[currentQuestion];
        $('.mst-quiz-question').text(q.question);
        
        // РАНДОМИЗАЦИЯ ОТВЕТОВ
        const answersWithIndex = q.answers.map((answer, index) => ({
            text: answer,
            originalIndex: index,
            isCorrect: index === q.correct
        }));
        
        // Перемешиваем (Fisher-Yates)
        for (let i = answersWithIndex.length - 1; i > 0; i--) {
            const j = Math.floor(Math.random() * (i + 1));
            [answersWithIndex[i], answersWithIndex[j]] = [answersWithIndex[j], answersWithIndex[i]];
        }
        
        const answersHtml = answersWithIndex.map((item) => 
            `<button class="mst-answer-btn" data-correct="${item.isCorrect}">${item.text}</button>`
        ).join('');
        
        $('.mst-quiz-answers').html(answersHtml);
        updateProgress();
    }

    function updateProgress() {
        const progress = ((currentQuestion + 1) / totalQuestions) * 100;
        $('.mst-quiz-progress-bar').css('width', progress + '%');
    }

    $(document).on('click', '.mst-answer-btn', function() {
        const $btn = $(this);
        const isCorrect = $btn.attr('data-correct') === 'true';

        $('.mst-answer-btn').prop('disabled', true);
        
        if (isCorrect) {
            $btn.addClass('correct');
            score++;
        } else {
            $btn.addClass('incorrect');
            $('.mst-answer-btn').each(function() {
                if ($(this).attr('data-correct') === 'true') {
                    $(this).addClass('correct');
                }
            });
        }

        setTimeout(function() {
            currentQuestion++;
            if (currentQuestion < totalQuestions) {
                loadQuestion();
            } else {
                showResults();
            }
        }, 1500);
    });

    function showResults() {
        const percentage = Math.round((score / totalQuestions) * 100);
        
        $.ajax({
            url: mstQuiz.ajax_url,
            type: 'POST',
            data: {
                action: 'mst_save_quiz_result',
                nonce: mstQuiz.nonce,
                city: city,
                score: score,
                total: totalQuestions
            },
            success: function(response) {
                if (response.success) {
                    const data = response.data;
                    const texts = data.texts;
                    
                    let line2 = texts.result_line2.replace('{score}', data.score).replace('{total}', data.total);
                    
                    let resultHtml = `<div class="mst-result-score">${data.percentage}%</div>`;
                    resultHtml += `<div class="mst-result-text">${texts.result_line1}</div>`;
                    resultHtml += `<div class="mst-result-text">${line2}</div>`;
                    if (texts.result_line3) {
                        resultHtml += `<div class="mst-result-stats"><p>${texts.result_line3}</p></div>`;
                    }
                    
                    $('.mst-quiz-question-wrapper').hide();
                    $('.mst-quiz-result').show();
                    $('.mst-quiz-score').html(resultHtml);
                    $('.mst-quiz-comparison').html('');
                    $('.mst-quiz-restart-btn').text(texts.restart_button);
                }
            }
        });
    }
});