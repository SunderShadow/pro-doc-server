<?php

namespace App\Service;

use App\Repository\EmailToNotifyRepository;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class NotificationService implements \App\Contracts\NotificationService
{
    public function __construct(
        #[Autowire('%app.notificator.from.email%')] private string $notificatorEmail,
        private EmailToNotifyRepository                            $emailToNotifyRepository, private readonly MailerInterface $mailer
    )
    {
    }

    public function subscribe(string $email): void
    {
        $this->emailToNotifyRepository->add($email);
    }

    public function unsubscribe(string $email): void
    {
        $this->emailToNotifyRepository->remove($email);
    }

    public function isSubscribed(string $email): bool
    {
        return $this->emailToNotifyRepository->findOneBy(['email' => $email]) !== null;
    }

    public function notify(callable $cb): void
    {
        foreach ($this->emailToNotifyRepository->findAll() as $email) {
            $this->mailer->send($cb(
                (new Email())
                    ->from($this->notificatorEmail)
                    ->to($email->getEmail())
            ));
        }
    }
}