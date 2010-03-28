<?php

/**
 * Pipc_Protocol_Messages_AbstractMessage
 * 
 * Template for a message
 * 
 * @author Martin Kuckert
 * @copyright Copyright &copy; 2010 Martin Kuckert
 * @license New BSD License
 * @package Pipc_Protocol_Messages
 * @since 27.03.2010
 */
abstract class Pipc_Protocol_Messages_AbstractMessage implements Pipc_Protocol_MessageInterface {
	
	/**
	 * @var mixed Data of this message
	 */
	protected $_data;
	
	/**
	 * Returns the data of this message
	 * 
	 * @return mixed
	 */
	public function getData() {
		return $this->_data;
	}
	
	/**
	 * Creates an instance of this message with the given data.
	 * 
	 * @throws Pipc_Protocol_Exception
	 * @param mixed
	 */
	public function __construct($data) {
		$this->_data=$data;
	}
	
}
