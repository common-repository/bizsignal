<?php
/**
Plugin Name: BizSignal
Plugin URI: https://icghn.com/bizsignal
Description: Keep social distance. This plugin is simple display that how crowded your business is.
Version: 1.0.1
Requires at least: 4.8.17
Requires PHP: 7.1
Author: Office Sungohan
Text Domain: kumapiyo_bzsgnl
Domain Path: /languages/
License: GPLv2
*/
require_once 'src/class-config.php';
require_once 'src/class-exceptions.php';
require_once 'src/class-i-arrayable.php';
require_once 'src/class-collection.php';
require_once 'src/class-signal.php';
require_once 'src/class-signals.php';
require_once 'src/class-main.php';
require_once 'src/class-admin.php';

define( 'BZSGNL__FILE__', __FILE__ );
define( 'BZSGNL__DIR__', dirname( __FILE__ ) );
$kumapiyo_bizsignal_errors = new WP_Error();

load_plugin_textdomain( kumapiyo\bizsignal\config::PLUGIN_NAME, false, plugin_basename( BZSGNL__DIR__ ). '/languages' );
add_action( 'init', array( "kumapiyo\bizsignal\main", "run" ) );


function kumapiyo_bizsignal_show_errors_admin_notice() {
	global $kumapiyo_bizsignal_errors;
?>
	<div class="notice notice-error">
		<p>
			<strong><?php esc_html_e( 'BizSignal', kumapiyo\bizsignal\Config::PLUGIN_NAME ); ?> <?php esc_html_e( 'Error.', kumapiyo\bizsignal\Config::PLUGIN_NAME ); ?></strong>
			<ul>
			<?php foreach ( array_keys( $kumapiyo_bizsignal_errors->errors ) as $error_code ) : ?>
				<?php foreach ( $kumapiyo_bizsignal_errors->get_error_messages( $error_code ) as $message ) : ?>
					<li>
						<?php esc_html_e( $message, kumapiyo\bizsignal\Config::PLUGIN_NAME ); ?>
					</li>
				<?php endforeach; ?>
			<?php endforeach; ?>
			</ul>
		</p>
	</div>
<?php
}

function kumapiyo_bizsignal_render_errors() {
	global $kumapiyo_bizsignal_errors;
	if ( ! empty( $kumapiyo_bizsignal_errors->errors ) ) {
		kumapiyo_bizsignal_show_errors_admin_notice();	
	}	
}

add_action( 'admin_notices', 'kumapiyo_bizsignal_render_errors' );
