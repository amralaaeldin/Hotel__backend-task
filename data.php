<?php
require_once('./suppliers.php');


class HandlingResources
{
  private $init_arr = [];
  static $data = [];

  function __construct($suppliers)
  {
    foreach ($suppliers as $supplier) {
      $this->init_arr = [...$this->init_arr, ...json_decode(file_get_contents($supplier))];
    }

    HandlingResources::$data = $this->formatting($this->init_arr, HandlingResources::$data);
    HandlingResources::$data = $this->sorting(HandlingResources::$data);

    if (isset($_GET['format'])) {
      $serveAPI = new Serve($_GET['format']);
    }
  }

  private function replace_key($arr, $oldkey, $newkey)
  {
    $keys = array_keys((array) $arr);
    if (array_key_exists($oldkey, (array) $arr)) {
      $keys[array_search($oldkey, $keys)] = $newkey;
    }
    return $arr = array_combine($keys, (array) $arr);
  }

  private function formatting($init_arr, $arr)
  {
    foreach ($init_arr as $nested) {

      $nested = (array) $nested;
      if (strpos($nested['Rate'], '*') === 0) {
        $nested['Rate'] = strlen($nested['Rate']);
      }
      $nested = $this->replace_key($nested, 'Fare', 'Price');
      $nested = $this->replace_key($nested, 'Hotel', 'hotelName');
      $nested = $this->replace_key($nested, 'amenities', 'roomAmenities');
      $arr[] = $nested;
    }
    return $arr;
  }

  static function array_to_xml($data, $xml_data)
  {
    foreach ($data as $key => $value) {
      if (is_array($value)) {
        if (is_numeric($key)) {
          $key = 'item' . $key;
        }
        $subnode = $xml_data->addChild($key);
        HandlingResources::array_to_xml($value, $subnode);
      } else {
        $xml_data->addChild("$key", htmlspecialchars("$value"));
      }
    }
  }

  private function sorting($data)
  {
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
    return $data;
  }
}



class Serve
{
  private $xml_data = null;

  function __construct($format)
  {
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: GET");
    header("Access-Control-Allow-Headers: *");

    if ($format === 'json') {
      $json_data = json_encode(HandlingResources::$data);
      print_r($json_data);
    }

    if ($format === 'xml') {
      $this->xml_data = new SimpleXMLElement('<?xml version="1.0"?><data></data>');
      HandlingResources::array_to_xml(HandlingResources::$data, $this->xml_data);
      print_r($this->xml_data->asXML());
    }
  }
}

new HandlingResources($suppliers);
