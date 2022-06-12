<?php

namespace App\DataFixtures;


use Faker;
use App\Entity\User;
use App\Entity\Annonce;
use App\Entity\Candidature;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{

    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->faker = Faker\Factory::create('fr_FR');
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        // Fause donnée ADMIN
        for ($b = 0; $b <  1; $b++) {
            $user = new User();
            $user->setName($this->faker->name())
                ->setEmail($this->faker->email())
                ->setRoles(['ROLE_ADMIN']);

            $hashPassword = $this->hasher->hashPassword(
                $user,
                'password'
            );

            $user->setPassword($hashPassword);

            $manager->persist($user);
        }

        // Fause donnée CONSULTANT
        for ($c = 0; $c <  5; $c++) {
            $user = new User();
            $user->setName($this->faker->name())
                ->setEmail($this->faker->email())
                ->setRoles(['ROLE_CONSULTANT']);

            $hashPassword = $this->hasher->hashPassword(
                $user,
                'password'
            );

            $user->setPassword($hashPassword);

            $manager->persist($user);
        }


        // Fause donnée USER
        for ($i = 0; $i <  10; $i++) {
            $user = new User();
            $user->setName($this->faker->name())
                ->setEmail($this->faker->email())
                ->setRoles(['ROLE_USER']);

            $hashPassword = $this->hasher->hashPassword(
                $user,
                'password'
            );

            $user->setPassword($hashPassword);
            $manager->persist($user);

            
        }

        // FAUSSE DONNÉES RECRUTEURS ET ANNONCES
        for ($j = 0; $j <  10; $j++) {
            $user = new User();
            $user->setName($this->faker->name())
                ->setEmail($this->faker->email())
                ->setRoles(['ROLE_RECRUTEUR']);

            $hashPassword = $this->hasher->hashPassword(
                $user,
                'password'
            );

            $user->setPassword($hashPassword);

            // FAUSSE DONNÉES ANNONCES
            for ($a = 0; $a <  3; $a++) {
                $annonce = new Annonce();
                $annonce->setTitle($this->faker->word())
                    ->setDescription($this->faker->paragraph(2))
                    ->setSalaire($this->faker->numberBetween(1200, 2300))
                    ->setValide(false)
                    ->setAuteur($user);

                    $manager->persist($user);

                    $manager->persist($annonce);
            }

            for ($q = 0; $q <  3; $q++) {
                $annonce = new Annonce();
                $annonce->setTitle($this->faker->word())
                    ->setDescription($this->faker->paragraph(2))
                    ->setSalaire($this->faker->numberBetween(1200, 2300))
                    ->setValide(true)
                    ->setAuteur($user);

                    $manager->persist($user);

                    $manager->persist($annonce);
            }

            
        }


        $manager->flush();
    }
}
