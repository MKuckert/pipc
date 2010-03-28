<?php

use Pipc\Protocol;
use Pipc\Protocol\Messages;

class Pipc_MethodInvocationRequestTest extends PHPUnit_Framework_TestCase {
	
	// Path to php process
	private $php='E:\\dev\\Zend Server\\ZendServer\\bin-php5.3\\php.exe';
	private $worker;
	
	protected function setUp() {
		$this->worker=__DIR__.'/methodInvocationRequestWorker.php';
	}
	
	public function testInvokeMethod() {
		$proc=new Pipc\PhpProcess($this->php, $this->worker);
		$writer=new Protocol\Writer($proc->getPipe(0));
		$reader=new Protocol\Reader($proc->getPipe(1));
		
		$writer
			->writeInitialMessage()
			->write(new Messages\MethodInvocationRequestMessage('class', 'method', array('args')));
		
		sleep(1);
		
		$message=$reader->read();
		var_dump($message);
		
		$proc->close();
	}
	
}
