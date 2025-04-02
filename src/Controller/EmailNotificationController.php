<?php

namespace App\Controller;

use App\Contracts\NotificationService;
use App\DTO\SubscribeNotificationsDTO;
use App\DTO\UnsubscribeNotificationsDTO;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class EmailNotificationController extends AbstractController
{
    public function __construct(
        private NotificationService $notificationService
    )
    {
    }

    #[Route('/notifications/subscribe', methods: ['POST'], format: 'json')]
    public function subscribe(
        #[MapRequestPayload] SubscribeNotificationsDTO $payload
    )
    {
        if ($this->notificationService->isSubscribed($payload->email)) {
            return $this->json([
                'message' => "\"$payload->email\" уже подписан"
            ]);
        }

        $this->notificationService->subscribe($payload->email);

        return $this->json([
            'message' => "\"$payload->email\" успешно подписан"
        ]);
    }

    #[Route('/notifications/unsubscribe', methods: ['POST'], format: 'json')]
    public function unsubscribe(
        #[MapRequestPayload] UnsubscribeNotificationsDTO $payload
    )
    {
        $this->notificationService->unsubscribe($payload->email);
    }
}