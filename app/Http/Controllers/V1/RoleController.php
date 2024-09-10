<?php

namespace App\Http\Controllers\V1;

use App\Http\Resources\RoleResource;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RoleController extends Controller
{
    /**
     * Get the list of all roles.
     */
    public function __invoke(Request $request)
    {
        $roles = RoleResource::collection(Role::all());

        return $this->okResponse('Roles gotten successfully', $roles);
    }
}