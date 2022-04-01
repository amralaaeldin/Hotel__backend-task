<?php

namespace Format;

abstract class FormatData
{
  public $data;
  function __construct($data)
  {
    $this->data = $data;
  }
  abstract public function formatData();
}
