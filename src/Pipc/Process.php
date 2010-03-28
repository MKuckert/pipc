<?php

namespace Pipc;

/**
 * Pipc\Process
 * 
 * Encapsulates the proc_* functions to spawn and control a child process
 *
 * @author Martin Kuckert
 * @copyright Copyright &copy; 2010 Martin Kuckert
 * @license New BSD License
 * @package Pipc
 * @since 27.03.2010
 */
class Process {
	
	/**
	 * @var resource The opened process
	 */
	private $_handle=NULL;
	
	/**
	 * @var array Pipes of the opened process
	 */
	private $_pipes=array();
	
	/**
	 * @var integer Exitcode of the opened process
	 */
	private $_exitcode=-1;
	
	/**
	 * @var array Default descriptor specifications
	 */
	static private $_defaultDescriptorSpec=array(
		array('pipe', 'r'),
		array('pipe', 'w'),
		array('pipe', 'w')
	);
	
	/**
	 * @var array Default options for the child process
	 */
	static private $_defaultOptions=array(
		'suppress_errors'	=> true,
		'bypass_shell'		=> true
	);
	
	/**
	 * Constructor
	 * 
	 * @throws Pipc\Process\Exception
	 * @param string Command to execute
	 * @param array Descriptor specifications
	 * @param string Working directory for the child process
	 * @param array Environment options for the child process
	 * @param array Other options for the child process
	 */
	public function __construct($cmd, array $descriptorSpec=NULL, $cwd=NULL, $env=NULL, array $options=array()) {
		if($descriptorSpec===NULL) {
			$descriptorSpec=self::$_defaultDescriptorSpec;
		}
		$options=array_merge(self::$_defaultOptions, $options);
		
		$this->_handle=proc_open($cmd, $descriptorSpec, $this->_pipes, $cwd, $env, $options);
		if(!is_resource($this->_handle)) {
			throw new Pipc\Process\Exception('Failed to open a process for the command '.$cmd);
		}
	}
	
	/**
	 * Returns the underlying process handle.
	 * 
	 * @return resource
	 */
	public function getProcHandle() {
		return $this->_handle;
	}
	
	/**
	 * Waits for this process to terminate and closes the underlying handle.
	 * 
	 * Note: Every opened pipe will be closed automatically.
	 * 
	 * @return integer Exitcode of the process
	 */
	public function close() {
		foreach($this->_pipes as $pipe) {
			// We have to read all data out of the pipe, so the child process may run correctly
			$meta=stream_get_meta_data($pipe);
			if($meta['mode']==='r') {
				stream_get_contents($pipe);
			}
			fclose($pipe);
		}
		
		$this->_exitcode=proc_close($this->_handle);
		return $this->_exitcode;
	}
	
	/**
	 * Returns informations about this process.
	 * 
	 * @throws Pipc\Process\Exception
	 * @return mixed
	 * @param string Status to return instead of the whole array
	 */
	public function getStatus($item=NULL) {
		$status=proc_get_status($this->_handle);
		
		if($item===NULL) {
			return $status;
		}
		else {
			return $status[$item];
		}
	}
	
	/**
	 * Returns the id of this process.
	 * 
	 * @return integer
	 */
	public function getProcessID() {
		return $this->getStatus('pid');
	}
	
	/**
	 * Checks if this process is still running.
	 * 
	 * @return boolean
	 */
	public function isRunning() {
		return $this->getStatus('running');
	}
	
	/**
	 * Returns the exitcode of this process.
	 * 
	 * @return integer
	 */
	public function getExitCode() {
		if($this->_exitcode===-1 and !$this->isRunning()) {
			$this->_exitcode=$this->getStatus('exitcode');
		}
		return $this->_exitcode;
	}
	
	/**
	 * Returns the pipe for the given index.
	 * 
	 * @return resource
	 * @param integer
	 */
	public function getPipe($index) {
		return $this->_pipes[$index];
	}
	
	/**
	 * Returns all opened pipes.
	 * 
	 * @return array
	 */
	public function getPipes() {
		return $this->_pipes;
	}
	
}
