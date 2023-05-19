<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewAppointment extends Notification
{
    use Queueable;

    private $doctor, $user, $service, $appointment;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($doctor, $user, $service, $appointment)
    {
        $this->user = $user;
        $this->doctor = $doctor;
        $this->service = $service;
        $this->appointment = $appointment;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $url = $notifiable->type != 'ADMIN'?'api/':"";
        $message = $this->appointment->appointment_status == 'reject'?"Reject Reason: {$this->appointment->reject_reason}":'';
        return (new MailMessage)
                    ->line('Dear '.$notifiable->name.',')
                    ->line($this->doctor->name.'\'s Appointment booked with following Details.')
                    ->line("Doctor Name: {$this->doctor->name}")
                    ->line("Patient Name: {$this->user->name}")
                    ->line("Email: {$this->user->email}")
                    ->line("Phone: {$this->user->phone}")
                    ->line("Address: {$this->user->address}")
                    ->line("Service: {$this->service}")
                    ->line("Appointment Type: {$this->appointment->appointment_type}")
                    ->line("Appointment Status: {$this->appointment->appointment_status}")
                    ->line($message)
                    ->line("Start at: {$this->appointment->appointment_datetime}")
                    ->action('Visit ', url($url.'/appointments/'.$this->appointment->id))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'vendor_id' => $this->doctor->id,
            'appointment_id' => $this->appointment->id,
            'user_id' => $this->user->id,
            'message' => $this->doctor->name.' Appointment has Updated',
        ];
    }
}
