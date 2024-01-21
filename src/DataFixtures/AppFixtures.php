<?php

namespace App\DataFixtures;

use App\Entity\Monitor;
use App\Entity\Resource;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private ?User $adminUser = null;
    private UserPasswordHasherInterface $hashed;

    public function __construct (UserPasswordHasherInterface $hashed){
        $this->hashed = $hashed;
    }

    public function load(ObjectManager $manager): void
    {
        $this->createUsers($manager);
        $resources = $this->loadResources($manager, 1, 10);
        $this->createMonitors($manager, $resources);

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
        $this->adminUser = $admin;

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

    private function loadResources(ObjectManager $manager, int $min, int $max): array
    {
        $resourceRepository = $manager->getRepository(Resource::class);
        return $resourceRepository->findBy(['id' => range($min, $max)]);
    }

    private function createMonitors(ObjectManager $manager, array $resources): void
    {
        $faker = Factory::create();
        $totalMonitors = 60; // Nombre total de moniteurs à créer
        $monitorsToday = 10; // Nombre de moniteurs à créer pour la date d'aujourd'hui
        $currentDate = new \DateTimeImmutable();

        foreach ($resources as $resource) {
            // Répartissez d'abord les moniteurs sur les 30 derniers jours
            $interval = 60 / ($totalMonitors - $monitorsToday);

            for ($i = 0; $i < $totalMonitors - $monitorsToday; $i++) {
                $creationDate = $currentDate->modify("-" . round($interval * $i) . " days");
                $monitor = new Monitor();
                $monitor->setUser($this->adminUser);
                $monitor->setResource($resource);
                $monitor->setPricePer1($faker->randomFloat(0, 1, 10));
                $monitor->setPricePer10($faker->randomFloat(0, 800, 1000));
                $monitor->setPricePer100($faker->randomFloat(0, 9500, 10000));
                $monitor->setCreatedAt($creationDate);
                $manager->persist($monitor);
            }
        }
    }
}
