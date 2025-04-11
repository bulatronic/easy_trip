<?php

namespace App\Controller\CLI;

use App\Domain\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:create-user',
    description: 'Creates a new user',
    hidden: false
)]
class CreateUserCommand extends Command
{
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly EntityManagerInterface $entityManager,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Creates a new user')
            ->setHelp('This command allows you to create a user...');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $user = new User();
        $user->setEmail('driver@example.com');
        $user->setUsername('driver');

        $hashedPassword = $this->passwordHasher->hashPassword(
            $user,
            'qwerty'
        );
        $user->setPassword($hashedPassword);

        $user->setRoles(['ROLE_DRIVER']);
        $user->setCreatedAt();

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $output->writeln('User successfully generated!');

        return Command::SUCCESS;
    }
}
