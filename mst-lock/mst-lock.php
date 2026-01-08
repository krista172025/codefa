<?php
/**
 * Plugin Name: MST Lock
 * Description: Максимальная защита клиентского UI: удаляет title/tooltips, очищает указанные data-атрибуты осторожно, минимизирует выдачу конфигураций на фронтенд, добавляет CSP, удаляет source map ссылки, аудит wp_localize_script и опциональное агрессивное детектирование DevTools.
 * Version: 1.1.0
 * Author: Telegram @l1ghtsun
 * Author URI: https://t.me/l1ghtsun
 * Text Domain: mst-lock
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Default options
 */
function mst_lock_get_defaults() {
	return [
		'remove_title_attributes' => 1,
		// только собственные префиксы по умолчанию; НЕ использовать data-* глобально
		'remove_data_attrs'       => "data-mst*\ndata-latepoint*",
		'strip_inline_configs'    => 1,
		'block_console'           => 0,
		'js_enabled'              => 1,
		'aggressive_devtools'     => 0, // если включено — клиентский overlay/detector активируется
		'csp_header'              => "default-src 'self' https:; script-src 'self' https:; style-src 'self' 'unsafe-inline' https:; object-src 'none'; base-uri 'self';",
		'remove_source_maps'      => 1,
	];
}

/**
 * Activation: set defaults (if not present)
 */
register_activation_hook( __FILE__, function() {
	$defaults = mst_lock_get_defaults();
	add_option( 'mst_lock_options', $defaults );
} );

/**
 * Deactivation: keep options for admin
 */
register_deactivation_hook( __FILE__, function() {
	// no destructive cleanup
} );

/**
 * Retrieve options
 */
function mst_lock_get_options() {
	$opts = get_option( 'mst_lock_options', [] );
	if ( ! is_array( $opts ) ) $opts = [];
	return wp_parse_args( $opts, mst_lock_get_defaults() );
}

/* =========================================================================
   Safe HTML stripper (do not break WooCommerce/XStore)
   ========================================================================= */

/**
 * Safe stripper — removes title and configured data-* patterns unless
 * content appears to contain WooCommerce/XStore product blocks.
 */
function mst_lock_strip_attrs_in_html( $html ) {
	if ( empty( $html ) ) return $html;

	$opts = mst_lock_get_options();

	// If nothing enabled, return early
	if ( empty( $opts['remove_title_attributes'] ) && empty( $opts['remove_data_attrs'] ) ) {
		return $html;
	}

	// Detect product-like content - if found, skip data-* removal
	$skip_data_removal = false;
	$indicators = [
		'[products',
		'[product',
		'[recent_products',
		'[best_selling_products',
		'class="products"',
		'class="woocommerce',
		'data-product',
		'woocommerce-loop',
		'wc-block-grid__product',
		'elementor',
		'xs-carousel',
		'data-wc-',
	];

	foreach ( $indicators as $token ) {
		if ( stripos( $html, $token ) !== false ) {
			$skip_data_removal = true;
			break;
		}
	}

	// Remove title attributes if enabled
	if ( ! empty( $opts['remove_title_attributes'] ) ) {
		$html = preg_replace( '/\s+title=(["\'])(?:.*?)*\1/i', '', $html );
		$html = preg_replace( '/\s+title=[^\s>]+/i', '', $html );
	}

	// Remove configured data attributes (only if not skipping)
	$patterns_raw = trim( $opts['remove_data_attrs'] );
	if ( ! $skip_data_removal && $patterns_raw !== '' ) {
		$lines = preg_split( "/\r\n|\n|\r/", $patterns_raw );
		foreach ( $lines as $pat ) {
			$pat = trim( $pat );
			if ( $pat === '' ) continue;
			$regex = '/\s+' . str_replace( '\*', '.*?', preg_quote( $pat, '/' ) ) . '=(["\'])(?:.*?)*\1/i';
			$html = preg_replace( $regex, '', $html );
			$regex2 = '/\s+' . str_replace( '\*', '.*?', preg_quote( $pat, '/' ) ) . '=[^\s>]+/i';
			$html = preg_replace( $regex2, '', $html );
		}
	}

	return $html;
}

/* Attach filters (content/excerpt/widget) */
add_filter( 'the_content', 'mst_lock_strip_attrs_in_html', 999 );
add_filter( 'get_the_excerpt', 'mst_lock_strip_attrs_in_html', 999 );
add_filter( 'widget_text', 'mst_lock_strip_attrs_in_html', 999 );

/* Also sanitise attachment image attributes (remove title) */
add_filter( 'wp_get_attachment_image_attributes', function( $attr, $attachment = null, $size = null ) {
	$opts = mst_lock_get_options();
	if ( ! empty( $opts['remove_title_attributes'] ) && isset( $attr['title'] ) ) {
		unset( $attr['title'] );
	}
	return $attr;
}, 20, 3 );

/* =========================================================================
   Output Buffer: remove inline sensitive tokens and sourceMappingURL comments
   ========================================================================= */

add_action( 'template_redirect', function() {
	$opts = mst_lock_get_options();
	if ( ! is_admin() && ( ! empty( $opts['strip_inline_configs'] ) || ! empty( $opts['remove_source_maps'] ) ) ) {
		ob_start( 'mst_lock_strip_inline_configs' );
	}
} );

function mst_lock_strip_inline_configs( $buffer ) {
	$opts = mst_lock_get_options();

	// Remove inline global assignment patterns (best-effort)
	if ( ! empty( $opts['strip_inline_configs'] ) ) {
		$tokens = [
			'window.MST_',
			'window.MSTConfig',
			'window.mstConfig',
			'window.mst_lock_config',
			'var MSTConfig',
			'var mstConfig',
		];

		foreach ( $tokens as $t ) {
			$pattern = '/[^\n]{0,200}(' . preg_quote( $t, '/' ) . ')[^\n;]*;?/i';
			$buffer = preg_replace( $pattern, '', $buffer );
		}

		// Remove script blocks containing MST_LOCK_SENSITIVE marker
		$buffer = preg_replace( '/<script\b[^>]*>[\s\S]*?MST_LOCK_SENSITIVE[\s\S]*?<\/script>/i', '', $buffer );
	}

	// Remove source map comments to prevent easy viewing of originals
	if ( ! empty( $opts['remove_source_maps'] ) ) {
		// remove both single-line and block occurrences of sourceMappingURL
		$buffer = preg_replace( '/\/\/[#@]\s*sourceMappingURL=.*?(\.map)?\s*/i', '', $buffer );
		$buffer = preg_replace( '/\/\*#\s*sourceMappingURL=.*?(\.map)?\s*\*\//i', '', $buffer );
	}

	return $buffer;
}

/* =========================================================================
   Send Content-Security-Policy header (admin-configurable)
   ========================================================================= */

add_action( 'send_headers', function() {
	$opts = mst_lock_get_options();
	$csp = trim( $opts['csp_header'] );
	if ( ! empty( $csp ) ) {
		// Use header only if not already sent
		if ( ! headers_sent() ) {
			header( 'Content-Security-Policy: ' . $csp );
		}
	}
} );

/* =========================================================================
   Frontend JS: MutationObserver + DevTools detector (aggressive optional)
   ========================================================================= */

add_action( 'wp_enqueue_scripts', function() {
	$opts = mst_lock_get_options();
	if ( empty( $opts['js_enabled'] ) ) return;

	wp_register_script(
		'mst-lock-strip',
		plugins_url( 'assets/js/mst-lock-strip.js', __FILE__ ),
		[ 'jquery' ],
		'1.1.0',
		true
	);

	$client_cfg = [
		'removeTitle'       => ! empty( $opts['remove_title_attributes'] ),
		'removePatterns'    => array_values( array_filter( array_map( 'trim', preg_split("/\r\n|\n|\r/", $opts['remove_data_attrs']) ) ) ),
		'blockConsole'      => ! empty( $opts['block_console'] ),
		'aggressiveDevtools'=> ! empty( $opts['aggressive_devtools'] ),
	];

	wp_add_inline_script( 'mst-lock-strip', 'window.mstLockClientConfig = ' . wp_json_encode( $client_cfg ) . ';', 'before' );
	wp_enqueue_script( 'mst-lock-strip' );
} );

/* =========================================================================
   Admin: Settings page + wp_localize_script audit + controls
   ========================================================================= */

add_action( 'admin_menu', function() {
	add_options_page( 'MST Lock', 'MST Lock', 'manage_options', 'mst-lock', 'mst_lock_admin_page' );
} );

function mst_lock_admin_page() {
	if ( ! current_user_can( 'manage_options' ) ) wp_die( __( 'Not allowed' ) );

	$opts = mst_lock_get_options();
	$updated = false;

	// Handle save
	if ( isset( $_POST['mst_lock_submit'] ) && check_admin_referer( 'mst_lock_save', 'mst_lock_nonce' ) ) {
		$opts['remove_title_attributes'] = isset( $_POST['remove_title_attributes'] ) ? 1 : 0;
		$attrs = isset( $_POST['remove_data_attrs'] ) ? wp_kses_post( wp_unslash( $_POST['remove_data_attrs'] ) ) : '';
		$opts['remove_data_attrs'] = $attrs;
		$opts['strip_inline_configs'] = isset( $_POST['strip_inline_configs'] ) ? 1 : 0;
		$opts['block_console'] = isset( $_POST['block_console'] ) ? 1 : 0;
		$opts['js_enabled'] = isset( $_POST['js_enabled'] ) ? 1 : 0;
		$opts['aggressive_devtools'] = isset( $_POST['aggressive_devtools'] ) ? 1 : 0;
		$opts['csp_header'] = isset( $_POST['csp_header'] ) ? wp_strip_all_tags( wp_unslash( $_POST['csp_header'] ) ) : $opts['csp_header'];
		$opts['remove_source_maps'] = isset( $_POST['remove_source_maps'] ) ? 1 : 0;

		update_option( 'mst_lock_options', $opts );
		$updated = true;
	}

	?>
	<div class="wrap">
		<h1>MST Lock — Security & UI cleanup</h1>
		<?php if ( $updated ) : ?>
			<div class="updated notice"><p>Настройки сохранены.</p></div>
		<?php endif; ?>

		<form method="post">
			<?php wp_nonce_field( 'mst_lock_save', 'mst_lock_nonce' ); ?>

			<table class="form-table" role="presentation">
				<tr>
					<th>Удалять title атрибуты</th>
					<td><input type="checkbox" name="remove_title_attributes" value="1" <?php checked( $opts['remove_title_attributes'], 1 ); ?>></td>
				</tr>

				<tr>
					<th>Удалять data-атрибуты по шаблону</th>
					<td>
						<textarea name="remove_data_attrs" rows="6" cols="60" class="large-text code"><?php echo esc_textarea( $opts['remove_data_attrs'] ); ?></textarea>
						<p class="description">Каждая строка — шаблон. Примеры: <code>data-mst*</code>, <code>data-latepoint*</code>. Никогда не используйте <code>data-*</code> глобально (ломает темы/плагины).</p>
					</td>
				</tr>

				<tr>
					<th>Стрипать inline-конфигурации (best-effort)</th>
					<td><input type="checkbox" name="strip_inline_configs" value="1" <?php checked( $opts['strip_inline_configs'], 1 ); ?>></td>
				</tr>

				<tr>
					<th>Удалять source map ссылки (recommended)</th>
					<td><input type="checkbox" name="remove_source_maps" value="1" <?php checked( $opts['remove_source_maps'], 1 ); ?>></td>
				</tr>

				<tr>
					<th>Включить клиентский JS очистки</th>
					<td><input type="checkbox" name="js_enabled" value="1" <?php checked( $opts['js_enabled'], 1 ); ?>></td>
				</tr>

				<tr>
					<th>Агрессивное детектирование DevTools (overlay)</th>
					<td>
						<input type="checkbox" name="aggressive_devtools" value="1" <?php checked( $opts['aggressive_devtools'], 1 ); ?>>
						<p class="description">Если включено — при обнаружении DevTools будет показан overlay. Это отпугивающий механизм; он может мешать разработчикам и пользователям.</p>
					</td>
				</tr>

				<tr>
					<th>Блокировать консоль / F12 (слабая защита)</th>
					<td><input type="checkbox" name="block_console" value="1" <?php checked( $opts['block_console'], 1 ); ?>></td>
				</tr>

				<tr>
					<th>Content-Security-Policy header</th>
					<td>
						<textarea name="csp_header" rows="3" cols="60" class="large-text code"><?php echo esc_textarea( $opts['csp_header'] ); ?></textarea>
						<p class="description">Настройка CSP. Будьте осторожны: строгий CSP может ломать сторонние скрипты. По умолчанию установлено безопасное значение.</p>
					</td>
				</tr>
			</table>

			<?php submit_button( 'Сохранить настройки', 'primary', 'mst_lock_submit' ); ?>
		</form>

		<hr>

		<h2>Аудит wp_localize_script</h2>
		<p>Ниже перечислены зарегистрированные скрипты и их локализованные данные (если есть). Проверьте на наличие ключей/значений с именами <code>key</code>, <code>token</code>, <code>secret</code>, <code>api_key</code>, <code>password</code> и т.п.</p>
		<?php mst_lock_render_localize_audit(); ?>
	</div>
	<?php
}

/**
 * Render audit table for wp_localize_script
 */
function mst_lock_render_localize_audit() {
	global $wp_scripts;
	if ( ! isset( $wp_scripts ) ) {
		$wp_scripts = wp_scripts();
	}
	$data = [];

	foreach ( $wp_scripts->registered as $handle => $obj ) {
		$localized = [];
		// look for extra_data 'data' created by wp_localize_script
		if ( ! empty( $obj->extra_data ) ) {
			foreach ( $obj->extra_data as $k => $v ) {
				if ( $k === 'data' ) {
					// $v contains the localized JS string; extract var name and content heuristically
					$localized[] = $v;
				}
			}
		}
		// also check 'l10n' property (some WP versions)
		if ( ! empty( $obj->l10n ) && is_array( $obj->l10n ) ) {
			foreach ( $obj->l10n as $var => $val ) {
				$localized[] = [ $var => $val ];
			}
		}
		if ( ! empty( $localized ) ) {
			$data[ $handle ] = $localized;
		}
	}

	if ( empty( $data ) ) {
		echo '<p>Локализаций не найдено.</p>';
		return;
	}

	echo '<table class="widefat fixed striped"><thead><tr><th>Handle</th><th>Localized data (truncated)</th><th>Danger keys</th></tr></thead><tbody>';
	$suspect_keys = [ 'key', 'token', 'secret', 'api_key', 'password', 'access_key' ];

	foreach ( $data as $handle => $localized_list ) {
		$truncated = esc_html( wp_trim_words( print_r( $localized_list, true ), 40, '...' ) );
		$danger = [];
		// naive scan for suspect substrings
		$text = strtolower( print_r( $localized_list, true ) );
		foreach ( $suspect_keys as $k ) {
			if ( strpos( $text, $k ) !== false ) $danger[] = $k;
		}
		echo '<tr>';
		echo '<td>' . esc_html( $handle ) . '</td>';
		echo '<td><pre style="white-space:pre-wrap;max-height:180px;overflow:auto;">' . $truncated . '</pre></td>';
		echo '<td>' . ( empty( $danger ) ? '—' : esc_html( implode( ', ', $danger ) ) ) . '</td>';
		echo '</tr>';
	}
	echo '</tbody></table>';
}

/* =========================================================================
   Secure endpoints: register plugin REST endpoints and admin-ajax actions
   ========================================================================= */

/**
 * REST endpoint: /mst-lock/v1/settings  (already exists in previous version)
 * REST endpoint: /mst-lock/v1/scan-localize - scan localized scripts and return results (admin only)
 */
add_action( 'rest_api_init', function() {
	register_rest_route( 'mst-lock/v1', '/settings', [
		'methods'             => 'GET',
		'permission_callback' => function() {
			return current_user_can( 'manage_options' );
		},
		'callback'            => function() {
			$opts = mst_lock_get_options();
			return rest_ensure_response( [
				'remove_title_attributes' => (bool) $opts['remove_title_attributes'],
				'remove_data_attrs'       => (string) $opts['remove_data_attrs'],
				'strip_inline_configs'    => (bool) $opts['strip_inline_configs'],
				'js_enabled'              => (bool) $opts['js_enabled'],
				'aggressive_devtools'     => (bool) $opts['aggressive_devtools'],
				'csp_header'              => (string) $opts['csp_header'],
				'remove_source_maps'      => (bool) $opts['remove_source_maps'],
			] );
		}
	] );

	register_rest_route( 'mst-lock/v1', '/scan-localize', [
		'methods'             => 'GET',
		'permission_callback' => function() {
			return current_user_can( 'manage_options' );
		},
		'callback'            => function() {
			// reuse the audit logic and return a compact result
			global $wp_scripts;
			if ( ! isset( $wp_scripts ) ) $wp_scripts = wp_scripts();
			$result = [];
			foreach ( $wp_scripts->registered as $handle => $obj ) {
				$localized = [];
				if ( ! empty( $obj->extra_data ) ) {
					foreach ( $obj->extra_data as $k => $v ) {
						if ( $k === 'data' ) $localized[] = $v;
					}
				}
				if ( ! empty( $obj->l10n ) ) {
					$localized = array_merge( $localized, (array) $obj->l10n );
				}
				if ( ! empty( $localized ) ) {
					$result[ $handle ] = $localized;
				}
			}
			return rest_ensure_response( $result );
		}
	] );
} );

/* Admin-Ajax secure endpoint sample (example: remove sensitive transient) */
add_action( 'wp_ajax_mst_lock_clear_cache', function() {
	if ( ! current_user_can( 'manage_options' ) ) wp_send_json_error( 'no_rights' );
	check_ajax_referer( 'mst_lock_admin_action', 'nonce' );
	// example action: clear plugin transient(s)
	delete_transient( 'mst_lock_last_scan' );
	wp_send_json_success( 'cleared' );
} );

/* =========================================================================
   Avoid outputting secrets from this plugin via wp_localize_script
   (we simply do not localize any secrets) - audit is provided above.
   ========================================================================= */

/* End of plugin file */