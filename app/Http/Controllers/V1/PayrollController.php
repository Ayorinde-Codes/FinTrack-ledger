<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePayrollRequest;
use App\Http\Requests\UpdatePayrollRequest;
use App\Http\Resources\PayrollResource;
use App\Models\Payroll;
use Illuminate\Support\Facades\DB;

class PayrollController extends Controller
{
    public function index()
    {
        $payrolls = Payroll::all();

        return $this->okResponse('Payroll retrieved successfully', PayrollResource::collection($payrolls));
    }

    public function store(StorePayrollRequest $request)
    {
        try {
            DB::beginTransaction();
            $payroll = Payroll::create($request->validated());
            if (!$payroll)
                return $this->badRequestResponse('Payroll failed to create');
            DB::commit();
            return $this->createdResponse('Payroll created successfully');
        } catch (\Exception $e) {
            DB::rollback();
            return $this->serverErrorResponse(
                'Error creating payroll',
                $e->getMessage()
            );
        }
    }

    public function show(Payroll $payroll)
    {
        return $this->okResponse('Payroll retrieved successfully', new PayrollResource($payroll));
    }

    public function update(UpdatePayrollRequest $request, Payroll $payroll)
    {
        try {
            DB::beginTransaction();

            $payroll->fill($request->only([
                'salary',
                'payment_date',
                'taxes'
            ]));

            if ($payroll->isDirty()) {
                $payroll->save();
                DB::commit();
                return $this->okResponse('Payroll updated successfully');
            }

            DB::rollBack();
            return $this->errorResponse('Payroll not updated');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->serverErrorResponse('Error updating payroll', $e->getMessage());
        }
    }

    public function destroy(Payroll $payroll)
    {
        try {
            $deleted = $payroll->delete();

            if (!$deleted) {
                return $this->badRequestResponse('Payroll failed to delete');
            }

            return $this->okResponse('Payroll deleted successfully');
        } catch (\Exception $e) {
            return $this->serverErrorResponse(
                'Error deleting payroll',
                $e->getMessage()
            );
        }
    }
}
