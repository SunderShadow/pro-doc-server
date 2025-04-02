<?php

namespace App\DTO;
use Symfony\Component\Validator\Constraints as Assert;

readonly class UnsubscribeNotificationsDTO
{
    public function __construct(
        #[Assert\Email(message: 'Неправильный формат')] public string $email
    )
    {
    }
}