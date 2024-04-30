<?php

namespace App\DataFixtures;

use App\Entity\Balance;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setEmail('test@vireye.com');
        $user->setPassword('hashed_password_here');
        $user->setRoles(['ROLE_USER']);

        $manager->persist($user);

        $data = [
            ['USDT', '5000.000'],
            ['Gold', '50.000'],
            ['USDT', '1500.000'],
            ['Gold', '20.000'],
            ['USDT', '2500.000'],
            ['Gold', '35.000'],
            ['USDT', '1200.000'],
            ['Gold', '45.000'],
            ['USDT', '3000.000'],
            ['Gold', '25.000']
        ];


        foreach ($data as $item) {
            $balance = new Balance();
            $balance->setCurrency($item[0]);
            $balance->setAmount($item[1]);
            $balance->setUser($user);
            $manager->persist($balance);
        }


        $manager->flush();
    }
}
