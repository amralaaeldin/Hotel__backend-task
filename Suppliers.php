<?php

namespace App;

class Suppliers
{
  public $suppliers;

  function __construct(array $suppliers)
  {
    $this->suppliers = $suppliers;
  }
}
