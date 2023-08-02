<?php

namespace App\DataFixtures;

use App\Entity\Client;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Faker\Factory;
use Faker\Generator;

class AppFixtures extends Fixture
{
    private Generator $faker;
    
  

    public function __construct() {
        $this->faker =  Factory::create('fr_FR');
       
    }
    public function load(ObjectManager $manager): void
    {
        

        for ($i=0; $i < 20 ; $i++) { 
           
           $Client = new Client();
           $Client->setNom($this->faker->name())
           ->setEmail($this->faker->email())
           ->setSexe(mt_rand(0,1) === 1 ? 'M' : 'F')
           ->setAdresse($this->faker->address())
           ->setStatut(mt_rand(0,1) === 1 ? true : false)
           ->setTel(mt_rand(770000000,780000000));
           
           $manager->persist($Client);
        }
       

        $manager->flush();
    }
}
