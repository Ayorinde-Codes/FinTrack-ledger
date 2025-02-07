<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePaymentRequest;
use App\Http\Requests\UpdatePaymentRequest;
use App\Http\Resources\PaymentResource;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function index()
    {
        $invoices = Payment::all();

        return $this->okResponse(
            'Payments retrieved successfully',
            PaymentResource::collection($invoices)
        );
    }

    public function store(StorePaymentRequest $request)
    {
        try {
            DB::beginTransaction();
            $payment = Payment::create($request->validated());

            if (! $payment) {
                return $this->errorResponse('Error creating payment');
            }

            DB::commit();

            return $this->createdResponse('Payment created successfully');
        } catch (\Exception $e) {
            DB::rollBack();

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
            if (! $payment) {
                return $this->notFoundResponse('Payment not found');
            }

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
            DB::beginTransaction();

            $payment->fill($request->only([
                'payment_date',
                'payment_method',
            ]));

            if ($payment->isDirty()) {
                $payment->save();
                DB::commit();

                return $this->okResponse('Payment updated successfully');
            }

            DB::rollBack();

            return $this->errorResponse('Payment not updated');
        } catch (\Exception $e) {
            DB::rollBack();

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
