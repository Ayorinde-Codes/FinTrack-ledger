<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateInventoryRequest;
use App\Http\Resources\InventoryResource;
use App\Models\Inventory;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index()
    {
        $inventory = Inventory::all();

        return $this->okResponse('Inventory retrieved successfully', InventoryResource::collection($inventory));
    }
    public function store(Request $request)
    {
        try {
            $inventory = Inventory::create($request->validated());

            if (!$inventory)
                return $this->errorResponse('Error creating inventory');

            return $this->createdResponse('Inventory created successfully');
        } catch (\Exception $e) {
            return $this->serverErrorResponse('Error creating inventory', $e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $inventory = Inventory::find($id);
            if (!$inventory)
                return $this->notFoundResponse('Inventory not found');

            return $this->okResponse(
                'Inventory retrieved successfully',
                new InventoryResource($inventory)
            );
        } catch (\Exception $e) {
            return $this->serverErrorResponse('Error getting inventory', $e->getMessage());
        }
    }

    public function update(UpdateInventoryRequest $request, Inventory $inventory)
    {
        try {
            $inventory->update($request->only([
                'product_name',
                'quantity',
                'price',
            ]));
            return $this->okResponse('Inventory updated successfully');
        } catch (\Exception $e) {
            return $this->serverErrorResponse('Error updating inventory', $e->getMessage());
        }
    }

    public function destroy(Inventory $inventory)
    {
        try {
            $inventory->delete();
            return $this->okResponse('Inventory deleted successfully');
        } catch (\Exception $e) {
            return $this->serverErrorResponse('Error deleting inventory', $e->getMessage());
        }
    }
}
