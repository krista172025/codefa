<?php
class Admin_Settings {
    private static $instance;

    public static function instance() {
        if (!self::$instance) {
            self::$instance = new self();
            self::init_hooks();
        }
        return self::$instance;
    }

    private static function init_hooks() {
        add_action('admin_menu', [self::class, 'add_settings_page']);
        add_action('admin_init', [self::class, 'register_settings']);
    }

    public static function add_settings_page() {
        add_menu_page(
            'WooCommerce Filter Settings',  // Заголовок страницы
            'Фильтры',                      // Название в меню
            'manage_options',               // Права доступа
            'wcaf-settings',                // Уникальный slug для страницы
            [self::class, 'render_settings_page'], // Callback для отображения
            'dashicons-filter',             // Иконка
            90                              // Позиция в меню
        );
    }

    public static function register_settings() {
        register_setting('wcaf-settings-group', 'wcaf_active_filters'); // Регистрация опции для хранения фильтров
    }

    public static function render_settings_page() {
        $available_filters = [
            'pa_tour-type' => 'Тип тура',
            'pa_duration' => 'Длительность',
            'pa_transport' => 'Транспорт',
        ];
        $active_filters = get_option('wcaf_active_filters', []); ?>

        <div class="wrap">
            <h1><?php echo esc_html('Настройки фильтров'); ?></h1>
            <form method="post" action="options.php">
                <?php settings_fields('wcaf-settings-group'); ?>
                <?php do_settings_sections('wcaf-settings-group'); ?>

                <table class="form-table">
                    <?php foreach ($available_filters as $taxonomy => $label): ?>
                        <tr>
                            <th scope="row">
                                <label for="wcaf_<?php echo esc_attr($taxonomy); ?>">
                                    <?php echo esc_html($label); ?>
                                </label>
                            </th>
                            <td>
                                <input type="checkbox" name="wcaf_active_filters[]" value="<?php echo esc_attr($taxonomy); ?>"
                                    <?php checked(in_array($taxonomy, (array) $active_filters, true)); ?>>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
                <?php submit_button('Сохранить настройки'); ?>
            </form>
        </div>
        <?php
    }
}

Admin_Settings::instance();