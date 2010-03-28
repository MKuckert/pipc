<?php

use Pipc\Protocol\Serializer as s;

class Pipc_SerializerFactory extends PHPUnit_Framework_TestCase {
	
	public function testGetDefaultInstance() {
		$f=s\Factory::getDefaultInstance();
		$this->assertTrue($f instanceof s\Factory);
	}
	
	public function testCreateSerializer() {
		$f=s\Factory::getDefaultInstance();
		$s=$f->createSerializer('json');
		
		$this->assertTrue($s instanceof s\JsonSerializer);
	}
	
	public function testGetName() {
		$s=new s\JsonSerializer();
		
		$this->assertEquals(
			s\Factory::getDefaultInstance()
				->getName($s),
			'json'
		);
	}
	
}
