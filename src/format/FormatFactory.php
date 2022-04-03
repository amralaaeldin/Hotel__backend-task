<?php

namespace App\Format;

class FormatFactory
{


  public function create($data)
  {
    $getFormat = 'App\Format\Format' . ucfirst($_GET['format']);
    $handleFormat = new $getFormat($data);
    return $handleFormat->formatData();
  }
}
