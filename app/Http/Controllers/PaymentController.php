<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use App\Services\PaymentGatewayService;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    protected $paymentGateway;
    protected $notificationService;

    public function __construct(PaymentGatewayService $paymentGateway, NotificationService $notificationService)
    {
        $this->paymentGateway = $paymentGateway;
        $this->notificationService = $notificationService;
    }

    public function initiatePayment(Request $request, Order $order)
    {
        $validator = Validator::make($request->all(), [
            'payment_method' => 'required|in:mtn,airtel,zamtel',
            'phone_number' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if ($order->payment_status === 'paid') {
            return response()->json(['message' => 'Order already paid'], 400);
        }

        DB::beginTransaction();

        try {
            // Create payment record
            $payment = Payment::create([
                'order_id' => $order->id,
                'user_id' => $request->user()->id,
                'amount' => $order->total - $order->wallet_used,
                'payment_method' => $request->payment_method,
                'payment_type' => $order->payment_type,
                'transaction_id' => 'TXN-' . uniqid(),
                'status' => 'pending'
            ]);

            // Process payment through gateway
            $response = $this->paymentGateway->processPayment($payment, $request->phone_number);

            if ($response['success']) {
                $payment->status = 'processing';
                $payment->provider_reference = $response['reference'];
                $payment->provider_response = $response;
                $payment->save();

                DB::commit();

                return response()->json([
                    'payment' => $payment,
                    'message' => 'Payment initiated successfully',
                    'verification_required' => true
                ]);
            } else {
                $payment->status = 'failed';
                $payment->provider_response = $response;
                $payment->save();

                DB::commit();

                return response()->json(['message' => 'Payment initiation failed'], 400);
            }

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Payment processing failed: ' . $e->getMessage()], 500);
        }
    }

    public function verifyPayment(Request $request, Payment $payment)
    {
        $validator = Validator::make($request->all(), [
            'transaction_reference' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Verify payment with gateway
        $verification = $this->paymentGateway->verifyPayment($payment, $request->transaction_reference);

        if ($verification['success']) {
            DB::transaction(function () use ($payment, $verification) {
                $payment->status = 'completed';
                $payment->provider_response = array_merge(
                    $payment->provider_response ?? [],
                    ['verification' => $verification]
                );
                $payment->save();

                $payment->order->payment_status = 'paid';
                $payment->order->transaction_reference = $payment->transaction_id;
                $payment->order->save();

                // Send notifications
                $this->notificationService->paymentCompleted($payment);
            });

            return response()->json([
                'message' => 'Payment verified successfully',
                'payment' => $payment->fresh()
            ]);
        }

        return response()->json(['message' => 'Payment verification failed'], 400);
    }

    public function getPaymentStatus(Payment $payment)
    {
        return response()->json([
            'status' => $payment->status,
            'amount' => $payment->amount,
            'method' => $payment->payment_method,
            'reference' => $payment->transaction_id
        ]);
    }
}