<?php
use System\Http\Request;
use System\Http\Kernel;
use System\Database\Database;

define('BASE_PATH', dirname(__DIR__));

require_once BASE_PATH . '/vendor/autoload.php';

$request = Request::create();

//$pdo = Database::getConnection();

$kernel = new Kernel();

$response = $kernel->handle($request);

$response->send();


