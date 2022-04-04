<?php
require_once './vendor/autoload.php';
require_once './src/Suppliers.php';

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: *");

$serveAPI = new App\HandlingResources();


if (isset($_GET['format'])) {
  $instance = new App\Format\FormatFactory();
  printf($instance->create($serveAPI->getData($suppliers)));
}
