<?php

namespace App\Format;

class FormatJson extends FormatData
{
  public function formatData()
  {
    header("Content-Type: application/json; charset=UTF-8");
    
    return json_encode($this->data);
  }
}
