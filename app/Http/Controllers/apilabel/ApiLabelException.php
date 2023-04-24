<?php

namespace App\Http\Controllers\apilabel;

use exception;

class ApiLabelException extends exception
{
    protected $items;

    public function __construct($message, $items = [])
    {
        parent::__construct($message);
        $this->items = $items;
    }

    public function getItems(){
        return $this->items;
    }

}
