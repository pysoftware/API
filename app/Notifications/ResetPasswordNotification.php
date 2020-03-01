<?php

namespace Modules\Auth\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class ResetPasswordNotification extends Notification implements ShouldQueue
{
    use Queueable, SerializesModels;
    private $userr;
    private $token;

    /**
     * Create a new message instance.
     *
     * @param $user
     * @param $token
     */
    public function __construct($userr, $token)
    {
        $this->userr = $userr;
        $this->token = $token;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Build the message.
     *
     * @return MailMessage
     */
    public function toMail()
    {
        $url = 'yandex.ru?token=' . $this->token;
        return (new MailMessage)
            ->from('mail@mail.ru')
            ->greeting('Здравствуйте, ' . $this->userr->email)
            ->subject('Восстановление пароля на сайте "Работа в Перми".')
            ->line('Был сделан запрос на восстановление пароля.')
            ->line('Перейдите по ссылке ниже для восстановления пароля.')
            ->action('восстановление пароля', $url)
            ->line('Если ссылка не работоспособна - скопируйте и вставьте её в адрессную строку браузера.')
            ->line('Если Вы не делали такого запрос - проигнорируйте данное письмо.')
            ->salutation('С уважением, команда сайта Работа в Перми.');
    }
}
