<?php

namespace Pipc;

/**
 * Pipc\Protocol
 * 
 * Encapsulates some protocol constants
 * 
 * @author Martin Kuckert
 * @copyright Copyright &copy; 2010 Martin Kuckert
 * @license New BSD License
 * @package Pipc
 * @since 27.03.2010
 */
interface Protocol {
	
	/**
	 * @desc Marker for the beginning of a message
	 */
	const BEGIN_MARKER=0x50495043;
	
	/**
	 * @desc Length of a message header
	 */
	const HEADER_LEN=12;
	
}
