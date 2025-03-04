<?php

namespace App\DataFixtures;

use App\Entity\CustomerEntity;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CustomerFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $customers = [
            [
                'firstname' => 'Valentin',
                'lastname' => 'Rongier',
                'email' => 'valentin.rongier@example.com',
                'phoneNumber' => '+33612345678',
                'address' => '10 Rue du Stade, Marseille, France',
            ],
            [
                'firstname' => 'Mason',
                'lastname' => 'Greenwood',
                'email' => 'mason.greenwood@example.com',
                'phoneNumber' => '+447712345678',
                'address' => '22 Manchester Street, London, UK',
            ],
            [
                'firstname' => 'Leonardo',
                'lastname' => 'Balerdi',
                'email' => 'leonardo.balerdi@example.com',
                'phoneNumber' => '+5491123456789',
                'address' => '55 Avenida Libertador, Buenos Aires, Argentina',
            ],
            [
                'firstname' => 'Geronimo',
                'lastname' => 'Rulli',
                'email' => 'geronimo.rulli@example.com',
                'phoneNumber' => '+5492212345678',
                'address' => '7 Calle Mayor, Madrid, Spain',
            ],
            [
                'firstname' => 'Ismael',
                'lastname' => 'Bennacer',
                'email' => 'ismael.bennacer@example.com',
                'phoneNumber' => '+213661234567',
                'address' => '40 Rue de la République, Alger, Algérie',
            ],
        ];

        foreach ($customers as $data) {
            $customer = new CustomerEntity();
            $customer->setFirstname($data['firstname']);
            $customer->setLastname($data['lastname']);
            $customer->setEmail($data['email']);
            $customer->setPhoneNumber($data['phoneNumber']);
            $customer->setAddress($data['address']);

            $manager->persist($customer);
        }

        $manager->flush();
    }
}
