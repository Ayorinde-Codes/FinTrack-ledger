<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreInvoiceRequest;
use App\Http\Requests\UpdateInvoiceRequest;
use App\Http\Resources\InvoiceResource;
use App\Models\Invoice;
use Exception;
use Carbon\Carbon;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function index()
    {
        $invoices = Invoice::all();

        return $this->okResponse(
            'Invoice retrieved successfully',
            InvoiceResource::collection($invoices)
        );
    }

    public function store(StoreInvoiceRequest $request)
    {
        try {

            $invoice = Invoice::create($request->validated());

            if ($invoice->recurrence) {
                $invoice->next_invoice_date = Carbon::parse($invoice->due_date)->addMonth();
                $invoice->save();
            }
            return $this->createdResponse('Invoice stored successfully');
        } catch (Exception $e) {
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
            $invoice->update($request->only([
                'invoice_number',
                'invoice_date',
                'due_date',
                'recurrence',
                'next_invoice_date',
            ]));
            return $this->okResponse('Invoice updated successfully');
        } catch (Exception $e) {
            return $this->serverErrorResponse('Error updating invoice', $e->getMessage());
        }
    }

    public function destroy(Invoice $invoice)
    {
        try {
            $invoice->delete();
            return $this->okResponse('Invoice Deleted successfully');
        } catch (Exception $e) {
            return $this->serverErrorResponse($e->getMessage());
        }
    }
}
