<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use App\Notifications\OrderStatusNotification;
use App\Notifications\PaymentNotification;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    protected $smsService;
    protected $pushService;

    public function __construct()
    {
        $this->smsService = new SmsService();
        $this->pushService = new PushNotificationService();
    }

    public function orderCreated(Order $order)
    {
        // Notify client
        $order->client->notify(new OrderStatusNotification($order, 'created'));
        $this->smsService->send($order->client->phone, "Your order #{$order->order_number} has been created successfully.");
        
        // Notify admins
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new OrderStatusNotification($order, 'created_admin'));
        }
    }

    public function orderStatusUpdated(Order $order, $oldStatus)
    {
        // Notify client
        $order->client->notify(new OrderStatusNotification($order, $order->status));
        
        // Send SMS based on status
        $messages = [
            'driver_assigned' => "A driver has been assigned to your order #{$order->order_number}.",
            'collected' => "Your laundry has been collected for order #{$order->order_number}.",
            'in_laundry' => "Your laundry is being processed for order #{$order->order_number}.",
            'out_for_delivery' => "Your order #{$order->order_number} is out for delivery.",
            'delivered' => "Your order #{$order->order_number} has been delivered. Thank you for choosing us!",
            'cancelled' => "Your order #{$order->order_number} has been cancelled."
        ];

        if (isset($messages[$order->status])) {
            $this->smsService->send($order->client->phone, $messages[$order->status]);
        }

        // Notify driver if assigned
        if ($order->driver) {
            $driverMessages = [
                'driver_assigned' => "New order #{$order->order_number} assigned to you.",
                'out_for_delivery' => "Please deliver order #{$order->order_number}."
            ];

            if (isset($driverMessages[$order->status])) {
                $order->driver->notify(new OrderStatusNotification($order, $order->status . '_driver'));
                $this->smsService->send($order->driver->phone, $driverMessages[$order->status]);
            }
        }

        // Push notification
        $this->pushService->sendToUser($order->client, 'Order Update', $messages[$order->status] ?? 'Your order status has been updated.');
    }

    public function paymentCompleted(Payment $payment)
    {
        $order = $payment->order;
        
        // Notify client
        $order->client->notify(new PaymentNotification($payment, 'completed'));
        $this->smsService->send($order->client->phone, "Payment of K{$payment->amount} for order #{$order->order_number} has been received.");

        // Notify admins
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new PaymentNotification($payment, 'completed_admin'));
        }
    }

    public function notifyDriver(Order $order)
    {
        if ($order->driver) {
            $message = "New order #{$order->order_number} assigned to you. Pickup at {$order->pickupLocation->address}";
            $order->driver->notify(new OrderStatusNotification($order, 'assigned'));
            $this->smsService->send($order->driver->phone, $message);
            $this->pushService->sendToUser($order->driver, 'New Assignment', $message);
        }
    }
}