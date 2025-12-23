<?php
/**
 * Админ-страница управления картой
 * 
 * @package MySuperTour_Map
 * @author Telegram @l1ghtsun
 * @link https://t.me/l1ghtsun
 */

if(!defined('ABSPATH')) exit;
?>

<div class="wrap mst-hub-wrap">
    <div class="mst-hub-header">
        <h1 class="mst-hub-title">
            🗺️ Карта экскурсий
            <span class="mst-version-badge">v<?php echo MST_MAP_VERSION; ?></span>
        </h1>
    </div>
    
    <?php if (isset($_GET['updated'])): ?>
    <div class="mst-save-notice">
        ✅ <?php echo $_GET['updated'] === 'true' ? 'Настройки сохранены!' : 'Обновлено товаров: ' . intval($_GET['updated']); ?>
    </div>
    <?php endif; ?>
    
    <!-- СТАТИСТИКА -->
    <div class="mst-stats-grid">
        <div class="mst-stat-card">
            <div class="mst-stat-value"><?php echo count($products_with_coords); ?></div>
            <div class="mst-stat-label">Товаров на карте</div>
        </div>
        <div class="mst-stat-card">
            <div class="mst-stat-value"><?php echo count($products_without_coords); ?></div>
            <div class="mst-stat-label">Без координат</div>
        </div>
        <div class="mst-stat-card">
            <div class="mst-stat-value">4</div>
            <div class="mst-stat-label">Города</div>
        </div>
    </div>
    
    <!-- НАСТРОЙКИ -->
    <div class="mst-panel" style="margin: 30px 0;">
        <h2><span class="mst-section-icon">⚙️</span> Настройки карты</h2>
        
        <form method="post" action="<?php echo admin_url('admin-post.php'); ?>">
            <input type="hidden" name="action" value="mst_map_save_settings">
            <?php wp_nonce_field('mst_map_settings', 'mst_map_nonce'); ?>
            
            <div class="mst-form-group">
                <label class="mst-form-label">Google Maps API Key</label>
                <input type="text" name="google_api_key" class="mst-form-control" 
                       value="<?php echo esc_attr($settings['google_api_key']); ?>" 
                       placeholder="AIzaSyXXXXXXXXXXXXXXXXXXXXXXXX">
                <p style="font-size:13px;color:#666;margin-top:8px;">
                    Получите ключ на <a href="https://console.cloud.google.com/google/maps-apis" target="_blank">Google Cloud Console</a>
                </p>
            </div>
            
            <div class="mst-admin-layout" style="grid-template-columns: 1fr 1fr 1fr;">
                <div class="mst-form-group">
                    <label class="mst-form-label">Стандартный зум</label>
                    <input type="number" name="default_zoom" class="mst-form-control" 
                           value="<?php echo $settings['default_zoom']; ?>" min="1" max="20">
                </div>
                
                <div class="mst-form-group">
                    <label class="mst-form-label">Стиль карты</label>
                    <select name="map_style" class="mst-form-control">
                        <option value="standard" <?php selected($settings['map_style'], 'standard'); ?>>Стандартная</option>
                        <option value="silver" <?php selected($settings['map_style'], 'silver'); ?>>Серебряная</option>
                        <option value="retro" <?php selected($settings['map_style'], 'retro'); ?>>Ретро</option>
                        <option value="dark" <?php selected($settings['map_style'], 'dark'); ?>>Темная</option>
                    </select>
                </div>
                
                <div class="mst-form-group">
                    <label class="mst-form-label">Цвет маркеров</label>
                    <input type="color" name="marker_color" class="mst-form-control" 
                           value="<?php echo $settings['marker_color']; ?>">
                </div>
            </div>
            
            <div class="mst-form-group">
                <label style="display:flex;align-items:center;gap:10px;">
                    <input type="checkbox" name="cluster_enabled" value="1" 
                           <?php checked($settings['cluster_enabled']); ?> style="width:20px;height:20px;">
                    <span style="font-weight:600;">Включить кластеризацию маркеров</span>
                </label>
            </div>
            
            <div class="mst-form-group">
                <label style="display:flex;align-items:center;gap:10px;">
                    <input type="checkbox" name="show_price_on_marker" value="1" 
                           <?php checked($settings['show_price_on_marker']); ?> style="width:20px;height:20px;">
                    <span style="font-weight:600;">Показывать цену на маркерах</span>
                </label>
            </div>
            
            <button type="submit" class="mst-btn mst-btn-primary" style="width:100%;">
                💾 Сохранить настройки
            </button>
        </form>
    </div>
    
    <!-- ТОВАРЫ С КООРДИНАТАМИ -->
    <div class="mst-panel" style="margin: 30px 0;">
        <h2><span class="mst-section-icon">📍</span> Товары на карте (<?php echo count($products_with_coords); ?>)</h2>
        
        <?php if (empty($products_with_coords)): ?>
        <div class="mst-empty-state">
            <div class="mst-empty-icon">🗺️</div>
            <p>Нет товаров с координатами</p>
        </div>
        <?php else: ?>
        
        <form method="post" action="<?php echo admin_url('admin-post.php'); ?>">
            <input type="hidden" name="action" value="mst_map_bulk_update">
            <?php wp_nonce_field('mst_map_bulk_update', 'mst_map_nonce'); ?>
            
            <div class="mst-products-list">
                <?php foreach ($products_with_coords as $product): ?>
                <div class="mst-product-item">
                    <div class="mst-product-thumb">
                        <?php echo $product['thumbnail']; ?>
                    </div>
                    <div class="mst-product-info" style="flex:1;">
                        <h4><?php echo esc_html($product['title']); ?></h4>
                        <p class="mst-meta">
                            ID: <?php echo $product['id']; ?> | 
                            Город: <strong><?php echo esc_html($product['city']); ?></strong> |
                            <a href="<?php echo $product['edit_url']; ?>" target="_blank">Редактировать</a>
                        </p>
                        <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:10px;margin-top:10px;">
                            <div>
                                <label style="font-size:12px;color:#666;">Широта</label>
                                <input type="text" name="coordinates[<?php echo $product['id']; ?>][lat]" 
                                       value="<?php echo esc_attr($product['lat']); ?>" 
                                       class="mst-form-control" style="padding:6px;">
                            </div>
                            <div>
                                <label style="font-size:12px;color:#666;">Долгота</label>
                                <input type="text" name="coordinates[<?php echo $product['id']; ?>][lng]" 
                                       value="<?php echo esc_attr($product['lng']); ?>" 
                                       class="mst-form-control" style="padding:6px;">
                            </div>
                            <div>
                                <label style="font-size:12px;color:#666;">Город</label>
                                <input type="text" name="coordinates[<?php echo $product['id']; ?>][city]" 
                                       value="<?php echo esc_attr($product['city']); ?>" 
                                       class="mst-form-control" style="padding:6px;">
                            </div>
                        </div>
                    </div>
                    <div>
                        <a href="https://www.google.com/maps?q=<?php echo $product['lat']; ?>,<?php echo $product['lng']; ?>" 
                           target="_blank" class="mst-btn mst-btn-secondary" style="padding:8px 16px;font-size:12px;">
                            🌍 Карта
                        </a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            
            <button type="submit" class="mst-btn mst-btn-primary" style="width:100%;margin-top:20px;">
                💾 Массово обновить координаты
            </button>
        </form>
        <?php endif; ?>
    </div>
    
    <!-- ТОВАРЫ БЕЗ КООРДИНАТ -->
    <?php if (!empty($products_without_coords)): ?>
    <div class="mst-panel" style="margin: 30px 0;">
        <h2><span class="mst-section-icon">⚠️</span> Товары без координат (<?php echo count($products_without_coords); ?>)</h2>
        
        <div class="mst-products-list">
            <?php foreach ($products_without_coords as $product): ?>
            <div class="mst-product-item">
                <div class="mst-product-thumb">
                    <?php echo $product['thumbnail']; ?>
                </div>
                <div class="mst-product-info" style="flex:1;">
                    <h4><?php echo esc_html($product['title']); ?></h4>
                    <p class="mst-meta">ID: <?php echo $product['id']; ?></p>
                </div>
                <div>
                    <a href="<?php echo $product['edit_url']; ?>" class="mst-btn mst-btn-primary" style="padding:8px 16px;">
                        ✏️ Добавить координаты
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>
    
    <!-- ШОРТКОД -->
    <div class="mst-panel" style="margin: 30px 0;">
        <h2><span class="mst-section-icon">📝</span> Шорткод для вставки карты</h2>
        
        <p style="margin-bottom:15px;">Используйте эти шорткоды для вставки карты на страницу:</p>
        
        <div style="background:#f9f9f9;padding:15px;border-radius:8px;margin-bottom:10px;">
            <code style="background:#fff;padding:8px 12px;border-radius:6px;display:inline-block;">[mst_map]</code>
            <p style="font-size:13px;color:#666;margin:8px 0 0;">Карта со всеми экскурсиями</p>
        </div>
        
        <div style="background:#f9f9f9;padding:15px;border-radius:8px;margin-bottom:10px;">
            <code style="background:#fff;padding:8px 12px;border-radius:6px;display:inline-block;">[mst_map city="Париж"]</code>
            <p style="font-size:13px;color:#666;margin:8px 0 0;">Карта экскурсий только в Париже</p>
        </div>
        
        <div style="background:#f9f9f9;padding:15px;border-radius:8px;margin-bottom:10px;">
            <code style="background:#fff;padding:8px 12px;border-radius:6px;display:inline-block;">[mst_map height="800px" zoom="14"]</code>
            <p style="font-size:13px;color:#666;margin:8px 0 0;">Карта с высотой 800px и зумом 14</p>
        </div>
        
        <div style="background:#f9f9f9;padding:15px;border-radius:8px;">
            <code style="background:#fff;padding:8px 12px;border-radius:6px;display:inline-block;">[mst_map city="Прага" show_list="no"]</code>
            <p style="font-size:13px;color:#666;margin:8px 0 0;">Только карта без списка товаров</p>
        </div>
    </div>
</div>