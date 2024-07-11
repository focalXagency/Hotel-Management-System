<?php

namespace App\Listeners;

use App\Events\EndingSoonEvent;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendEmail
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     */
    public function handle(EndingSoonEvent $event): void
    {
        $reservation = $event->reservation;
        $user = $reservation->user;
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = env('MAIL_HOST');
            $mail->SMTPAuth   = true;
            $mail->Username   = env('MAIL_USERNAME');
            $mail->Password   = env('MAIL_PASSWORD');
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port       = env('MAIL_PORT');

            // Recipients
            $mail->setFrom(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
            $mail->addAddress($user->email);

            // Content
            $mail->isHTML(true);
            $mail->Subject = "Reminder";
            $mail->Body    = "<p>Welcome {$user->name},</p>
                <p>Thank you for booking at our hotel. We are very pleased to communicate with you.</p>
                <p>We would like to inform you that your room reservation will expire tomorrow.</p>
                <p>We hope you will be satisfied with our services.</p>";

            $mail->send();
        } catch (\Exception $e) {
            Log::error("Email could not be sent. Mailer Error: {$mail->ErrorInfo}");
        }
    }
}