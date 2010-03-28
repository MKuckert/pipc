<?php

set_include_path(realpath(dirname(__DIR__).'/src').PATH_SEPARATOR.get_include_path());

function pipc_test_autoload($class) {
	if(substr($class, 0, 4)==='Pipc' or substr($class, 0, 4)==='Zend') {
		require_once str_replace(array('\\', '_'), DIRECTORY_SEPARATOR, $class).'.php';
	}
}
spl_autoload_register('pipc_test_autoload');