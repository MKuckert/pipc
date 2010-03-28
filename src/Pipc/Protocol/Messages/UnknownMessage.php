<?php

/**
 * Pipc_Protocol_Messages_UnknownMessage
 * 
 * Message used by the MessageRegistry if an unknown message type was given.
 * 
 * @author Martin Kuckert
 * @copyright Copyright &copy; 2010 Martin Kuckert
 * @license New BSD License
 * @package Pipc_Protocol_Messages
 * @since 27.03.2010
 */
class Pipc_Protocol_Messages_UnknownMessage extends Pipc_Protocol_Messages_AbstractMessage {
	
	/**
	 * Returns the type of this message
	 * 
	 * @throws Pipc_Protocol_Exception
	 * @return integer
	 */
	public function getType() {
		throw new Pipc_Protocol_Exception('The type of this message is unknown');
	}
	
}
