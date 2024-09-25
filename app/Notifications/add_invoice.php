<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\invoices;
use Illuminate\Support\Facades\Auth;
class add_invoice extends Notification
{
    use Queueable;

    private $invoices;
    /**
     * Create a new notification instance.
     */
    public function __construct(invoices $invoices)
    {
        $this->invoices = $invoices;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the database representation of the notification.
     */
    public function toDatabase($notifiable){
        return[
            'id'=>$this->invoices->id,
            'title'=>'تم اضافه فاتورة جديده بواسطه',
            'user'=>Auth::user()->name,
        ];

    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
   
}
