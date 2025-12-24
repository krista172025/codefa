<?php
namespace MST_Elementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;

if (!defined('ABSPATH')) exit;

class Team_Section extends Widget_Base {

    public function get_name() {
        return 'mst-team-section';
    }

    public function get_title() {
        return __('Team Section', 'my-super-tour-elementor');
    }

    public function get_icon() {
        return 'eicon-person';
    }

    public function get_categories() {
        return ['my-super-tour'];
    }

    protected function register_controls() {
        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Team Members', 'my-super-tour-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'member_image',
            [
                'label' => __('Photo', 'my-super-tour-elementor'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $repeater->add_control(
            'member_name',
            [
                'label' => __('Name', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Member Name', 'my-super-tour-elementor'),
            ]
        );

        $repeater->add_control(
            'member_role',
            [
                'label' => __('Role', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Guide', 'my-super-tour-elementor'),
            ]
        );

        $repeater->add_control(
            'member_description',
            [
                'label' => __('Description', 'my-super-tour-elementor'),
                'type' => Controls_Manager::TEXTAREA,
                'default' => __('Member description', 'my-super-tour-elementor'),
            ]
        );

        $this->add_control(
            'team_members',
            [
                'label' => __('Team Members', 'my-super-tour-elementor'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    ['member_name' => __('Guide 1', 'my-super-tour-elementor')],
                    ['member_name' => __('Guide 2', 'my-super-tour-elementor')],
                    ['member_name' => __('Guide 3', 'my-super-tour-elementor')],
                ],
                'title_field' => '{{{ member_name }}}',
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="mst-team-section">
            <div class="mst-team-grid">
                <?php foreach ($settings['team_members'] as $member): ?>
                    <div class="mst-team-member">
                        <div class="mst-team-image">
                            <img src="<?php echo esc_url($member['member_image']['url']); ?>" alt="<?php echo esc_attr($member['member_name']); ?>">
                        </div>
                        <div class="mst-team-info">
                            <h4 class="mst-team-name"><?php echo esc_html($member['member_name']); ?></h4>
                            <p class="mst-team-role"><?php echo esc_html($member['member_role']); ?></p>
                            <p class="mst-team-description"><?php echo esc_html($member['member_description']); ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
    }
}
