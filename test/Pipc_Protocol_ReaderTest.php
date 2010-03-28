<?php

use Pipc\Protocol;

class Pipc_Protocol_ReaderTest extends PHPUnit_Framework_TestCase {
	
	private $file;
	private $handle;
	
	/**
	 * @var Pipc\Protocol\Reader
	 */
	private $reader;
	
	protected function setUp() {
		$this->file=__DIR__.'/protocol.log';
		$data='{"timestamp":1269723432.6003}';
		file_put_contents($this->file,
			'PIPC'.
			str_repeat("\0", 4).
			pack('N', strlen($data)).
			$data
		);
		$this->handle=fopen($this->file, 'r');
	}
	
	public function testCreate() {
		$this->reader=new Protocol\Reader($this->handle);
		$this->assertTrue($this->reader instanceof Protocol\Reader);
	}
	
	public function testReadInitialMessage() {
		$this->testCreate();
		
		$message=$this->reader->read();
		$this->assertTrue($message instanceof Protocol\Messages\InitialMessage);
	}
	
}
