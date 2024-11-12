<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaxRequest;
use App\Http\Requests\UpdateTaxRequest;
use App\Http\Resources\TaxResource;
use App\Models\Tax;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TaxController extends Controller
{
    public function index()
    {
        $tax = Tax::all();

        return $this->okResponse('Tax retrieved successfully', TaxResource::collection($tax));
    }

    public function store(StoreTaxRequest $request)
    {
        try {
            DB::beginTransaction();

            $data = array_merge($request->validated(), [
                'user_id' => $request->user()->id,
                'client_id' => $request->client_id,
            ]);

            $tax = Tax::create($data);

            if (!$tax)
                return $this->errorResponse('Error creating tax');
            DB::commit();
            return $this->createdResponse('Tax created successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->serverErrorResponse('Error creating tax', $e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $tax = Tax::find($id);
            if (!$tax)
                return $this->notFoundResponse('Tax not found');

            return $this->okResponse(
                'Tax retrieved successfully',
                new TaxResource($tax)
            );
        } catch (\Exception $e) {
            return $this->serverErrorResponse('Error getting tax', $e->getMessage());
        }
    }

    public function update(UpdateTaxRequest $request, Tax $tax)
    {
        try {
            DB::beginTransaction();

            $tax->fill($request->only([
                'amount',
                'tax_type',
                'tax_date',
            ]));

            if ($tax->isDirty()) {
                $tax->save();
                DB::commit();
                return $this->okResponse('Tax updated successfully');
            }

            DB::rollBack();
            return $this->okResponse('No changes detected');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->serverErrorResponse('Error updating tax', $e->getMessage());
        }
    }

    public function destroy(Tax $tax)
    {
        try {
            $tax->delete();
            return $this->okResponse('Tax deleted successfully');
        } catch (\Exception $e) {
            return $this->serverErrorResponse('Error deleting tax', $e->getMessage());
        }
    }
}
