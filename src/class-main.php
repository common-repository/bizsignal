<?php
namespace kumapiyo\bizsignal;

/**
 * This defines shortcode and more.
 * 
 * @author sungohan
 * @package bizsignal
 * @since 0.9
 */
final class Main {

	/**
	 * Singleton object it is myself.
	 * 
	 * @var main
	 */
	private static $main;
	
	/**
	 * You can uses each signal.
	 * 
	 * @var signals|signal[]
	 */
	protected $enable_signals;

	/**
	 * Require Signals.
	 */
	private function __construct() {
	}

	/**
	 * The first, You acceess this one and finished.
	 */
	public static function run() {
		global $kumapiyo_bizsignal_errors;
		$preset = config::get_preset_signals();
		try {
			$signals = Signals::make( $preset[2]['set'] );
		}catch(signalValidationException $e) {
			$kumapiyo_bizsignal_errors->add( __LINE__, "Invalid Signals data." );
			$signals = null;
		}
		self::init( $signals );
		if ( is_admin() ) {
			Admin::run( $signals );
		}
	}

	/**
	 * Render Latest signals.
	 */
	public function render() {
		add_action( 'wp_enqueue_scripts', array( $this, '_enqueue_style' ) );

		add_filter( 'get_the_excerpt', function() {
				return '<div>' . do_shortcode( wp_trim_words( get_the_content(), 55 ) ) . '</div>';
			}
		);
		add_shortcode( 'bizsignal', array( $this, '_set_main_shortcode' ) );
	}

	public function _enqueue_style() {
		wp_enqueue_style( config::PLUGIN_ID, plugins_url( 'assets/css/bizsignal.css', dirname( __FILE__ ) ) );
	}
	
	public function _set_main_shortcode( $attr ) {
		$shortcode_type = array(
			'mode' => 'now',
		);
		
		$shortcode_atts = shortcode_atts( $shortcode_type, $attr );
		
		switch( $shortcode_atts['mode'] ) {
			case 'hanrei':
			case 'legend':
			case 'Legend':
			case 'usage':
			case 'Usage':
			case 'guide':
			case 'Guide':
				$html = "<div class='bizsignals'>";
				if( $this->enable_signals instanceof Signals ) {
					foreach( $this->enable_signals as $sig ) {
						$html .= $sig->render( self::get_sig_tpl() );
					}
				}
				$html .= '</div>';
				return $html;
			case 'image':
				return $this->get_now()->render( self::get_sig_tpl( 'image' ));
			default:
				return $this->get_now()->render( self::get_sig_tpl() );
		}
	}

	/**
	 * Returns the current `signal`.
	 * 
	 * @return signal
	 */
	public function get_now() {
		static $now = null;
		if(! $this->enable_signals instanceof Signals) {
			return signal::make();
		}
		if( is_null( $now ) ) {
			$_now = get_option( config::PREFIX_FIELD_NAME )?: '';
			$now = explode( ',', $_now );
		}
		return $this->enable_signals->get( $now[0], signal::make() );
	}

	/**
	 * Return Signal's html parts.
	 * 
	 * @return callable
	 */
	private static function get_sig_tpl( $mode = 'all', $id = '', $version = '01' ) {
		$modes = array( "all", "image", "dispname" );
		if( ! in_array( $mode, $modes ) ) {
			$mode = 'all';
		}

		return function( callable $replacement = null, $domain = config::PLUGIN_NAME ) use ( $id, $version, $mode ) {
			$_id = '';
			if ( is_string( $id ) && '' !== $id ) {
				$_id = "id = '{$id}'";
			}

			$path = esc_url( plugins_url( "assets/images/signals/{$version}", dirname( __FILE__ ), array( 'http', 'https' ) ) );
			$dispname = '%dispname%';
			if ( ! is_null( $replacement ) ) {
				$dispname = esc_html__( $replacement( '%dispname%' ), $domain );
			}
			$html = '';
			$html .= "<div {$_id} class='bizsignal'>";
			if( in_array( $mode, array( "all", "image" ) ) ) {
				$html .= "<img src='{$path}/%image_filename%' class='bizsigimg' alt='{$dispname}' />";
			}

			if( in_array( $mode, array( "all", "dispname" ) ) ) {
				$html .= "<p class='bizsigname'>{$dispname}</p>";
			}

			$html .= '</div>';
			
			if ( ! is_null( $replacement ) ) {
				return $replacement( $html );
			}
			return $html;
		};
	}

	public static function init( signals $sigs = null ) {
		static $i = 0;
		if( ! $i++ ) {
			if( ! is_null( $sigs ) ) {
				self::get_instanse()->enable_signals = $sigs;
			}
			self::get_instanse()->render();
		}
	}

	/**
	 * Return singleton object.
	 * 
	 * @return class-main
	 */
	public static function get_instanse() {
		if ( ! isset( self::$main ) ) {
			self::$main = new self();
		}
		return self::$main;
	}
	
	public function __get( $property ) {
		if ( property_exists( $this, $property ) ) {
			return $this->$property;
		}
		return null;
	}
}

