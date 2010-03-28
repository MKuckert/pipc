<?php

use Pipc\Protocol;
use Pipc\Protocol\Messages;

require_once __DIR__.'/bootstrap.php';

$reader=new Protocol\Reader(STDIN);
$writer=new Protocol\Writer(STDOUT);
$message=$reader->read();

$writer->writeInitialMessage();

