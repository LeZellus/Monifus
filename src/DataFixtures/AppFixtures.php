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
    private Generator $faker;
    private UserPasswordHasherInterface $hasher;
    private $users;
    private $resources;
    private $stocks;

    public function __construct (UserPasswordHasherInterface $hasher){
        $this->hasher = $hasher;
        $this->faker = Factory::create("fr_FR");
    }

    public function load(ObjectManager $manager): void
    {
        $this->createUsers($manager);
        $this->createStocks($manager);
        $this->createResources($manager);
        $this->createMonitors($manager);

        $manager->flush();
    }

    public function createUsers($manager) {
        $admin = new User();
        $admin->setRoles(["ROLE_SUPER_ADMIN"]);
        $password = $this->hasher->hashPassword($admin, 'admin');
        $admin->setPassword($password);
        $admin->setPseudonymeDofus("Playden");
        $admin->setPseudonymeWebsite("LeZeller");
        $admin->setEmail("admin@admin.com");

        $manager->persist($admin);
        $user[] = $admin;

        $trust = new User();
        $trust->setRoles(["ROLE_TRUST"]);
        $password = $this->hasher->hashPassword($trust, 'trust');
        $trust->setPassword($password);
        $trust->setPseudonymeDofus("TrustDofus");
        $trust->setPseudonymeWebsite("TrustSite");
        $trust->setEmail("trust@trust.com");

        $manager->persist($trust);
        $user[] = $trust;

        $user = new User();
        $user->setRoles(["ROLE_USER"]);
        $password = $this->hasher->hashPassword($user, 'user');
        $user->setPassword($password);
        $user->setPseudonymeDofus("UserDofus");
        $user->setPseudonymeWebsite("UserSite");
        $user->setEmail("user@user.com");

        $manager->persist($user);
        $this->users[] = $user;
    }

    public function createStocks($manager) {
        $quantities = [1, 10, 100];

        for($i = 0; $i <= 2; $i++) {
            $stock = new Stock();
            $stock->setQuantity($quantities[$i]);
            $manager->persist($stock);

            $this->stocks[] = $stock;
        }
    }

    public function createResources($manager) {
        for($i = 0; $i < 50; $i++) {
            $resource = new Resource();
            $resource->setAnkamaId(rand(1,1500));
            $resource->setLevel(rand(1, 200));
            $resource->setName($this->faker->word());
            $resource->setDescription($this->faker->sentence());
            $resource->setIsImportant($this->faker->boolean());
            $resource->setImgUrl("https://images.frandroid.com/wp-content/uploads/2020/06/dofus-touch-une.jpg");

            $this->resources[] = $resource;
            $manager->persist($resource);
        }
    }

    public function createMonitors($manager) {
        for ($i = 0; $i < 500; $i++) {
            $user = $this->users[array_rand($this->users)];
            $resource = $this->resources[array_rand($this->resources)];
            $stock = $this->stocks[array_rand($this->stocks)];


            $monitor = new Monitor();
            $monitor->setPrice(rand(1, 100000000));
            $monitor->setResource($resource);
            $monitor->setStock($stock);
            $monitor->setUser($user);

            $manager->persist($monitor);
        }
    }
}
