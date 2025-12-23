<?php
class Admin_Settings {
    public static function init() {
        add_action('admin_menu', [self::class, 'add_settings_page']);
        add_action('admin_init', [self::class, 'register_settings']);
    }

    public static function add_settings_page() {
        add_menu_page(
            'Настройки фильтров',
            'Фильтры',
            'manage_options',
            'wcaf-settings',
            [self::class, 'render_settings_page'],
            'dashicons-filter',
            90
        );
    }

    public static function register_settings() {
        register_setting('wcaf-settings-group', 'wcaf_active_filters');
    }

    public static function render_settings_page() {
        $available_filters = [
            'pa_tour-type' => 'Тип тура',
            'pa_duration' => 'Длительность тура',
            'pa_transport' => 'Транспорт',
        ];

        $active_filters = get_option('wcaf_active_filters', []);

        ?>
        <div class="wrap">
            <h1>Настройки фильтров</h1>
            <form method="post" action="options.php">
                <?php settings_fields('wcaf-settings-group'); ?>
                <?php do_settings_sections('wcaf-settings-group'); ?>

                <table class="form-table">
                <?php foreach ($available_filters as $taxonomy => $label): ?>
                    <tr>
                        <th scope="row">
                            <label for="wcaf_<?php echo $taxonomy; ?>"><?php echo esc_html($label); ?></label>
                        </th>
                        <td>
                            <input type="checkbox" name="wcaf_active_filters[]" value="<?php echo esc_attr($taxonomy); ?>"
                                <?php checked(in_array($taxonomy, $active_filters)); ?>>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </table>

                <?php submit_button(); ?>
            </form>
        </div>
        <?php
    }
}

Admin_Settings::init();