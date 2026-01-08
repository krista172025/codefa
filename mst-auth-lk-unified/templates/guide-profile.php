<?php
/**
 * Guide Profile Template - FULL VERSION v4.1
 * Features: New Lightbox with Liquid Glass, AJAX pagination, bigger cards
 * Author: Telegram @l1ghtsun
 */
if (!defined('ABSPATH')) exit;

$guide_id = isset($_GET['guide_id']) ? intval($_GET['guide_id']) : 0;
$guide = get_userdata($guide_id);

if (!$guide) {
    echo '<div class="mst-guide-not-found"><div class="mst-guide-not-found-icon">👤</div><h2>' . __('Гид не найден', 'mst-auth-lk') . '</h2><p>' . __('Возможно, профиль был удален или ссылка недействительна', 'mst-auth-lk') . '</p></div>';
    return;
}

$custom_avatar = get_user_meta($guide_id, 'mst_lk_avatar', true);
$avatar_url = $custom_avatar ? wp_get_attachment_url($custom_avatar) : get_avatar_url($guide_id, ['size' => 200]);

$rating = get_user_meta($guide_id, 'mst_guide_rating', true) ?: '5.0';
$reviews_count = get_user_meta($guide_id, 'mst_guide_reviews_count', true) ?: '0';
$bio = get_user_meta($guide_id, 'mst_guide_bio', true) ?: get_user_meta($guide_id, 'mst_guide_experience', true) ?: '';
$languages = get_user_meta($guide_id, 'mst_guide_languages', true) ?: '';
$specialization = get_user_meta($guide_id, 'mst_guide_specialization', true) ?: '';
$city = get_user_meta($guide_id, 'mst_guide_city', true) ?: '';
$experience_years = get_user_meta($guide_id, 'mst_guide_experience_years', true) ?: '8';
$tours_count = get_user_meta($guide_id, 'mst_guide_tours_count', true) ?: '0';
$achievements = get_user_meta($guide_id, 'mst_guide_achievements', true) ?: '';
$is_verified = get_user_meta($guide_id, 'mst_guide_verified', true);
$academic_title = get_user_meta($guide_id, 'mst_guide_academic_title', true) ?: '';

// Get profile display settings
$profile_settings = get_option('mst_guide_profile_settings', [
    'card_width' => '380',
    'card_padding' => '20',
    'avatar_size' => '60',
    'title_size' => '16',
    'text_size' => '14',
    'reviews_per_page' => '9',
]);

$primary_color = '#9952E0';
$secondary_color = '#fbd603';
$rating_color = '#fbd603';
$verified_color = '#10b981';

// Referrer URL for back button
$referrer_url = isset($_SERVER['HTTP_REFERER']) ? esc_url($_SERVER['HTTP_REFERER']) : home_url('/shop/');

// Pagination
$reviews_per_page = intval($profile_settings['reviews_per_page'] ?? 9);
$current_page = 1; // Always start at 1, AJAX loads more

// Get guide's tours
$tours_query = new WP_Query([
    'post_type' => 'product',
    'posts_per_page' => 6,
    'meta_query' => [[
        'key' => '_mst_guide_id',
        'value' => $guide_id,
        'compare' => '='
    ]]
]);

// Get REAL reviews with all meta (photos, city, tour info)
global $wpdb;
$total_reviews = $wpdb->get_var($wpdb->prepare(
    "SELECT COUNT(DISTINCT c.comment_ID)
     FROM {$wpdb->comments} c
     JOIN {$wpdb->commentmeta} cm3 ON c.comment_ID = cm3.comment_id AND cm3.meta_key = 'mst_guide_id' AND cm3.meta_value = %d
     WHERE c.comment_approved = 1 AND c.comment_type = 'review'",
    $guide_id
));

$reviews = $wpdb->get_results($wpdb->prepare(
    "SELECT c.*, 
            cm.meta_value as rating, 
            cm2.meta_value as user_city, 
            cm4.meta_value as review_photos,
            cm5.meta_value as author_avatar_id
     FROM {$wpdb->comments} c
     JOIN {$wpdb->commentmeta} cm ON c.comment_ID = cm.comment_id AND cm.meta_key = 'rating'
     JOIN {$wpdb->commentmeta} cm3 ON c.comment_ID = cm3.comment_id AND cm3.meta_key = 'mst_guide_id' AND cm3.meta_value = %d
     LEFT JOIN {$wpdb->commentmeta} cm2 ON c.comment_ID = cm2.comment_id AND cm2.meta_key = 'mst_user_city'
     LEFT JOIN {$wpdb->commentmeta} cm4 ON c.comment_ID = cm4.comment_id AND cm4.meta_key = 'mst_review_photos'
     LEFT JOIN {$wpdb->commentmeta} cm5 ON c.comment_ID = cm5.comment_id AND cm5.meta_key = 'mst_author_avatar_id'
     WHERE c.comment_approved = 1 AND c.comment_type = 'review'
     ORDER BY c.comment_date DESC
     LIMIT %d OFFSET 0",
    $guide_id,
    $reviews_per_page
));

// Get fake reviews
$fake_reviews = get_user_meta($guide_id, 'mst_guide_fake_reviews', true);
if (!is_array($fake_reviews)) $fake_reviews = [];

// Collect all photos for lightbox
$all_lightbox_photos = [];

// Process reviews
$all_reviews = [];
foreach ($reviews as $r) {
    $product_id = $r->comment_post_ID;
    $product = wc_get_product($product_id);
    $tour_title = $product ? $product->get_name() : '';
    
    $review_city = '';
    if ($product) {
        $pa_city = $product->get_attribute('pa_city');
        if (!empty($pa_city)) {
            $review_city = $pa_city;
        }
    }
    if (empty($review_city) && !empty($r->user_city)) {
        $review_city = $r->user_city;
    }
    
    $author_avatar_url = '';
    if (!empty($r->author_avatar_id)) {
        $author_avatar_url = wp_get_attachment_image_url($r->author_avatar_id, 'thumbnail');
    }
    if (empty($author_avatar_url) && $r->user_id) {
        $user_avatar_id = get_user_meta($r->user_id, 'mst_lk_avatar', true);
        if ($user_avatar_id) {
            $author_avatar_url = wp_get_attachment_image_url($user_avatar_id, 'thumbnail');
        }
    }
    if (empty($author_avatar_url)) {
        $author_avatar_url = get_avatar_url($r->comment_author_email, ['size' => 80]);
    }
    
    $photos = !empty($r->review_photos) ? maybe_unserialize($r->review_photos) : [];
    
    // Add to lightbox collection
    foreach ($photos as $photo_id) {
        $photo_url = is_numeric($photo_id) ? wp_get_attachment_image_url($photo_id, 'large') : $photo_id;
        if ($photo_url) {
            $all_lightbox_photos[] = [
                'url' => $photo_url,
                'author' => $r->comment_author,
                'avatar' => $author_avatar_url,
                'rating' => intval($r->rating),
                'date' => date_i18n('d F Y', strtotime($r->comment_date)),
                'city' => $review_city,
                'tour' => $tour_title,
                'text' => wp_trim_words($r->comment_content, 80)
            ];
        }
    }
    
    $all_reviews[] = (object)[
        'comment_author' => $r->comment_author,
        'comment_content' => $r->comment_content,
        'comment_date' => $r->comment_date,
        'rating' => intval($r->rating),
        'city' => $review_city,
        'tour_title' => $tour_title,
        'photos' => $photos,
        'author_avatar_url' => $author_avatar_url,
        'is_fake' => false,
    ];
}

// Add fake reviews
if (count($all_reviews) < $reviews_per_page && !empty($fake_reviews)) {
    $needed = $reviews_per_page - count($all_reviews);
    $fake_to_add = array_slice($fake_reviews, 0, $needed);
    foreach ($fake_to_add as $fake) {
        $fake_avatar_url = '';
        if (!empty($fake['author_avatar_id'])) {
            $fake_avatar_url = wp_get_attachment_image_url($fake['author_avatar_id'], 'thumbnail');
        }
        $fake_photos = [];
        if (!empty($fake['photos']) && is_array($fake['photos'])) {
            $fake_photos = $fake['photos'];
            foreach ($fake_photos as $photo_id) {
                $photo_url = is_numeric($photo_id) ? wp_get_attachment_image_url($photo_id, 'large') : $photo_id;
                if ($photo_url) {
                    $all_lightbox_photos[] = [
                        'url' => $photo_url,
                        'author' => $fake['author_name'] ?? 'Гость',
                        'avatar' => $fake_avatar_url,
                        'rating' => intval($fake['rating'] ?? 5),
                        'date' => $fake['date'] ?? date('d F Y'),
                        'city' => $fake['city'] ?? '',
                        'tour' => $fake['tour_title'] ?? '',
                        'text' => wp_trim_words($fake['text'] ?? '', 80)
                    ];
                }
            }
        }
        $all_reviews[] = (object)[
            'comment_author' => $fake['author_name'] ?? 'Гость',
            'comment_content' => $fake['text'] ?? '',
            'comment_date' => $fake['date'] ?? date('d F Y'),
            'rating' => intval($fake['rating'] ?? 5),
            'city' => $fake['city'] ?? '',
            'tour_title' => $fake['tour_title'] ?? '',
            'photos' => $fake_photos,
            'author_avatar_url' => $fake_avatar_url,
            'is_fake' => true,
        ];
    }
}

$total_with_fake = $total_reviews + count($fake_reviews);
$has_more_reviews = $total_with_fake > $reviews_per_page;
?>

<style>
/* Guide Profile Section */
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

.mst-guide-profile-section { font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif; padding: 2rem 0; -webkit-font-smoothing: antialiased; }
.mst-guide-profile-hero-section { max-width: 1200px; margin: 0 auto; padding: 0 1rem; }
.mst-guide-profile-hero { border-radius: 1.5rem; padding: 2rem; background: linear-gradient(135deg, rgba(255,255,255,0.9), rgba(255,255,255,0.7)); border: 1.5px solid rgba(255,255,255,0.5); }
.mst-guide-profile-hero.mst-liquid-glass { 
    background: linear-gradient(135deg, rgba(255,255,255,0.85), rgba(255,255,255,0.65)); 
    backdrop-filter: blur(28px) saturate(200%); 
    -webkit-backdrop-filter: blur(28px) saturate(200%); 
    border: 1.5px solid rgba(255,255,255,0.4); 
    box-shadow: 0 12px 40px -10px rgba(153, 82, 224, 0.2), inset 0 2px 4px rgba(255,255,255,0.6); 
}
.mst-guide-profile-grid { display: grid; grid-template-columns: 1fr 2fr; gap: 2rem; }
@media (max-width: 768px) { .mst-guide-profile-grid { grid-template-columns: 1fr; } }
.mst-guide-profile-left { display: flex; flex-direction: column; align-items: center; text-align: center; padding: 1.5rem; border-right: 1px solid rgba(153, 82, 224, 0.1); }
@media (max-width: 768px) { .mst-guide-profile-left { border-right: none; border-bottom: 1px solid rgba(153, 82, 224, 0.1); padding-bottom: 2rem; } }
.mst-guide-profile-avatar-wrap { position: relative; margin-bottom: 1rem; }
.mst-guide-profile-avatar { width: 128px; height: 128px; border-radius: 50%; overflow: hidden; border: 4px solid rgba(153, 82, 224, 0.2); box-shadow: 0 8px 24px -8px rgba(153, 82, 224, 0.25); }
.mst-guide-profile-avatar img { width: 100%; height: 100%; object-fit: cover; }
.mst-guide-profile-badges { display: flex; gap: 0.5rem; margin-top: 0.75rem; justify-content: center; }
.mst-guide-profile-badge-verified, .mst-guide-profile-badge-academic { display: inline-flex; align-items: center; gap: 0.25rem; padding: 0.35rem 0.65rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; }
.mst-guide-profile-badge-verified { background: <?php echo esc_attr($verified_color); ?>20; color: <?php echo esc_attr($verified_color); ?>; }
.mst-guide-profile-badge-academic { background: <?php echo esc_attr($secondary_color); ?>30; }
.mst-guide-profile-name { font-size: 1.5rem; font-weight: 700; margin: 0.5rem 0; color: #1a1a2e; }
.mst-guide-profile-location { display: flex; align-items: center; gap: 0.35rem; color: #6b7280; font-size: 0.875rem; margin-bottom: 0.75rem; justify-content: center; }
.mst-guide-profile-rating { display: flex; align-items: center; gap: 0.35rem; margin-bottom: 0.75rem; justify-content: center; }
.mst-guide-profile-rating-value { font-size: 1.125rem; font-weight: 600; color: #1a1a2e; }
.mst-guide-profile-reviews-count { font-size: 0.875rem; color: #6b7280; }
.mst-guide-profile-our-badge { display: inline-flex; align-items: center; gap: 0.35rem; padding: 0.4rem 0.8rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; margin-bottom: 1rem; background: <?php echo esc_attr($primary_color); ?>15; border: 1px solid <?php echo esc_attr($primary_color); ?>40; }
.mst-guide-profile-our-badge span { color: <?php echo esc_attr($primary_color); ?>; }
.mst-guide-profile-stats { width: 100%; display: flex; flex-direction: column; gap: 0.5rem; margin-bottom: 1.5rem; }
.mst-guide-profile-stat { display: flex; justify-content: space-between; font-size: 0.875rem; }
.mst-guide-profile-stat-label { color: #6b7280; }
.mst-guide-profile-stat-value { font-weight: 600; color: #1a1a2e; }
.mst-guide-profile-actions { width: 100%; }
.mst-guide-profile-book-btn { display: flex; align-items: center; justify-content: center; gap: 0.5rem; width: 100%; padding: 0.875rem 1.5rem; background: <?php echo esc_attr($primary_color); ?>; color: white; border: none; border-radius: 12px; font-size: 0.9rem; font-weight: 600; cursor: pointer; text-decoration: none; transition: box-shadow 0.3s ease; margin-bottom: 0.75rem; }
.mst-guide-profile-book-btn:hover { box-shadow: 0 8px 24px -6px <?php echo esc_attr($primary_color); ?>80; color: white; }
.mst-guide-profile-secondary-actions { display: flex; gap: 0.5rem; }
.mst-guide-profile-icon-btn { flex: 1; display: flex; align-items: center; justify-content: center; gap: 0.5rem; padding: 0.75rem; background: rgba(255,255,255,0.7); border: 1.5px solid rgba(153, 82, 224, 0.15); border-radius: 12px; cursor: pointer; transition: all 0.3s ease; text-decoration: none; color: #374151; font-size: 0.85rem; font-weight: 500; }
.mst-guide-profile-icon-btn:hover { background: rgba(255,255,255,0.9); border-color: <?php echo esc_attr($primary_color); ?>40; color: <?php echo esc_attr($primary_color); ?>; }
.mst-guide-profile-right { padding: 1rem; }
.mst-guide-profile-info-block { margin-bottom: 1.5rem; }
.mst-guide-profile-info-block h3 { font-size: 0.9rem; font-weight: 600; color: #1a1a2e; margin: 0 0 0.75rem 0; }
.mst-guide-profile-info-header { display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.75rem; }
.mst-guide-profile-info-header h3 { margin: 0; }
.mst-guide-profile-tags { display: flex; flex-wrap: wrap; gap: 0.5rem; }
.mst-guide-profile-language { display: inline-flex; padding: 0.4rem 0.75rem; background: rgba(251, 214, 3, 0.12); border: 1.5px solid rgba(251, 214, 3, 0.3); border-radius: 9999px; font-size: 0.8rem; font-weight: 500; }
.mst-guide-profile-specialty { display: inline-flex; padding: 0.4rem 0.75rem; background: rgba(22, 191, 255, 0.1); color: #0891b2; border: 1.5px solid rgba(22, 191, 255, 0.25); border-radius: 9999px; font-size: 0.8rem; font-weight: 500; }
.mst-guide-profile-bio { color: #4b5563; line-height: 1.7; margin: 0; font-size: 0.9rem; }
.mst-guide-profile-achievements { display: flex; flex-direction: column; gap: 0.5rem; }
.mst-guide-profile-achievement { display: flex; align-items: center; gap: 0.5rem; padding: 0.6rem 1rem; border-radius: 12px; font-size: 0.875rem; background: rgba(251, 214, 3, 0.1); }
.mst-guide-profile-tours-section, .mst-guide-profile-testimonials-section { max-width: 1200px; margin: 3rem auto; padding: 0 1rem; }
.mst-guide-profile-section-title { font-size: 1.75rem; font-weight: 700; color: #1a1a2e; margin: 0 0 1.5rem 0; }
.mst-guide-profile-tours-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem; }
@media (max-width: 1024px) { .mst-guide-profile-tours-grid { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 640px) { .mst-guide-profile-tours-grid { grid-template-columns: 1fr; } }
.mst-guide-tour-card { display: block; border-radius: 20px; overflow: hidden; background: linear-gradient(135deg, rgba(255,255,255,0.85), rgba(255,255,255,0.65)); backdrop-filter: blur(20px) saturate(180%); border: 1.5px solid rgba(255,255,255,0.4); box-shadow: 0 8px 32px -8px rgba(153, 82, 224, 0.15); text-decoration: none; transition: all 0.35s ease; }
.mst-guide-tour-card:hover { border-color: rgba(255,255,255,0.5); box-shadow: 0 16px 48px -12px rgba(153, 82, 224, 0.25); transform: translateY(-4px); }
.mst-guide-tour-image { height: 180px; overflow: hidden; }
.mst-guide-tour-image img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s ease; }
.mst-guide-tour-card:hover .mst-guide-tour-image img { transform: scale(1.08); }
.mst-guide-tour-content { padding: 1rem; }
.mst-guide-tour-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.5rem; }
.mst-guide-tour-title { font-size: 0.95rem; font-weight: 600; color: #1a1a2e; margin: 0; flex: 1; }
.mst-guide-tour-rating { display: flex; align-items: center; gap: 0.25rem; font-size: 0.875rem; font-weight: 500; }
.mst-guide-tour-meta { display: flex; justify-content: space-between; align-items: center; font-size: 0.85rem; }
.mst-guide-tour-duration { color: #6b7280; }
.mst-guide-tour-price { font-weight: 600; color: #1a1a2e; }

/* Testimonials - ENHANCED */
.mst-guide-profile-testimonials-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem; }
@media (max-width: 1024px) { .mst-guide-profile-testimonials-grid { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 640px) { .mst-guide-profile-testimonials-grid { grid-template-columns: 1fr; } }
.mst-guide-testimonial-card { padding: 1.5rem; border-radius: 20px; background: linear-gradient(135deg, rgba(255,255,255,0.6), rgba(255,255,255,0.4)); backdrop-filter: blur(16px) saturate(160%); border: 1px solid rgba(255,255,255,0.3); transition: all 0.35s ease; }
.mst-guide-testimonial-card:hover { box-shadow: 0 12px 32px -8px rgba(251, 214, 3, 0.2); }
.mst-guide-testimonial-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.75rem; }
.mst-guide-testimonial-author { display: flex; align-items: center; gap: 0.75rem; }
.mst-guide-testimonial-avatar { width: 44px; height: 44px; border-radius: 50%; overflow: hidden; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, <?php echo esc_attr($primary_color); ?>, <?php echo esc_attr($secondary_color); ?>); color: white; font-weight: 600; font-size: 1rem; flex-shrink: 0; }
.mst-guide-testimonial-avatar img { width: 100%; height: 100%; object-fit: cover; }
.mst-guide-testimonial-author-info { display: flex; flex-direction: column; }
.mst-guide-testimonial-name { font-weight: 600; color: #1a1a2e; font-size: 0.9rem; }
.mst-guide-testimonial-date { font-size: 0.75rem; color: #6b7280; }
.mst-guide-testimonial-meta { font-size: 0.75rem; color: #9952E0; margin-top: 2px; }
.mst-guide-testimonial-rating { display: flex; gap: 0.125rem; }
.mst-guide-testimonial-tour-title { font-weight: 600; color: #374151; font-size: 0.85rem; margin-bottom: 0.5rem; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
.mst-guide-testimonial-text { color: #4b5563; line-height: 1.6; font-size: 0.9rem; margin: 0 0 0.75rem 0; }

/* Section titles */
.mst-guide-profile-tours-section, .mst-guide-profile-testimonials-section { max-width: 1200px; margin: 3rem auto; padding: 0 1rem; }
.mst-guide-profile-section-title { font-size: 1.75rem; font-weight: 700; color: #1a1a2e; margin: 0 0 1.5rem 0; }

/* Tours Grid - Shop Grid Style */
.mst-guide-profile-tours-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem; }
@media (max-width: 1024px) { .mst-guide-profile-tours-grid { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 640px) { .mst-guide-profile-tours-grid { grid-template-columns: 1fr; } }

/* Tour Card - Like Shop Grid */
.mst-guide-tour-card { 
    display: flex;
    flex-direction: column;
    border-radius: 20px;
    overflow: hidden;
    background: linear-gradient(135deg, rgba(255,255,255,0.85), rgba(255,255,255,0.65));
    backdrop-filter: blur(20px) saturate(180%);
    -webkit-backdrop-filter: blur(20px) saturate(180%);
    border: 1.5px solid rgba(255,255,255,0.4);
    box-shadow: 0 8px 32px -8px rgba(153, 82, 224, 0.15);
    text-decoration: none;
    transition: all 0.35s ease;
}
.mst-guide-tour-card:hover { 
    border-color: rgba(255,255,255,0.5); 
    box-shadow: 0 16px 48px -12px rgba(153, 82, 224, 0.25); 
    transform: translateY(-4px); 
}
.mst-guide-tour-image { 
    height: 200px; 
    overflow: hidden; 
    margin: 8px;
    border-radius: 16px;
    position: relative;
}
.mst-guide-tour-image img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s ease; }
.mst-guide-tour-card:hover .mst-guide-tour-image img { transform: scale(1.08); }

/* Tour Card Content */
.mst-guide-tour-content { padding: 0 16px 16px; }
.mst-guide-tour-top-row { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 8px; }
.mst-guide-tour-title { font-size: 15px; font-weight: 600; color: #1a1a2e; margin: 0; flex: 1; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
.mst-guide-tour-price { font-size: 16px; font-weight: 700; color: #9952E0; white-space: nowrap; margin-left: 12px; }
.mst-guide-tour-bottom-row { display: flex; justify-content: space-between; align-items: center; }
.mst-guide-tour-city { font-size: 13px; color: #6b7280; display: flex; align-items: center; gap: 4px; }
.mst-guide-tour-rating { display: flex; align-items: center; gap: 4px; font-size: 13px; }
.mst-guide-tour-rating .star { color: #fbbf24; }
.mst-guide-tour-rating .value { font-weight: 600; color: #1f2937; }
.mst-guide-tour-rating .count { color: #9ca3af; }

/* ===============================================
   TESTIMONIALS GRID - ENHANCED BIGGER CARDS
   =============================================== */
.mst-guide-profile-testimonials-grid { 
    display: grid; 
    grid-template-columns: repeat(3, 1fr); 
    gap: 24px; 
}
@media (max-width: 1024px) { .mst-guide-profile-testimonials-grid { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 640px) { .mst-guide-profile-testimonials-grid { grid-template-columns: 1fr; } }

.mst-guide-testimonial-card { 
    padding: <?php echo intval($profile_settings['card_padding'] ?? 20); ?>px;
    border-radius: 20px; 
    background: linear-gradient(135deg, rgba(255,255,255,0.9), rgba(255,255,255,0.7)); 
    backdrop-filter: blur(16px) saturate(160%); 
    -webkit-backdrop-filter: blur(16px) saturate(160%);
    border: 1.5px solid rgba(255,255,255,0.4); 
    transition: all 0.35s ease;
    box-shadow: 0 4px 20px -4px rgba(153, 82, 224, 0.1);
}
.mst-guide-testimonial-card:hover { 
    box-shadow: 0 12px 32px -8px rgba(153, 82, 224, 0.2); 
    transform: translateY(-2px);
}

.mst-guide-testimonial-header { 
    display: flex; 
    justify-content: space-between; 
    align-items: flex-start; 
    margin-bottom: 12px; 
}
.mst-guide-testimonial-author { display: flex; align-items: center; gap: 12px; }
.mst-guide-testimonial-avatar { 
    width: <?php echo intval($profile_settings['avatar_size'] ?? 60); ?>px; 
    height: <?php echo intval($profile_settings['avatar_size'] ?? 60); ?>px; 
    border-radius: 50%; 
    overflow: hidden; 
    display: flex; 
    align-items: center; 
    justify-content: center; 
    background: linear-gradient(135deg, #9952E0, #fbd603); 
    color: white; 
    font-weight: 600; 
    font-size: 1.25rem; 
    flex-shrink: 0; 
}
.mst-guide-testimonial-avatar img { width: 100%; height: 100%; object-fit: cover; }
.mst-guide-testimonial-author-info { display: flex; flex-direction: column; gap: 2px; }
.mst-guide-testimonial-name { 
    font-weight: 600; 
    color: #1a1a2e; 
    font-size: <?php echo intval($profile_settings['title_size'] ?? 16); ?>px; 
}
.mst-guide-testimonial-date { font-size: 13px; color: #6b7280; }
.mst-guide-testimonial-meta { font-size: 13px; color: #9952E0; font-weight: 500; }
.mst-guide-testimonial-rating { display: flex; gap: 2px; font-size: 16px; }
.mst-guide-testimonial-rating .star { color: #fbbf24; }

.mst-guide-testimonial-tour-title { 
    font-weight: 600; 
    color: #374151; 
    font-size: 15px; 
    margin-bottom: 8px; 
    display: -webkit-box; 
    -webkit-line-clamp: 2; 
    -webkit-box-orient: vertical; 
    overflow: hidden; 
}
.mst-guide-testimonial-text { 
    color: #4b5563; 
    line-height: 1.7; 
    font-size: <?php echo intval($profile_settings['text_size'] ?? 14); ?>px; 
    margin: 0 0 12px 0; 
}

/* Review Photos - Bigger */
.mst-guide-testimonial-photos { 
    display: flex; 
    gap: 10px; 
    flex-wrap: wrap; 
    margin-top: 12px; 
}
.mst-guide-testimonial-photo { 
    width: 72px; 
    height: 72px; 
    border-radius: 12px; 
    overflow: hidden; 
    cursor: pointer; 
    transition: all 0.25s ease;
    border: 2px solid transparent;
}
.mst-guide-testimonial-photo:hover { 
    transform: scale(1.08); 
    border-color: #9952E0;
    box-shadow: 0 4px 12px rgba(153, 82, 224, 0.3);
}
.mst-guide-testimonial-photo img { width: 100%; height: 100%; object-fit: cover; }
.mst-guide-testimonial-photo-more {
    width: 72px;
    height: 72px;
    border-radius: 12px;
    background: rgba(153, 82, 224, 0.15);
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    color: #9952E0;
    font-size: 16px;
    cursor: pointer;
    transition: all 0.25s ease;
}
.mst-guide-testimonial-photo-more:hover {
    background: rgba(153, 82, 224, 0.25);
}

/* ===============================================
   NEW LIGHTBOX - LIQUID GLASS STYLE
   =============================================== */
.mst-lightbox-v2 { 
    position: fixed; 
    top: 0; 
    left: 0; 
    width: 100%; 
    height: 100%; 
    background: rgba(0,0,0,0.92); 
    z-index: 999999; 
    display: none; 
    opacity: 0; 
    transition: opacity 0.3s ease;
}
.mst-lightbox-v2.active { display: flex; opacity: 1; }

.mst-lightbox-v2-inner {
    display: flex;
    width: 100%;
    height: 100%;
}

/* Left side - Image 70% */
.mst-lightbox-v2-image-area {
    flex: 0 0 70%;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    padding: 60px 40px;
}
.mst-lightbox-v2-image-area img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
    border-radius: 12px;
}

/* Right side - Info Panel 30% with Liquid Glass */
.mst-lightbox-v2-info-panel {
    flex: 0 0 30%;
    background: linear-gradient(135deg, rgba(30, 30, 40, 0.85), rgba(20, 20, 30, 0.75));
    backdrop-filter: blur(32px) saturate(180%);
    -webkit-backdrop-filter: blur(32px) saturate(180%);
    border-left: 1px solid rgba(255,255,255,0.1);
    padding: 32px;
    display: flex;
    flex-direction: column;
    position: relative;
    overflow-y: auto;
}
.mst-lightbox-v2-info-panel::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)'/%3E%3C/svg%3E");
    opacity: 0.03;
    pointer-events: none;
}

/* Close button */
.mst-lightbox-v2-close { 
    position: absolute; 
    top: 24px; 
    right: 24px; 
    width: 48px; 
    height: 48px; 
    background: rgba(255,255,255,0.1); 
    border: 1px solid rgba(255,255,255,0.2);
    border-radius: 50%; 
    color: white; 
    font-size: 24px; 
    cursor: pointer; 
    display: flex; 
    align-items: center; 
    justify-content: center; 
    transition: all 0.2s;
    z-index: 10;
}
.mst-lightbox-v2-close:hover { background: rgba(255,255,255,0.2); }

/* Navigation arrows - Fixed position */
.mst-lightbox-v2-nav { 
    position: absolute; 
    top: 50%; 
    transform: translateY(-50%); 
    width: 56px; 
    height: 56px; 
    background: rgba(255,255,255,0.1); 
    border: 1px solid rgba(255,255,255,0.2);
    border-radius: 50%; 
    color: white; 
    font-size: 24px; 
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s;
    z-index: 10;
}
.mst-lightbox-v2-nav:hover { background: rgba(255,255,255,0.2); }
.mst-lightbox-v2-prev { left: 24px; }
.mst-lightbox-v2-next { right: calc(30% + 24px); }

/* Photo counter */
.mst-lightbox-v2-counter {
    position: absolute;
    top: 24px;
    left: 24px;
    color: #fff;
    font-size: 16px;
    font-weight: 600;
    z-index: 10;
}

/* Info panel content */
.mst-lightbox-v2-author {
    display: flex;
    align-items: center;
    gap: 16px;
    margin-bottom: 20px;
    padding-bottom: 20px;
    border-bottom: 1px solid rgba(255,255,255,0.1);
}
.mst-lightbox-v2-author-avatar {
    width: 56px;
    height: 56px;
    border-radius: 50%;
    overflow: hidden;
    background: linear-gradient(135deg, #9952E0, #fbd603);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-weight: 600;
    font-size: 1.25rem;
    flex-shrink: 0;
}
.mst-lightbox-v2-author-avatar img { width: 100%; height: 100%; object-fit: cover; }
.mst-lightbox-v2-author-info { flex: 1; }
.mst-lightbox-v2-author-name { font-weight: 600; color: #fff; font-size: 18px; margin-bottom: 4px; }
.mst-lightbox-v2-author-rating { display: flex; align-items: center; gap: 8px; }
.mst-lightbox-v2-author-rating .stars { color: #fbbf24; font-size: 16px; }
.mst-lightbox-v2-author-rating .date { color: rgba(255,255,255,0.6); font-size: 14px; }

.mst-lightbox-v2-meta {
    display: flex;
    flex-direction: column;
    gap: 8px;
    margin-bottom: 20px;
}
.mst-lightbox-v2-city {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    color: #9952E0;
    font-size: 14px;
    font-weight: 500;
}
.mst-lightbox-v2-tour {
    font-weight: 600;
    color: #fff;
    font-size: 16px;
    line-height: 1.4;
}

.mst-lightbox-v2-text {
    color: rgba(255,255,255,0.85);
    font-size: 15px;
    line-height: 1.7;
    flex: 1;
}

/* Thumbnails */
.mst-lightbox-v2-thumbnails {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 10px;
    margin-top: auto;
    padding-top: 20px;
    border-top: 1px solid rgba(255,255,255,0.1);
}
.mst-lightbox-v2-thumb {
    aspect-ratio: 1;
    border-radius: 10px;
    overflow: hidden;
    cursor: pointer;
    opacity: 0.5;
    transition: all 0.2s;
    border: 2px solid transparent;
}
.mst-lightbox-v2-thumb:hover { opacity: 0.8; }
.mst-lightbox-v2-thumb.active { 
    opacity: 1; 
    border-color: #fbd603;
}
.mst-lightbox-v2-thumb img { width: 100%; height: 100%; object-fit: cover; }

/* Responsive */
@media (max-width: 1024px) {
    .mst-lightbox-v2-image-area { flex: 0 0 60%; }
    .mst-lightbox-v2-info-panel { flex: 0 0 40%; padding: 24px; }
    .mst-lightbox-v2-next { right: calc(40% + 24px); }
}
@media (max-width: 768px) {
    .mst-lightbox-v2-inner { flex-direction: column; }
    .mst-lightbox-v2-image-area { flex: 0 0 55%; padding: 60px 20px 20px; }
    .mst-lightbox-v2-info-panel { flex: 0 0 45%; }
    .mst-lightbox-v2-nav { width: 44px; height: 44px; font-size: 20px; }
    .mst-lightbox-v2-prev { left: 16px; }
    .mst-lightbox-v2-next { right: 16px; top: 30%; }
    .mst-lightbox-v2-thumbnails { grid-template-columns: repeat(4, 1fr); }
}

/* Load More Button */
.mst-load-more-wrap { text-align: center; margin-top: 2.5rem; }
.mst-load-more-btn { 
    display: inline-flex; 
    align-items: center; 
    gap: 10px; 
    padding: 14px 32px; 
    background: linear-gradient(135deg, #9952E0 0%, #7B3FC4 100%); 
    color: white; 
    border: none; 
    border-radius: 14px; 
    font-size: 15px; 
    font-weight: 600; 
    cursor: pointer; 
    transition: all 0.3s ease;
    box-shadow: 0 4px 16px rgba(153, 82, 224, 0.3);
}
.mst-load-more-btn:hover { 
    transform: translateY(-2px); 
    box-shadow: 0 8px 24px rgba(153, 82, 224, 0.4); 
}
.mst-load-more-btn:disabled { 
    opacity: 0.6; 
    cursor: not-allowed; 
    transform: none; 
}
.mst-load-more-btn .spinner {
    width: 18px;
    height: 18px;
    border: 2px solid rgba(255,255,255,0.3);
    border-top-color: #fff;
    border-radius: 50%;
    animation: spin 0.8s linear infinite;
}
@keyframes spin { to { transform: rotate(360deg); } }

/* Back button */
.mst-guide-back-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 20px;
    background: rgba(255,255,255,0.8);
    border: 1.5px solid rgba(153, 82, 224, 0.2);
    border-radius: 12px;
    color: #374151;
    font-size: 14px;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.2s;
    margin-bottom: 1.5rem;
}
.mst-guide-back-btn:hover {
    background: #fff;
    border-color: #9952E0;
    color: #9952E0;
}
</style>

<div class="mst-guide-profile-section">
    
    <!-- HERO SECTION -->
    <section class="mst-guide-profile-hero-section">
        <div class="mst-guide-profile-hero mst-liquid-glass">
            <div class="mst-guide-profile-grid">
                
                <!-- Left Column: Avatar & Quick Info -->
                <div class="mst-guide-profile-left">
                    <div class="mst-guide-profile-avatar-wrap">
                        <div class="mst-guide-profile-avatar">
                            <img src="<?php echo esc_url($avatar_url); ?>" alt="<?php echo esc_attr($guide->display_name); ?>">
                        </div>
                        
                        <div class="mst-guide-profile-badges">
                            <?php if ($is_verified): ?>
                            <span class="mst-guide-profile-badge-verified">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                            </span>
                            <?php endif; ?>
                            
                            <?php if (!empty($academic_title)): ?>
                            <span class="mst-guide-profile-badge-academic">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>
                                <?php echo esc_html($academic_title); ?>
                            </span>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <h1 class="mst-guide-profile-name"><?php echo esc_html($guide->display_name); ?></h1>
                    
                    <?php if ($city): ?>
                    <div class="mst-guide-profile-location">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                        <span><?php echo esc_html($city); ?></span>
                    </div>
                    <?php endif; ?>
                    
                    <div class="mst-guide-profile-rating">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="<?php echo esc_attr($rating_color); ?>" stroke="none"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                        <span class="mst-guide-profile-rating-value"><?php echo esc_html($rating); ?></span>
                        <span class="mst-guide-profile-reviews-count">(<?php echo esc_html($reviews_count); ?> <?php _e('отзывов', 'mst-auth-lk'); ?>)</span>
                    </div>
                    
                    <div class="mst-guide-profile-our-badge">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="<?php echo esc_attr($primary_color); ?>" stroke="none"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>
                        <span>My Super Tour</span>
                    </div>
                    
                    <div class="mst-guide-profile-stats">
                        <div class="mst-guide-profile-stat">
                            <span class="mst-guide-profile-stat-label"><?php _e('Опыт', 'mst-auth-lk'); ?></span>
                            <span class="mst-guide-profile-stat-value"><?php echo esc_html($experience_years); ?> <?php _e('лет', 'mst-auth-lk'); ?></span>
                        </div>
                        <div class="mst-guide-profile-stat">
                            <span class="mst-guide-profile-stat-label"><?php _e('Туров проведено', 'mst-auth-lk'); ?></span>
                            <span class="mst-guide-profile-stat-value"><?php echo esc_html($tours_count); ?></span>
                        </div>
                    </div>
                    
                    <div class="mst-guide-profile-actions">
                        <a href="<?php echo esc_url($referrer_url); ?>" class="mst-guide-profile-book-btn">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                            <?php _e('Вернуться к турам', 'mst-auth-lk'); ?>
                        </a>
                        <div class="mst-guide-profile-secondary-actions">
                            <a href="mailto:<?php echo esc_attr($guide->user_email); ?>?subject=<?php echo esc_attr(sprintf(__('Вопрос гиду %s', 'mst-auth-lk'), $guide->display_name)); ?>" class="mst-guide-profile-icon-btn">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                                <span><?php _e('Задать вопрос', 'mst-auth-lk'); ?></span>
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Right Column: Details -->
                <div class="mst-guide-profile-right">
                    
                    <?php if ($languages): ?>
                    <div class="mst-guide-profile-info-block">
                        <div class="mst-guide-profile-info-header">
                            <span>🗣️</span>
                            <h3><?php _e('Языки', 'mst-auth-lk'); ?></h3>
                        </div>
                        <div class="mst-guide-profile-tags">
                            <?php foreach (explode(',', $languages) as $lang): ?>
                                <span class="mst-guide-profile-language"><?php echo esc_html(trim($lang)); ?></span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if ($specialization): ?>
                    <div class="mst-guide-profile-info-block">
                        <div class="mst-guide-profile-info-header">
                            <span>🎯</span>
                            <h3><?php _e('Специализация', 'mst-auth-lk'); ?></h3>
                        </div>
                        <div class="mst-guide-profile-tags">
                            <?php foreach (explode(',', $specialization) as $spec): ?>
                                <span class="mst-guide-profile-specialty"><?php echo esc_html(trim($spec)); ?></span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if ($bio): ?>
                    <div class="mst-guide-profile-info-block">
                        <div class="mst-guide-profile-info-header">
                            <span>📖</span>
                            <h3><?php _e('О гиде', 'mst-auth-lk'); ?></h3>
                        </div>
                        <p class="mst-guide-profile-bio"><?php echo nl2br(esc_html($bio)); ?></p>
                    </div>
                    <?php endif; ?>
                    
                    <?php if ($achievements): ?>
                    <div class="mst-guide-profile-info-block">
                        <div class="mst-guide-profile-info-header">
                            <span>🏆</span>
                            <h3><?php _e('Достижения', 'mst-auth-lk'); ?></h3>
                        </div>
                        <div class="mst-guide-profile-achievements">
                            <?php foreach (explode("\n", $achievements) as $achievement): 
                                if (trim($achievement)): ?>
                            <div class="mst-guide-profile-achievement">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="#fbd603" stroke="none"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                                <span><?php echo esc_html(trim($achievement)); ?></span>
                            </div>
                            <?php endif; endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                </div>
            </div>
        </div>
    </section>
    
    <!-- Popular Tours -->
    <?php if ($tours_query->have_posts()): ?>
    <div class="mst-guide-profile-tours-section">
        <h2 class="mst-guide-profile-section-title"><?php _e('Популярные туры', 'mst-auth-lk'); ?></h2>
        <div class="mst-guide-profile-tours-grid">
            <?php while ($tours_query->have_posts()): $tours_query->the_post(); 
                $product = wc_get_product(get_the_ID());
                $tour_rating = $product->get_average_rating();
                $tour_reviews = $product->get_review_count();
                $tour_city = '';
                $city_terms = wp_get_post_terms(get_the_ID(), 'pa_city');
                if (!is_wp_error($city_terms) && !empty($city_terms)) {
                    $tour_city = $city_terms[0]->name;
                }
            ?>
            <a href="<?php the_permalink(); ?>" class="mst-guide-tour-card">
                <div class="mst-guide-tour-image">
                    <?php if (has_post_thumbnail()): ?>
                    <img src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'medium_large'); ?>" alt="<?php the_title_attribute(); ?>">
                    <?php endif; ?>
                </div>
                <div class="mst-guide-tour-content">
                    <div class="mst-guide-tour-top-row">
                        <h3 class="mst-guide-tour-title"><?php the_title(); ?></h3>
                        <span class="mst-guide-tour-price"><?php echo $product->get_price_html(); ?></span>
                    </div>
                    <div class="mst-guide-tour-bottom-row">
                        <?php if ($tour_city): ?>
                        <span class="mst-guide-tour-city">📍 <?php echo esc_html($tour_city); ?></span>
                        <?php else: ?>
                        <span></span>
                        <?php endif; ?>
                        <div class="mst-guide-tour-rating">
                            <span class="star">★</span>
                            <span class="value"><?php echo $tour_rating ? number_format($tour_rating, 1) : '5.0'; ?></span>
                            <span class="count">(<?php echo $tour_reviews; ?>)</span>
                        </div>
                    </div>
                </div>
            </a>
            <?php endwhile; wp_reset_postdata(); ?>
        </div>
    </div>
    <?php endif; ?>
    
    <!-- Reviews Section -->
    <div class="mst-guide-profile-testimonials-section">
        <h2 class="mst-guide-profile-section-title"><?php _e('Отзывы клиентов', 'mst-auth-lk'); ?></h2>
        
        <div class="mst-guide-profile-testimonials-grid" id="mst-reviews-container" data-guide-id="<?php echo $guide_id; ?>" data-page="1" data-per-page="<?php echo $reviews_per_page; ?>">
            <?php 
            $photo_global_index = 0;
            foreach ($all_reviews as $review): 
                $initials = mb_strtoupper(mb_substr($review->comment_author, 0, 2));
                $date_formatted = is_numeric(strtotime($review->comment_date)) ? date_i18n('d F Y', strtotime($review->comment_date)) : $review->comment_date;
            ?>
            <div class="mst-guide-testimonial-card">
                <div class="mst-guide-testimonial-header">
                    <div class="mst-guide-testimonial-author">
                        <div class="mst-guide-testimonial-avatar">
                            <?php if (!empty($review->author_avatar_url)): ?>
                            <img src="<?php echo esc_url($review->author_avatar_url); ?>" alt="">
                            <?php else: ?>
                            <?php echo esc_html($initials); ?>
                            <?php endif; ?>
                        </div>
                        <div class="mst-guide-testimonial-author-info">
                            <span class="mst-guide-testimonial-name"><?php echo esc_html($review->comment_author); ?></span>
                            <span class="mst-guide-testimonial-date"><?php echo esc_html($date_formatted); ?></span>
                            <?php if ($review->city): ?>
                            <span class="mst-guide-testimonial-meta"><?php echo esc_html($review->city); ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="mst-guide-testimonial-rating">
                        <?php for ($i = 0; $i < 5; $i++): ?>
                        <span class="star"><?php echo $i < $review->rating ? '★' : '☆'; ?></span>
                        <?php endfor; ?>
                    </div>
                </div>
                
                <?php if ($review->tour_title): ?>
                <div class="mst-guide-testimonial-tour-title"><?php echo esc_html($review->tour_title); ?></div>
                <?php endif; ?>
                
                <p class="mst-guide-testimonial-text"><?php echo esc_html($review->comment_content); ?></p>
                
                <?php if (!empty($review->photos)): ?>
                <div class="mst-guide-testimonial-photos">
                    <?php 
                    $shown_photos = array_slice($review->photos, 0, 3);
                    $extra_count = count($review->photos) - 3;
                    foreach ($shown_photos as $photo_id): 
                        $photo_url = is_numeric($photo_id) ? wp_get_attachment_image_url($photo_id, 'thumbnail') : $photo_id;
                        if ($photo_url):
                    ?>
                    <div class="mst-guide-testimonial-photo" data-lightbox-index="<?php echo $photo_global_index; ?>">
                        <img src="<?php echo esc_url($photo_url); ?>" alt="">
                    </div>
                    <?php 
                        $photo_global_index++;
                        endif;
                    endforeach; 
                    if ($extra_count > 0):
                    ?>
                    <div class="mst-guide-testimonial-photo-more" data-lightbox-index="<?php echo $photo_global_index - 1; ?>">
                        +<?php echo $extra_count; ?>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
        
        <?php if ($has_more_reviews): ?>
        <div class="mst-load-more-wrap">
            <button type="button" class="mst-load-more-btn" id="mst-load-more-reviews">
                <?php _e('Загрузить ещё', 'mst-auth-lk'); ?> ↓
            </button>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- New Lightbox V2 -->
<div class="mst-lightbox-v2" id="mst-lightbox-v2">
    <button class="mst-lightbox-v2-close" aria-label="Close">×</button>
    <span class="mst-lightbox-v2-counter">Фото <span id="lb-current">1</span>/<span id="lb-total"><?php echo count($all_lightbox_photos); ?></span></span>
    
    <button class="mst-lightbox-v2-nav mst-lightbox-v2-prev" aria-label="Previous">‹</button>
    <button class="mst-lightbox-v2-nav mst-lightbox-v2-next" aria-label="Next">›</button>
    
    <div class="mst-lightbox-v2-inner">
        <div class="mst-lightbox-v2-image-area">
            <img id="lb-main-image" src="" alt="">
        </div>
        
        <div class="mst-lightbox-v2-info-panel">
            <div class="mst-lightbox-v2-author">
                <div class="mst-lightbox-v2-author-avatar" id="lb-avatar"></div>
                <div class="mst-lightbox-v2-author-info">
                    <div class="mst-lightbox-v2-author-name" id="lb-author-name"></div>
                    <div class="mst-lightbox-v2-author-rating">
                        <span class="stars" id="lb-stars"></span>
                        <span class="date" id="lb-date"></span>
                    </div>
                </div>
            </div>
            
            <div class="mst-lightbox-v2-meta">
                <span class="mst-lightbox-v2-city" id="lb-city"></span>
                <span class="mst-lightbox-v2-tour" id="lb-tour"></span>
            </div>
            
            <div class="mst-lightbox-v2-text" id="lb-text"></div>
            
            <div class="mst-lightbox-v2-thumbnails" id="lb-thumbnails"></div>
        </div>
    </div>
</div>

<script>
(function($) {
    'use strict';
    
    // Lightbox data
    var lightboxPhotos = <?php echo json_encode($all_lightbox_photos); ?>;
    var currentIndex = 0;
    
    function openLightbox(index) {
        if (!lightboxPhotos.length) return;
        currentIndex = index;
        updateLightbox();
        $('#mst-lightbox-v2').addClass('active');
        $('body').css('overflow', 'hidden');
    }
    
    function closeLightbox() {
        $('#mst-lightbox-v2').removeClass('active');
        $('body').css('overflow', '');
    }
    
    function updateLightbox() {
        var photo = lightboxPhotos[currentIndex];
        if (!photo) return;
        
        $('#lb-main-image').attr('src', photo.url);
        $('#lb-current').text(currentIndex + 1);
        $('#lb-total').text(lightboxPhotos.length);
        
        // Author info
        if (photo.avatar) {
            $('#lb-avatar').html('<img src="' + photo.avatar + '" alt="">');
        } else {
            var initials = photo.author ? photo.author.substring(0, 2).toUpperCase() : '??';
            $('#lb-avatar').text(initials);
        }
        
        $('#lb-author-name').text(photo.author || '');
        
        var stars = '';
        for (var i = 0; i < 5; i++) {
            stars += i < photo.rating ? '★' : '☆';
        }
        $('#lb-stars').text(stars);
        $('#lb-date').text(photo.date || '');
        
        $('#lb-city').html(photo.city ? '📍 ' + photo.city : '');
        $('#lb-tour').text(photo.tour || '');
        $('#lb-text').text(photo.text || '');
        
        // Thumbnails
        var thumbsHtml = '';
        var startIdx = Math.max(0, currentIndex - 2);
        var endIdx = Math.min(lightboxPhotos.length, startIdx + 6);
        for (var i = startIdx; i < endIdx; i++) {
            var activeClass = i === currentIndex ? 'active' : '';
            thumbsHtml += '<div class="mst-lightbox-v2-thumb ' + activeClass + '" data-index="' + i + '"><img src="' + lightboxPhotos[i].url + '" alt=""></div>';
        }
        $('#lb-thumbnails').html(thumbsHtml);
    }
    
    function prevPhoto() {
        currentIndex = currentIndex > 0 ? currentIndex - 1 : lightboxPhotos.length - 1;
        updateLightbox();
    }
    
    function nextPhoto() {
        currentIndex = currentIndex < lightboxPhotos.length - 1 ? currentIndex + 1 : 0;
        updateLightbox();
    }
    
    $(document).ready(function() {
        // Open lightbox
        $(document).on('click', '.mst-guide-testimonial-photo, .mst-guide-testimonial-photo-more', function() {
            var index = parseInt($(this).data('lightbox-index')) || 0;
            openLightbox(index);
        });
        
        // Close
        $('.mst-lightbox-v2-close').on('click', closeLightbox);
        $('#mst-lightbox-v2').on('click', function(e) {
            if ($(e.target).is('#mst-lightbox-v2')) closeLightbox();
        });
        
        // Navigation
        $('.mst-lightbox-v2-prev').on('click', prevPhoto);
        $('.mst-lightbox-v2-next').on('click', nextPhoto);
        
        // Thumbnail click
        $(document).on('click', '.mst-lightbox-v2-thumb', function() {
            currentIndex = parseInt($(this).data('index'));
            updateLightbox();
        });
        
        // Keyboard
        $(document).on('keydown', function(e) {
            if (!$('#mst-lightbox-v2').hasClass('active')) return;
            if (e.key === 'Escape') closeLightbox();
            if (e.key === 'ArrowLeft') prevPhoto();
            if (e.key === 'ArrowRight') nextPhoto();
        });
        
        // AJAX Load More Reviews
        $('#mst-load-more-reviews').on('click', function() {
            var btn = $(this);
            var container = $('#mst-reviews-container');
            var guideId = container.data('guide-id');
            var page = parseInt(container.data('page')) + 1;
            var perPage = container.data('per-page');
            
            btn.prop('disabled', true).html('<span class="spinner"></span> <?php _e("Загрузка...", "mst-auth-lk"); ?>');
            
            $.ajax({
                url: mstAuthLK.ajax_url,
                type: 'POST',
                data: {
                    action: 'mst_load_more_guide_reviews',
                    nonce: mstAuthLK.nonce,
                    guide_id: guideId,
                    page: page,
                    per_page: perPage
                },
                success: function(response) {
                    if (response.success && response.data.html) {
                        container.append(response.data.html);
                        container.data('page', page);
                        
                        // Update lightbox photos
                        if (response.data.photos) {
                            lightboxPhotos = lightboxPhotos.concat(response.data.photos);
                            $('#lb-total').text(lightboxPhotos.length);
                        }
                        
                        if (!response.data.has_more) {
                            btn.parent().remove();
                        } else {
                            btn.prop('disabled', false).html('<?php _e("Загрузить ещё", "mst-auth-lk"); ?> ↓');
                        }
                    } else {
                        btn.parent().remove();
                    }
                },
                error: function() {
                    btn.prop('disabled', false).html('<?php _e("Загрузить ещё", "mst-auth-lk"); ?> ↓');
                }
            });
        });
    });
})(jQuery);
</script>
