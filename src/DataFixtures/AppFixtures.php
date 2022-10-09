<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct (UserPasswordHasherInterface $hasher){
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $admin = new User();
        $admin->setRoles(["ROLE_SUPER_ADMIN"]);
        $password = $this->hasher->hashPassword($admin, 'admin');
        $admin->setPassword($password);
        $admin->setPseudonymeDofus("Playden");
        $admin->setPseudonymeWebsite("LeZeller");
        $admin->setEmail("admin@admin.com");

        $manager->persist($admin);

        $trust = new User();
        $trust->setRoles(["ROLE_TRUST"]);
        $password = $this->hasher->hashPassword($trust, 'trust');
        $trust->setPassword($password);
        $trust->setPseudonymeDofus("Playden");
        $trust->setPseudonymeWebsite("LeZeller");
        $trust->setEmail("trust@trust.com");

        $manager->persist($trust);

        $user = new User();
        $user->setRoles(["ROLE_USER"]);
        $password = $this->hasher->hashPassword($user, 'user');
        $user->setPassword($password);
        $user->setPseudonymeDofus("Playden");
        $user->setPseudonymeWebsite("LeZeller");
        $user->setEmail("user@user.com");

        $manager->persist($user);

        $manager->flush();
    }
}
