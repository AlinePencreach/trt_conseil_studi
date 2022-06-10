<?php

namespace App\DataFixtures;


use Faker;
use App\Entity\User;
use App\Entity\Annonce;
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
        for ($i=0; $i <  10; $i++) { 
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


        for ($i=0; $i <  10; $i++) { 
            $annonce = new Annonce();
            $annonce->setTitle($this->faker->word())
                    ->setDescription($this->faker->paragraph(2))
                    ->setSalaire($this->faker->numberBetween(1200, 2300));

            
                $manager->persist($annonce);
        }

    
        $manager->flush();
    }
}
