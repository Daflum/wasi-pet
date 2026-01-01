<?php

namespace App\Notifications;

use App\Models\Pet;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AdoptionWaitlistClosedNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public Pet $pet
    )
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
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('Actualización sobre tu solicitud para ' . $this->pet->name)
                    ->greeting('Hola ' . $notifiable->name . ',')
                    ->line('Te escribimos para darte una gran noticia: ¡' . $this->pet->name . ', por quien mostraste interés, ha encontrado un hogar definitivo!')
                    ->line('Aunque en esta ocasión la adopción ha sido para otra familia, queremos agradecerte de corazón por tu interés y tu deseo de ayudar.')
                    ->line('Tu gesto significa mucho para nosotros. Te invitamos a que sigas viendo a nuestros otros maravillosos animales que aún esperan una oportunidad. ¡Tu compañero ideal podría estar a un clic de distancia!')
                    ->action('Ver más mascotas', route('public.pets.index'))
                    ->salutation('Gracias de nuevo, El equipo de Adra Uni');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
