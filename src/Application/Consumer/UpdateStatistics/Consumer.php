<?php

namespace App\Application\Consumer\UpdateStatistics;

use App\Application\Consumer\UpdateStatistics\Input\UpdateStatisticsDTO;
use App\Domain\Service\StatisticsService;
use App\Domain\Service\UserService;
use Doctrine\ORM\EntityManagerInterface;
use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

readonly class Consumer implements ConsumerInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private SerializerInterface $serializer,
        private ValidatorInterface $validator,
        private StatisticsService $statisticsService,
        private UserService $userService,
    ) {
    }

    public function execute(AMQPMessage $msg): int
    {
        try {
            $message = $this->serializer->deserialize($msg->getBody(), UpdateStatisticsDTO::class, 'json');
            $errors = $this->validator->validate($message);

            if ($errors->count() > 0) {
                return $this->reject((string) $errors);
            }

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
                    $endDate
                );
            } else {
                $this->statisticsService->generateGlobalStatistics(
                    $message->periodType,
                    $startDate,
                    $endDate
                );
            }

            return self::MSG_ACK;
        } catch (\Throwable $e) {
            return $this->reject($e->getMessage());
        } finally {
            $this->entityManager->clear();
            $this->entityManager->getConnection()->close();
        }
    }

    protected function reject(string $error): int
    {
        echo "Error processing message: $error\n";

        return self::MSG_REJECT;
    }
}
