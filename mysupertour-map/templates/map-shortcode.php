<?php
/**
 * Шаблон шорткода карты для frontend
 * Author: Telegram @l1ghtsun
 * Author URI: https://t.me/l1ghtsun
 */

if(!defined('ABSPATH')) exit;

// Получаем форматы и транспорт
$formats = get_option('mst_formats', []);
$transports = get_option('mst_transports', []);

// Добавляем иконки к products
foreach ($products as &$product) {
    if ($product['format'] && isset($formats[$product['format']])) {
        $product['format_icon'] = $formats[$product['format']]['icon'];
        $product['format_name'] = $formats[$product['format']]['name'];
    }
    if ($product['transport'] && isset($transports[$product['transport']])) {
        $product['transport_icon'] = $transports[$product['transport']]['icon'];
        $product['transport_name'] = $transports[$product['transport']]['name'];
    }
}
?>

<div class="mst-map-container" data-products='<?php echo esc_attr(json_encode($products)); ?>'>
    <!-- КАРТА -->
    <div class="mst-map-wrapper" style="height: <?php echo esc_attr($atts['height']); ?>;">
        <div id="mst-google-map" class="mst-google-map"></div>
    </div>
    
    <?php if ($atts['show_list'] === 'yes' && !empty($products)): ?>
    <!-- СПИСОК ТОВАРОВ ПОД КАРТОЙ -->
    <div class="mst-map-products-list">
        <div class="mst-map-list-header">
            <h3>📍 Найдено экскурсий: <?php echo count($products); ?></h3>
            <div class="mst-map-filters">
                <select id="mst-map-city-filter" class="mst-map-select">
                    <option value="">Все города</option>
                    <?php
                    $cities = array_unique(array_column($products, 'city'));
                    foreach ($cities as $city):
                        if (!empty($city)):
                    ?>
                    <option value="<?php echo esc_attr($city); ?>"><?php echo esc_html($city); ?></option>
                    <?php 
                        endif;
                    endforeach; 
                    ?>
                </select>
            </div>
        </div>
        
        <div class="mst-map-products-grid" id="mst-map-products-grid">
            <?php foreach ($products as $product): ?>
            <div class="mst-map-product-card" data-product-id="<?php echo $product['id']; ?>" data-city="<?php echo esc_attr($product['city']); ?>">
                <div class="mst-map-product-image">
                    <?php if ($product['image']): ?>
                    <img src="<?php echo esc_url($product['image']); ?>" alt="<?php echo esc_attr($product['title']); ?>">
                    <?php else: ?>
                    <div style="height:100%;background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);"></div>
                    <?php endif; ?>
                    
                    <?php if (!empty($product['format_name']) || !empty($product['duration']) || !empty($product['transport_icon'])): ?>
                    <div class="mst-map-product-badge">
                        <?php if (!empty($product['format_icon']) && !empty($product['format_name'])): ?>
                        <span><?php echo $product['format_icon']; ?> <?php echo esc_html($product['format_name']); ?></span>
                        <?php endif; ?>
                        
                        <?php if (!empty($product['duration'])): ?>
                        <span>⏱ <?php echo esc_html($product['duration']); ?></span>
                        <?php endif; ?>
                        
                        <?php if (!empty($product['transport_icon'])): ?>
                        <span><?php echo $product['transport_icon']; ?></span>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                </div>
                
                <div class="mst-map-product-content">
                    <h4><?php echo esc_html($product['title']); ?></h4>
                    
                    <?php if ($product['city']): ?>
                    <p class="mst-map-product-city">📍 <?php echo esc_html($product['city']); ?></p>
                    <?php endif; ?>
                    
                    <?php if ($product['excerpt']): ?>
                    <p class="mst-map-product-excerpt"><?php echo esc_html($product['excerpt']); ?></p>
                    <?php endif; ?>
                    
                    <div class="mst-map-product-footer">
                        <div class="mst-map-product-price">
                            <?php echo $product['price_html']; ?>
                        </div>
                        <a href="<?php echo esc_url($product['url']); ?>" class="mst-map-product-button">
                            Подробнее →
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>
</div>