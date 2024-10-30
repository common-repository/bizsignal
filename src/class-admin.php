<?php
namespace kumapiyo\bizsignal;

/**
 * This defines the wp-admin pages for bizsignal.
 * 
 * @author sungohan
 * @package bizsignal
 * @since 0.9
 */
final class Admin {

	/**
	 * Singleton object.
	 * 
	 * @var self
	 */
	private static $admin;

	/**
	 * We can use this signals.
	 * 
	 * @since 0.9
	 * @var signals
	 */
	private $signals;

	private const FIELDSKEY = config::PLUGIN_ID;
	
	/**
	 * Requires Signals.
	 * 
	 * @since 0.9
	 * @param signals $signals
	 */
	private function __construct() {
	}

	/**
	 * The first, You acceess this one and finished.
	 */
	public static function run( signals $sigs = null ) {
		static $i = 0;
		if ( ! $i++ ) {
			if( ! is_null( $sigs ) ) {
				self::getInstanse()->signals = $sigs;
			}

			self::getInstanse()->render();
		}
	}
	
	/**
	 * Insert Admin's page.
	 * 
	 * @since 0.9
	 */
	public function render() {
		add_action( 'admin_enqueue_scripts', array( $this, '_enqueue_scripts' ) );
		add_action( 'admin_menu', array( $this, '_setup_admin_menu' ) );
		add_action( 'admin_init', array( $this, '_admin_init' ) );
	}

	public function _enqueue_scripts() {
		wp_enqueue_script( config::PLUGIN_NAME, plugins_url( 'assets/js/bzsignal.js', dirname( __FILE__ ) ), array(), config::VERSION, false );
		wp_enqueue_style( config::PLUGIN_NAME, plugins_url( 'assets/css/bizsignal.css', dirname( __FILE__ ) ) );
		if($this->signals instanceof signals) {
			wp_add_inline_script(
				config::PLUGIN_NAME,
				sprintf(
					'var _bzsignal_signals = %s;',
					wp_json_encode( $this->signals->toArray() )
				),
				'before'
			);
		}
	}

	public function _setup_admin_menu() {
		add_menu_page( esc_html__( 'BizSignal', config::PLUGIN_NAME ), esc_html__( 'BizSignal', config::PLUGIN_NAME ), 'manage_options', 'bizsignal', array( $this, 'show_main_view' ), 'dashicons-format-gallery', 99 );
	}

	public function _admin_init() {
		if( ! $this->signals instanceof signals ) {
			return;
		}
		register_setting( self::FIELDSKEY, config::PREFIX_FIELD_NAME, function( $values ) {
			$value = explode( ",", $values );
			if($this->signals->hasKey( $value[0] ) ) {
				return $values;
			}
			return "";
		});
	}
	
	/**
	 * Echo main view.
	 * 
	 * @return string
	 * @since 0.9
	 */
	public function show_main_view() {
		$id = config::PLUGIN_NAME;
		?>
		<div class="wrap">
		<h1><?php esc_html_e( 'BizSignal', $id );?></h1>
			<div>
			<h2><?php esc_html_e( 'How to use.', $id );?></h2>
			<p><?php esc_html_e( 'You can use the shortcode below.', $id );?></p>
			<code>[bizsignal]</code>

			<p><?php esc_html_e( 'If you want to see the legend, do the following;', $id );?></p>
			<code>[bizsignal mode=legend]</code>
			
			<h3><?php esc_html_e( 'When writing in the template file, do as follows.', $id );?></h3>
			<p><code>do_shortcode( '[bizsignal]' );</code></p>
			<p><code>do_shortcode( '[bizsignal mode=legend]' );</code></p>
			</div>
			
			<div>
			<?php if( ! $this->signals instanceof signals ): ?>
				<h2><?php esc_html_e( 'We have not signals.', $id );?></h2>

			<?php else:?>
				<h2><?php esc_html_e( 'Crowded? or Not crowded? You can change the image of signal now.', $id );?></h2>			
				<h3><?php esc_html_e( 'This is the image of signal currently displayed.', $id );?></h3>
				<?php echo wp_kses_post( do_shortcode( '[bizsignal]' ) );?>

				<h3><?php esc_html_e( 'You can change the signal image here.', $id );?></h3>
				<form method="POST" action="options.php">
				<?php
					settings_fields( self::FIELDSKEY );
					do_settings_sections( self::FIELDSKEY );
				?>
				<table class="form-table">
				<tr valign="top">
				<td colspan="2">
				<div id="after">
				<?php echo wp_kses_post( do_shortcode( '[bizsignal mode=image]' ) );?>
				</div>
				<select id="<?php echo wp_kses_post( config::PREFIX_FIELD_NAME );?>" name="<?php echo wp_kses_post( config::PREFIX_FIELD_NAME );?>">
				<?php
					$_now = get_option( config::PREFIX_FIELD_NAME )?: "_off";
					$now = explode( ",", $_now );
					$time = time();
				?>
				<?php foreach ( $this->signals->getKeyValues() as $key => $label ):?>
					<?php $selected = ($now[0] === $key)? "selected='selected'": '';?>
					<option <?php esc_attr_e( $selected );?> value="<?php esc_attr_e( $key );?>,<?php esc_attr_e( $time );?>"><?php esc_html_e( $label, $id );?></option>
				<?php endforeach;?>
				</select>
				</td>
				</tr>		
				</table>
				<?php submit_button( esc_html__( 'Save & display', $id ) );?>
				</form>
			<?php endif;?>
			</div>

			<?php if( $this->signals instanceof signals ): ?>
				<h2><?php esc_html_e( 'Legend', $id );?></h2>
				<?php echo wp_kses_post( do_shortcode( '[bizsignal mode=hanrei]' ) );?>
			<?php endif;?>
		</div>
		<?php
	}
	
	/**
	 * Return singleton admin instance.
	 * 
	 * @since 0.9
	 * @static
	 * @param signals $sigs
	 * @return class-admin
	 */
	private static function getInstanse() {
	if( ! isset( self::$admin ) ) {
			self::$admin = new self();
		}
		return self::$admin;
	}
}

