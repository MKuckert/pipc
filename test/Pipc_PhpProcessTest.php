<?php

class Pipc_PhpProcessTest extends PHPUnit_Framework_TestCase {
	
	// Path to php process
	private $php='E:\\dev\\Zend Server\\ZendServer\\bin\\php.exe';
	
	private $worker;
	private $workerTimer;
	
	private $cmd;
	
	protected function setUp() {
		$this->worker=__DIR__.'/worker.php';
		$this->workerTimer=__DIR__.'/worker.txt';
		file_put_contents($this->worker, '<?php echo "foo"; echo stream_get_contents(STDIN, 3); file_put_contents("'.addslashes($this->workerTimer).'", "<?php return ".microtime(true).";");');
		
		@unlink($this->workerTimer);
	}
	
	public function testOpen() {
		$proc=new Pipc\PhpProcess($this->php, $this->worker);
		$proc->close();
		
		$this->assertFileExists($this->workerTimer);
	}
	
}
