<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderStatusNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $order;
    protected $status;
    protected $type;

    public function __construct(Order $order, $status, $type = 'client')
    {
        $this->order = $order;
        $this->status = $status;
        $this->type = $type;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        $statusMessages = [
            'created' => 'Your order has been created successfully',
            'driver_assigned' => 'A driver has been assigned to your order',
            'collected' => 'Your laundry has been collected',
            'in_laundry' => 'Your laundry is being processed',
            'out_for_delivery' => 'Your order is out for delivery',
            'delivered' => 'Your order has been delivered',
            'cancelled' => 'Your order has been cancelled',
        ];

        $message = $statusMessages[$this->status] ?? "Order status updated to {$this->status}";

        return (new MailMessage)
                    ->subject("Order #{$this->order->order_number} Update")
                    ->greeting("Hello {$notifiable->name}!")
                    ->line($message)
                    ->line("Order Number: {$this->order->order_number}")
                    ->line("Current Status: " . ucwords(str_replace('_', ' ', $this->order->status)))
                    ->action('View Order', url("/orders/{$this->order->id}"))
                    ->line('Thank you for using our service!');
    }

    public function toArray($notifiable)
    {
        return [
            'order_id' => $this->order->id,
            'order_number' => $this->order->order_number,
            'status' => $this->order->status,
            'old_status' => $this->status,
            'message' => "Order #{$this->order->order_number} status updated to {$this->order->status}"
        ];
    }
}