<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Storage;

class WeeklyReportGeneratedNotification extends Notification
{
    use Queueable;

    public $weeklyReport;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($weeklyReport)
    {
        $this->weeklyReport = $weeklyReport;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        $success = !!$this->weeklyReport->filepath;

        $message = $success
            ? 'Successfully generated weekly report # ' . $this->weeklyReport->id
            : 'Failed to generate weekly report # ' . $this->weeklyReport->id;

        return [
            'from'      => 'System',
            'message'   => $message,
            'actionUrl' => Storage::url($this->weeklyReport->filepath),
            'actionText'=> 'Download'
        ];
    }
}
