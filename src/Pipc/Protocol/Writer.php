<?php

namespace Pipc\Protocol;
use Pipc\Protocol\Messages;
use Pipc\Protocol\Serializer;

/**
 * Pipc\Protocol\Writer
 * 
 * @author Martin Kuckert
 * @copyright Copyright &copy; 2010 Martin Kuckert
 * @license New BSD License
 * @package Pipc\Protocol
 * @since 27.03.2010
 */
class Writer {
	
	/**
	 * @var integer Pre-packed marker for the beginning of a message
	 */
	private $_marker;
	
	/**
	 * @var integer The current message sequence number
	 */
	private $_seq=0;
	
	/**
	 * @var resource Data stream to use for writing protocol messages
	 */
	private $_stream=NULL;
	
	/**
	 * @var Pipc\Protocol\Serializer\ISerializer The serializer instance to use
	 */
	private $_serializer=NULL;
	
	/**
	 * Constructor
	 * 
	 * @param resource Data stream to use for writing protocol messages
	 * @param Pipc\Protocol\Serializer\ISerializer
	 */
	public function __construct($stream, Serializer\ISerializer $serializer=NULL) {
		$this->_marker=pack('N', \Pipc\Protocol::BEGIN_MARKER);
		$this->_stream=$stream;
		
		if($serializer===NULL) {
			$serializer=new Serializer\JsonSerializer();
		}
		$this->setSerializer($serializer);
	}
	
	/**
	 * Returns the serializer instance to use
	 * 
	 * @return Pipc\Protocol\Serializer\ISerializer
	 */
	public function getSerializer() {
		return $this->_serializer;
	}
	
	/**
	 * Sets the serializer instance to use
	 * 
	 * @return Pipc\Protocol\Writer
	 * @param Pipc\Protocol\Serializer\ISerializer
	 */
	public function setSerializer(Serializer\ISerializer $serializer) {
		$this->_serializer=$serializer;
		return $this;
	}
	
	/**
	 * Returns the current message sequence number
	 * 
	 * @return integer
	 */
	public function getSeq() {
		return $this->_seq;
	}
	
	/**
	 * Writes the given message into the data stream.
	 * 
	 * @throws Pipc\Protocol\Exception
	 * @return Pipc\Protocol\Writer
	 * @param Pipc\Protocol\IMessage
	 */
	public function write(IMessage $message) {
		// This is an initial message
		if($message->getType()===MessageRegistry::INITIAL) {
			$data=$this->serializeInitialMessage($message);
		}
		else {
			$data=$this->_serializer->serialize($message);
		}
		
		$len=strlen($data);
		
		$this->writeHeader($len, $message->getType());
		
		if(fwrite($this->_stream, $data)!==$len) {
			throw new Exception('Failed to correctly write the message data');
		}
		
		return $this;
	}
	
	/**
	 * Writes the initial message into the data stream.
	 * 
	 * @throws Pipc\Protocol\Exception
	 * @return Pipc\Protocol\Writer
	 */
	public function writeInitialMessage() {
		return $this->write(
			new Messages\InitialMessage(
				Serializer\Factory::getDefaultInstance()
					->getName($this->_serializer)
			)
		);
	}
	
	/**
	 * Returns the message header for the given data length and message type.
	 * 
	 * @throws Pipc\Protocol\Exception
	 * @return void
	 * @param integer Message data len
	 * @param integer Message type
	 */
	protected function writeHeader($len, $type) {
		if(
			fwrite($this->_stream, 
				$this->_marker.
				pack('n', $this->_seq++).
				pack('n', $type).
				pack('N', $len)
			)
			!==\Pipc\Protocol::HEADER_LEN
		) {
			throw new Exception('Failed to correctly write the message header');
		}
	}
	
	/**
	 * Serializes an initial message
	 * 
	 * @return string
	 * @param Pipc\Protocol\Messages\InitialMessage
	 */
	protected function serializeInitialMessage(Messages\InitialMessage $message) {
		$data=$message->getData();
		return $data['serializer'].'|'.$data['timestamp'];
	}
	
}
