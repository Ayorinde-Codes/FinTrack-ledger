<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreInvoiceRequest;
use App\Http\Requests\UpdateInvoiceRequest;
use App\Http\Resources\InvoiceResource;
use App\Models\Invoice;
use Exception;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    public function index()
    {
        $invoices = Invoice::all();

        return $this->okResponse(
            'Invoices retrieved successfully',
            InvoiceResource::collection($invoices)
        );
    }

    public function store(StoreInvoiceRequest $request)
    {

        try {
            DB::beginTransaction();
            $invoice = Invoice::create([
                'invoice_number' => Invoice::generateInvoiceNumber(),
                'amount' => $request->amount,
                'due_date' => Carbon::parse($request->due_date),
                'status' => $request->status,
                'recurrence' => $request->recurrence,
                'next_invoice_date' => Carbon::parse($request->next_invoice_date),
                'user_id' => $request->user()->id,
                'client_id' => $request->client_id,
            ]);

            if ($invoice->recurrence) {
                $invoice->next_invoice_date = Carbon::parse($invoice->due_date)->addMonth();
                $invoice->save();
            }
            DB::commit();
            return $this->createdResponse('Invoice created successfully');
        } catch (Exception $e) {
            DB::rollBack();
            return $this->serverErrorResponse($e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $invoice = Invoice::find($id);
            if (!$invoice)
                return $this->notFoundResponse('Invoice not found');

            return $this->okResponse(
                'Invoice retrieved successfully',
                new InvoiceResource($invoice)
            );
        } catch (Exception $e) {
            return $this->serverErrorResponse($e->getMessage());
        }
    }

    public function update(UpdateInvoiceRequest $request, Invoice $invoice)
    {
        try {
            DB::beginTransaction();

            $invoice->fill($request->only([
                'invoice_date',
                'due_date',
                'recurrence',
                'next_invoice_date',
            ]));

            if ($invoice->isDirty()) {
                $invoice->save();
                DB::commit();
                return $this->okResponse('Invoice updated successfully');
            }

            DB::rollBack();
            return $this->errorResponse('Invoice not updated');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->serverErrorResponse('Error updating invoice', $e->getMessage());
        }
    }

    public function destroy(Invoice $invoice)
    {
        try {
            $invoice->delete();
            return $this->okResponse('Invoice deleted successfully');
        } catch (Exception $e) {
            return $this->serverErrorResponse($e->getMessage());
        }
    }
}
