<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreExpenseRequest;
use App\Http\Requests\UpdateExpenseRequest;
use App\Http\Resources\ExpenseResource;
use App\Models\Expense;
use Illuminate\Support\Facades\DB;

class ExpenseController extends Controller
{
    public function index()
    {
        $expenses = Expense::all();

        return $this->okResponse(
            'Expense retrieved successfully',
            ExpenseResource::collection($expenses)
        );
    }

    public function store(StoreExpenseRequest $request)
    {
        try {
            DB::beginTransaction();

            $expense = Expense::create($request->validated());

            if (!$expense)
                return $this->errorResponse('Unable to create expense');
            DB::commit();
            return $this->createdResponse('Expense created successfully', new ExpenseResource($expense));
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->serverErrorResponse('Error creating expense', $e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $expense = Expense::find($id);
            if (!$expense)
                return $this->notFoundResponse('Expense not found');

            return $this->okResponse(
                'Expense retrieved successfully',
                new ExpenseResource($expense)
            );
        } catch (\Exception $e) {
            return $this->serverErrorResponse('Error getting expense', $e->getMessage());
        }
    }

    public function update(UpdateExpenseRequest $request, Expense $expense)
    {
        try {
            DB::beginTransaction();

            $expense->fill($request->only([
                'expense_category',
                'amount',
                'receipt'
            ]));

            if ($expense->isDirty()) {
                $expense->save();
                DB::commit();
                return $this->okResponse('Expense updated successfully');
            }

            DB::rollBack();
            return $this->errorResponse('Expense not updated');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->serverErrorResponse('Error updating bank expense', $e->getMessage());
        }
    }

    public function destroy(Expense $expense)
    {
        try {
            $expense->delete();
            return $this->okResponse('Expense deleted successfully');
        } catch (\Exception $e) {
            return $this->serverErrorResponse('Error deleting expense', $e->getMessage());
        }
    }
}
