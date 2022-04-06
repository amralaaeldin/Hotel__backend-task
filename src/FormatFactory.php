<?php

namespace App;

class FormatFactory
{

  public function create($data)
  {
    $dataType = 'App\Format'.'\Format' . ucfirst($_GET['format']);
    $type = new $dataType($data);
    return $type->formatData();
  }
}
