<?php

namespace App\Contracts;

use Symfony\Component\Mime\Email;

interface NotificationService
{
    public function subscribe(string $email): void;

    public function unsubscribe(string $email): void;

    public function isSubscribed(string $email): bool;

    /**
     * (Symfony\Component\Mime\Email $email) => $email
     * @param callable<Email> $cb
     * @return void
     */
    public function notify(callable $cb): void;
}