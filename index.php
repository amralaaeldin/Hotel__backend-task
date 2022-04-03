<?php
require_once './vendor/autoload.php';
require_once './src/Suppliers.php';

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: *");



$serveAPI = new App\HandlingResources();

$serveAPI->data = $serveAPI->collect($suppliers);
$serveAPI->data = $serveAPI->format($serveAPI->init_arr, $serveAPI->data);
$serveAPI->data = $serveAPI->sort($serveAPI->data);

if (isset($_GET['format'])) {
  $instance = new App\Format\FormatFactory();
  printf($instance->create($serveAPI->data));
}
