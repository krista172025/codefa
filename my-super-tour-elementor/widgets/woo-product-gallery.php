<?php
/**
 * WooCommerce Product Gallery Widget
 * 
 * Displays product gallery images from WooCommerce product
 * Supports masonry/grid layout with lightbox
 */

namespace MySuperTour\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if (!defined('ABSPATH')) exit;

class Woo_Product_Gallery extends Widget_Base {

    public function get_name() {
        return 'mst_woo_product_gallery';
    }

    public function get_title() {
        return __('WooCommerce Product Gallery', 'my-super-tour-elementor');
    }

    public function get_icon() {
        return 'eicon-gallery-grid';
    }

    public function get_categories() {
        return ['my-super-tour'];
    }

    protected function register_controls() {
        // Content Section
        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Gallery Settings', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'product_source',
            [
                'label' => __('Product Source', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SELECT,
                'default' => 'current',
                'options' => [
                    'current' => __('Current Product', 'my-super-tour-elementor'),
                    'manual' => __('Select Product', 'my-super-tour-elementor'),
                ],
            ]
        );

        $this->add_control(
            'product_id',
            [
                'label' => __('Product ID', 'my-super-tour-elementor'),
                'type' => Controls_Manager::NUMBER,
                'default' => '',
                'condition' => ['product_source' => 'manual'],
                'description' => __('Enter WooCommerce Product ID', 'my-super-tour-elementor'),
            ]
        );

        $this->add_control(
            'gallery_layout',
            [
                'label' => __('Gallery Layout', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SELECT,
                'default' => 'masonry',
                'options' => [
                    'masonry' => __('Masonry (1 large + small)', 'my-super-tour-elementor'),
                    'grid' => __('Grid', 'my-super-tour-elementor'),
                    'slider' => __('Slider', 'my-super-tour-elementor'),
                ],
            ]
        );

        $this->add_responsive_control(
            'columns',
            [
                'label' => __('Columns', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SELECT,
                'default' => '3',
                'options' => [
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                ],
                'condition' => ['gallery_layout' => 'grid'],
            ]
        );

        $this->add_control(
            'max_images',
            [
                'label' => __('Max Images to Show', 'my-super-tour-elementor'),
                'type' => Controls_Manager::NUMBER,
                'default' => 4,
                'min' => 1,
                'max' => 20,
            ]
        );

        $this->add_control(
            'show_counter',
            [
                'label' => __('Show Remaining Counter', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'description' => __('Show "+X" counter on last image if more images available', 'my-super-tour-elementor'),
            ]
        );

        $this->add_control(
            'enable_lightbox',
            [
                'label' => __('Enable Lightbox', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'include_featured',
            [
                'label' => __('Include Featured Image', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->end_controls_section();

        // Style Section
        $this->start_controls_section(
            'style_section',
            [
                'label' => __('Style', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'image_gap',
            [
                'label' => __('Image Gap', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 30,
                        'step' => 2,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 8,
                ],
            ]
        );

        $this->add_responsive_control(
            'image_border_radius',
            [
                'label' => __('Border Radius', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 30,
                        'step' => 2,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 12,
                ],
            ]
        );

        $this->add_responsive_control(
            'main_image_height',
            [
                'label' => __('Main Image Height', 'my-super-tour-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'vh'],
                'range' => [
                    'px' => [
                        'min' => 200,
                        'max' => 800,
                        'step' => 10,
                    ],
                    'vh' => [
                        'min' => 20,
                        'max' => 80,
                        'step' => 5,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 400,
                ],
                'condition' => ['gallery_layout' => 'masonry'],
            ]
        );

        $this->add_control(
            'overlay_color',
            [
                'label' => __('Hover Overlay', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(0, 0, 0, 0.3)',
            ]
        );

        $this->add_control(
            'counter_bg_color',
            [
                'label' => __('Counter Background', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(0, 0, 0, 0.6)',
            ]
        );

        $this->add_control(
            'counter_text_color',
            [
                'label' => __('Counter Text Color', 'my-super-tour-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        
        // Get product ID
        $product_id = 0;
        if ($settings['product_source'] === 'current') {
            global $product;
            if ($product) {
                $product_id = $product->get_id();
            } else {
                $product_id = get_the_ID();
            }
        } else {
            $product_id = intval($settings['product_id']);
        }
        
        if (!$product_id) {
            echo '<p>' . __('No product found. Please select a product or use this widget on a product page.', 'my-super-tour-elementor') . '</p>';
            return;
        }
        
        // Get product
        $product = wc_get_product($product_id);
        if (!$product) {
            echo '<p>' . __('Product not found.', 'my-super-tour-elementor') . '</p>';
            return;
        }
        
        // Get gallery images
        $gallery_ids = $product->get_gallery_image_ids();
        
        // Include featured image if enabled
        if ($settings['include_featured'] === 'yes') {
            $featured_id = $product->get_image_id();
            if ($featured_id) {
                array_unshift($gallery_ids, $featured_id);
            }
        }
        
        if (empty($gallery_ids)) {
            echo '<p>' . __('No gallery images found for this product.', 'my-super-tour-elementor') . '</p>';
            return;
        }
        
        // Remove duplicates
        $gallery_ids = array_unique($gallery_ids);
        
        $total_images = count($gallery_ids);
        $max_images = intval($settings['max_images']);
        $show_counter = $settings['show_counter'] === 'yes' && $total_images > $max_images;
        $remaining = $total_images - $max_images;
        
        // Limit images
        $display_ids = array_slice($gallery_ids, 0, $max_images);
        
        // Style variables
        $layout = $settings['gallery_layout'];
        $gap = $settings['image_gap']['size'] ?? 8;
        $radius = $settings['image_border_radius']['size'] ?? 12;
        $main_height = $settings['main_image_height']['size'] ?? 400;
        $main_height_unit = $settings['main_image_height']['unit'] ?? 'px';
        $overlay = $settings['overlay_color'];
        $counter_bg = $settings['counter_bg_color'];
        $counter_text = $settings['counter_text_color'];
        $columns = $settings['columns'] ?? 3;
        
        $unique_id = 'mst-gallery-' . uniqid();
        ?>
        
        <style>
            #<?php echo esc_attr($unique_id); ?> {
                width: 100%;
            }
            
            #<?php echo esc_attr($unique_id); ?> .mst-gallery-masonry {
                display: grid;
                grid-template-columns: 2fr 1fr;
                grid-template-rows: 1fr 1fr;
                gap: <?php echo esc_attr($gap); ?>px;
                height: <?php echo esc_attr($main_height . $main_height_unit); ?>;
            }
            
            #<?php echo esc_attr($unique_id); ?> .mst-gallery-masonry .mst-gallery-item:first-child {
                grid-row: 1 / 3;
            }
            
            #<?php echo esc_attr($unique_id); ?> .mst-gallery-grid {
                display: grid;
                grid-template-columns: repeat(<?php echo esc_attr($columns); ?>, 1fr);
                gap: <?php echo esc_attr($gap); ?>px;
            }
            
            #<?php echo esc_attr($unique_id); ?> .mst-gallery-item {
                position: relative;
                overflow: hidden;
                border-radius: <?php echo esc_attr($radius); ?>px;
                cursor: pointer;
            }
            
            #<?php echo esc_attr($unique_id); ?> .mst-gallery-item img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                transition: transform 0.4s ease;
            }
            
            #<?php echo esc_attr($unique_id); ?> .mst-gallery-item:hover img {
                transform: scale(1.05);
            }
            
            #<?php echo esc_attr($unique_id); ?> .mst-gallery-item::after {
                content: '';
                position: absolute;
                inset: 0;
                background: <?php echo esc_attr($overlay); ?>;
                opacity: 0;
                transition: opacity 0.3s ease;
            }
            
            #<?php echo esc_attr($unique_id); ?> .mst-gallery-item:hover::after {
                opacity: 1;
            }
            
            #<?php echo esc_attr($unique_id); ?> .mst-gallery-counter {
                position: absolute;
                bottom: 12px;
                right: 12px;
                background: <?php echo esc_attr($counter_bg); ?>;
                color: <?php echo esc_attr($counter_text); ?>;
                padding: 8px 16px;
                border-radius: 8px;
                font-size: 16px;
                font-weight: 600;
                z-index: 2;
                pointer-events: none;
            }
            
            /* Lightbox Styles */
            #<?php echo esc_attr($unique_id); ?>-lightbox {
                display: none;
                position: fixed;
                inset: 0;
                background: rgba(0, 0, 0, 0.95);
                z-index: 99999;
                align-items: center;
                justify-content: center;
            }
            
            #<?php echo esc_attr($unique_id); ?>-lightbox.active {
                display: flex;
            }
            
            #<?php echo esc_attr($unique_id); ?>-lightbox .mst-lightbox-content {
                position: relative;
                max-width: 90vw;
                max-height: 90vh;
            }
            
            #<?php echo esc_attr($unique_id); ?>-lightbox .mst-lightbox-content img {
                max-width: 100%;
                max-height: 90vh;
                object-fit: contain;
                border-radius: 8px;
            }
            
            #<?php echo esc_attr($unique_id); ?>-lightbox .mst-lightbox-close {
                position: absolute;
                top: -40px;
                right: 0;
                background: transparent;
                border: none;
                color: white;
                font-size: 32px;
                cursor: pointer;
                padding: 8px;
                line-height: 1;
            }
            
            #<?php echo esc_attr($unique_id); ?>-lightbox .mst-lightbox-nav {
                position: absolute;
                top: 50%;
                transform: translateY(-50%);
                background: rgba(255, 255, 255, 0.2);
                border: none;
                color: white;
                font-size: 24px;
                cursor: pointer;
                padding: 16px;
                border-radius: 50%;
                transition: background 0.2s;
            }
            
            #<?php echo esc_attr($unique_id); ?>-lightbox .mst-lightbox-nav:hover {
                background: rgba(255, 255, 255, 0.4);
            }
            
            #<?php echo esc_attr($unique_id); ?>-lightbox .mst-lightbox-prev {
                left: -60px;
            }
            
            #<?php echo esc_attr($unique_id); ?>-lightbox .mst-lightbox-next {
                right: -60px;
            }
            
            #<?php echo esc_attr($unique_id); ?>-lightbox .mst-lightbox-counter {
                position: absolute;
                bottom: -40px;
                left: 50%;
                transform: translateX(-50%);
                color: white;
                font-size: 14px;
            }
            
            /* Responsive */
            @media (max-width: 768px) {
                #<?php echo esc_attr($unique_id); ?> .mst-gallery-masonry {
                    grid-template-columns: 1fr 1fr;
                    height: auto;
                }
                
                #<?php echo esc_attr($unique_id); ?> .mst-gallery-masonry .mst-gallery-item:first-child {
                    grid-row: auto;
                    grid-column: 1 / 3;
                    aspect-ratio: 16/9;
                }
                
                #<?php echo esc_attr($unique_id); ?> .mst-gallery-masonry .mst-gallery-item:not(:first-child) {
                    aspect-ratio: 1;
                }
                
                #<?php echo esc_attr($unique_id); ?> .mst-gallery-grid {
                    grid-template-columns: repeat(2, 1fr);
                }
                
                #<?php echo esc_attr($unique_id); ?>-lightbox .mst-lightbox-nav {
                    display: none;
                }
            }
            
            @media (max-width: 480px) {
                #<?php echo esc_attr($unique_id); ?> .mst-gallery-masonry {
                    grid-template-columns: 1fr;
                }
                
                #<?php echo esc_attr($unique_id); ?> .mst-gallery-masonry .mst-gallery-item:first-child {
                    grid-column: auto;
                }
                
                #<?php echo esc_attr($unique_id); ?> .mst-gallery-masonry .mst-gallery-item {
                    aspect-ratio: 16/9;
                }
            }
        </style>
        
        <div id="<?php echo esc_attr($unique_id); ?>" class="mst-product-gallery">
            <div class="mst-gallery-<?php echo esc_attr($layout); ?>">
                <?php 
                foreach ($display_ids as $index => $image_id): 
                    $full_url = wp_get_attachment_image_url($image_id, 'full');
                    $thumb_url = wp_get_attachment_image_url($image_id, 'large');
                    $is_last = $index === count($display_ids) - 1;
                ?>
                <div class="mst-gallery-item" data-index="<?php echo esc_attr($index); ?>" data-full="<?php echo esc_url($full_url); ?>">
                    <img src="<?php echo esc_url($thumb_url); ?>" alt="<?php echo esc_attr($product->get_name()); ?> - <?php echo ($index + 1); ?>" loading="lazy">
                    <?php if ($show_counter && $is_last): ?>
                    <div class="mst-gallery-counter">+<?php echo esc_html($remaining); ?></div>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        
        <?php if ($settings['enable_lightbox'] === 'yes'): ?>
        <div id="<?php echo esc_attr($unique_id); ?>-lightbox" class="mst-product-gallery-lightbox">
            <div class="mst-lightbox-content">
                <button class="mst-lightbox-close">&times;</button>
                <button class="mst-lightbox-nav mst-lightbox-prev">&larr;</button>
                <img src="" alt="Gallery Image">
                <button class="mst-lightbox-nav mst-lightbox-next">&rarr;</button>
                <div class="mst-lightbox-counter"><span class="current">1</span> / <span class="total"><?php echo count($gallery_ids); ?></span></div>
            </div>
        </div>
        
        <script>
        (function() {
            const gallery = document.getElementById('<?php echo esc_js($unique_id); ?>');
            const lightbox = document.getElementById('<?php echo esc_js($unique_id); ?>-lightbox');
            if (!gallery || !lightbox) return;
            
            const allImages = <?php echo json_encode(array_map(function($id) { return wp_get_attachment_image_url($id, 'full'); }, $gallery_ids)); ?>;
            let currentIndex = 0;
            
            const img = lightbox.querySelector('img');
            const counter = lightbox.querySelector('.mst-lightbox-counter');
            const currentSpan = counter.querySelector('.current');
            
            function showImage(index) {
                currentIndex = index;
                if (currentIndex < 0) currentIndex = allImages.length - 1;
                if (currentIndex >= allImages.length) currentIndex = 0;
                img.src = allImages[currentIndex];
                currentSpan.textContent = currentIndex + 1;
            }
            
            gallery.querySelectorAll('.mst-gallery-item').forEach(item => {
                item.addEventListener('click', () => {
                    const index = parseInt(item.dataset.index);
                    showImage(index);
                    lightbox.classList.add('active');
                    document.body.style.overflow = 'hidden';
                });
            });
            
            lightbox.querySelector('.mst-lightbox-close').addEventListener('click', () => {
                lightbox.classList.remove('active');
                document.body.style.overflow = '';
            });
            
            lightbox.querySelector('.mst-lightbox-prev').addEventListener('click', () => {
                showImage(currentIndex - 1);
            });
            
            lightbox.querySelector('.mst-lightbox-next').addEventListener('click', () => {
                showImage(currentIndex + 1);
            });
            
            lightbox.addEventListener('click', (e) => {
                if (e.target === lightbox) {
                    lightbox.classList.remove('active');
                    document.body.style.overflow = '';
                }
            });
            
            document.addEventListener('keydown', (e) => {
                if (!lightbox.classList.contains('active')) return;
                if (e.key === 'Escape') {
                    lightbox.classList.remove('active');
                    document.body.style.overflow = '';
                }
                if (e.key === 'ArrowLeft') showImage(currentIndex - 1);
                if (e.key === 'ArrowRight') showImage(currentIndex + 1);
            });
        })();
        </script>
        <?php endif; ?>
        <?php
    }
}
