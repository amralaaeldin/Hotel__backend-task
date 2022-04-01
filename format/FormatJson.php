<?php

namespace Format;

class FormatJson extends FormatData
{
  public function formatData()
  {
    return json_encode($this->data);
  }
}
