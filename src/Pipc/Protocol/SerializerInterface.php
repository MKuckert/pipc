<?php

/**
 * Pipc_Protocol_SerializerInterface
 * 
 * Provides methods to serialize and unserialize message data from and
 * into a specific format
 * 
 * @author Martin Kuckert
 * @copyright Copyright &copy; 2010 Martin Kuckert
 * @license New BSD License
 * @package Pipc.Protocol
 * @since 28.03.2010
 */
interface Pipc_Protocol_SerializerInterface {
	
	/**
	 * Serializes the given message
	 * 
	 * @throws Pipc_Protocol_Serializer_Exception
	 * @return string
	 * @param Pipc_Protocol_MessageInterface
	 */
	public function serialize(Pipc_Protocol_MessageInterface $message);
	
	/**
	 * Unserializes the given message data
	 * 
	 * @throws Pipc_Protocol_Serializer_Exception
	 * @return mixed
	 * @param string
	 * @param integer Type of the message data
	 */
	public function unserialize($data, $type);
	
}
	