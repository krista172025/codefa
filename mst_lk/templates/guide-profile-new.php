<?php
/**
 * Guide Profile Page - New Design
 * Shows detailed guide information including tours, reviews, achievements
 */

if (!defined('ABSPATH')) exit;

get_header();

$guide_id = get_query_var('guide_id') ? intval(get_query_var('guide_id')) : 0;

if (!$guide_id) {
    echo '<div style="text-align:center;padding:60px 20px;"><h2>Гид не найден</h2><p>Пожалуйста, выберите гида из каталога экскурсий.</p></div>';
    get_footer();
    exit;
}

$guide = get_userdata($guide_id);
if (!$guide) {
    echo '<div style="text-align:center;padding:60px 20px;"><h2>Гид не найден</h2><p>Пожалуйста, выберите гида из каталога экскурсий.</p></div>';
    get_footer();
    exit;
}

$custom_avatar = get_user_meta($guide_id, 'mst_lk_avatar', true);
$avatar_url = $custom_avatar ? wp_get_attachment_url($custom_avatar) : get_avatar_url($guide_id, ['size' => 200]);

$rating = get_user_meta($guide_id, 'mst_guide_rating', true) ?: '5.0';
$reviews_count = get_user_meta($guide_id, 'mst_guide_reviews_count', true) ?: '0';
$experience = get_user_meta($guide_id, 'mst_guide_experience', true) ?: '';
$languages = get_user_meta($guide_id, 'mst_guide_languages', true) ?: '';
$specialization = get_user_meta($guide_id, 'mst_guide_specialization', true) ?: '';
$city = get_user_meta($guide_id, 'mst_guide_city', true) ?: '';
$experience_years = get_user_meta($guide_id, 'mst_guide_experience_years', true) ?: '8';
$tours_count = get_user_meta($guide_id, 'mst_guide_tours_count', true) ?: '234';
$achievements = get_user_meta($guide_id, 'mst_guide_achievements', true) ?: '';

$user_status = get_user_meta($guide_id, 'mst_user_status', true) ?: 'guide';
$status_colors = [
    'bronze' => '#CD7F32',
    'silver' => '#C0C0C0', 
    'gold' => '#FFD700',
    'guide' => '#00c896'
];
$border_color = $status_colors[$user_status] ?? '#00c896';

$tours_args = [
    'post_type' => 'product',
    'posts_per_page' => 6,
    'meta_query' => [[
        'key' => '_mst_guide_id',
        'value' => $guide_id,
        'compare' => '='
    ]]
];
$tours_query = new WP_Query($tours_args);
?>

<div class="mst-guide-profile" style="max-width:1200px;margin:0 auto;padding:40px 20px;">
    
    <!-- Hero Section -->
    <div style="background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);border-radius:20px;padding:50px 40px;color:#fff;margin-bottom:40px;">
        <div style="display:flex;gap:30px;align-items:flex-start;flex-wrap:wrap;">
            <div style="flex-shrink:0;">
                <div style="width:180px;height:180px;border-radius:50%;padding:5px;background:<?php echo $border_color; ?>;box-shadow:0 10px 30px rgba(0,0,0,0.3);">
                    <img src="<?php echo esc_url($avatar_url); ?>" alt="<?php echo esc_attr($guide->display_name); ?>" style="width:170px;height:170px;border-radius:50%;object-fit:cover;border:5px solid #fff;">
                </div>
            </div>
            <div style="flex:1;min-width:300px;">
                <h1 style="font-size:42px;font-weight:700;margin:0 0 10px;color:#fff;"><?php echo esc_html($guide->display_name); ?></h1>
                <?php if ($city): ?>
                    <div style="font-size:18px;margin-bottom:15px;opacity:0.95;">📍 <?php echo esc_html($city); ?></div>
                <?php endif; ?>
                <div style="display:flex;align-items:center;gap:8px;font-size:22px;margin-bottom:30px;">
                    <span style="font-size:26px;">⭐</span>
                    <span style="font-weight:700;"><?php echo esc_html($rating); ?></span>
                    <span style="opacity:0.85;">(<?php echo esc_html($reviews_count); ?> отзывов)</span>
                </div>
                <div style="display:flex;gap:50px;flex-wrap:wrap;">
                    <div>
                        <div style="font-size:14px;opacity:0.8;margin-bottom:5px;">Опыт</div>
                        <div style="font-size:32px;font-weight:700;"><?php echo esc_html($experience_years); ?> лет</div>
                    </div>
                    <div>
                        <div style="font-size:14px;opacity:0.8;margin-bottom:5px;">Туров проведено</div>
                        <div style="font-size:32px;font-weight:700;"><?php echo esc_html($tours_count); ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <?php if ($languages): ?>
        <div style="background:#fff;border-radius:15px;padding:30px;margin-bottom:30px;box-shadow:0 2px 10px rgba(0,0,0,0.05);">
            <h3 style="font-size:22px;font-weight:700;margin:0 0 20px;color:#333;">🗣️ Языки</h3>
            <div style="display:flex;flex-wrap:wrap;gap:10px;">
                <?php foreach (explode(',', $languages) as $lang): ?>
                    <span style="padding:10px 18px;border-radius:20px;font-size:15px;font-weight:600;background:#FFF4E6;color:#F59E0B;"><?php echo esc_html(trim($lang)); ?></span>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
    
    <?php if ($specialization): ?>
        <div style="background:#fff;border-radius:15px;padding:30px;margin-bottom:30px;box-shadow:0 2px 10px rgba(0,0,0,0.05);">
            <h3 style="font-size:22px;font-weight:700;margin:0 0 20px;color:#333;">🎯 Специализация</h3>
            <div style="display:flex;flex-wrap:wrap;gap:10px;">
                <?php foreach (explode(',', $specialization) as $spec): ?>
                    <span style="padding:10px 18px;border-radius:20px;font-size:15px;font-weight:600;background:#E0F2FE;color:#0EA5E9;"><?php echo esc_html(trim($spec)); ?></span>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
    
    <?php if ($experience): ?>
        <div style="background:#fff;border-radius:15px;padding:30px;margin-bottom:30px;box-shadow:0 2px 10px rgba(0,0,0,0.05);">
            <h3 style="font-size:22px;font-weight:700;margin:0 0 20px;color:#333;">📖 О гиде</h3>
            <div style="line-height:1.8;font-size:16px;color:#555;"><?php echo nl2br(esc_html($experience)); ?></div>
        </div>
    <?php endif; ?>
    
    <?php if ($achievements): ?>
        <div style="background:#fff;border-radius:15px;padding:30px;margin-bottom:30px;box-shadow:0 2px 10px rgba(0,0,0,0.05);">
            <h3 style="font-size:22px;font-weight:700;margin:0 0 20px;color:#333;">🏆 Достижения</h3>
            <ul style="list-style:none;padding:0;margin:0;">
                <?php foreach (explode("\n", $achievements) as $achievement): 
                    if (trim($achievement)): ?>
                        <li style="padding:12px 0;border-bottom:1px solid #f0f0f0;font-size:16px;color:#555;">🎖️ <?php echo esc_html(trim($achievement)); ?></li>
                    <?php endif;
                endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
    
    <?php if ($tours_query->have_posts()): ?>
        <div style="background:#fff;border-radius:15px;padding:30px;box-shadow:0 2px 10px rgba(0,0,0,0.05);">
            <h2 style="font-size:30px;font-weight:700;margin:0 0 30px;color:#333;">Популярные туры</h2>
            <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:25px;">
                <?php while ($tours_query->have_posts()): $tours_query->the_post();
                    $product = wc_get_product(get_the_ID());
                    ?>
                    <div style="border-radius:12px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,0.08);transition:transform 0.2s,box-shadow 0.2s;" onmouseover="this.style.transform='translateY(-5px)';this.style.boxShadow='0 8px 20px rgba(0,0,0,0.15)';" onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 2px 8px rgba(0,0,0,0.08)';">
                        <a href="<?php the_permalink(); ?>" style="text-decoration:none;color:inherit;display:block;">
                            <?php if (has_post_thumbnail()): ?>
                                <?php the_post_thumbnail('medium', ['style' => 'width:100%;height:200px;object-fit:cover;']); ?>
                            <?php endif; ?>
                            <div style="padding:20px;">
                                <h4 style="font-size:16px;font-weight:600;margin:0 0 15px;color:#333;"><?php the_title(); ?></h4>
                                <div style="color:#00c896;font-weight:700;font-size:18px;"><?php echo $product->get_price_html(); ?></div>
                            </div>
                        </a>
                    </div>
                <?php endwhile; wp_reset_postdata(); ?>
            </div>
        </div>
    <?php endif; ?>
    
</div>

<?php get_footer(); ?>
