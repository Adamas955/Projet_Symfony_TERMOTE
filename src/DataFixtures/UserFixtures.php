<?php

namespace App\DataFixtures;

use App\Entity\UserEntity;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $usersData = [
            ['email' => 'nasri@gmail.com', 'firstname' => 'Samir', 'lastname' => 'Nasri', 'roles' => ['ROLE_USER'], 'password' => 'nasri'],
            ['email' => 'payet@gmail.com', 'firstname' => 'Dimitri', 'lastname' => 'Payet', 'roles' => ['ROLE_USER'], 'password' => 'payet'],
            ['email' => 'benatia@gmail.com', 'firstname' => 'Medhi', 'lastname' => 'Benatia', 'roles' => ['ROLE_ADMIN'], 'password' => 'benatia'],
            ['email' => 'longoria@gmail.com', 'firstname' => 'Pablo', 'lastname' => 'Longoria', 'roles' => ['ROLE_MANAGER'], 'password' => 'longoria'],
        ];

        foreach ($usersData as $data) {
            $user = new UserEntity();
            $user->setEmail($data['email']);
            $user->setFirstname($data['firstname']);
            $user->setLastname($data['lastname']);
            $user->setRoles($data['roles']);
            $user->setPassword($this->passwordHasher->hashPassword($user, $data['password']));

            $manager->persist($user);
        }

        $manager->flush();
    }
}
