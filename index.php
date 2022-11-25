<?php

use function PHPSTORM_META\type;

require_once './vendor/autoload.php';
require_once './src/Suppliers.php';
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");

$serveAPI = new App\HandlingResources();
$results = new App\GettingResults();

// json as default value
if (isset($_GET['format']) || $_GET['format'] = "json") {
    $instance = new App\FormatFactory();
    printf($instance->create($results->getResults($serveAPI->getData($suppliers))));
}
