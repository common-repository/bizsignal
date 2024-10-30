<?php
namespace kumapiyo\bizsignal;

/**
 * This is Signal-type collection.
 * 
 * @author sungohan
 * @package bizsignal
 * @since 0.9
 */
final class Signals extends Collection {

	private $name = '';
	private $type = "kumapiyo\bizsignal\signal";

	/**
	 * Create new collection.
	 * 
	 * @param array $array (Arrays which has signal.)
	 * @return class-signals
	 */
	private function __construct( $array ) {
		foreach($array as $key => $value) {
			$this->push($value, $key);
			unset($array[$key]);
		}
		$this->name = $this->genUniqueName();
		return $this;
	}

	/**
	 * Return arrays which has `slug` in key and `dispname` in value.
	 * 
	 * @return array
	 */
	public function getKeyValues() {
		$items = $this->toArray();
		return array_column( $items, 'dispname', 'slug' );
	}


	/**
	 * Return TRUE when there have $key_strings at `$this->items`.
	 * 
	 * @param string $key_strings
	 * @return bool
	 */
	public function hasKey( $key_strings ) {
		return $this->offsetExists( $key_strings );
	}
	
	public function push($value, $index = null) {
		if($value instanceof $this->type) {
			parent::push($value, $index);
		}
		return $this;
	}

	/**
	 * Return a default Signal.
	 * 
	 * @return Signal
	 */
	public function getDefaultSignal() {

		return array_filter($this->all(), function($signal) {
			return $signal->is_default() === TRUE;
		});
	}
	
	/**
	 * Return the single at a given `$slug`.
	 * 
	 * @param string $key
	 * @return signal
	 */
	public function _get( $slug, $updated = null ) {
		if ( ! preg_match( '/[a-z0-9]{2,14}/', $slug )) {
			return new signal( $updated );
		}
		if ( ! array_key_exists( $slug, $this->items ) ) {
			return new signal( $updated );
		}
		return $this->items[ $slug ]->setUpdated( $updated );
	}


	/**
	 * Return all signal's HTML List
	 * 
	 * @return string $html
	 */
	public function getHtml( $id = "id='signals'" ) {
		$html = "<div {$id}>";
		foreach ( $this->items as $sig ) {
			$html .= $sig->getHtml();
		}
		$html .= '</div>';
		return $html;
	}

	/**
	 * Return a new Signals From array.
	 * 
	 * @param array $items (It's has signal-type arrays.)
	 * @throws signalValidationException
	 * @return signals
	 */
	public static function make( $items = array() ) {
		return new self( self::arraySet2Signal( $items ) );
	}


	/**
	 * Generate name from items.
	 */
	private function genUniqueName() {
		return md5( serialize( $this->all() ) );
	}

	/**
	 * Return arrays which has many signal.
	 *
	 * @param array $items ($items has signal formed arrays.)
	 * @throws signalValidationException
	 * @return array (as signal set)
	 */
	private static function arraySet2Signal( $items ) {
		$new = array();
		foreach ( $items as $item ) {
			if(signal::is_signal_type_array( $item )) {
				$new[ $item['slug'] ] = signal::make( $item );
			}
		}
		return $new;
	}
}
