<?php
use System\Http\Request;
use System\Http\Response;
use System\Http\Kernel;

define('BASE_PATH', dirname(__DIR__));

require_once BASE_PATH . '/vendor/autoload.php';

$request = Request::create();

$kernel = new Kernel();

$response = $kernel->handle($request);

$response->send();


