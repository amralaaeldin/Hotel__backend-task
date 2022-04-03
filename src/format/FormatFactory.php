<?php

namespace App\Format;

class FormatFactory
{
  public $data;

  function __construct($data)
  {
    $this->data = $data;
  }


  public function create()
  {
    $getFormat = 'App\Format\Format' . ucfirst($_GET['format']);
    $handleFormat = new $getFormat($this->data);
    $this->data = $handleFormat->formatData();
    return $this->data;
  }
}
