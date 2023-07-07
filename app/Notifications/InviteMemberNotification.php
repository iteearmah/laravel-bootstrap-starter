<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Password;

class InviteMemberNotification extends Notification
{
    use Queueable;

    private mixed $team;
    private mixed $user;

    /**
     * Create a new notification instance.
     */
    public function __construct($team, $user)
    {
        $this->team = $team;
        $this->user = $user;
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
       // teams.show-invitation-form
        $url = route('teams.show-invitation-form', [
            'team' => $this->team->id,
            'user' => $this->user->id,
            'token' => Password::broker()->createToken($this->user),
        ]);

        return (new MailMessage)
            ->subject('Invitation to join ' . config('team.team_label'))
            ->greeting('Hello ' . $this->user->name . '!')
            ->line('You have been invited to join the '  . config('team.team_label') . $this->team->name . '.')
            ->line('Click the button below to accept the invitation.')
            ->action('Accept Invitation', $url)
            ->line('Thank you for using our application!');

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
