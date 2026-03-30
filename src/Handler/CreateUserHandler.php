<?php

namespace App\Handler;

use App\Entity\Profile;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class CreateUserHandler
{
    public function __construct(
        protected UserPasswordHasherInterface $passwordHasher,
        protected EntityManagerInterface $entityManager,
    ) {
    }

    public function __invoke(User $user): void
    {
        $hashedPassword = $this->passwordHasher->hashPassword($user, $user->getPassword());

        $user->setPassword($hashedPassword);

        $profile = (new Profile())
            ->setUser($user);

        $this->entityManager->persist($profile);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}
