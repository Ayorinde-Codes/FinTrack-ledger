<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreExpenseRequest;
use App\Http\Requests\UpdateExpenseRequest;
use App\Http\Resources\ExpenseResource;
use App\Models\Expense;
use Illuminate\Http\Request;

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
            $expense = Expense::create($request->validated());
            return $this->okResponse('Expense created successfully');
        } catch (\Exception $e) {
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
            return $this->serverErrorResponse($e->getMessage());
        }
    }

    public function update(UpdateExpenseRequest $request, Expense $expense)
    {
        try {
            $expense->update($request->only([
                'expense_category',
                'amount',
                'receipt'
            ]));
            return $this->okResponse('Expense updated successfully');
        } catch (\Exception $e) {
            return $this->serverErrorResponse('Error updating expense', $e->getMessage());
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
