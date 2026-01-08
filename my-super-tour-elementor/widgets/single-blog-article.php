<?php
namespace MST_Elementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;

if (!defined('ABSPATH')) exit;

/**
 * Single Blog Article Widget - Liquid Glass Design (Extended)
 * Берет данные из WP-поста, привязанного к товару через WooCommerce интеграцию
 * Работает на страницах записей (post) или товаров (product)
 * 
 * НОВЫЕ БЛОКИ:
 * - Table of Contents (Содержание)
 * - Author Bio (Об авторе)
 * - Video Section (Видео)
 * - Tips/Highlights (Советы)
 * - Timeline (Хронология)
 * - Related Products (Связанные товары)
 * - Share Buttons (Поделиться)
 * - Checklist (Чек-лист)
 * - Quote Block (Цитата)
 */
class Single_Blog_Article extends Widget_Base {

    public function get_name() {
        return 'mst-single-blog-article';
    }

    public function get_title() {
        return __('Blog Article (Liquid Glass)', 'my-super-tour-elementor');
    }

    public function get_icon() {
        return 'eicon-post-content';
    }

    public function get_categories() {
        return ['my-super-tour'];
    }

    public function get_keywords() {
        return ['blog', 'article', 'post', 'liquid', 'glass', 'content'];
    }

    protected function register_controls() {
        // =============================================
        // CONTENT SOURCE SECTION
        // =============================================
        $this->start_controls_section(
            'section_source',
            [
                'label' => __('Источник контента', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'content_source',
            [
                'label' => __('Источник', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SELECT,
                'default' => 'auto',
                'options' => [
                    'auto' => __('Авто (текущий пост)', 'my-super-tour-elementor'),
                    'product_linked' => __('Пост, привязанный к товару (WooCommerce)', 'my-super-tour-elementor'),
                    'manual' => __('Указать Post ID', 'my-super-tour-elementor'),
                ],
            ]
        );

        $this->add_control(
            'post_id',
            [
                'label' => __('Post ID', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'description' => __('Введите ID поста или оставьте пустым для текущего', 'my-super-tour-elementor'),
                'condition' => ['content_source' => 'manual'],
            ]
        );

        $this->end_controls_section();

        // =============================================
        // CONTENT SECTIONS
        // =============================================
        $this->start_controls_section(
            'section_content',
            [
                'label' => __('Секции контента', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'show_featured_image',
            [
                'label' => __('Показывать обложку', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_title',
            [
                'label' => __('Показывать заголовок', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_meta',
            [
                'label' => __('Показывать мета (дата/автор)', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_excerpt',
            [
                'label' => __('Показывать вступление', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_content',
            [
                'label' => __('Показывать контент', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_categories',
            [
                'label' => __('Показывать категории', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_tags',
            [
                'label' => __('Показывать теги', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->end_controls_section();

        // =============================================
        // TABLE OF CONTENTS
        // =============================================
        $this->start_controls_section(
            'section_toc',
            [
                'label' => __('📑 Содержание (TOC)', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'show_toc',
            [
                'label' => __('Показывать содержание', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
            ]
        );

        $this->add_control(
            'toc_title',
            [
                'label' => __('Заголовок', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Содержание статьи', 'my-super-tour-elementor'),
                'condition' => ['show_toc' => 'yes'],
            ]
        );

        $this->add_control(
            'toc_heading_levels',
            [
                'label' => __('Уровни заголовков', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SELECT,
                'default' => 'h2',
                'options' => [
                    'h2' => 'H2',
                    'h2h3' => 'H2, H3',
                    'h2h3h4' => 'H2, H3, H4',
                ],
                'condition' => ['show_toc' => 'yes'],
            ]
        );

        $this->end_controls_section();

        // =============================================
        // AUTHOR BIO
        // =============================================
        $this->start_controls_section(
            'section_author',
            [
                'label' => __('👤 Об авторе', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'show_author_bio',
            [
                'label' => __('Показывать блок автора', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
            ]
        );

        $this->add_control(
            'author_bio_title',
            [
                'label' => __('Заголовок блока', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Об авторе', 'my-super-tour-elementor'),
                'condition' => ['show_author_bio' => 'yes'],
            ]
        );

        $this->end_controls_section();

        // =============================================
        // VIDEO SECTION
        // =============================================
        $this->start_controls_section(
            'section_video',
            [
                'label' => __('🎬 Видео', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'show_video',
            [
                'label' => __('Показывать видео', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
            ]
        );

        $this->add_control(
            'video_title',
            [
                'label' => __('Заголовок секции', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Смотреть видео', 'my-super-tour-elementor'),
                'condition' => ['show_video' => 'yes'],
            ]
        );

        $this->add_control(
            'video_url',
            [
                'label' => __('URL видео', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'placeholder' => 'https://youtube.com/watch?v=...',
                'description' => __('YouTube, Vimeo или прямая ссылка', 'my-super-tour-elementor'),
                'condition' => ['show_video' => 'yes'],
            ]
        );

        $this->add_control(
            'video_description',
            [
                'label' => __('Описание видео', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXTAREA,
                'rows' => 2,
                'condition' => ['show_video' => 'yes'],
            ]
        );

        $this->end_controls_section();

        // =============================================
        // TIPS / HIGHLIGHTS
        // =============================================
        $this->start_controls_section(
            'section_tips',
            [
                'label' => __('💡 Советы / Подсказки', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'show_tips',
            [
                'label' => __('Показывать блок советов', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
            ]
        );

        $this->add_control(
            'tips_title',
            [
                'label' => __('Заголовок блока', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Полезные советы', 'my-super-tour-elementor'),
                'condition' => ['show_tips' => 'yes'],
            ]
        );

        $this->add_control(
            'tips_icon',
            [
                'label' => __('Иконка', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SELECT,
                'default' => 'bulb',
                'options' => [
                    'bulb' => '💡 Лампочка',
                    'star' => '⭐ Звезда',
                    'check' => '✅ Галочка',
                    'info' => 'ℹ️ Инфо',
                    'fire' => '🔥 Огонь',
                    'pin' => '📌 Пин',
                ],
                'condition' => ['show_tips' => 'yes'],
            ]
        );

        $tips_repeater = new Repeater();

        $tips_repeater->add_control(
            'tip_text',
            [
                'label' => __('Совет', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXTAREA,
                'rows' => 2,
                'default' => 'Текст совета...',
            ]
        );

        $this->add_control(
            'tips_items',
            [
                'label' => __('Советы', 'my-super-tour-elementor'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $tips_repeater->get_controls(),
                'default' => [],
                'title_field' => '{{{ tip_text.substring(0, 40) }}}...',
                'condition' => ['show_tips' => 'yes'],
            ]
        );

        $this->end_controls_section();

        // =============================================
        // TIMELINE
        // =============================================
        $this->start_controls_section(
            'section_timeline',
            [
                'label' => __('📅 Хронология / Timeline', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'show_timeline',
            [
                'label' => __('Показывать таймлайн', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
            ]
        );

        $this->add_control(
            'timeline_title',
            [
                'label' => __('Заголовок', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Как проходит', 'my-super-tour-elementor'),
                'condition' => ['show_timeline' => 'yes'],
            ]
        );

        $timeline_repeater = new Repeater();

        $timeline_repeater->add_control(
            'time',
            [
                'label' => __('Время / Номер', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => '09:00',
            ]
        );

        $timeline_repeater->add_control(
            'event_title',
            [
                'label' => __('Заголовок', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Событие',
            ]
        );

        $timeline_repeater->add_control(
            'event_description',
            [
                'label' => __('Описание', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXTAREA,
                'rows' => 2,
            ]
        );

        $this->add_control(
            'timeline_items',
            [
                'label' => __('Этапы', 'my-super-tour-elementor'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $timeline_repeater->get_controls(),
                'default' => [],
                'title_field' => '{{{ time }}} - {{{ event_title }}}',
                'condition' => ['show_timeline' => 'yes'],
            ]
        );

        $this->end_controls_section();

        // =============================================
        // CHECKLIST
        // =============================================
        $this->start_controls_section(
            'section_checklist',
            [
                'label' => __('☑️ Чек-лист', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'show_checklist',
            [
                'label' => __('Показывать чек-лист', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
            ]
        );

        $this->add_control(
            'checklist_title',
            [
                'label' => __('Заголовок', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Что взять с собой', 'my-super-tour-elementor'),
                'condition' => ['show_checklist' => 'yes'],
            ]
        );

        $checklist_repeater = new Repeater();

        $checklist_repeater->add_control(
            'item_text',
            [
                'label' => __('Пункт', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Пункт списка',
            ]
        );

        $checklist_repeater->add_control(
            'is_important',
            [
                'label' => __('Важный', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
            ]
        );

        $this->add_control(
            'checklist_items',
            [
                'label' => __('Пункты', 'my-super-tour-elementor'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $checklist_repeater->get_controls(),
                'default' => [],
                'title_field' => '{{{ item_text }}}',
                'condition' => ['show_checklist' => 'yes'],
            ]
        );

        $this->end_controls_section();

        // =============================================
        // QUOTE BLOCK
        // =============================================
        $this->start_controls_section(
            'section_quote',
            [
                'label' => __('💬 Цитата', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'show_quote',
            [
                'label' => __('Показывать цитату', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
            ]
        );

        $this->add_control(
            'quote_text',
            [
                'label' => __('Текст цитаты', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXTAREA,
                'rows' => 3,
                'default' => 'Текст цитаты...',
                'condition' => ['show_quote' => 'yes'],
            ]
        );

        $this->add_control(
            'quote_author',
            [
                'label' => __('Автор', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => '',
                'condition' => ['show_quote' => 'yes'],
            ]
        );

        $this->end_controls_section();

        // =============================================
        // RELATED PRODUCTS
        // =============================================
        $this->start_controls_section(
            'section_related',
            [
                'label' => __('🛍️ Связанные товары', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'show_related_products',
            [
                'label' => __('Показывать связанные товары', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
            ]
        );

        $this->add_control(
            'related_title',
            [
                'label' => __('Заголовок', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Рекомендуемые туры', 'my-super-tour-elementor'),
                'condition' => ['show_related_products' => 'yes'],
            ]
        );

        $this->add_control(
            'related_products_ids',
            [
                'label' => __('ID товаров (через запятую)', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'placeholder' => '123, 456, 789',
                'description' => __('Оставьте пустым для авто-выбора из той же категории', 'my-super-tour-elementor'),
                'condition' => ['show_related_products' => 'yes'],
            ]
        );

        $this->add_control(
            'related_count',
            [
                'label' => __('Количество', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SELECT,
                'default' => '3',
                'options' => ['2' => '2', '3' => '3', '4' => '4'],
                'condition' => ['show_related_products' => 'yes'],
            ]
        );

        $this->end_controls_section();

        // =============================================
        // SHARE BUTTONS
        // =============================================
        $this->start_controls_section(
            'section_share',
            [
                'label' => __('🔗 Поделиться', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'show_share',
            [
                'label' => __('Показывать кнопки "Поделиться"', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'share_networks',
            [
                'label' => __('Соцсети', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SELECT2,
                'multiple' => true,
                'default' => ['telegram', 'whatsapp', 'vk'],
                'options' => [
                    'telegram' => 'Telegram',
                    'whatsapp' => 'WhatsApp',
                    'vk' => 'VK',
                    'facebook' => 'Facebook',
                    'twitter' => 'X (Twitter)',
                    'copy' => 'Копировать ссылку',
                ],
                'condition' => ['show_share' => 'yes'],
            ]
        );

        $this->end_controls_section();

        // =============================================
        // PHOTO GALLERY
        // =============================================
        $this->start_controls_section(
            'section_gallery',
            [
                'label' => __('🖼️ Галерея', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'show_gallery',
            [
                'label' => __('Показывать галерею', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'gallery_source',
            [
                'label' => __('Источник галереи', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SELECT,
                'default' => 'auto',
                'options' => [
                    'auto' => __('Авто (из контента поста)', 'my-super-tour-elementor'),
                    'acf' => __('ACF Gallery поле', 'my-super-tour-elementor'),
                    'manual' => __('Ручной выбор', 'my-super-tour-elementor'),
                ],
                'condition' => ['show_gallery' => 'yes'],
            ]
        );

        $this->add_control(
            'acf_gallery_field',
            [
                'label' => __('ACF Gallery Field', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'gallery',
                'condition' => [
                    'show_gallery' => 'yes',
                    'gallery_source' => 'acf',
                ],
            ]
        );

        $this->add_control(
            'gallery_images',
            [
                'label' => __('Изображения', 'my-super-tour-elementor'),
                'type' => Controls_Manager::GALLERY,
                'condition' => [
                    'show_gallery' => 'yes',
                    'gallery_source' => 'manual',
                ],
            ]
        );

        $this->add_control(
            'gallery_title',
            [
                'label' => __('Заголовок галереи', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Фотографии', 'my-super-tour-elementor'),
                'condition' => ['show_gallery' => 'yes'],
            ]
        );

        $this->add_control(
            'gallery_columns',
            [
                'label' => __('Колонки', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SELECT,
                'default' => '3',
                'options' => ['2' => '2', '3' => '3', '4' => '4'],
                'condition' => ['show_gallery' => 'yes'],
            ]
        );

        $this->end_controls_section();

        // =============================================
        // FAQ SECTION
        // =============================================
        $this->start_controls_section(
            'section_faq',
            [
                'label' => __('❓ FAQ', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'show_faq',
            [
                'label' => __('Показывать FAQ', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
            ]
        );

        $this->add_control(
            'faq_title',
            [
                'label' => __('Заголовок FAQ', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Часто задаваемые вопросы', 'my-super-tour-elementor'),
                'condition' => ['show_faq' => 'yes'],
            ]
        );

        $faq_repeater = new Repeater();

        $faq_repeater->add_control(
            'question',
            [
                'label' => __('Вопрос', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Вопрос?',
            ]
        );

        $faq_repeater->add_control(
            'answer',
            [
                'label' => __('Ответ', 'my-super-tour-elementor'),
                'type' => Controls_Manager::WYSIWYG,
                'default' => 'Ответ на вопрос...',
            ]
        );

        $this->add_control(
            'faq_items',
            [
                'label' => __('FAQ пункты', 'my-super-tour-elementor'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $faq_repeater->get_controls(),
                'default' => [],
                'title_field' => '{{{ question }}}',
                'condition' => ['show_faq' => 'yes'],
            ]
        );

        $this->end_controls_section();

        // =============================================
        // STYLE - GENERAL
        // =============================================
        $this->start_controls_section(
            'style_general',
            [
                'label' => __('Стили', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'primary_color',
            [
                'label' => __('Основной цвет', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#9952E0',
            ]
        );

        $this->add_control(
            'secondary_color',
            [
                'label' => __('Акцентный цвет', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#fbd603',
            ]
        );

        $this->add_control(
            'glass_blur',
            [
                'label' => __('Glass Blur (px)', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => ['px' => ['min' => 0, 'max' => 40]],
                'default' => ['size' => 16],
            ]
        );

        $this->add_control(
            'glass_opacity',
            [
                'label' => __('Glass Opacity', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => ['px' => ['min' => 0, 'max' => 100]],
                'default' => ['size' => 85],
            ]
        );

        $this->add_control(
            'section_gap',
            [
                'label' => __('Отступ между секциями', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => ['px' => ['min' => 20, 'max' => 100]],
                'default' => ['size' => 40],
            ]
        );

        $this->add_control(
            'heading_color',
            [
                'label' => __('Цвет заголовков', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#1a1a2e',
            ]
        );

        $this->add_control(
            'text_color',
            [
                'label' => __('Цвет текста', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#4b5563',
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Get post data based on content source
     */
    private function get_article_data($settings) {
        $post_id = 0;
        
        if ($settings['content_source'] === 'auto') {
            $post_id = get_the_ID();
        } elseif ($settings['content_source'] === 'product_linked') {
            // Get from WooCommerce native field
            if (is_singular('product') || (function_exists('wc_get_product') && get_post_type() === 'product')) {
                $product_id = get_the_ID();
                // First try WooCommerce native field
                $post_id = get_post_meta($product_id, '_mst_linked_article_id', true);
                // Fallback to URL-based
                if (!$post_id) {
                    $article_url = get_post_meta($product_id, '_mst_recommended_article_url', true);
                    if ($article_url) {
                        $post_id = url_to_postid($article_url);
                    }
                }
            }
        } else {
            $post_id = intval($settings['post_id']);
        }
        
        if (!$post_id) {
            return null;
        }
        
        $post = get_post($post_id);
        if (!$post || $post->post_status !== 'publish') {
            return null;
        }
        
        $data = [
            'id' => $post_id,
            'title' => get_the_title($post_id),
            'content' => apply_filters('the_content', $post->post_content),
            'excerpt' => has_excerpt($post_id) ? get_the_excerpt($post_id) : wp_trim_words($post->post_content, 40),
            'date' => get_the_date('', $post_id),
            'modified' => get_the_modified_date('', $post_id),
            'author_id' => $post->post_author,
            'author_name' => get_the_author_meta('display_name', $post->post_author),
            'author_bio' => get_the_author_meta('description', $post->post_author),
            'author_avatar' => get_avatar_url($post->post_author, ['size' => 80]),
            'featured_image' => get_the_post_thumbnail_url($post_id, 'large'),
            'permalink' => get_permalink($post_id),
            'categories' => [],
            'tags' => [],
            'gallery' => [],
        ];
        
        // Categories
        $categories = get_the_category($post_id);
        if ($categories) {
            foreach ($categories as $cat) {
                $data['categories'][] = [
                    'name' => $cat->name,
                    'url' => get_category_link($cat->term_id),
                ];
            }
        }
        
        // Tags
        $tags = get_the_tags($post_id);
        if ($tags) {
            foreach ($tags as $tag) {
                $data['tags'][] = [
                    'name' => $tag->name,
                    'url' => get_tag_link($tag->term_id),
                ];
            }
        }
        
        // Extract gallery from content
        $data['gallery'] = $this->extract_gallery_from_content($post->post_content, $post_id);
        
        return $data;
    }

    /**
     * Extract gallery images from post content or attached media
     */
    private function extract_gallery_from_content($content, $post_id) {
        $images = [];
        
        if (preg_match('/\[gallery[^\]]*ids=["\']([^"\']+)["\'][^\]]*\]/', $content, $matches)) {
            $ids = explode(',', $matches[1]);
            foreach ($ids as $id) {
                $url = wp_get_attachment_image_url(trim($id), 'large');
                $thumb = wp_get_attachment_image_url(trim($id), 'medium');
                if ($url) {
                    $images[] = ['url' => $url, 'thumb' => $thumb, 'alt' => get_post_meta($id, '_wp_attachment_image_alt', true)];
                }
            }
        }
        
        if (empty($images)) {
            $attachments = get_posts([
                'post_type' => 'attachment',
                'posts_per_page' => 10,
                'post_parent' => $post_id,
                'post_mime_type' => 'image',
                'orderby' => 'menu_order',
                'order' => 'ASC',
            ]);
            
            foreach ($attachments as $attachment) {
                $url = wp_get_attachment_image_url($attachment->ID, 'large');
                $thumb = wp_get_attachment_image_url($attachment->ID, 'medium');
                if ($url) {
                    $images[] = ['url' => $url, 'thumb' => $thumb, 'alt' => get_post_meta($attachment->ID, '_wp_attachment_image_alt', true)];
                }
            }
        }
        
        return $images;
    }

    /**
     * Extract headings for Table of Contents
     */
    private function extract_headings($content, $levels = 'h2') {
        $headings = [];
        $pattern = '';
        
        switch ($levels) {
            case 'h2':
                $pattern = '/<h2[^>]*>(.*?)<\/h2>/i';
                break;
            case 'h2h3':
                $pattern = '/<h[23][^>]*>(.*?)<\/h[23]>/i';
                break;
            case 'h2h3h4':
                $pattern = '/<h[234][^>]*>(.*?)<\/h[234]>/i';
                break;
        }
        
        if (preg_match_all($pattern, $content, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $index => $match) {
                $text = strip_tags($match[1]);
                $id = sanitize_title($text) . '-' . $index;
                $level = (int) substr($match[0], 2, 1);
                $headings[] = [
                    'id' => $id,
                    'text' => $text,
                    'level' => $level,
                ];
            }
        }
        
        return $headings;
    }

    /**
     * Get related products
     */
    private function get_related_products($ids_string, $count, $current_post_id = 0) {
        if (!function_exists('wc_get_products')) {
            return [];
        }
        
        $products = [];
        
        if (!empty($ids_string)) {
            $ids = array_map('intval', array_filter(explode(',', $ids_string)));
            foreach ($ids as $id) {
                $product = wc_get_product($id);
                if ($product) {
                    $products[] = $this->format_product($product);
                }
            }
        } else {
            // Auto-select from category
            $args = [
                'limit' => intval($count),
                'status' => 'publish',
                'orderby' => 'rand',
            ];
            
            $wc_products = wc_get_products($args);
            foreach ($wc_products as $product) {
                $products[] = $this->format_product($product);
            }
        }
        
        return array_slice($products, 0, intval($count));
    }

    private function format_product($product) {
        return [
            'id' => $product->get_id(),
            'title' => $product->get_name(),
            'url' => $product->get_permalink(),
            'image' => wp_get_attachment_image_url($product->get_image_id(), 'medium'),
            'price' => $product->get_price_html(),
        ];
    }

    /**
     * Get embed URL for video
     */
    private function get_embed_url($url) {
        if (strpos($url, 'youtube.com') !== false || strpos($url, 'youtu.be') !== false) {
            preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $url, $matches);
            if (!empty($matches[1])) {
                return 'https://www.youtube.com/embed/' . $matches[1];
            }
        } elseif (strpos($url, 'vimeo.com') !== false) {
            preg_match('/vimeo\.com\/(\d+)/', $url, $matches);
            if (!empty($matches[1])) {
                return 'https://player.vimeo.com/video/' . $matches[1];
            }
        }
        return $url;
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $data = $this->get_article_data($settings);
        
        if (!$data) {
            if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
                echo '<div style="padding: 40px; background: linear-gradient(135deg, rgba(153, 82, 224, 0.1), rgba(123, 63, 196, 0.05)); border-radius: 20px; text-align: center;">';
                echo '<p style="margin: 0; color: #6b7280; font-size: 16px;">📝 Статья будет отображена на основе источника контента</p>';
                echo '<p style="margin: 10px 0 0; color: #9ca3af; font-size: 14px;">Выберите источник в настройках виджета</p>';
                echo '</div>';
            }
            return;
        }
        
        $unique_id = 'mst-blog-' . $this->get_id();
        
        $primary_color = $settings['primary_color'] ?? '#9952E0';
        $secondary_color = $settings['secondary_color'] ?? '#fbd603';
        $glass_blur = $settings['glass_blur']['size'] ?? 16;
        $glass_opacity = $settings['glass_opacity']['size'] ?? 85;
        $section_gap = $settings['section_gap']['size'] ?? 40;
        $heading_color = $settings['heading_color'] ?? '#1a1a2e';
        $text_color = $settings['text_color'] ?? '#4b5563';
        
        // Icons
        $tip_icons = [
            'bulb' => '💡',
            'star' => '⭐',
            'check' => '✅',
            'info' => 'ℹ️',
            'fire' => '🔥',
            'pin' => '📌',
        ];
        $tip_icon = $tip_icons[$settings['tips_icon'] ?? 'bulb'] ?? '💡';
        
        // Gallery images
        $gallery_images = [];
        if ($settings['show_gallery'] === 'yes') {
            if ($settings['gallery_source'] === 'manual' && !empty($settings['gallery_images'])) {
                foreach ($settings['gallery_images'] as $img) {
                    $gallery_images[] = [
                        'url' => $img['url'],
                        'thumb' => wp_get_attachment_image_url($img['id'], 'medium') ?: $img['url'],
                        'alt' => get_post_meta($img['id'], '_wp_attachment_image_alt', true) ?: '',
                    ];
                }
            } elseif ($settings['gallery_source'] === 'acf' && function_exists('get_field')) {
                $acf_gallery = get_field($settings['acf_gallery_field'], $data['id']);
                if ($acf_gallery) {
                    foreach ($acf_gallery as $img) {
                        $gallery_images[] = [
                            'url' => $img['url'],
                            'thumb' => $img['sizes']['medium'] ?? $img['url'],
                            'alt' => $img['alt'] ?? '',
                        ];
                    }
                }
            } else {
                $gallery_images = $data['gallery'];
            }
        }
        
        // Extract TOC headings
        $toc_headings = [];
        if ($settings['show_toc'] === 'yes') {
            $toc_headings = $this->extract_headings($data['content'], $settings['toc_heading_levels']);
        }
        
        // Related products
        $related_products = [];
        if ($settings['show_related_products'] === 'yes') {
            $related_products = $this->get_related_products(
                $settings['related_products_ids'] ?? '',
                $settings['related_count'] ?? 3,
                $data['id']
            );
        }
        
        $columns = intval($settings['gallery_columns'] ?? 3);
        ?>
        
        <article class="mst-blog-article" id="<?php echo esc_attr($unique_id); ?>" 
                 itemscope itemtype="https://schema.org/Article">
            
            <!-- HERO IMAGE -->
            <?php if ($settings['show_featured_image'] === 'yes' && !empty($data['featured_image'])): ?>
            <figure class="mst-blog-hero">
                <img src="<?php echo esc_url($data['featured_image']); ?>" 
                     alt="<?php echo esc_attr($data['title']); ?>"
                     class="mst-blog-hero-img"
                     itemprop="image">
                <div class="mst-blog-hero-overlay"></div>
            </figure>
            <?php endif; ?>
            
            <!-- HEADER -->
            <header class="mst-blog-header mst-liquid-glass">
                <?php if ($settings['show_categories'] === 'yes' && !empty($data['categories'])): ?>
                <div class="mst-blog-categories">
                    <?php foreach ($data['categories'] as $cat): ?>
                    <a href="<?php echo esc_url($cat['url']); ?>" class="mst-blog-category" rel="tag">
                        <?php echo esc_html($cat['name']); ?>
                    </a>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
                
                <?php if ($settings['show_title'] === 'yes'): ?>
                <h1 class="mst-blog-title" itemprop="headline"><?php echo esc_html($data['title']); ?></h1>
                <?php endif; ?>
                
                <?php if ($settings['show_meta'] === 'yes'): ?>
                <div class="mst-blog-meta">
                    <div class="mst-blog-author" itemprop="author" itemscope itemtype="https://schema.org/Person">
                        <img src="<?php echo esc_url($data['author_avatar']); ?>" 
                             alt="<?php echo esc_attr($data['author_name']); ?>"
                             class="mst-blog-author-avatar">
                        <span itemprop="name"><?php echo esc_html($data['author_name']); ?></span>
                    </div>
                    <time class="mst-blog-date" itemprop="datePublished" datetime="<?php echo esc_attr(get_the_date('c', $data['id'])); ?>">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="16" y1="2" x2="16" y2="6"></line>
                            <line x1="8" y1="2" x2="8" y2="6"></line>
                            <line x1="3" y1="10" x2="21" y2="10"></line>
                        </svg>
                        <?php echo esc_html($data['date']); ?>
                    </time>
                </div>
                <?php endif; ?>
                
                <?php if ($settings['show_excerpt'] === 'yes' && !empty($data['excerpt'])): ?>
                <p class="mst-blog-excerpt" itemprop="description"><?php echo esc_html($data['excerpt']); ?></p>
                <?php endif; ?>
            </header>
            
            <!-- TABLE OF CONTENTS -->
            <?php if ($settings['show_toc'] === 'yes' && !empty($toc_headings)): ?>
            <nav class="mst-blog-section mst-blog-toc mst-liquid-glass" aria-label="Содержание">
                <h2 class="mst-blog-section-title">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="8" y1="6" x2="21" y2="6"></line>
                        <line x1="8" y1="12" x2="21" y2="12"></line>
                        <line x1="8" y1="18" x2="21" y2="18"></line>
                        <line x1="3" y1="6" x2="3.01" y2="6"></line>
                        <line x1="3" y1="12" x2="3.01" y2="12"></line>
                        <line x1="3" y1="18" x2="3.01" y2="18"></line>
                    </svg>
                    <?php echo esc_html($settings['toc_title']); ?>
                </h2>
                <ol class="mst-blog-toc-list">
                    <?php foreach ($toc_headings as $heading): ?>
                    <li class="mst-blog-toc-item" data-level="<?php echo $heading['level']; ?>">
                        <a href="#<?php echo esc_attr($heading['id']); ?>" class="mst-blog-toc-link">
                            <?php echo esc_html($heading['text']); ?>
                        </a>
                    </li>
                    <?php endforeach; ?>
                </ol>
            </nav>
            <?php endif; ?>
            
            <!-- VIDEO SECTION -->
            <?php if ($settings['show_video'] === 'yes' && !empty($settings['video_url'])): ?>
            <section class="mst-blog-section mst-blog-video mst-liquid-glass">
                <h2 class="mst-blog-section-title">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polygon points="5 3 19 12 5 21 5 3"></polygon>
                    </svg>
                    <?php echo esc_html($settings['video_title']); ?>
                </h2>
                <div class="mst-blog-video-wrapper">
                    <iframe src="<?php echo esc_url($this->get_embed_url($settings['video_url'])); ?>" 
                            frameborder="0" 
                            allowfullscreen
                            loading="lazy"></iframe>
                </div>
                <?php if (!empty($settings['video_description'])): ?>
                <p class="mst-blog-video-desc"><?php echo esc_html($settings['video_description']); ?></p>
                <?php endif; ?>
            </section>
            <?php endif; ?>
            
            <!-- TIPS / HIGHLIGHTS -->
            <?php if ($settings['show_tips'] === 'yes' && !empty($settings['tips_items'])): ?>
            <section class="mst-blog-section mst-blog-tips mst-liquid-glass">
                <h2 class="mst-blog-section-title">
                    <?php echo $tip_icon; ?>
                    <?php echo esc_html($settings['tips_title']); ?>
                </h2>
                <ul class="mst-blog-tips-list">
                    <?php foreach ($settings['tips_items'] as $tip): ?>
                    <li class="mst-blog-tip-item">
                        <span class="mst-blog-tip-icon"><?php echo $tip_icon; ?></span>
                        <span class="mst-blog-tip-text"><?php echo esc_html($tip['tip_text']); ?></span>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </section>
            <?php endif; ?>
            
            <!-- QUOTE -->
            <?php if ($settings['show_quote'] === 'yes' && !empty($settings['quote_text'])): ?>
            <blockquote class="mst-blog-section mst-blog-quote mst-liquid-glass">
                <svg class="mst-blog-quote-icon" width="40" height="40" viewBox="0 0 24 24" fill="currentColor" opacity="0.15">
                    <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/>
                </svg>
                <p class="mst-blog-quote-text"><?php echo esc_html($settings['quote_text']); ?></p>
                <?php if (!empty($settings['quote_author'])): ?>
                <cite class="mst-blog-quote-author">— <?php echo esc_html($settings['quote_author']); ?></cite>
                <?php endif; ?>
            </blockquote>
            <?php endif; ?>
            
            <!-- TIMELINE -->
            <?php if ($settings['show_timeline'] === 'yes' && !empty($settings['timeline_items'])): ?>
            <section class="mst-blog-section mst-blog-timeline mst-liquid-glass">
                <h2 class="mst-blog-section-title">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"></circle>
                        <polyline points="12 6 12 12 16 14"></polyline>
                    </svg>
                    <?php echo esc_html($settings['timeline_title']); ?>
                </h2>
                <div class="mst-blog-timeline-list">
                    <?php foreach ($settings['timeline_items'] as $index => $item): ?>
                    <div class="mst-blog-timeline-item">
                        <div class="mst-blog-timeline-marker">
                            <span class="mst-blog-timeline-time"><?php echo esc_html($item['time']); ?></span>
                        </div>
                        <div class="mst-blog-timeline-content">
                            <h4 class="mst-blog-timeline-title"><?php echo esc_html($item['event_title']); ?></h4>
                            <?php if (!empty($item['event_description'])): ?>
                            <p class="mst-blog-timeline-desc"><?php echo esc_html($item['event_description']); ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </section>
            <?php endif; ?>
            
            <!-- CHECKLIST -->
            <?php if ($settings['show_checklist'] === 'yes' && !empty($settings['checklist_items'])): ?>
            <section class="mst-blog-section mst-blog-checklist mst-liquid-glass">
                <h2 class="mst-blog-section-title">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 11l3 3L22 4"></path>
                        <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
                    </svg>
                    <?php echo esc_html($settings['checklist_title']); ?>
                </h2>
                <ul class="mst-blog-checklist-list">
                    <?php foreach ($settings['checklist_items'] as $item): ?>
                    <li class="mst-blog-checklist-item<?php echo $item['is_important'] === 'yes' ? ' mst-important' : ''; ?>">
                        <span class="mst-blog-checklist-check">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                        </span>
                        <span class="mst-blog-checklist-text"><?php echo esc_html($item['item_text']); ?></span>
                        <?php if ($item['is_important'] === 'yes'): ?>
                        <span class="mst-blog-checklist-badge">Важно!</span>
                        <?php endif; ?>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </section>
            <?php endif; ?>
            
            <!-- GALLERY -->
            <?php if ($settings['show_gallery'] === 'yes' && !empty($gallery_images)): ?>
            <section class="mst-blog-section mst-blog-gallery mst-liquid-glass">
                <?php if (!empty($settings['gallery_title'])): ?>
                <h2 class="mst-blog-section-title">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                        <circle cx="8.5" cy="8.5" r="1.5"></circle>
                        <polyline points="21 15 16 10 5 21"></polyline>
                    </svg>
                    <?php echo esc_html($settings['gallery_title']); ?>
                </h2>
                <?php endif; ?>
                
                <div class="mst-blog-gallery-grid" style="--columns: <?php echo $columns; ?>;">
                    <?php foreach ($gallery_images as $index => $img): ?>
                    <figure class="mst-blog-gallery-item" data-index="<?php echo $index; ?>">
                        <img src="<?php echo esc_url($img['thumb']); ?>" 
                             data-full="<?php echo esc_url($img['url']); ?>"
                             alt="<?php echo esc_attr($img['alt'] ?: $data['title'] . ' - фото ' . ($index + 1)); ?>"
                             loading="lazy"
                             class="mst-blog-gallery-img">
                    </figure>
                    <?php endforeach; ?>
                </div>
            </section>
            <?php endif; ?>
            
            <!-- MAIN CONTENT -->
            <?php if ($settings['show_content'] === 'yes' && !empty($data['content'])): ?>
            <section class="mst-blog-section mst-blog-content mst-liquid-glass" itemprop="articleBody">
                <div class="mst-blog-content-inner">
                    <?php echo $data['content']; ?>
                </div>
            </section>
            <?php endif; ?>
            
            <!-- FAQ -->
            <?php if ($settings['show_faq'] === 'yes' && !empty($settings['faq_items'])): ?>
            <section class="mst-blog-section mst-blog-faq mst-liquid-glass" aria-label="FAQ" itemscope itemtype="https://schema.org/FAQPage">
                <h2 class="mst-blog-section-title">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"></circle>
                        <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path>
                        <line x1="12" y1="17" x2="12.01" y2="17"></line>
                    </svg>
                    <?php echo esc_html($settings['faq_title']); ?>
                </h2>
                
                <div class="mst-blog-faq-list">
                    <?php foreach ($settings['faq_items'] as $index => $faq): ?>
                    <div class="mst-blog-faq-item" itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">
                        <button class="mst-blog-faq-question" aria-expanded="false" aria-controls="faq-<?php echo $index; ?>">
                            <span itemprop="name"><?php echo esc_html($faq['question']); ?></span>
                            <svg class="mst-blog-faq-arrow" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg>
                        </button>
                        <div class="mst-blog-faq-answer" id="faq-<?php echo $index; ?>" itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer">
                            <div itemprop="text"><?php echo wp_kses_post($faq['answer']); ?></div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </section>
            <?php endif; ?>
            
            <!-- RELATED PRODUCTS -->
            <?php if ($settings['show_related_products'] === 'yes' && !empty($related_products)): ?>
            <section class="mst-blog-section mst-blog-related mst-liquid-glass">
                <h2 class="mst-blog-section-title">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="9" cy="21" r="1"></circle>
                        <circle cx="20" cy="21" r="1"></circle>
                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                    </svg>
                    <?php echo esc_html($settings['related_title']); ?>
                </h2>
                
                <div class="mst-blog-related-grid">
                    <?php foreach ($related_products as $product): ?>
                    <a href="<?php echo esc_url($product['url']); ?>" class="mst-blog-related-item">
                        <?php if ($product['image']): ?>
                        <img src="<?php echo esc_url($product['image']); ?>" 
                             alt="<?php echo esc_attr($product['title']); ?>"
                             class="mst-blog-related-img">
                        <?php endif; ?>
                        <div class="mst-blog-related-info">
                            <h4 class="mst-blog-related-title"><?php echo esc_html($product['title']); ?></h4>
                            <span class="mst-blog-related-price"><?php echo $product['price']; ?></span>
                        </div>
                    </a>
                    <?php endforeach; ?>
                </div>
            </section>
            <?php endif; ?>
            
            <!-- AUTHOR BIO -->
            <?php if ($settings['show_author_bio'] === 'yes'): ?>
            <section class="mst-blog-section mst-blog-author-bio mst-liquid-glass">
                <h2 class="mst-blog-section-title">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                    <?php echo esc_html($settings['author_bio_title']); ?>
                </h2>
                <div class="mst-blog-author-bio-content">
                    <img src="<?php echo esc_url($data['author_avatar']); ?>" 
                         alt="<?php echo esc_attr($data['author_name']); ?>"
                         class="mst-blog-author-bio-avatar">
                    <div class="mst-blog-author-bio-info">
                        <h4 class="mst-blog-author-bio-name"><?php echo esc_html($data['author_name']); ?></h4>
                        <?php if (!empty($data['author_bio'])): ?>
                        <p class="mst-blog-author-bio-text"><?php echo esc_html($data['author_bio']); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            </section>
            <?php endif; ?>
            
            <!-- SHARE BUTTONS -->
            <?php if ($settings['show_share'] === 'yes' && !empty($settings['share_networks'])): ?>
            <section class="mst-blog-section mst-blog-share mst-liquid-glass">
                <span class="mst-blog-share-label">Поделиться:</span>
                <div class="mst-blog-share-buttons">
                    <?php 
                    $share_url = urlencode($data['permalink']);
                    $share_title = urlencode($data['title']);
                    $networks = $settings['share_networks'];
                    ?>
                    
                    <?php if (in_array('telegram', $networks)): ?>
                    <a href="https://t.me/share/url?url=<?php echo $share_url; ?>&text=<?php echo $share_title; ?>" 
                       class="mst-blog-share-btn mst-share-telegram" target="_blank" rel="noopener" title="Telegram">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0a12 12 0 0 0-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 0 1 .171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z"/>
                        </svg>
                    </a>
                    <?php endif; ?>
                    
                    <?php if (in_array('whatsapp', $networks)): ?>
                    <a href="https://wa.me/?text=<?php echo $share_title; ?>%20<?php echo $share_url; ?>" 
                       class="mst-blog-share-btn mst-share-whatsapp" target="_blank" rel="noopener" title="WhatsApp">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                        </svg>
                    </a>
                    <?php endif; ?>
                    
                    <?php if (in_array('vk', $networks)): ?>
                    <a href="https://vk.com/share.php?url=<?php echo $share_url; ?>&title=<?php echo $share_title; ?>" 
                       class="mst-blog-share-btn mst-share-vk" target="_blank" rel="noopener" title="VK">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M15.684 0H8.316C1.592 0 0 1.592 0 8.316v7.368C0 22.408 1.592 24 8.316 24h7.368C22.408 24 24 22.408 24 15.684V8.316C24 1.592 22.391 0 15.684 0zm3.692 17.123h-1.744c-.66 0-.864-.525-2.05-1.727-1.033-1-1.49-1.135-1.744-1.135-.356 0-.458.102-.458.593v1.575c0 .424-.135.678-1.253.678-1.846 0-3.896-1.118-5.335-3.202C4.624 10.857 4 8.657 4 8.184c0-.254.102-.491.593-.491h1.744c.44 0 .61.203.78.677.863 2.49 2.303 4.675 2.896 4.675.22 0 .322-.102.322-.66V9.721c-.068-1.186-.695-1.287-.695-1.71 0-.203.17-.407.44-.407h2.744c.373 0 .508.203.508.643v3.473c0 .372.17.508.271.508.22 0 .407-.136.814-.542 1.27-1.422 2.18-3.625 2.18-3.625.119-.254.322-.491.763-.491h1.744c.525 0 .644.27.525.643-.22 1.017-2.354 4.031-2.354 4.031-.186.305-.254.44 0 .78.186.254.796.779 1.203 1.253.745.847 1.32 1.558 1.473 2.049.17.49-.085.744-.576.744z"/>
                        </svg>
                    </a>
                    <?php endif; ?>
                    
                    <?php if (in_array('facebook', $networks)): ?>
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $share_url; ?>" 
                       class="mst-blog-share-btn mst-share-facebook" target="_blank" rel="noopener" title="Facebook">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                        </svg>
                    </a>
                    <?php endif; ?>
                    
                    <?php if (in_array('twitter', $networks)): ?>
                    <a href="https://twitter.com/intent/tweet?url=<?php echo $share_url; ?>&text=<?php echo $share_title; ?>" 
                       class="mst-blog-share-btn mst-share-twitter" target="_blank" rel="noopener" title="X (Twitter)">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                        </svg>
                    </a>
                    <?php endif; ?>
                    
                    <?php if (in_array('copy', $networks)): ?>
                    <button class="mst-blog-share-btn mst-share-copy" 
                            data-url="<?php echo esc_url($data['permalink']); ?>" 
                            title="Копировать ссылку">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                            <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                        </svg>
                    </button>
                    <?php endif; ?>
                </div>
            </section>
            <?php endif; ?>
            
            <!-- TAGS -->
            <?php if ($settings['show_tags'] === 'yes' && !empty($data['tags'])): ?>
            <footer class="mst-blog-footer mst-liquid-glass">
                <div class="mst-blog-tags">
                    <span class="mst-blog-tags-label">Теги:</span>
                    <?php foreach ($data['tags'] as $tag): ?>
                    <a href="<?php echo esc_url($tag['url']); ?>" class="mst-blog-tag">#<?php echo esc_html($tag['name']); ?></a>
                    <?php endforeach; ?>
                </div>
            </footer>
            <?php endif; ?>
            
        </article>
        
        <!-- JSON-LD Article Schema -->
        <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "Article",
            "headline": "<?php echo esc_js($data['title']); ?>",
            "description": "<?php echo esc_js($data['excerpt']); ?>",
            "author": {
                "@type": "Person",
                "name": "<?php echo esc_js($data['author_name']); ?>"
            },
            "datePublished": "<?php echo esc_js(get_the_date('c', $data['id'])); ?>",
            "dateModified": "<?php echo esc_js(get_the_modified_date('c', $data['id'])); ?>",
            <?php if (!empty($data['featured_image'])): ?>
            "image": "<?php echo esc_url($data['featured_image']); ?>",
            <?php endif; ?>
            "mainEntityOfPage": {
                "@type": "WebPage",
                "@id": "<?php echo esc_url(get_permalink($data['id'])); ?>"
            }
        }
        </script>
        
        <style>
        /* ===============================================
           BLOG ARTICLE - LIQUID GLASS DESIGN (EXTENDED)
           =============================================== */
        #<?php echo esc_attr($unique_id); ?> {
            --primary-color: <?php echo esc_attr($primary_color); ?>;
            --secondary-color: <?php echo esc_attr($secondary_color); ?>;
            --glass-blur: <?php echo esc_attr($glass_blur); ?>px;
            --glass-opacity: <?php echo esc_attr($glass_opacity / 100); ?>;
            --section-gap: <?php echo esc_attr($section_gap); ?>px;
            --heading-color: <?php echo esc_attr($heading_color); ?>;
            --text-color: <?php echo esc_attr($text_color); ?>;
        }
        
        .mst-blog-article {
            display: flex;
            flex-direction: column;
            gap: var(--section-gap);
            max-width: 900px;
            margin: 0 auto;
            padding: 0 1rem;
        }
        
        .mst-liquid-glass {
            background: linear-gradient(
                135deg,
                rgba(255, 255, 255, calc(var(--glass-opacity) + 0.1)),
                rgba(255, 255, 255, calc(var(--glass-opacity) - 0.15))
            );
            backdrop-filter: blur(var(--glass-blur)) saturate(180%);
            -webkit-backdrop-filter: blur(var(--glass-blur)) saturate(180%);
            border: 1px solid rgba(255, 255, 255, 0.5);
            border-radius: 24px;
            box-shadow: 
                0 8px 32px -8px rgba(0, 0, 0, 0.08),
                0 4px 16px -4px rgba(153, 82, 224, 0.06),
                inset 0 1px 0 rgba(255, 255, 255, 0.8);
        }
        
        /* Hero Image */
        .mst-blog-hero {
            position: relative;
            border-radius: 24px;
            overflow: hidden;
            max-height: 500px;
            margin: 0;
        }
        
        .mst-blog-hero-img {
            width: 100%;
            height: auto;
            object-fit: cover;
            display: block;
        }
        
        .mst-blog-hero-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 40%;
            background: linear-gradient(to top, rgba(0,0,0,0.4), transparent);
            pointer-events: none;
        }
        
        /* Header */
        .mst-blog-header {
            padding: 40px;
            text-align: center;
        }
        
        .mst-blog-categories {
            display: flex;
            justify-content: center;
            gap: 8px;
            flex-wrap: wrap;
            margin-bottom: 16px;
        }
        
        .mst-blog-category {
            display: inline-block;
            padding: 6px 16px;
            background: linear-gradient(135deg, var(--primary-color), #7B3FC4);
            color: #fff;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border-radius: 20px;
            text-decoration: none;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        
        .mst-blog-category:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(153, 82, 224, 0.3);
            color: #fff;
        }
        
        .mst-blog-title {
            font-size: 2.25rem;
            font-weight: 800;
            line-height: 1.2;
            color: var(--heading-color);
            margin: 0 0 20px 0;
            letter-spacing: -0.02em;
        }
        
        .mst-blog-meta {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 24px;
            flex-wrap: wrap;
            margin-bottom: 20px;
        }
        
        .mst-blog-author {
            display: flex;
            align-items: center;
            gap: 10px;
            color: var(--text-color);
            font-weight: 500;
        }
        
        .mst-blog-author-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            border: 2px solid var(--primary-color);
        }
        
        .mst-blog-date {
            display: flex;
            align-items: center;
            gap: 6px;
            color: var(--text-color);
            font-size: 0.9375rem;
        }
        
        .mst-blog-excerpt {
            font-size: 1.125rem;
            line-height: 1.7;
            color: var(--text-color);
            margin: 0;
            max-width: 700px;
            margin-left: auto;
            margin-right: auto;
        }
        
        /* Section */
        .mst-blog-section {
            padding: 32px;
        }
        
        .mst-blog-section-title {
            font-size: 1.375rem;
            font-weight: 700;
            color: var(--heading-color);
            margin: 0 0 24px 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .mst-blog-section-title svg {
            color: var(--primary-color);
        }
        
        /* Table of Contents */
        .mst-blog-toc-list {
            list-style: none;
            padding: 0;
            margin: 0;
            counter-reset: toc;
        }
        
        .mst-blog-toc-item {
            counter-increment: toc;
        }
        
        .mst-blog-toc-item[data-level="3"] {
            padding-left: 24px;
        }
        
        .mst-blog-toc-item[data-level="4"] {
            padding-left: 48px;
        }
        
        .mst-blog-toc-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            margin-bottom: 6px;
            background: rgba(153, 82, 224, 0.04);
            border-radius: 12px;
            color: var(--text-color);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.2s;
        }
        
        .mst-blog-toc-link::before {
            content: counter(toc);
            display: flex;
            align-items: center;
            justify-content: center;
            width: 28px;
            height: 28px;
            background: var(--primary-color);
            color: white;
            font-size: 0.8125rem;
            font-weight: 700;
            border-radius: 8px;
            flex-shrink: 0;
        }
        
        .mst-blog-toc-link:hover {
            background: rgba(153, 82, 224, 0.1);
            color: var(--primary-color);
            transform: translateX(4px);
        }
        
        /* Video */
        .mst-blog-video-wrapper {
            position: relative;
            padding-bottom: 56.25%;
            height: 0;
            overflow: hidden;
            border-radius: 16px;
            background: #000;
        }
        
        .mst-blog-video-wrapper iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }
        
        .mst-blog-video-desc {
            margin: 16px 0 0;
            font-size: 0.9375rem;
            color: var(--text-color);
            text-align: center;
        }
        
        /* Tips */
        .mst-blog-tips-list {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        
        .mst-blog-tip-item {
            display: flex;
            align-items: flex-start;
            gap: 14px;
            padding: 16px 18px;
            background: linear-gradient(135deg, rgba(251, 214, 3, 0.08), rgba(251, 214, 3, 0.02));
            border-radius: 14px;
            border-left: 3px solid var(--secondary-color);
        }
        
        .mst-blog-tip-icon {
            font-size: 1.25rem;
            flex-shrink: 0;
        }
        
        .mst-blog-tip-text {
            font-size: 1rem;
            line-height: 1.6;
            color: var(--text-color);
        }
        
        /* Quote */
        .mst-blog-quote {
            position: relative;
            text-align: center;
            padding: 48px 40px;
        }
        
        .mst-blog-quote-icon {
            position: absolute;
            top: 20px;
            left: 30px;
            color: var(--primary-color);
        }
        
        .mst-blog-quote-text {
            font-size: 1.375rem;
            font-weight: 500;
            line-height: 1.6;
            color: var(--heading-color);
            font-style: italic;
            margin: 0 0 16px 0;
        }
        
        .mst-blog-quote-author {
            display: block;
            font-size: 1rem;
            color: var(--text-color);
            font-style: normal;
        }
        
        /* Timeline */
        .mst-blog-timeline-list {
            position: relative;
            padding-left: 40px;
        }
        
        .mst-blog-timeline-list::before {
            content: '';
            position: absolute;
            left: 15px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: linear-gradient(180deg, var(--primary-color), rgba(153, 82, 224, 0.2));
        }
        
        .mst-blog-timeline-item {
            position: relative;
            margin-bottom: 24px;
        }
        
        .mst-blog-timeline-item:last-child {
            margin-bottom: 0;
        }
        
        .mst-blog-timeline-marker {
            position: absolute;
            left: -40px;
            top: 0;
            width: 30px;
            height: 30px;
            background: var(--primary-color);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 0 0 4px rgba(153, 82, 224, 0.2);
        }
        
        .mst-blog-timeline-time {
            font-size: 0.625rem;
            font-weight: 700;
            color: white;
        }
        
        .mst-blog-timeline-content {
            background: rgba(255, 255, 255, 0.6);
            padding: 16px 20px;
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, 0.5);
        }
        
        .mst-blog-timeline-title {
            font-size: 1rem;
            font-weight: 600;
            color: var(--heading-color);
            margin: 0 0 6px 0;
        }
        
        .mst-blog-timeline-desc {
            font-size: 0.9375rem;
            color: var(--text-color);
            margin: 0;
            line-height: 1.5;
        }
        
        /* Checklist */
        .mst-blog-checklist-list {
            list-style: none;
            padding: 0;
            margin: 0;
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 12px;
        }
        
        .mst-blog-checklist-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 16px;
            background: rgba(255, 255, 255, 0.5);
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, 0.4);
            transition: transform 0.2s;
        }
        
        .mst-blog-checklist-item:hover {
            transform: translateX(4px);
        }
        
        .mst-blog-checklist-item.mst-important {
            background: linear-gradient(135deg, rgba(251, 214, 3, 0.1), rgba(251, 214, 3, 0.02));
            border-color: var(--secondary-color);
        }
        
        .mst-blog-checklist-check {
            width: 24px;
            height: 24px;
            background: var(--primary-color);
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            color: white;
        }
        
        .mst-blog-checklist-text {
            font-size: 0.9375rem;
            color: var(--text-color);
            flex: 1;
        }
        
        .mst-blog-checklist-badge {
            font-size: 0.6875rem;
            font-weight: 600;
            padding: 4px 8px;
            background: var(--secondary-color);
            color: #1a1a2e;
            border-radius: 6px;
            text-transform: uppercase;
        }
        
        /* Gallery */
        .mst-blog-gallery-grid {
            display: grid;
            grid-template-columns: repeat(var(--columns, 3), 1fr);
            gap: 12px;
        }
        
        .mst-blog-gallery-item {
            margin: 0;
            border-radius: 12px;
            overflow: hidden;
            cursor: pointer;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .mst-blog-gallery-item:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 32px -8px rgba(153, 82, 224, 0.25);
        }
        
        .mst-blog-gallery-img {
            width: 100%;
            height: auto;
            display: block;
            transition: transform 0.4s ease;
        }
        
        .mst-blog-gallery-item:hover .mst-blog-gallery-img {
            transform: scale(1.05);
        }
        
        /* Content */
        .mst-blog-content-inner {
            font-size: 1.0625rem;
            line-height: 1.85;
            color: var(--text-color);
        }
        
        .mst-blog-content-inner h2,
        .mst-blog-content-inner h3,
        .mst-blog-content-inner h4 {
            color: var(--heading-color);
            margin-top: 2rem;
            margin-bottom: 1rem;
            scroll-margin-top: 100px;
        }
        
        .mst-blog-content-inner h2 { font-size: 1.5rem; }
        .mst-blog-content-inner h3 { font-size: 1.25rem; }
        .mst-blog-content-inner h4 { font-size: 1.125rem; }
        
        .mst-blog-content-inner p {
            margin: 0 0 1.25rem 0;
        }
        
        .mst-blog-content-inner img {
            max-width: 100%;
            height: auto;
            border-radius: 12px;
            margin: 1.5rem 0;
        }
        
        .mst-blog-content-inner ul,
        .mst-blog-content-inner ol {
            margin: 0 0 1.25rem 0;
            padding-left: 24px;
        }
        
        .mst-blog-content-inner li {
            margin-bottom: 8px;
        }
        
        .mst-blog-content-inner blockquote {
            margin: 2rem 0;
            padding: 20px 24px;
            background: rgba(153, 82, 224, 0.06);
            border-left: 4px solid var(--primary-color);
            border-radius: 0 12px 12px 0;
            font-style: italic;
        }
        
        /* FAQ */
        .mst-blog-faq-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        
        .mst-blog-faq-item {
            border-radius: 14px;
            background: rgba(255,255,255,0.5);
            border: 1px solid rgba(255,255,255,0.4);
            overflow: hidden;
        }
        
        .mst-blog-faq-question {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            padding: 18px 20px;
            background: transparent;
            border: none;
            cursor: pointer;
            font-size: 1rem;
            font-weight: 600;
            color: var(--heading-color);
            text-align: left;
            transition: background 0.2s;
        }
        
        .mst-blog-faq-question:hover {
            background: rgba(153, 82, 224, 0.05);
        }
        
        .mst-blog-faq-arrow {
            flex-shrink: 0;
            transition: transform 0.3s ease;
            color: var(--primary-color);
        }
        
        .mst-blog-faq-item.active .mst-blog-faq-arrow {
            transform: rotate(180deg);
        }
        
        .mst-blog-faq-answer {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease, padding 0.3s ease;
        }
        
        .mst-blog-faq-item.active .mst-blog-faq-answer {
            max-height: 500px;
            padding: 0 20px 18px 20px;
        }
        
        .mst-blog-faq-answer > div {
            font-size: 0.9375rem;
            line-height: 1.7;
            color: var(--text-color);
        }
        
        /* Related Products */
        .mst-blog-related-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 16px;
        }
        
        .mst-blog-related-item {
            display: block;
            background: rgba(255, 255, 255, 0.6);
            border-radius: 16px;
            overflow: hidden;
            text-decoration: none;
            transition: transform 0.3s, box-shadow 0.3s;
            border: 1px solid rgba(255, 255, 255, 0.4);
        }
        
        .mst-blog-related-item:hover {
            transform: translateY(-6px);
            box-shadow: 0 16px 40px -12px rgba(153, 82, 224, 0.25);
        }
        
        .mst-blog-related-img {
            width: 100%;
            height: 140px;
            object-fit: cover;
        }
        
        .mst-blog-related-info {
            padding: 14px 16px;
        }
        
        .mst-blog-related-title {
            font-size: 0.9375rem;
            font-weight: 600;
            color: var(--heading-color);
            margin: 0 0 8px 0;
            line-height: 1.3;
        }
        
        .mst-blog-related-price {
            font-size: 1rem;
            font-weight: 700;
            color: var(--primary-color);
        }
        
        /* Author Bio */
        .mst-blog-author-bio-content {
            display: flex;
            gap: 20px;
            align-items: flex-start;
        }
        
        .mst-blog-author-bio-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            border: 3px solid var(--primary-color);
            flex-shrink: 0;
        }
        
        .mst-blog-author-bio-info {
            flex: 1;
        }
        
        .mst-blog-author-bio-name {
            font-size: 1.125rem;
            font-weight: 700;
            color: var(--heading-color);
            margin: 0 0 8px 0;
        }
        
        .mst-blog-author-bio-text {
            font-size: 0.9375rem;
            color: var(--text-color);
            line-height: 1.6;
            margin: 0;
        }
        
        /* Share */
        .mst-blog-share {
            display: flex;
            align-items: center;
            gap: 16px;
            flex-wrap: wrap;
        }
        
        .mst-blog-share-label {
            font-weight: 600;
            color: var(--heading-color);
        }
        
        .mst-blog-share-buttons {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        
        .mst-blog-share-btn {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-decoration: none;
            transition: transform 0.2s, box-shadow 0.2s;
            border: none;
            cursor: pointer;
        }
        
        .mst-blog-share-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px -4px rgba(0, 0, 0, 0.2);
        }
        
        .mst-share-telegram { background: linear-gradient(135deg, #0088cc, #229ED9); }
        .mst-share-whatsapp { background: linear-gradient(135deg, #25D366, #128C7E); }
        .mst-share-vk { background: linear-gradient(135deg, #4a76a8, #5181B8); }
        .mst-share-facebook { background: linear-gradient(135deg, #1877F2, #4267B2); }
        .mst-share-twitter { background: linear-gradient(135deg, #1DA1F2, #000); }
        .mst-share-copy { 
            background: linear-gradient(135deg, var(--primary-color), #7B3FC4);
            color: white;
        }
        
        /* Footer / Tags */
        .mst-blog-footer {
            padding: 24px 32px;
        }
        
        .mst-blog-tags {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }
        
        .mst-blog-tags-label {
            font-weight: 600;
            color: var(--heading-color);
        }
        
        .mst-blog-tag {
            display: inline-block;
            padding: 6px 14px;
            background: rgba(153, 82, 224, 0.08);
            color: var(--primary-color);
            font-size: 0.875rem;
            font-weight: 500;
            border-radius: 20px;
            text-decoration: none;
            transition: background 0.2s, color 0.2s;
        }
        
        .mst-blog-tag:hover {
            background: var(--primary-color);
            color: #fff;
        }
        
        /* Mobile */
        @media (max-width: 768px) {
            .mst-blog-gallery-grid,
            .mst-blog-checklist-list {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .mst-blog-title {
                font-size: 1.625rem;
            }
            
            .mst-blog-header,
            .mst-blog-section,
            .mst-blog-footer {
                padding: 24px 20px;
            }
            
            .mst-blog-hero {
                max-height: 300px;
            }
            
            .mst-blog-author-bio-content {
                flex-direction: column;
                align-items: center;
                text-align: center;
            }
            
            .mst-blog-quote {
                padding: 32px 24px;
            }
            
            .mst-blog-quote-text {
                font-size: 1.125rem;
            }
        }
        
        @media (max-width: 480px) {
            .mst-blog-gallery-grid,
            .mst-blog-related-grid,
            .mst-blog-checklist-list {
                grid-template-columns: 1fr;
            }
            
            .mst-blog-meta {
                flex-direction: column;
                gap: 12px;
            }
            
            .mst-blog-share {
                flex-direction: column;
                align-items: flex-start;
            }
        }
        </style>
        
        <script>
        (function() {
            document.addEventListener('DOMContentLoaded', function() {
                var container = document.getElementById('<?php echo esc_js($unique_id); ?>');
                if (!container) return;
                
                // FAQ Accordion
                var faqItems = container.querySelectorAll('.mst-blog-faq-item');
                faqItems.forEach(function(item) {
                    var question = item.querySelector('.mst-blog-faq-question');
                    if (question) {
                        question.addEventListener('click', function() {
                            var isActive = item.classList.contains('active');
                            // Close all
                            faqItems.forEach(function(i) { i.classList.remove('active'); });
                            // Open clicked if wasn't active
                            if (!isActive) {
                                item.classList.add('active');
                                question.setAttribute('aria-expanded', 'true');
                            } else {
                                question.setAttribute('aria-expanded', 'false');
                            }
                        });
                    }
                });
                
                // Gallery Lightbox
                var galleryItems = container.querySelectorAll('.mst-blog-gallery-item');
                if (galleryItems.length) {
                    var lightbox = document.createElement('div');
                    lightbox.className = 'mst-blog-lightbox';
                    lightbox.innerHTML = '<button class="mst-blog-lightbox-close">&times;</button><img src="" alt="">';
                    document.body.appendChild(lightbox);
                    
                    var lightboxImg = lightbox.querySelector('img');
                    var lightboxClose = lightbox.querySelector('.mst-blog-lightbox-close');
                    
                    galleryItems.forEach(function(item) {
                        item.addEventListener('click', function() {
                            var img = item.querySelector('img');
                            var fullUrl = img.getAttribute('data-full') || img.src;
                            lightboxImg.src = fullUrl;
                            lightboxImg.alt = img.alt;
                            lightbox.classList.add('active');
                            document.body.style.overflow = 'hidden';
                        });
                    });
                    
                    lightboxClose.addEventListener('click', closeLightbox);
                    lightbox.addEventListener('click', function(e) {
                        if (e.target === lightbox) closeLightbox();
                    });
                    
                    document.addEventListener('keydown', function(e) {
                        if (e.key === 'Escape') closeLightbox();
                    });
                    
                    function closeLightbox() {
                        lightbox.classList.remove('active');
                        document.body.style.overflow = '';
                    }
                }
                
                // Copy link
                var copyBtn = container.querySelector('.mst-share-copy');
                if (copyBtn) {
                    copyBtn.addEventListener('click', function() {
                        var url = this.getAttribute('data-url');
                        navigator.clipboard.writeText(url).then(function() {
                            copyBtn.innerHTML = '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg>';
                            setTimeout(function() {
                                copyBtn.innerHTML = '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg>';
                            }, 2000);
                        });
                    });
                }
                
                // Smooth scroll for TOC
                var tocLinks = container.querySelectorAll('.mst-blog-toc-link');
                tocLinks.forEach(function(link) {
                    link.addEventListener('click', function(e) {
                        var targetId = this.getAttribute('href').substring(1);
                        var target = document.getElementById(targetId);
                        if (target) {
                            e.preventDefault();
                            target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                        }
                    });
                });
            });
        })();
        </script>
        
        <?php
    }
}
