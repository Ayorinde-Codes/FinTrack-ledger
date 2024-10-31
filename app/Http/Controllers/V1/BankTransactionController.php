<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBankTransactionRequest;
use App\Http\Requests\UpdateBankTransactionRequest;
use App\Http\Resources\BankTransactionResource;
use App\Models\BankTransaction;
use Illuminate\Support\Facades\DB;

class BankTransactionController extends Controller
{
    public function index()
    {
        $bankTransaction = BankTransaction::all();

        return $this->okResponse(
            'Bank transactions retrieved successfully',
            BankTransactionResource::collection($bankTransaction)
        );
    }
    public function store(StoreBankTransactionRequest $request)
    {
        try {
            DB::beginTransaction();

            $data = array_merge($request->validated(), [
                'user_id' => $request->user()->id,
                'client_id' => $request->client_id,
            ]);

            $transaction = BankTransaction::create($data);
            if (!$transaction)
                return $this->errorResponse('Unable to create bank transaction');
            DB::commit();
            return $this->createdResponse('Bank transaction created successfully', new BankTransactionResource($transaction));
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->serverErrorResponse('Error creating transaction', $e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $bankTransaction = BankTransaction::find($id);
            if (!$bankTransaction)
                return $this->notFoundResponse('Bank transaction not found');

            return $this->okResponse(
                'BankTransaction retrieved successfully',
                new BankTransactionResource($bankTransaction)
            );
        } catch (\Exception $e) {
            return $this->serverErrorResponse('Error getting bank transaction', $e->getMessage());
        }
    }

    public function update(UpdateBankTransactionRequest $request, BankTransaction $bankTransaction)
    {
        try {
            DB::beginTransaction();

            $bankTransaction->fill($request->only([
                'transaction_type',
                'transaction_date'
            ]));

            if ($bankTransaction->isDirty()) {
                $bankTransaction->save();
                DB::commit();
                return $this->okResponse('Bank transaction updated successfully');
            }

            DB::rollBack();
            return $this->errorResponse('Bank transaction not updated');
        } catch (\Exception $e) {
            DB::rollBack();
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
