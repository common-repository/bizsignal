<?php
namespace kumapiyo\bizsignal;
use IteratorAggregate;
use ArrayAccess;
use Exception;
use Countable;

/**
 * This is a collection for bizsignal.
 * 
 * @package bizsignal
 * @author sungohan
 * @since 0.9
 */
class Collection implements IteratorAggregate, ArrayAccess, Arrayable, Countable{

	private $items = [];
	public function __construct( array $items ) {
		$this->items = $items;
	}

	public function push($value, $index = null) {
		if($index === "") {
			$index = null;
		}
		$this->offsetSet($index, $value);
		return $this;
	}

	public function get($index, $default = null) {
		if($this->offsetExists($index)) {
			return $this->items[$index];
		}

		if($default instanceof \Closure) {
			return $default();
		}
		return $default;
	}

	/**
	 * Return raw items.
	 * @return array
	 */
	public function all() {
		return $this->items;
	}

	/**
	 * Return items as a plain array.
	 * @return array
	 */
	public function toArray() {
		return array_map(function ($value) {
			return $value instanceof Arrayable ? $value->toArray() : $value;
		}, $this->items);
	}
	
	public function getIterator() {
		return new \ArrayIterator($this->items);
	}
	
	/**
	 * Return a first item.
	 * 
	 * @return mixed
	 */
	public function first() {
		foreach($this->items as $value) {
			return $value;
		}
	}

	/**
	 * Synonym last().
	 * 
	 * @return mixed
	 */
	public function end() {
		return $this->last();
	}

	/**
	 * Return a last item.
	 * 
	 * @return mixed
	 */
	public function last() {
		$upsidedown = array_reverse($this->items, true);
		foreach($upsidedown as $value) {
			return $value;
		}
	}
	
	public function offsetGet($index) {
		return isset($this->items[$index])? $this->items[$index] : null;
	}

	public function offsetExists($index) {
		return isset($this->items[$index]);
	}

	/**
	 * Set the item at a given offset.
	 * {@inheritDoc}
	 * @see ArrayAccess::offsetSet()
	 * @return void
	 */
	public function offsetSet($index, $value) {
		if (is_null($index)) {
			$this->items[] = $value;
		} else {
			if(!$this->offsetExists($index)) {
				$this->items[$index] = $value;
			}
		}
	}

	public function offsetUnset($index) {
		unset($this->items[$index]);
	}

	/**
	 * 
	 * @since 1.0.1
	 * @return number
	 */
	public function count() {
		return count($this->items);
	}
}