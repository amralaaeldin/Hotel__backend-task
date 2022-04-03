<?php
require_once './vendor/autoload.php';

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: *");



$serveAPI = new App\HandlingResources();
$suppliers = new App\Suppliers(['https://bit.ly/3GlDsSw', 'https://bit.ly/3Gr5T1t']);


foreach ($suppliers->suppliers as $supplier) {
  $serveAPI->init_arr = [...$serveAPI->init_arr, ...json_decode(file_get_contents($supplier))];
}

$serveAPI->data = $serveAPI->format($serveAPI->init_arr, $serveAPI->data);
$serveAPI->data = $serveAPI->sort($serveAPI->data);

if (isset($_GET['format'])) {
  $instance = new FormatFactory();
  printf($instance->create($serveAPI->data));
}
