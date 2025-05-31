<?php
session_start();

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");

define('BASE_PATH', dirname(__DIR__));

// 1. D'abord charger l'autoloader
require_once BASE_PATH . '/vendor/autoload.php';

// 2. Ensuite charger les variables d'environnement
use Dotenv\Dotenv;
$dotenv = Dotenv::createImmutable(BASE_PATH); // Utiliser BASE_PATH au lieu de __DIR__
$dotenv->load();

// 3. Maintenant on peut utiliser les autres classes
use System\Http\Request;
use System\Http\Kernel;
use System\Database\Database;

$request = Request::create();

//$pdo = Database::getConnection();

$kernel = new Kernel();

$response = $kernel->handle($request);

$response->send();