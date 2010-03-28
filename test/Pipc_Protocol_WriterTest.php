<?php

use Pipc\Protocol;
use Pipc\Protocol\Messages as m;

class Pipc_Protocol_WriterTest extends PHPUnit_Framework_TestCase {
	
	private $file;
	private $handle;
	
	/**
	 * @var Pipc\Protocol\Writer
	 */
	private $writer;
	
	protected function setUp() {
		$this->file=__DIR__.'/protocol.log';
		$this->handle=fopen($this->file, 'w');
	}
	
	protected function tearDown() {
		fclose($this->handle);
	}
	
	public function testCreate() {
		$this->writer=new Protocol\Writer($this->handle);
		
		$this->assertTrue($this->writer instanceof Protocol\Writer);
	}
	
	public function testWriteInitialMessage() {
		$this->testCreate();
		
		$this->writer->write(new m\InitialMessage());
		
		$data=file_get_contents($this->file);
		$this->assertEquals('PIPC', substr($data, 0, 4));
		
		// Seq=0
		$this->assertEquals("\0\0", substr($data, 4, 2));
		
		// Type=0
		$this->assertEquals("\0\0", substr($data, 6, 2));
		
		// DataLen
		$len=unpack('N', substr($data, 8, 4));
		$len=$len[1];
		
		$this->assertGreaterThan(0, $len);
		$this->assertEquals(strlen($data)-12, $len);
	}
	
}
