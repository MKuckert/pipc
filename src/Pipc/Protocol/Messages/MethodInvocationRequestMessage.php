<?php

/**
 * Pipc_Protocol_Messages_MethodInvocationRequestMessage
 * 
 * Represents a message to execute a class method
 * 
 * @author Martin Kuckert
 * @copyright Copyright &copy; 2010 Martin Kuckert
 * @license New BSD License
 * @package Pipc.Protocol.Messages
 * @since 28.03.2010
 */
class Pipc_Protocol_Messages_MethodInvocationRequestMessage extends Pipc_Protocol_Messages_AbstractMessage {
	
	/**
	 * Returns the type of this message
	 * 
	 * @return integer
	 */
	public function getType() {
		return Pipc_Protocol_MessageRegistry::METHOD_INVOCATION_REQUEST;
	}
	
	/**
	 * Creates an instance of this message with the given data.
	 * 
	 * @throws Pipc_Protocol_Exception
	 * @param mixed
	 */
	public function __construct($class, $method='', array $args=array()) {
		if(is_array($class)) {
			foreach(array('class', 'method', 'args') as $req) {
				if(!isset($class[$req])) {
					throw new Pipc_Protocol_Exception('Missing required argument '.$req);
				}
			}
			
			$method=$class['method'];
			$args=(array)$class['args'];
			$class=$class['class'];
		}
		
		$this->_data=array(
			'class' => $class,
			'method' => $method,
			'args' => $args
		);
	}
	
}
