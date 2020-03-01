<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class NuevoMensaje extends Notification
{
    use Queueable;
    public $usuario;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        $this->usuario = $user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    public function toDatabase($notifiable) {
        return [
            'mensaje' => "Nuevo mensaje de ".$this->usuario->primer_nombre." ".$this->usuario->segundo_nombre." ".$this->usuario->primer_apellido." ".$this->usuario->segundo_apellido
        ];
    }

    public function toBroadcast($notifiable) {
        return [
            'data' => [
                'mensaje' => "Nuevo mensaje de ".$this->usuario->primer_nombre." ".$this->usuario->segundo_nombre." ".$this->usuario->primer_apellido." ".$this->usuario->segundo_apellido
            ]
        ];
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
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
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
            'mensaje' => "Nuevo mensaje de ".$this->usuario->primer_nombre." ".$this->usuario->segundo_nombre." ".$this->usuario->primer_apellido." ".$this->usuario->segundo_apellido
        ];
    }
}
