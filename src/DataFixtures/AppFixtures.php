<?php

namespace App\DataFixtures;

use App\Entity\Monitor;
use App\Entity\Resource;
use App\Entity\Stock;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $hashed;

    public function __construct (UserPasswordHasherInterface $hashed){
        $this->hashed = $hashed;
    }

    public function load(ObjectManager $manager): void
    {
        $this->createUsers($manager);
        $manager->flush();
    }

    public function createUsers($manager): void
    {
        $admin = new User();
        $admin->setRoles(["ROLE_SUPER_ADMIN"]);
        $password = $this->hashed->hashPassword($admin, 'admin');
        $admin->setPassword($password);
        $admin->setPseudonymeDofus("Playden");
        $admin->setPseudonymeWebsite("LeZeller");
        $admin->setEmail("admin@admin.com");

        $manager->persist($admin);
        $this->users[] = $admin;

        $trust = new User();
        $trust->setRoles(["ROLE_TRUST"]);
        $password = $this->hashed->hashPassword($trust, 'trust');
        $trust->setPassword($password);
        $trust->setPseudonymeDofus("TrustDofus");
        $trust->setPseudonymeWebsite("TrustSite");
        $trust->setEmail("trust@trust.com");

        $manager->persist($trust);
        $this->users[] = $trust;

        $user = new User();
        $user->setRoles(["ROLE_USER"]);
        $password = $this->hashed->hashPassword($user, 'user');
        $user->setPassword($password);
        $user->setPseudonymeDofus("UserDofus");
        $user->setPseudonymeWebsite("UserSite");
        $user->setEmail("user@user.com");

        $manager->persist($user);
        $this->users[] = $user;
    }
}
