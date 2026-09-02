<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\ReadingPlan;

class ReadingPlanReminder extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public ReadingPlan $readingPlan)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('読書計画のお知らせ')
            ->line('明日が読書予定日です。')
            ->line('書籍：' . $this->readingPlan->book->title)
            ->line('予定日：' . $this->readingPlan->target_date->format('Y年m月d日'))
            ->action('読書計画を確認する', url('/reading-plans'))
            ->line('BookShelfをご利用いただきありがとうございます。');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'message' => '明日が読書予定日です。',
            'book_title' => $this->readingPlan->book->title,
            'target_date' => $this->readingPlan->target_date->format('Y-m-d'),
        ];
    }
}
