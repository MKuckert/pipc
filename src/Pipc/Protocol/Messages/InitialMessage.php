<?php

/**
 * Pipc_Protocol_Messages_InitialMessage
 * 
 * Message send as the first message in a PIPC communication session
 * 
 * @author Martin Kuckert
 * @copyright Copyright &copy; 2010 Martin Kuckert
 * @license New BSD License
 * @package Pipc.Protocol.Messages
 * @since 27.03.2010
 */
class Pipc_Protocol_Messages_InitialMessage extends Pipc_Protocol_Messages_AbstractMessage {
	
	/**
	 * Returns the type of this message
	 * 
	 * @return integer
	 */
	public function getType() {
		return Pipc_Protocol_MessageRegistry::INITIAL;
	}
	
	/**
	 * Creates an instance of this message with the given data.
	 * 
	 * @param array|string Type of serializer format used
	 */
	public function __construct($serializerFormat='json') {
		if(is_array($serializerFormat)) {
			$this->_data=$serializerFormat;
			return;
		}
		
		$this->_data=array(
			'serializer' => $serializerFormat,
		 	'timestamp' => microtime(true)
		);
	}
	
}
