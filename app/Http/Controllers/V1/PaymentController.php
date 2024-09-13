<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePaymentRequest;
use App\Http\Requests\UpdatePaymentRequest;
use App\Http\Resources\InvoiceResource;
use App\Http\Resources\PaymentResource;
use Illuminate\Http\Request;
use App\Models\Payment;

class PaymentController extends Controller
{
    public function index()
    {
        $invoices = Payment::all();

        return $this->okResponse(
            'Payment retrieved successfully',
            PaymentResource::collection($invoices)
        );
    }

    public function store(StorePaymentRequest $request)
    {
        try {
            $payment = Payment::create($request->validated());
            return $this->createdResponse('Payment created successfully');
        } catch (\Exception $e) {
            return $this->serverErrorResponse(
                'Error creating payment',
                $e->getMessage()
            );
        }
    }

    public function show($id)
    {
        try {
            $payment = Payment::find($id);
            if (!$payment)
                return $this->notFoundResponse('Payment not found');

            return $this->okResponse(
                'Payment retrieved successfully',
                new PaymentResource($payment)
            );
        } catch (\Exception $e) {
            return $this->serverErrorResponse($e->getMessage());
        }
    }

    public function update(UpdatePaymentRequest $request, Payment $payment)
    {
        try {
            $payment->update($request->only([
                'payment_date',
                'payment_method'
            ]));
            return $this->okResponse('Payment updated successfully');
        } catch (\Exception $e) {
            return $this->serverErrorResponse('Error updating payment', $e->getMessage());
        }
    }

    public function destroy(Payment $payment)
    {
        try {
            $payment->delete();
            return $this->okResponse('Payment deleted successfully');
        } catch (\Exception $e) {
            return $this->serverErrorResponse(
                'Error deleting payment',
                $e->getMessage()
            );
        }
    }
}
