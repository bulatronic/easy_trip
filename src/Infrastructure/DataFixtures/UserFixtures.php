<?php

namespace App\Infrastructure\DataFixtures;

use App\Domain\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher,
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $users = [
            [
                'username' => 'Админ',
                'email' => 'admin@example.com',
                'password' => 'admin123',
                'role' => 'ROLE_ADMIN',
            ],
            [
                'username' => 'Антон',
                'email' => 'anton@example.com',
                'password' => 'anton123',
                'role' => 'ROLE_DRIVER',
            ],
            [
                'username' => 'Вадим',
                'email' => 'vadim@example.com',
                'password' => 'vadim123',
                'role' => 'ROLE_PASSENGER',
            ],
            [
                'username' => 'Иван',
                'email' => 'ivan@example.com',
                'password' => 'ivan123',
                'role' => 'ROLE_DRIVER',
            ],
            [
                'username' => 'Мария',
                'email' => 'maria@example.com',
                'password' => 'maria123',
                'role' => 'ROLE_PASSENGER',
            ],
            [
                'username' => 'Елена',
                'email' => 'lena@example.com',
                'password' => 'lena123',
                'role' => 'ROLE_DRIVER',
            ],
            [
                'username' => 'Александр',
                'email' => 'alex@example.com',
                'password' => 'alex123',
                'role' => 'ROLE_PASSENGER',
            ],
            [
                'username' => 'Анна',
                'email' => 'anna@example.com',
                'password' => 'anna123',
                'role' => 'ROLE_DRIVER',
            ],
            [
                'username' => 'Кирилл',
                'email' => 'kirill@example.com',
                'password' => 'kirill123',
                'role' => 'ROLE_PASSENGER',
            ],
        ];

        foreach ($users as $userData) {
            $user = new User();
            $user->setUsername($userData['username']);
            $user->setEmail($userData['email']);
            $user->setPassword($this->passwordHasher->hashPassword($user, $userData['password']));
            $user->setRole($userData['role']);
            $user->setCreatedAt();

            $manager->persist($user);
        }

        $manager->flush();
    }
}
