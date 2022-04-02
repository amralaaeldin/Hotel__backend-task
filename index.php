<?php
require_once('./suppliers.php');
require_once('./HandlingResources.php');
require_once('./FormatData.php');

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: *");



$serveAPI = new HandlingResources();

foreach ($suppliers as $supplier) {
  $serveAPI->init_arr = [...$serveAPI->init_arr, ...json_decode(file_get_contents($supplier))];
}

$serveAPI->data = $serveAPI->formatting($serveAPI->init_arr, $serveAPI->data);
$serveAPI->data = $serveAPI->sorting($serveAPI->data);

if (isset($_GET['format'])) {
  $getFormat = ucfirst($_GET['format']);
  $handleFormat = new $getFormat($serveAPI->data);
  $serveAPI->data = $handleFormat->formatData();

  printf($serveAPI->data);
}
