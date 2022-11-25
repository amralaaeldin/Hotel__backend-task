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
      $nested = $this->replaceKey($nested, 'Fare', 'price');
      $nested = $this->replaceKey($nested, 'Price', 'price');
      $nested = $this->replaceKey($nested, 'Hotel', 'name');
      $nested = $this->replaceKey($nested, 'hotelName', 'name');
      $nested = $this->replaceKey($nested, 'Rate', 'rate');
      $nested = $this->replaceKey($nested, 'roomAmenities', 'amenities');
      $arr[] = $nested;
    }
    return $arr;
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
    
    return $this->data;
  }
}
