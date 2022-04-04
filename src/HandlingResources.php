<?php

namespace App;

class HandlingResources
{
  private $data = [];

  private function replaceKey($arr, $old_key, $new_key)
  {
    $keys = array_keys((array) $arr);
    if (array_key_exists($old_key, (array) $arr)) {
      $keys[array_search($old_key, $keys)] = $new_key;
    }
    return $arr = array_combine($keys, (array) $arr);
  }

    private function format($init_arr)
  {
    $arr = [];
    foreach ($init_arr as $nested) {
      $nested = (array) $nested;
      if (strpos($nested['Rate'], '*') === 0) {
        $nested['Rate'] = strlen($nested['Rate']);
      }
      if (array_key_exists('roomAmenities', $nested)) {
        $nested['roomAmenities'] = explode(',', $nested['roomAmenities']);
      }
      $nested = $this->replaceKey($nested, 'Fare', 'Price');
      $nested = $this->replaceKey($nested, 'Hotel', 'hotelName');
      $nested = $this->replaceKey($nested, 'amenities', 'roomAmenities');
      $arr[] = $nested;
    }
    return $arr;
  }

  private function sort($data)
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
  
  private function collect(array $suppliers)
  {
    foreach ($suppliers as $supplier) {
      $this->data = [...$this->data, ...json_decode(file_get_contents($supplier))];
    }
    return $this->data;
  }
  
  public function getData(array $suppliers)
  {
    $this->data = $this->collect($suppliers);
    $this->data = $this->format($this->data);
    $this->data = $this->sort($this->data);
    
    return $this->data;
  }
}
