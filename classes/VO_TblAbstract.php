<?php

class VO_TblAbstract {
	private static $propertyNames;
	
	// Guard against spelling mistakes for setters.
	function __set($key, $value) {
		$msg = "Attempt to set nonexistent member:[$key] to value: [$value] in class [".static::class."]";
		WOOOF::$instance->debug("BOOM: $msg", true);
		throw new Exception( __CLASS__ . ": $msg " );
	}
	
	// Guard against spelling mistakes for getters.
	function __get($key) {
		$msg = "Attempt to get nonexistent member:[$key] in class [".static::class."]";
		WOOOF::$instance->debug("BOOM: $msg", true);
		throw new Exception( __CLASS__ . ": $msg"   );
	}
	
	function toArray() {
		$out = [];
		foreach ( self::$propertyNames as $aPropName ) {
			$out[$aPropName] = $this->{$aPropName};
		}
		return $out;
	}
	
	function fillFromArray($in) {
		foreach ( self::$propertyNames as $aPropName ) {
			if ( isset($in[$aPropName]) ) {
				$this->{$aPropName} = $in[$aPropName];
			}
		}
		return true;
	}
	
	function __construct($in=[]) {
		self::$propertyNames = array_keys(get_object_vars($this));
		$this->fillFromArray($in);
	}
	
	
	function getProps() {
		return self::$propertyNames;
	}
	
	
	
}	// VO_TblAbstract

