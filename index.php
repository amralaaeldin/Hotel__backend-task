<?php
require_once './vendor/autoload.php';
require_once './src/Suppliers.php';

$serveAPI = new App\HandlingResources();


if (isset($_GET['format'])) {
  $instance = new App\FormatFactory();
  printf($instance->create($serveAPI->getData($suppliers)));
}
