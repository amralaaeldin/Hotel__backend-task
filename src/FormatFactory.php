<?php

namespace App;

class FormatFactory
{

  public function create($data)
  {
    $formatType = 'App\Format'.'\Format' . ucfirst($_GET['format']);
    $format = new $formatType($data);
    return $format->formatData();
  }
}
