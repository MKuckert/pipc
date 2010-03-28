<?php

namespace Pipc\Protocol;

/**
 * Pipc\Protocol\MessageRegistry
 * 
 * Registry for message types
 * 
 * @author Martin Kuckert
 * @copyright Copyright &copy; 2010 Martin Kuckert
 * @license New BSD License
 * @package Pipc\Protocol
 * @since 27.03.2010
 */
class MessageRegistry {
	
	const INITIAL=0;
	const METHOD_INVOCATION_REQUEST=32;
	const METHOD_INVOCATION_RETURN=48;
	const METHOD_INVOCATION_EXCEPTION=49;
	const METHOD_INVOCATION_ERROR=50;
	
	/**
	 * @var array Default mapping of message types to class names
	 */
	static private $_defaultMapping=array(
		self::INITIAL						=> 'InitialMessage',
		self::METHOD_INVOCATION_REQUEST		=> 'MethodInvocationRequestMessage'
	);
	
	/**
	 * @var array Mapping of message types to class names
	 */
	private $_mapping=array();
	
	/**
	 * Constructor
	 */
	public function __construct() {
		$this->_mapping=self::$_defaultMapping;
	}
	
	/**
	 * Registers a new message type at this registry
	 * 
	 * @return Pipc\Protocol\MessageRegistry
	 * @param integer Message type
	 * @param string Message class
	 */
	public function registerMessageType($type, $class) {
		if(!class_exists($class)) {
			throw new Exception('The class '.$class.' does not exist');
		}
		$this->_mapping[$type]=$class;
		return $this;
	}
	
	/**
	 * Unregisters a previously registered message type from
	 * this registry
	 * 
	 * @return Pipc\Protocol\MessageRegistry
	 * @param mixed Message type or class
	 */
	public function unregisterMessageType($typeOrClass) {
		foreach($this->_mapping as $type=>$class) {
			if($type===$typeOrClass or $class===$typeOrClass) {
				unset($this->_mapping[$type]);
				break;
			}
		}
		
		return $this;
	}
	
	/**
	 * Creates a message for the given type
	 * 
	 * @return Pipc\Protocol\IMessage
	 * @param integer Message type
	 * @param mixed Data for the message
	 */
	public function createMessage($type, $data) {
		if(isset(self::$_mapping[$type])) {
			$class='Pipc\Protocol\Messages\\'.self::$_mapping[$type];
		}
		else {
			$class='Pipc\Protocol\Messages\UnknownMessage';
		}
		
		return new $class($data);
	}
	
}
