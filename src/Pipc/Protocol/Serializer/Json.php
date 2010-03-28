<?php

/**
 * Pipc_Protocol_Serializer_Json
 * 
 * Provides methods to serialize and unserialize message data from and
 * into the json format
 * 
 * @author Martin Kuckert
 * @copyright Copyright &copy; 2010 Martin Kuckert
 * @license New BSD License
 * @package Pipc.Protocol.Serializer
 * @since 28.03.2010
 */
class Pipc_Protocol_Serializer_Json implements Pipc_Protocol_Serializer_SerializerInterface {
	
	/**
	 * Serializes the given message
	 * 
	 * @throws Pipc_Protocol_Serializer_Exception
	 * @return string
	 * @param Pipc_Protocol_MessageInterface
	 */
	public function serialize(Pipc_Protocol_MessageInterface $message) {
		try {
			return Zend_Json::encode($message->getData(), false, array(
				'enableJsonExprFinder' => true
			));
		}
		catch(Zend_Json_Exception $ex) {
			throw new Exception('Failed to serialize the given message');
		}
	}
	
	/**
	 * Unserializes the given message data
	 * 
	 * @throws Pipc_Protocol_Serializer_Exception
	 * @return mixed
	 * @param string
	 * @param integer Type of the message data
	 */
	public function unserialize($data, $type) {
		try {
			return Zend_Json::decode($data);
		}
		catch(Zend_Json_Exception $ex) {
			throw new Exception('Failed to unserialize the given message data');
		}
	}
	
}
	