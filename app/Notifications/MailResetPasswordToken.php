<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class MailResetPasswordToken extends Notification
{
    use Queueable;

        public $token;

        /**
         * Create a new notification instance.
         *
         * @return void
         */
        public function __construct($token)
        {
            $this->token = $token;
        }

        /**
         * Get the notification's delivery channels.
         *
         * @param  mixed  $notifiable
         * @return array
         */
        public function via($notifiable)
        {
            return ['mail'];
        }

        /**
         * Get the mail representation of the notification.
         *
         * @param  mixed  $notifiable
         * @return \Illuminate\Notifications\Messages\MailMessage
         */
        public function toMail($notifiable)
        {
            return (new MailMessage)
                        ->subject("กู้คืนพาสเวิร์ด")
                        ->line("สวัสดี, คุณลืมรหัสผ่านของคุณหรือไม่ คลิกที่ปุ่มเพื่อตั้งค่าใหม่.")
                        ->action(' ตั้งค่าพาสเวิร์ดใหม่', url('password/reset', $this->token))
                        ->line('ขอบคุณสำหรับการเป็นเพื่อน');
        }

    }
