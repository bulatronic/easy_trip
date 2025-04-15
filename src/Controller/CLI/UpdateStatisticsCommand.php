<?php

namespace App\Controller\CLI;

use App\Application\Consumer\UpdateStatistics\Input\UpdateStatisticsDTO;
use App\Domain\Service\StatisticsService;
use App\Domain\Service\UserService;
use OldSound\RabbitMqBundle\RabbitMq\ProducerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:generate-statistics', description: 'Send statistics update messages to the queue')]
class UpdateStatisticsCommand extends Command
{
    public function __construct(
        private readonly ProducerInterface $updateStatisticsProducer,
        // private readonly StatisticsService $statisticsService,
        // private readonly UserService $userService
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addOption('type', 't', InputOption::VALUE_REQUIRED, 'Statistics type (personal/global)', 'global')
            ->addOption('period', 'p', InputOption::VALUE_REQUIRED, 'Period type (daily/weekly/monthly/yearly)', 'weekly')
            ->addOption('driver-id', 'd', InputOption::VALUE_OPTIONAL, 'Driver ID for personal statistics')
            ->addOption('start-date', 's', InputOption::VALUE_OPTIONAL, 'Start date (Y-m-d)')
            ->addOption('end-date', 'f', InputOption::VALUE_OPTIONAL, 'End date (Y-m-d)');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $statisticsType = $input->getOption('type');
        $periodType = $input->getOption('period');
        $driverId = $input->getOption('driver-id');

        try {
            // Проверка для personal статистики (до создания DTO)
            if ('personal' === $statisticsType && null === $driverId) {
                throw new \RuntimeException('Driver ID is required for personal statistics');
            }

            // Получаем даты из ввода
            $startDateInput = $input->getOption('start-date');
            $endDateInput = $input->getOption('end-date');

            // Если даты не указаны - получаем дефолтные
            if (null === $startDateInput || null === $endDateInput) {
                [$defaultStartDate, $defaultEndDate] = $this->getDefaultDates($periodType);
                $startDateInput ??= $defaultStartDate;
                $endDateInput ??= $defaultEndDate;
            }

            // Преобразуем строки в DateTime объекты перед созданием DTO
            $startDate = new \DateTime($startDateInput);
            $endDate = new \DateTime($endDateInput);

            $dto = new UpdateStatisticsDTO(
                $periodType,
                $statisticsType,
                $startDate,
                $endDate,
                'personal' === $statisticsType ? (int) $driverId : null,
            );

            $this->updateStatisticsProducer->publish(
                json_encode([
                    'periodType' => $dto->periodType,
                    'statisticsType' => $dto->statisticsType,
                    'driverId' => $dto->driverId,
                    'startDate' => $dto->startDate->format('Y-m-d H:i:s'),
                    'endDate' => $dto->endDate->format('Y-m-d H:i:s'),
                ]),
                'update_statistics'
            );

            $output->writeln(sprintf(
                '<info>Statistics update sent. Type: %s, Period: %s, Dates: %s to %s</info>',
                $statisticsType,
                $periodType,
                $dto->startDate->format('Y-m-d'),
                $dto->endDate->format('Y-m-d')
            ));

            return Command::SUCCESS;
        } catch (\RuntimeException $e) {
            $output->writeln(sprintf('<error>%s</error>', $e->getMessage()));

            return Command::FAILURE;
        } catch (\Throwable $e) {
            $output->writeln(sprintf('<error>Failed to send message: %s</error>', $e->getMessage()));

            return Command::FAILURE;
        }
    }

    private function getDefaultDates(string $period): array
    {
        $now = new \DateTimeImmutable();

        return match ($period) {
            'daily' => [$now->format('Y-m-d 00:00:00'), $now->format('Y-m-d 23:59:59')],
            'weekly' => [
                $now->modify('monday this week')->format('Y-m-d'),
                $now->modify('sunday this week')->format('Y-m-d'),
            ],
            'monthly' => [
                $now->modify('first day of this month')->format('Y-m-d'),
                $now->modify('last day of this month')->format('Y-m-d'),
            ],
            'yearly' => [
                $now->modify('first day of january')->format('Y-m-d'),
                $now->modify('last day of december')->format('Y-m-d'),
            ],
            default => [$now->format('Y-m-d'), $now->format('Y-m-d')],
        };
    }
}
