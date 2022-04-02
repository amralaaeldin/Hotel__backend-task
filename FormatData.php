<?php

abstract class Format
{
  public $data;
  function __construct($data)
  {
    $this->data = $data;
  }
  abstract public function formatData();
}

class Json extends Format
{
  public function formatData()
  {
    $json_data = json_encode($this->data);
    return $json_data;
  }
}

class Xml extends Format
{
  public function formatData()
  {
    $this->xml_data = new SimpleXMLElement('<?xml version="1.0"?><data></data>');
    $this->arrayToXml($this->data, $this->xml_data);
    return $this->xml_data->asXML();
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
}
