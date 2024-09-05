<?php

namespace App\Http\Controllers;
use App\Traits\ApiResponseTrait;

abstract class Controller
{
    use ApiResponseTrait;

    public function init()
    {
        return $this->okResponse("success", ["Handiwork Api Version 1"]);
    }
}
