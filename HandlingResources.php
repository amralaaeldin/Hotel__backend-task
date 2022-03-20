<?php

class HandlingResources
{
  private $xml_data = null;
  public $init_arr = [];
  public $data = [];

  private function replaceKey($arr, $old_key, $new_key)
  {
    $keys = array_keys((array) $arr);
    if (array_key_exists($old_key, (array) $arr)) {
      $keys[array_search($old_key, $keys)] = $new_key;
    }
    return $arr = array_combine($keys, (array) $arr);
  }

  public function formatting($init_arr, $arr)
  {
    foreach ($init_arr as $nested) {

      $nested = (array) $nested;
      if (strpos($nested['Rate'], '*') === 0) {
        $nested['Rate'] = strlen($nested['Rate']);
      }
      $nested = $this->replaceKey($nested, 'Fare', 'Price');
      $nested = $this->replaceKey($nested, 'Hotel', 'hotelName');
      $nested = $this->replaceKey($nested, 'amenities', 'roomAmenities');
      $arr[] = $nested;
    }
    return $arr;
  }

  public function sorting($data)
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

  private function arrayToXml($data, $xml_data)
  {
    foreach ($data as $key => $value) {
      if (is_array($value)) {
        if (is_numeric($key)) {
          $key = 'item' . $key;
        }
        $sub_node = $xml_data->addChild($key);
        $this->arrayToXml($value, $sub_node);
      } else {
        $xml_data->addChild("$key", htmlspecialchars("$value"));
      }
    }
  }

  public function formatOutput()
  {
    if (isset($_GET['format'])) {
      if ($_GET['format'] === 'json') {
        $json_data = json_encode($this->data);
        return $json_data;
      }

      if ($_GET['format'] === 'xml') {
        $this->xml_data = new SimpleXMLElement('<?xml version="1.0"?><data></data>');
        $this->arrayToXml($this->data, $this->xml_data);
        return $this->xml_data->asXML();
      }
    }
  }
}
