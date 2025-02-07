<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponseTrait;

class ApiController extends Controller
{
    use ApiResponseTrait;

    public function __invoke()
    {
        return $this->okResponse('success', ['FinTrack-Ledger Api Version 1']);
    }
}
