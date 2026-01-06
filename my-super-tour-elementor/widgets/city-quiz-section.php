<?php
/**
 * City Quiz Section Widget
 * 
 * Interactive quiz about the city with liquid glass design
 */

namespace MySuperTour\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;

if (!defined('ABSPATH')) exit;

class City_Quiz_Section extends Widget_Base {

    public function get_name() {
        return 'mst_city_quiz_section';
    }

    public function get_title() {
        return __('City Quiz Section', 'my-super-tour-elementor');
    }

    public function get_icon() {
        return 'eicon-bullet-list';
    }

    public function get_categories() {
        return ['my-super-tour'];
    }

    protected function register_controls() {
        // Content Section
        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Quiz Settings', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'city_name',
            [
                'label' => __('City Name', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Paris',
            ]
        );

        $this->end_controls_section();

        // Questions Section
        $this->start_controls_section(
            'questions_section',
            [
                'label' => __('Quiz Questions', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'question',
            [
                'label' => __('Question', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXTAREA,
                'default' => 'В каком году была построена Эйфелева башня?',
            ]
        );

        $repeater->add_control(
            'option_1',
            [
                'label' => __('Option 1', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => '1889',
            ]
        );

        $repeater->add_control(
            'option_2',
            [
                'label' => __('Option 2', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => '1900',
            ]
        );

        $repeater->add_control(
            'option_3',
            [
                'label' => __('Option 3', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => '1878',
            ]
        );

        $repeater->add_control(
            'option_4',
            [
                'label' => __('Option 4', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => '1901',
            ]
        );

        $repeater->add_control(
            'correct_answer',
            [
                'label' => __('Correct Answer', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SELECT,
                'default' => '1',
                'options' => [
                    '1' => __('Option 1', 'my-super-tour-elementor'),
                    '2' => __('Option 2', 'my-super-tour-elementor'),
                    '3' => __('Option 3', 'my-super-tour-elementor'),
                    '4' => __('Option 4', 'my-super-tour-elementor'),
                ],
            ]
        );

        $this->add_control(
            'questions',
            [
                'label' => __('Questions', 'my-super-tour-elementor'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'question' => 'В каком году была построена Эйфелева башня?',
                        'option_1' => '1889',
                        'option_2' => '1900',
                        'option_3' => '1878',
                        'option_4' => '1901',
                        'correct_answer' => '1',
                    ],
                    [
                        'question' => 'Какой музей является самым посещаемым в Париже?',
                        'option_1' => 'Музей Орсе',
                        'option_2' => 'Лувр',
                        'option_3' => 'Центр Помпиду',
                        'option_4' => 'Музей Родена',
                        'correct_answer' => '2',
                    ],
                    [
                        'question' => 'Сколько районов (округов) в Париже?',
                        'option_1' => '15',
                        'option_2' => '18',
                        'option_3' => '20',
                        'option_4' => '25',
                        'correct_answer' => '3',
                    ],
                ],
                'title_field' => '{{{ question.substring(0, 40) }}}...',
            ]
        );

        $this->end_controls_section();

        // Result Messages Section
        $this->start_controls_section(
            'results_section',
            [
                'label' => __('Result Messages', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'result_100',
            [
                'label' => __('100% Message', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Превосходно! Вы настоящий знаток! 🏆',
            ]
        );

        $this->add_control(
            'result_80',
            [
                'label' => __('80%+ Message', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Отлично! Вы хорошо знаете город! 🌟',
            ]
        );

        $this->add_control(
            'result_60',
            [
                'label' => __('60%+ Message', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Хорошо! Но есть куда расти 👍',
            ]
        );

        $this->add_control(
            'result_40',
            [
                'label' => __('40%+ Message', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Неплохо! Стоит узнать город получше 📚',
            ]
        );

        $this->add_control(
            'result_0',
            [
                'label' => __('Below 40% Message', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Время открыть город заново! 🗼',
            ]
        );

        $this->end_controls_section();

        // Style Section - Colors
        $this->start_controls_section(
            'style_colors',
            [
                'label' => __('Colors', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'primary_color',
            [
                'label' => __('Primary Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#9b87f5',
            ]
        );

        $this->add_control(
            'secondary_color',
            [
                'label' => __('Secondary Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#FEF7CD',
            ]
        );

        $this->add_control(
            'success_color',
            [
                'label' => __('Success Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#22c55e',
            ]
        );

        $this->add_control(
            'error_color',
            [
                'label' => __('Error Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ef4444',
            ]
        );

        $this->add_control(
            'text_color',
            [
                'label' => __('Text Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#1A1F2C',
            ]
        );

        $this->add_control(
            'muted_text_color',
            [
                'label' => __('Muted Text Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#6B7280',
            ]
        );

        $this->end_controls_section();

        // Style Section - Glass
        $this->start_controls_section(
            'style_glass',
            [
                'label' => __('Liquid Glass', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'glass_bg_color',
            [
                'label' => __('Glass Background', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(255, 255, 255, 0.85)',
            ]
        );

        $this->add_control(
            'glass_border_color',
            [
                'label' => __('Glass Border', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(255, 255, 255, 0.5)',
            ]
        );

        $this->add_control(
            'glass_blur',
            [
                'label' => __('Blur Amount', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 40,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 20,
                ],
            ]
        );

        $this->add_control(
            'card_border_radius',
            [
                'label' => __('Card Border Radius', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 40,
                        'step' => 2,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 24,
                ],
            ]
        );

        $this->end_controls_section();

        // Responsive Section
        $this->start_controls_section(
            'responsive_section',
            [
                'label' => __('Responsive', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'section_padding',
            [
                'label' => __('Section Padding', 'my-super-tour-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .mst-city-quiz' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'default' => [
                    'top' => '64',
                    'right' => '24',
                    'bottom' => '64',
                    'left' => '24',
                    'unit' => 'px',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        
        $city_name = $settings['city_name'];
        
        // Colors
        $primary_color = $settings['primary_color'];
        $secondary_color = $settings['secondary_color'];
        $success_color = $settings['success_color'];
        $error_color = $settings['error_color'];
        $text_color = $settings['text_color'];
        $muted_text = $settings['muted_text_color'];
        
        // Glass
        $glass_bg = $settings['glass_bg_color'];
        $glass_border = $settings['glass_border_color'];
        $glass_blur = $settings['glass_blur']['size'] ?? 20;
        $card_radius = $settings['card_border_radius']['size'] ?? 24;
        
        // Result messages
        $result_messages = [
            100 => $settings['result_100'],
            80 => $settings['result_80'],
            60 => $settings['result_60'],
            40 => $settings['result_40'],
            0 => $settings['result_0'],
        ];
        
        $unique_id = 'mst-quiz-' . uniqid();
        $questions_json = json_encode(array_map(function($q) {
            return [
                'question' => $q['question'],
                'options' => [$q['option_1'], $q['option_2'], $q['option_3'], $q['option_4']],
                'correct' => (int)$q['correct_answer'] - 1,
            ];
        }, $settings['questions']));
        ?>
        
        <style>
            #<?php echo esc_attr($unique_id); ?> .mst-quiz-card {
                background: <?php echo esc_attr($glass_bg); ?>;
                backdrop-filter: blur(<?php echo esc_attr($glass_blur); ?>px);
                -webkit-backdrop-filter: blur(<?php echo esc_attr($glass_blur); ?>px);
                border: 1px solid <?php echo esc_attr($glass_border); ?>;
                border-radius: <?php echo esc_attr($card_radius); ?>px;
                padding: 3rem;
                max-width: 900px;
                margin: 0 auto;
                box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
            }
            
            #<?php echo esc_attr($unique_id); ?> .mst-quiz-progress {
                margin-bottom: 2rem;
                height: auto !important;  
                min-height: 1px !important;
            }
            
            #<?php echo esc_attr($unique_id); ?> .mst-quiz-progress-header {
                display: flex !important;  
                justify-content: space-between;
                align-items: center;
                margin-bottom: 0.75rem;

                visibility: visible !important;
                opacity: 1 !important;
                height: auto !important;
            }
            
            #<?php echo esc_attr($unique_id); ?> .mst-quiz-progress-text {
                font-size: 0.875rem;
                font-weight: 600;
                color: <?php echo esc_attr($muted_text); ?>;
            }
            
            #<?php echo esc_attr($unique_id); ?> .mst-quiz-score-badge {
                background: <?php echo esc_attr($secondary_color); ?>;
                color: <?php echo esc_attr($text_color); ?>;
                padding: 0.25rem 0.75rem;
                border-radius: 999px;
                font-size: 0.875rem;
                font-weight: 600;
            }
            
            #<?php echo esc_attr($unique_id); ?> .mst-quiz-progress-bar {
                height: 8px;
                background: rgba(0, 0, 0, 0.1);
                border-radius: 999px;
                overflow: hidden;
            }
            
            #<?php echo esc_attr($unique_id); ?> .mst-quiz-progress-fill {
                height: 100%;
                background: linear-gradient(90deg, <?php echo esc_attr($primary_color); ?>, <?php echo esc_attr($secondary_color); ?>);
                border-radius: 999px;
                transition: width 0.5s ease;
            }
            
            #<?php echo esc_attr($unique_id); ?> .mst-quiz-question {
                font-size: 1.75rem;
                font-weight: 700;
                color: <?php echo esc_attr($text_color); ?>;
                margin-bottom: 2rem;
                line-height: 1.4;
            }
            
            #<?php echo esc_attr($unique_id); ?> .mst-quiz-options {
                display: grid;
                grid-template-columns: repeat(2, 1fr);
                gap: 1rem;
            }
            
            @media (max-width: 640px) {
                #<?php echo esc_attr($unique_id); ?> .mst-quiz-options {
                    grid-template-columns: 1fr;
                }
                
                #<?php echo esc_attr($unique_id); ?> .mst-quiz-question {
                    font-size: 1.25rem;
                }
                
                #<?php echo esc_attr($unique_id); ?> .mst-quiz-card {
                    padding: 1.5rem;
                }
                
                #<?php echo esc_attr($unique_id); ?> .mst-quiz-progress-header {
                    display: flex !important;
                    flex-direction: row;
                    justify-content: space-between;
                    align-items: center;
                    margin-bottom: 0.75rem;
                }
                
                #<?php echo esc_attr($unique_id); ?> .mst-quiz-progress-text {
                    font-size: 0.75rem;
                }
                
                #<?php echo esc_attr($unique_id); ?> .mst-quiz-score-badge {
                    font-size: 0.75rem;
                    padding: 0.2rem 0.5rem;
                }
            }

            
            #<?php echo esc_attr($unique_id); ?> .mst-quiz-option {
                background: rgba(255, 255, 255, 0.6);
                backdrop-filter: blur(10px);
                border: 1px solid rgba(255, 255, 255, 0.4);
                border-radius: 16px;
                padding: 1.5rem;
                cursor: pointer;
                transition: all 0.3s ease;
                text-align: left;
                font-size: 1.125rem;
                font-weight: 500;
                color: <?php echo esc_attr($text_color); ?>;
            }
            
            #<?php echo esc_attr($unique_id); ?> .mst-quiz-option:hover:not(.disabled) {
                transform: scale(1.02);
                box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.2);
            }
            
            #<?php echo esc_attr($unique_id); ?> .mst-quiz-option.correct {
                background: <?php echo esc_attr($success_color); ?>20;
                border-color: <?php echo esc_attr($success_color); ?>50;
            }
            
            #<?php echo esc_attr($unique_id); ?> .mst-quiz-option.wrong {
                background: <?php echo esc_attr($error_color); ?>20;
                border-color: <?php echo esc_attr($error_color); ?>50;
            }
            
            #<?php echo esc_attr($unique_id); ?> .mst-quiz-option.disabled {
                cursor: not-allowed;
            }
            
            #<?php echo esc_attr($unique_id); ?> .mst-quiz-result {
                text-align: center;
                display: none;
            }
            
            #<?php echo esc_attr($unique_id); ?> .mst-quiz-result.active {
                display: block;
            }
            
            #<?php echo esc_attr($unique_id); ?> .mst-quiz-content.hidden {
                display: none;
            }
            
            #<?php echo esc_attr($unique_id); ?> .mst-result-trophy {
                width: 64px;
                height: 64px;
                color: <?php echo esc_attr($secondary_color); ?>;
                margin: 0 auto 1.5rem;
                animation: bounce 1s ease infinite;
            }
            
            @keyframes bounce {
                0%, 100% { transform: translateY(0); }
                50% { transform: translateY(-10px); }
            }
            
            #<?php echo esc_attr($unique_id); ?> .mst-result-title {
                font-size: 2rem;
                font-weight: 700;
                color: <?php echo esc_attr($text_color); ?>;
                margin-bottom: 1rem;
            }
            
            #<?php echo esc_attr($unique_id); ?> .mst-result-message {
                font-size: 1.25rem;
                color: <?php echo esc_attr($muted_text); ?>;
                margin-bottom: 2rem;
            }
            
            #<?php echo esc_attr($unique_id); ?> .mst-result-score-box {
                background: rgba(255, 255, 255, 0.6);
                backdrop-filter: blur(10px);
                border-radius: 20px;
                padding: 2rem;
                display: inline-block;
                margin-bottom: 2rem;
            }
            
            #<?php echo esc_attr($unique_id); ?> .mst-result-score {
                font-size: 4rem;
                font-weight: 700;
                color: <?php echo esc_attr($primary_color); ?>;
            }
            
            #<?php echo esc_attr($unique_id); ?> .mst-result-label {
                font-size: 0.875rem;
                color: <?php echo esc_attr($muted_text); ?>;
            }
            
            #<?php echo esc_attr($unique_id); ?> .mst-quiz-buttons {
                display: flex;
                flex-wrap: wrap;
                gap: 1rem;
                justify-content: center;
            }
            
            #<?php echo esc_attr($unique_id); ?> .mst-quiz-btn {
                padding: 1rem 2rem;
                border-radius: 12px;
                font-size: 1rem;
                font-weight: 600;
                cursor: pointer;
                transition: all 0.3s ease;
                border: none;
            }
            
            #<?php echo esc_attr($unique_id); ?> .mst-quiz-btn-primary {
                background: <?php echo esc_attr($primary_color); ?>;
                color: white;
            }
            
            #<?php echo esc_attr($unique_id); ?> .mst-quiz-btn-primary:hover {
                filter: brightness(0.9);
            }
            
            #<?php echo esc_attr($unique_id); ?> .mst-quiz-btn-secondary {
                background: rgba(255, 255, 255, 0.6);
                backdrop-filter: blur(10px);
                color: <?php echo esc_attr($text_color); ?>;
            }
            
            #<?php echo esc_attr($unique_id); ?> .mst-quiz-btn-secondary:hover {
                background: rgba(255, 255, 255, 0.8);
            }
        </style>
        
        <section id="<?php echo esc_attr($unique_id); ?>" class="mst-city-quiz">
            <div class="mst-quiz-card">
                <div class="mst-quiz-content">
                    <div class="mst-quiz-progress">
                        <div class="mst-quiz-progress-header">
                            <span class="mst-quiz-progress-text">Вопрос <span class="current-q">1</span> из <span class="total-q"><?php echo count($settings['questions']); ?></span></span>
                            <span class="mst-quiz-score-badge">Счет: <span class="quiz-score">0</span></span>
                        </div>
                        <div class="mst-quiz-progress-bar">
                            <div class="mst-quiz-progress-fill" style="width: 0%"></div>
                        </div>
                    </div>
                    
                    <h2 class="mst-quiz-question"></h2>
                    
                    <div class="mst-quiz-options"></div>
                </div>
                
                <div class="mst-quiz-result">
                    <svg class="mst-result-trophy" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"/><path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"/><path d="M4 22h16"/><path d="M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20.24 7 22"/><path d="M14 14.66V17c0 .55.47.98.97 1.21C16.15 18.75 17 20.24 17 22"/><path d="M18 2H6v7a6 6 0 0 0 12 0V2Z"/></svg>
                    <h2 class="mst-result-title">Тест завершен!</h2>
                    <p class="mst-result-message"></p>
                    <div class="mst-result-score-box">
                        <div class="mst-result-score"><span class="final-score">0</span>/<span class="max-score"><?php echo count($settings['questions']); ?></span></div>
                        <div class="mst-result-label">правильных ответов</div>
                    </div>
                    <div class="mst-quiz-buttons">
                        <button class="mst-quiz-btn mst-quiz-btn-primary restart-quiz">Пройти еще раз</button>
                        <button class="mst-quiz-btn mst-quiz-btn-secondary close-quiz">Вернуться к городу</button>
                    </div>
                </div>
            </div>
        </section>
        
        <script>
        (function() {
            const container = document.getElementById('<?php echo esc_js($unique_id); ?>');
            const questions = <?php echo $questions_json; ?>;
            const resultMessages = <?php echo json_encode($result_messages); ?>;
            
            let currentQuestion = 0;
            let score = 0;
            let answered = false;
            
            const content = container.querySelector('.mst-quiz-content');
            const result = container.querySelector('.mst-quiz-result');
            const questionEl = container.querySelector('.mst-quiz-question');
            const optionsEl = container.querySelector('.mst-quiz-options');
            const progressFill = container.querySelector('.mst-quiz-progress-fill');
            const currentQEl = container.querySelector('.current-q');
            const scoreEl = container.querySelector('.quiz-score');
            const finalScoreEl = container.querySelector('.final-score');
            const resultMsgEl = container.querySelector('.mst-result-message');
            
            function showQuestion() {
                answered = false;
                const q = questions[currentQuestion];
                questionEl.textContent = q.question;
                optionsEl.innerHTML = '';
                
                q.options.forEach((option, index) => {
                    const btn = document.createElement('button');
                    btn.className = 'mst-quiz-option';
                    btn.textContent = option;
                    btn.onclick = () => selectAnswer(index);
                    optionsEl.appendChild(btn);
                });
                
                currentQEl.textContent = currentQuestion + 1;
                progressFill.style.width = ((currentQuestion + 1) / questions.length * 100) + '%';
            }
            
            function selectAnswer(index) {
                if (answered) return;
                answered = true;
                
                const options = optionsEl.querySelectorAll('.mst-quiz-option');
                const correct = questions[currentQuestion].correct;
                
                options.forEach((opt, i) => {
                    opt.classList.add('disabled');
                    if (i === correct) opt.classList.add('correct');
                    if (i === index && i !== correct) opt.classList.add('wrong');
                });
                
                if (index === correct) {
                    score++;
                    scoreEl.textContent = score;
                }
                
                setTimeout(() => {
                    if (currentQuestion < questions.length - 1) {
                        currentQuestion++;
                        showQuestion();
                    } else {
                        showResult();
                    }
                }, 1500);
            }
            
            function showResult() {
                content.classList.add('hidden');
                result.classList.add('active');
                
                finalScoreEl.textContent = score;
                const percentage = (score / questions.length) * 100;
                
                let message = resultMessages['0'];
                if (percentage === 100) message = resultMessages['100'];
                else if (percentage >= 80) message = resultMessages['80'];
                else if (percentage >= 60) message = resultMessages['60'];
                else if (percentage >= 40) message = resultMessages['40'];
                
                resultMsgEl.textContent = message;
            }
            
            function restart() {
                currentQuestion = 0;
                score = 0;
                scoreEl.textContent = 0;
                content.classList.remove('hidden');
                result.classList.remove('active');
                showQuestion();
            }
            
            container.querySelector('.restart-quiz').onclick = restart;
            container.querySelector('.close-quiz').onclick = () => {
                container.style.display = 'none';
            };
            
            showQuestion();
        })();
        </script>
        
        <?php
    }

    protected function content_template() {}
}
