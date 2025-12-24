<?php
/**
 * Guides List Page
 * Shows all guides with cards
 */

if (!defined('ABSPATH')) exit;

get_header();

$guides = get_users([
    'meta_key' => 'mst_user_status',
    'meta_value' => 'guide',
    'orderby' => 'display_name',
    'order' => 'ASC',
    'number' => 50
]);
?>

<div style="max-width:1400px;margin:0 auto;padding:60px 20px;">
    <div style="text-align:center;margin-bottom:60px;">
        <h1 style="font-size:48px;font-weight:700;margin:0 0 15px;color:#333;">Наши гиды</h1>
        <p style="font-size:18px;color:#666;max-width:600px;margin:0 auto;">Профессиональные гиды с опытом и отличными отзывами готовы провести для вас незабываемую экскурсию</p>
    </div>
    
    <?php if (empty($guides)): ?>
        <p style="text-align:center;padding:60px 20px;font-size:18px;color:#999;">Гиды не найдены</p>
    <?php else: ?>
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(320px,1fr));gap:35px;">
        <?php foreach ($guides as $guide): 
            $guide_id = $guide->ID;
            $custom_avatar = get_user_meta($guide_id, 'mst_lk_avatar', true);
            $avatar_url = $custom_avatar ? wp_get_attachment_url($custom_avatar) : get_avatar_url($guide_id, ['size' => 200]);
            
            $rating = get_user_meta($guide_id, 'mst_guide_rating', true) ?: '5.0';
            $reviews_count = get_user_meta($guide_id, 'mst_guide_reviews_count', true) ?: '0';
            $languages = get_user_meta($guide_id, 'mst_guide_languages', true) ?: '';
            $specialization = get_user_meta($guide_id, 'mst_guide_specialization', true) ?: '';
            $city = get_user_meta($guide_id, 'mst_guide_city', true) ?: '';
            $experience_years = get_user_meta($guide_id, 'mst_guide_experience_years', true) ?: '0';
            
            $user_status = get_user_meta($guide_id, 'mst_user_status', true) ?: 'guide';
            $status_colors = [
                'bronze' => '#CD7F32',
                'silver' => '#C0C0C0', 
                'gold' => '#FFD700',
                'guide' => '#00c896'
            ];
            $border_color = $status_colors[$user_status] ?? '#00c896';
            
            $guide_url = home_url('/guide/' . $guide_id);
        ?>
        <a href="<?php echo esc_url($guide_url); ?>" style="text-decoration:none;color:inherit;">
            <div style="background:#fff;border-radius:24px;padding:30px;box-shadow:0 2px 20px rgba(0,0,0,0.08);transition:all 0.3s ease;cursor:pointer;" onmouseover="this.style.transform='translateY(-8px)';this.style.boxShadow='0 12px 35px rgba(0,0,0,0.15)';" onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 2px 20px rgba(0,0,0,0.08)';">
                <div style="text-align:center;margin-bottom:25px;">
                    <div style="width:140px;height:140px;margin:0 auto 18px;border-radius:50%;padding:5px;background:<?php echo $border_color; ?>;box-shadow:0 8px 25px rgba(0,0,0,0.15);">
                        <img src="<?php echo esc_url($avatar_url); ?>" alt="<?php echo esc_attr($guide->display_name); ?>" style="width:130px;height:130px;border-radius:50%;object-fit:cover;border:5px solid #fff;">
                    </div>
                    <h3 style="font-size:24px;font-weight:700;margin:0 0 10px;color:#333;"><?php echo esc_html($guide->display_name); ?></h3>
                    <?php if ($city): ?>
                        <div style="color:#666;font-size:15px;margin-bottom:15px;">📍 <?php echo esc_html($city); ?></div>
                    <?php endif; ?>
                    <div style="display:flex;align-items:center;justify-content:center;gap:6px;font-size:20px;margin-bottom:20px;">
                        <span style="color:#ffa500;">⭐</span>
                        <span style="font-weight:700;"><?php echo esc_html($rating); ?></span>
                        <span style="color:#999;font-size:15px;">(<?php echo esc_html($reviews_count); ?>)</span>
                    </div>
                </div>
                
                <?php if ($languages): ?>
                <div style="margin-bottom:18px;">
                    <div style="font-size:11px;color:#999;margin-bottom:10px;font-weight:700;text-transform:uppercase;letter-spacing:1px;">Языки</div>
                    <div style="display:flex;flex-wrap:wrap;gap:8px;justify-content:center;">
                        <?php foreach (explode(',', $languages) as $lang): ?>
                            <span style="padding:7px 14px;border-radius:18px;font-size:13px;font-weight:600;background:#FFF4E6;color:#F59E0B;"><?php echo esc_html(trim($lang)); ?></span>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
                
                <?php if ($specialization): ?>
                <div style="margin-bottom:18px;">
                    <div style="font-size:11px;color:#999;margin-bottom:10px;font-weight:700;text-transform:uppercase;letter-spacing:1px;">Специализация</div>
                    <div style="display:flex;flex-wrap:wrap;gap:8px;justify-content:center;">
                        <?php 
                        $specs = explode(',', $specialization);
                        $display_specs = array_slice($specs, 0, 3);
                        foreach ($display_specs as $spec): ?>
                            <span style="padding:7px 14px;border-radius:18px;font-size:13px;font-weight:600;background:#E0F2FE;color:#0EA5E9;"><?php echo esc_html(trim($spec)); ?></span>
                        <?php endforeach; ?>
                        <?php if (count($specs) > 3): ?>
                            <span style="padding:7px 14px;border-radius:18px;font-size:13px;font-weight:600;background:#F3F4F6;color:#6B7280;">+<?php echo count($specs) - 3; ?></span>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>
                
                <div style="text-align:center;padding-top:20px;border-top:2px solid #f5f5f5;">
                    <span style="color:#00c896;font-weight:700;font-size:15px;">Опыт: <?php echo esc_html($experience_years); ?> лет</span>
                </div>
            </div>
        </a>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>

<?php get_footer(); ?>
