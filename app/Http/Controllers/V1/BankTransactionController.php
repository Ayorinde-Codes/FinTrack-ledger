<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBankTransactionRequest;
use App\Http\Requests\UpdateBankTransactionRequest;
use App\Http\Resources\BankTransactionResource;
use App\Models\BankTransaction;
use Illuminate\Http\Request;

class BankTransactionController extends Controller
{
    public function index()
    {
        $invoices = BankTransaction::all();

        return $this->okResponse(
            'Bank transactions retrieved successfully',
            BankTransactionResource::collection($invoices)
        );
    }
    public function store(StoreBankTransactionRequest $request)
    {
        try {
            $transaction = BankTransaction::create($request->validated());
            if (!$transaction)
                return $this->errorResponse('Unable to create bank transaction');
            return $this->createdResponse('Bank transaction created successfully', new BankTransactionResource($transaction));
        } catch (\Exception $e) {
            return $this->serverErrorResponse('Error creating transaction', $e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $BankTransaction = BankTransaction::find($id);
            if (!$BankTransaction)
                return $this->notFoundResponse('BankTransaction not found');

            return $this->okResponse(
                'BankTransaction retrieved successfully',
                new BankTransactionResource($BankTransaction)
            );
        } catch (\Exception $e) {
            return $this->serverErrorResponse('Error getting expense', $e->getMessage());
        }
    }

    public function update(UpdateBankTransactionRequest $request, BankTransaction $bankTransaction)
    {
        try {
            $bankTransaction->update($request->only([
                'transaction_type',
                'transaction_date'
            ]));
            return $this->okResponse('Bank transaction updated successfully');
        } catch (\Exception $e) {
            return $this->serverErrorResponse('Error updating bank transaction', $e->getMessage());
        }
    }
    public function destroy(BankTransaction $bankTransaction)
    {
        try {
            $bankTransaction->delete();
            return $this->okResponse('Bank transaction deleted successfully');
        } catch (\Exception $e) {
            return $this->serverErrorResponse('Error deleting transaction', $e->getMessage());
        }
    }
}
