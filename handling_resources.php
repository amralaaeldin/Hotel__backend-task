class handling_resources
{
  private $init_arr = [];
  static $data = [];

  function __construct($suppliers)
  {
    foreach ($suppliers as $supplier) {
      $this->init_arr = [...$this->init_arr, ...json_decode(file_get_contents($supplier))];
    }

    handling_resources::$data = $this->formatting($this->init_arr, handling_resources::$data);
    handling_resources::$data = $this->sorting(handling_resources::$data);

    if (isset($_GET['format'])) {
      $serveAPI = new serve($_GET['format']);
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
        handling_resources::array_to_xml($value, $subnode);
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
