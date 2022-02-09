<?php

$init_data = [];
$data = [];

function replace_key($arr, $oldkey, $newkey, $oldkey2, $newkey2, $oldkey3, $newkey3)
{
  $keys = array_keys($arr);
  if (array_key_exists($oldkey, $arr)) {
    $keys = [...$keys];
    $keys[array_search($oldkey, $keys)] = $newkey;
  }
  if (array_key_exists($oldkey2, $arr)) {
    $keys = [...$keys];
    $keys[array_search($oldkey2, $keys)] = $newkey2;
  }
  if (array_key_exists($oldkey3, $arr)) {
    $keys = [...$keys];
    $keys[array_search($oldkey3, $keys)] = $newkey3;
  }
  return $arr = array_combine($keys, $arr);
}

function myfunction($v)
{
  if (gettype($v) === 'string') {
    return (strlen($v));
  }
}

$supplier1 = json_decode(file_get_contents('https://bit.ly/3GlDsSw'));
$supplier2 = json_decode(file_get_contents('https://bit.ly/3Gr5T1t'));
$init_data = array_merge($supplier1, $supplier2);

foreach ($init_data as $nested) {
  $nested = (array) $nested;
  if (strpos($nested['Rate'], '*') === 0) {
    $nested['Rate'] = strlen($nested['Rate']);
  }
  global $data;
  $data = [...$data, replace_key($nested, 'Hotel', 'hotelName', 'Fare', 'Price', 'amenities', 'roomAmenities')];
}

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



header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: *");
header("Access-Control-Allow-Headers: *");
print_r($json_data);

