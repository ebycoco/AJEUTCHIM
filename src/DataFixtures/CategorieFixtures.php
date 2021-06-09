<?php

namespace App\DataFixtures;

use App\Entity\Categorie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class CategorieFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');
        for ($nbCategorie = 1; $nbCategorie <= 5; $nbCategorie++) {
            $user = $this->getReference('user_' . $faker->numberBetween(1, 1));
            $categorie = new Categorie();
            $categorie->setName($faker->realText(10));
            $categorie->setSlug($faker->realText(50));
            $categorie->setCouleur("#e9e018");
            $categorie->setUser($user);
            $manager->persist($categorie);
            $this->setReference('categorie_' . $nbCategorie, $categorie);
        }
        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}