<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: *");

require_once('./suppliers.php');
$init_data = [];
$data = [];

function replace_key($arr, $oldkey, $newkey)
{
  $keys = array_keys($arr);
  if (array_key_exists($oldkey, $arr)) {
    $keys[array_search($oldkey, $keys)] = $newkey;
  }
  return $arr = array_combine($keys, $arr);
}

function grouping($suppliers, $init_arr)
{
  foreach ($suppliers as $supplier) {
    $init_arr = [...$init_arr, ...json_decode(file_get_contents($supplier))];
  }
  return $init_arr;
}

function formatting($init_arr, $arr)
{
  foreach ($init_arr as $nested) {
    $nested = (array) $nested;
    if (strpos($nested['Rate'], '*') === 0) {
      $nested['Rate'] = strlen($nested['Rate']);
    }
    $nested = replace_key($nested, 'Fare', 'Price');
    $nested = replace_key($nested, 'Hotel', 'hotelName');
    $nested = replace_key($nested, 'amenities', 'roomAmenities');
    $arr[] = $nested;
  }
  return $arr;
}

$init_data = grouping($suppliers, $init_data);

$data = formatting($init_data, $data);

$count = count($data);
for ($i = 0; $i < $count; $i++) {
  for ($j = $i + 1; $j < $count; $j++) {
    if ($data[$i]['Rate'] < $data[$j]['Rate']) {
      $temp = $data[$i];
      $data[$i] = $data[$j];
      $data[$j] = $temp;
    }
  }
}

$json_data = json_encode($data);

print_r($json_data);
