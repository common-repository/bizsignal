<?php
namespace kumapiyo\bizsignal;


/**
 * This defines the `signal` type.
 * 
 * @author sungohan
 * @package bizsignal
 * @since 0.9
 *
 */
final class Signal implements Arrayable, \IteratorAggregate {

	private $slug;
	private $dispname;
	private $image_filename;
	private $short_desc;
	private $is_default;
	

	/**
	 * Create new signal.
	 * 
	 * @param string $updated
	 */
	private function __construct() {
		$this->slug = '_off';
		$this->dispname = '-';
		$this->image_filename = 'off.gif';
		$this->is_default = false;
	}

	/**
	 * Return HTML-Strings for Display.
	 * 
	 * @param callable | string $template
 	 * @return string html_strings
	 */
	public function render( $template ) {
		return $this->makeHtml( $template );
	}

	public function is_default() {
		return $this->is_default;
	}

	/**
	 * Return HTML from template-strings.
	 * 
	 * @param callable | string $template
	 * @return string (HTML-strings)
	 */
	protected function makeHtml( $template ) {
		$replacement = function( $str ) {
			$ptn = '/\%(\w+)\%/';
			$matches = array();
			preg_match_all( $ptn, $str, $matches );
			foreach ( $matches[1] as $key => $propname ) {
				if ( ! property_exists( $this, $propname ) ) {
					continue;
				}
				$ptn = "/%{$matches[1][ $key ]}%/";
				$str = preg_replace( $ptn, $this->$propname, $str );
			}
			return $str;
		};

		if( is_string( $template ) ) {
			return $replacement( $template );
		} elseif ( is_callable( $template ) ) {
			return $template( $replacement );
		}
		return '';
	}

	/**
	 * Return new signal.
	 * 
	 * @param array $array (signal-type array)
	 * @throws signalValidationException
	 * @return signal
	 */
	public static function make( array $item = [], $updated = null ) {
		$item = array_map(function($value) {
			if(is_null($value)) {
				 $value = "";
			}
			$value = trim($value);
			return $value;
		}, $item);

		$sig = new self( $updated );
		$sig->slug = $item['slug']?? $sig->slug;
		$sig->image_filename = $item['image_filename']?? $sig->image_filename;
		$sig->dispname = $item['dispname']?? $sig->dispname;
		$sig->short_desc = $item['short_desc']?? $sig->short_desc;
		$sig->is_default = $item['is_default']?? $sig->is_default;

		$sig->validate();
		return $sig;
	}

	/**
	 * Return TRUE when currently signal's slug has value.
	 * 
	 * @return boolean
	 */
	public function is_empty() {
		return '' === $this->slug;
	}

	/**
	 * Return FALSE when currently signal's slug has not value.
	 * 
	 * @return boolean
	 */
	public function is_not_empty() {
		return !$this->is_empty();
	}

	public function __toString() {
		return $this->render();
	}

	public function toArray() {
		return array (
			"slug" => $this->slug,
			"image_filename" => $this->image_filename,
			"dispname" => $this->dispname,
			"short_desc" => $this->short_desc,
			"is_default" => $this->is_default
		);	
	}

	/**
	 * Return TRUE if all rules passed.
	 * 
	 * @throws signalValidationException
	 * @return boolean
	 */
	public function validate() {
		foreach($this as $property => $value) {
			if(!self::rules($property)($value)) {
				throw new signalValidationException('');
			}
		}
		return true;
	}

	private static function rules($key) {
		$rules = [
			"slug" => function($value) {
					return (bool) preg_match("/^[a-zA-Z0-9\_\-]{2,30}$/", $value);
			},
			"image_filename" => function($value) {
				return (bool) preg_match("/^[a-zA-Z0-9\_\-\.]{3,30}$/", $value);
			},
			"dispname" => function($value) {
				return (bool) preg_match("/^[a-zA-Z0-9\!\,\.\-\_\s\p{Han}\p{Hiragana}\p{Katakana}]{1,60}$/u", $value);
			},
			"short_desc" => function($value) {
				if($value == '') {
					return true;
				}
				return (bool) preg_match("/^[a-zA-Z0-9\!\,\.\-\_\s\p{Han}\p{Hiragana}\p{Katakana}]{1,30}$/u", $value);
			},
			"is_default" => function($value) {
				return is_bool($value);
			}
		];
		if(array_key_exists($key, $rules)) {
			return $rules[$key];
		}

		return function($value) {
			return false;
		};		
	}
	
	/**
	 * Return TRUE if `$array` is signal-type.
	 * 
	 * @param array $array
	 * @return bool
	 */
	public static function is_signal_type_array( $array ) {
		static $expected_keys = null;
		if(is_null($expected_keys)) {
			$expected_keys = get_class_vars(__CLASS__);
		}

		$res = array_diff_key($array, $expected_keys);

		return count($res) === 0;		
	}

	public function getIterator() {
		return new \ArrayIterator($this->toArray());
	}
}
