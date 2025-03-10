<?php

namespace App\DataFixtures;

use App\Factory\AutorFactory;
use App\Factory\EditorialFactory;
use App\Factory\LibroFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        //$manager->flush();

        EditorialFactory::createMany(5);

        AutorFactory::createMany(10);

        LibroFactory::createMany(10, function () {
            return [
                'autores' => AutorFactory::randomRange(0, 3),
                'editorial' => EditorialFactory::random()
            ];
        });

        

        
    }
}
