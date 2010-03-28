<?php

namespace Pipc\Protocol;
use Pipc\Protocol\Serializer;

/**
 * Pipc\Protocol\Reader
 * 
 * @author Martin Kuckert
 * @copyright Copyright &copy; 2010 Martin Kuckert
 * @license New BSD License
 * @package Pipc\Protocol
 * @since 27.03.2010
 */
class Reader {
	
	/**
	 * @var integer Pre-packed marker for the beginning of a message
	 */
	private $_marker;
	
	/**
	 * @var integer The sequence number of the next message to read
	 */
	private $_seq=0;
	
	/**
	 * @var resource Data stream to use for reading protocol messages
	 */
	private $_stream=NULL;
	
	/**
	 * @var Pipc\Protocol\MessageRegistry The message registry instance to use
	 */
	private $_registry=NULL;
	
	/**
	 * @var Pipc\Protocol\Serializer\ISerializer The serializer instance to use
	 */
	private $_serializer=NULL;
	
	/**
	 * Constructor
	 * 
	 * @param resource Data stream to use for reading protocol messages
	 */
	public function __construct($stream) {
		$this->_marker=pack('N', \Pipc\Protocol::BEGIN_MARKER);
		$this->_stream=$stream;
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
	 * The message registry instance to use
	 * 
	 * @return Pipc\Protocol\MessageRegistry
	 */
	public function getRegistry() {
		if($this->_registry===NULL) {
			$this->_registry=new MessageRegistry();
		}
		return $this->_registry;
	}
	
	/**
	 * Sets the message registry instance to use
	 * 
	 * @return Pipc\Protocol\Reader
	 * @param Pipc\Protocol\MessageRegistry
	 */
	public function setRegistry(MessageRegistry $registry) {
		$this->_registry=$registry;
		return $this;
	}
	
	/**
	 * Trys to read a message from the data stream.
	 * 
	 * @throws Pipc\Protocol\Exception
	 * @return Pipc\Protocol\IMessage
	 */
	public function read() {
		$meta=stream_get_meta_data($this->_stream);
		if($meta['unread_bytes']>0 and $meta['unread_bytes']<=\Pipc\Protocol::HEADER_LEN) {
			throw new Exception('The underlying stream contains not enough bytes to read a full header');
		}
		
		list($type, $len)=$this->readHeader();
		
		// Read message data
		$data=stream_get_contents($this->_stream, $len);
		if(strlen($data)<$len) {
			throw new Exception('Failed to read message data. Only '.strlen($data).' of '.$len.' bytes have been read');
		}
		
		// This is an initial message
		if($type===MessageRegistry::INITIAL) {
			$data=$this->unserializeInitialMessage($data);
		}
		else {
			$data=$this->_serializer->unserialize($data);
		}
		
		// Create message
		return $this->getRegistry()
			->createMessage($type, $data);
	}
	
	/**
	 * Reads the message header out of the underlying stream.
	 * 
	 * @throws Pipc\Protocol\Exception
	 * @return array( TYPE, LEN )
	 */
	protected function readHeader() {
		$header=stream_get_contents($this->_stream, \Pipc\Protocol::HEADER_LEN);
		
		// Check header marker
		if(substr($header, 0, 4)!==$this->_marker) {
			throw new Exception('Invalid message beginning marker detected: '.$header);
		}
		
		$meta=unpack('nseq/ntype/Nlen', substr($header, 4));
		
		// Check sequence number
		if($meta['seq']<$this->_seq) {
			throw new Exception('Invalid message sequence number '.$meta['seq'].' in header '.$header.' detected');
		}
		$this->_seq=$meta['seq']+1;
		
		return array($meta['type'], $meta['len']);
	}
	
	/**
	 * Unserializes an initial message
	 * 
	 * @throws Pipc\Protocol\Exception
	 * @return mixed
	 * @param string
	 */
	protected function unserializeInitialMessage($data) {
		$data=explode('|', $data);
		if(count($data)!==2) {
			throw new Exception('Invalid initial message data detected: '.implode('|', $data));
		}
		
		$this->_serializer=Serializer\Factory::getDefaultInstance()
			->createSerializer($data[0]);
		
		return array(
			'serializer'	=> $data[0],
			'timestamp'		=> (float)$data[1]
		);
	}
	
}
