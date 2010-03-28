<?php

namespace Pipc;

/**
 * Pipc\PhpProcess
 * 
 * Encapsulates a php process
 *
 * @author Martin Kuckert
 * @copyright Copyright &copy; 2010 Martin Kuckert
 * @license New BSD License
 * @package Pipc
 * @since 28.03.2010
 */
class PhpProcess extends Process {
	
	/**
	 * Constructor
	 * 
	 * @throws Pipc\Process\Exception
	 * @param string Path to the php executable
	 * @param string Path to the php script to execute
	 * @param array List of script arguments
	 * @param array Descriptor specifications
	 * @param string Working directory for the child process
	 * @param array Environment options for the child process
	 * @param array Other options for the child process
	 */
	public function __construct($phpBin, $scriptPath, array $args=array(), array $descriptorSpec=NULL, $cwd=NULL, $env=NULL, array $options=array()) {
		array_unshift($args, $phpBin, $scriptPath);
		$args=array_map('escapeshellarg', $args);
		$cmd=implode(' ', $args);
		
		parent::__construct($cmd, $descriptorSpec, $cwd, $env, $options);
	}
	
}
