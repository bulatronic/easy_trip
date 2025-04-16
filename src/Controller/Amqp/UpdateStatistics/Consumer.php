<?php

namespace App\Controller\Amqp\UpdateStatistics;

use App\Application\RabbitMq\AbstractConsumer;
use App\Controller\Amqp\UpdateStatistics\Input\Message;
use App\Domain\Service\StatisticsService;
use App\Domain\Service\UserService;

readonly class Consumer extends AbstractConsumer
{
    public function __construct(
        private StatisticsService $statisticsService,
        private UserService $userService,
    ) {
    }

    protected function getMessageClass(): string
    {
        return Message::class;
    }

    /**
     * @throws \Exception
     */
    protected function handle($message): int
    {
        $startDate = new \DateTime($message->startDate->format('Y-m-d H:i:s'));
        $endDate = new \DateTime($message->endDate->format('Y-m-d H:i:s'));

        if ('personal' === $message->statisticsType) {
            if (null === $message->driverId) {
                return $this->reject('Driver ID is required for personal statistics');
            }

            $driver = $this->userService->findUserById($message->driverId);
            if (!$driver) {
                return $this->reject('Driver not found');
            }

            $this->statisticsService->generatePersonalStatistics(
                $driver,
                $message->periodType,
                $startDate,
                $endDate,
            );
        } else {
            $this->statisticsService->generateGlobalStatistics(
                $message->periodType,
                $startDate,
                $endDate,
            );
        }

        return self::MSG_ACK;
    }
}
