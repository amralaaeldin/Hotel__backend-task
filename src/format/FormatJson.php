<?php

namespace App;

class FormatJson extends FormatData
{
  public function formatData()
  {
    return json_encode($this->data);
  }
}
