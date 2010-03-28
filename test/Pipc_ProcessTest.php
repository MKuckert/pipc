<?php

class Pipc_ProcessTest extends PHPUnit_Framework_TestCase {
	
	// Path to php process
	private $php='"E:\\dev\\Zend Server\\ZendServer\\bin\\php.exe"';
	
	private $worker;
	private $workerTimer;
	
	private $cmd;
	
	protected function setUp() {
		$this->worker=__DIR__.'/worker.php';
		$this->workerTimer=__DIR__.'/worker.txt';
		file_put_contents($this->worker, '<?php echo "foo"; echo stream_get_contents(STDIN, 3); file_put_contents("'.addslashes($this->workerTimer).'", "<?php return ".microtime(true).";");');
		
		@unlink($this->workerTimer);
		
		$this->cmd=$this->php.' '.$this->worker;
	}
	
	public function testOpen() {
		$proc=new Pipc\Process($this->cmd);
		
		$this->assertTrue($proc instanceof Pipc\Process);
	}
	
	public function testClose() {
		$start=microtime(true);
		
		$proc=new Pipc\Process($this->cmd);
		$proc->close();
		
		$this->assertFileExists($this->workerTimer);
		$w=include $this->workerTimer;
		
		$this->assertGreaterThan($start, $w);
	}
	
	public function testReadPipe() {
		$proc=new Pipc\Process($this->cmd);
		$this->assertEquals(stream_get_contents($proc->getPipe(1), 3), 'foo');
		$proc->close();
	}
	
	public function testWriteToPipe() {
		$proc=new Pipc\Process($this->cmd);
		$this->assertEquals(stream_get_contents($proc->getPipe(1), 3), 'foo');
		
		fwrite($proc->getPipe(0), 'bar');
		$this->assertEquals(stream_get_contents($proc->getPipe(1), 3), 'bar');
		
		$proc->close();
	}
	
}
