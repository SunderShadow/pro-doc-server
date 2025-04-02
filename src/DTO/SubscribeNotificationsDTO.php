<?php

namespace App\DTO;
use Symfony\Component\Validator\Constraints as Assert;

readonly class SubscribeNotificationsDTO
{
    public function __construct(
        #[Assert\Email(message: 'Неправильный формат email')] public string $email
    )
    {
    }
}