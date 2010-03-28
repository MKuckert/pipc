<?php

namespace Pipc\Protocol\Serializer;

/**
 * Pipc\Protocol\Serializer\Factory
 * 
 * Factory to create serializer instances
 * 
 * @author Martin Kuckert
 * @copyright Copyright &copy; 2010 Martin Kuckert
 * @license New BSD License
 * @package Pipc\Protocol\Serializer
 * @since 28.03.2010
 */
class Factory {
	
	/**
	 * @var Pipc\Protocol\Serializer\Factory Default instance to use
	 */
	static private $_defaultInstance=NULL;
	
	/**
	 * Returns the default instance to use
	 * 
	 * @return Pipc\Protocol\Serializer\Factory
	 */
	static public function getDefaultInstance() {
		if(self::$_defaultInstance===NULL) {
			self::$_defaultInstance=new self();
		}
		return self::$_defaultInstance;
	}
	
	/**
	 * Sets the default instance
	 * 
	 * @return void
	 * @param Pipc\Protocol\Serializer\Factory
	 */
	static public function setDefaultInstance(Factory $instance) {
		self::$_defaultInstance=$instance;
	}
	
	/**
	 * Creates a serializer instance for the given name.
	 * 
	 * @throws Pipc\Protocol\Serializer\Exception
	 * @return Pipc\Protocol\Serializer\ISeralizer
	 * @param string
	 */
	public function createSerializer($name) {
		if(class_exists($name)) {
			$class=$name;
		}
		else {
			$class='Pipc\Protocol\Serializer\\'.ucfirst($name).'Serializer';
			if(!class_exists($class)) {
				throw new Exception('Failed to find a serializer class for the given name '.$name);
			}
		}
		
		return new $class();
	}
	
	/**
	 * Determines the name for the given serializer instance.
	 * 
	 * @return string
	 * @param Pipc\Protocol\Serializer\ISeralizer
	 */
	public function getName(ISerializer $serializer) {
		$class=get_class($serializer);
		
		if(!preg_match('~^Pipc\\\\Protocol\\\\Serializer\\\\(\\w+)Serializer$~', $class, $matches)) {
			return $class;
		}
		
		return strtolower($matches[1]);
	}
	
}
	