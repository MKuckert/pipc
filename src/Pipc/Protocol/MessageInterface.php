<?php

namespace Pipc\Protocol;

/**
 * Pipc\Protocol\MessageInterface
 * 
 * @author Martin Kuckert
 * @copyright Copyright &copy; 2010 Martin Kuckert
 * @license New BSD License
 * @package Pipc\Protocol
 * @since 27.03.2010
 */
interface MessageInterface {
	
	/**
	 * Returns the type of this message
	 * 
	 * @return integer
	 */
	public function getType();
	
	/**
	 * Returns the data of this message
	 * 
	 * @return mixed
	 */
	public function getData();
	
	/**
	 * Creates an instance of this message with the given data.
	 * 
	 * @throws Pipc\Protocol\Exception
	 * @param mixed
	 */
	public function __construct($data);
	
}