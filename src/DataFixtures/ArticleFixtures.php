<?php

namespace App\DataFixtures;

use App\Entity\Adhesion;
use App\Entity\Ajeutchim;
use App\Entity\Annee;
use App\Entity\Apropos;
use App\Entity\Comment;
use App\Entity\Article;
use App\Entity\Evenementrealiser;
use App\Entity\Flash;
use App\Entity\ImageAccueil;
use App\Entity\Membre;
use App\Entity\MontantAnnuelle;
use App\Entity\PostAjeutchim;
use App\Entity\President;
use App\Entity\Video;
use App\Entity\Village;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

class ArticleFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');
        for ($an=0; $an < 9; $an++) {  
            $annee=new Annee();
            $annee->setAnnee("202".$an); 
            $manager->persist($annee);
        }
        for ($adhesionn=1; $adhesionn < 2; $adhesionn++) {
            $user = $this->getReference('user_' . $faker->numberBetween(1, 30));  
            $adhesion=New Adhesion();
            $adhesion->setMontant("500"); 
            $adhesion->setUser($user); 
            $manager->persist($adhesion);
        }
        for ($ac=1; $ac < 2; $ac++) {
            $user = $this->getReference('user_' . $faker->numberBetween(1, 30));  
            $montanta=new MontantAnnuelle();
            $montanta->setMontant("5000"); 
            $montanta->setUser($user); 
            $manager->persist($montanta);
        }
        for ($i = 1; $i <= 30; $i++) {
            $user = $this->getReference('user_' . $faker->numberBetween(1, 30));
            $categorie = $this->getReference('categorie_' . $faker->numberBetween(1, 5));

            $article = new Article();
            $article->setTitle($faker->sentence());
            $article->setUser($user);
            $article->setCategorie($categorie);
            $article->setDescription($faker->realText(100));
            $article->setContent($faker->realText(1000));
            $article->setActive($faker->numberBetween(0, 1));
            $article->setImageName($faker->imageUrl());
            $manager->persist($article);
            for ($j = 1; $j <= rand(5, 25); $j++) {
                $comment = new Comment();
                $comment->setContent($faker->realText(mt_rand(20, 500)));
                $comment->setAuthor($faker->name);
                $days = (new \DateTime())->diff($article->getPublishedAt())->days;
                $comment->setPostedAt($faker->dateTimeBetween('-' . $days . ' days'));
                $comment->setArticle($article);
                $manager->persist($comment);
            }
        }
        for ($a = 1; $a <= 1; $a++) {
            $ajeutchim = new Ajeutchim();
            $user = $this->getReference('user_' . $faker->numberBetween(1, 30));
            $ajeutchim->setContenue($faker->realText(2000));
            $ajeutchim->setImageName($faker->imageUrl());
            $ajeutchim->setUser($user);
            $manager->persist($ajeutchim);
        }
        for ($a = 1; $a <= 1; $a++) {
            $user = $this->getReference('user_' . $faker->numberBetween(1, 30));
            $village = new Village();
            $village->setHistoire($faker->realText(2000));
            $village->setDescription($faker->realText(50));
            $village->setImageName($faker->imageUrl());
            $village->setUser($user);
            $manager->persist($village);
        }
        for ($k = 1; $k <= 1; $k++) {
            $apropos = new Apropos();
            $user = $this->getReference('user_' . $faker->numberBetween(1, 30));
            $apropos->setContenue($faker->realText(3000));
            $apropos->setUser($user);
            $manager->persist($apropos);
        }
        for ($m = 1; $m <= 1; $m++) {
            $user = $this->getReference('user_' . $faker->numberBetween(1, 30));
            $flash = new Flash();
            $flash->setContent($faker->text);
            $flash->setUser($user);
            $manager->persist($flash);
        }


        for ($n = 1; $n <= 5; $n++) {
            $user = $this->getReference('user_' . $faker->numberBetween(1, 30));
            $evenementrealiser = new Evenementrealiser();
            $evenementrealiser->setTitle($faker->sentence());
            $evenementrealiser->setDescription($faker->realText(50));
            $evenementrealiser->setImageName($faker->imageUrl());
            $evenementrealiser->setUser($user);
            $evenementrealiser->setEventedAt($faker->dateTimeBetween('-6 months'));

            $manager->persist($evenementrealiser);
        }
        for ($b = 1; $b <= 5; $b++) {
            $user = $this->getReference('user_' . $faker->numberBetween(1, 30));
            $video = new Video();
            $video->setTitle($faker->sentence());
            $video->setImageName($faker->company);
            $video->setLien($faker->realText(20));
            $video->setUser($user);
            $manager->persist($video);
        }

        for ($r = 1; $r <= 5; $r++) {
            $user = $this->getReference('user_' . $faker->numberBetween(1, 30));
            $imageAccueil = new ImageAccueil();
            $imageAccueil->setTitle($faker->sentence());
            $imageAccueil->setDescription($faker->realText(50));
            $imageAccueil->setImageName($faker->imageUrl());
            $imageAccueil->setUser($user);
            $manager->persist($imageAccueil);
        }

        for ($nbAdhesion = 1; $nbAdhesion <= 1; $nbAdhesion++) {
            $user = $this->getReference('user_' . $faker->numberBetween(1, 30));
            $adhesion = new Adhesion();
            $adhesion->setMontant(500);
            $adhesion->setUser($user);
            $manager->persist($adhesion);
            for ($nbmembre = 1; $nbmembre <= 30; $nbmembre++) {
                $user = $this->getReference('user_' . $faker->numberBetween(1, 30));
                $membre = new Membre();
                $membre->setNom($faker->lastName);
                $membre->setPrenom($faker->firstName);
                $membre->setVille($faker->country);
                $membre->setContact($faker->e164PhoneNumber);
                $membre->setProfession($faker->company);
                $membre->setEmail($faker->email);
                $membre->setAnnee($faker->numberBetween(2020, 2021));
                $membre->setReferenceAjeutchim('AJEUT' . mt_rand(99, 999) . 'CHIM');
                $membre->setUser($user);
                $membre->setAdhesion(500);
                $manager->persist($membre);
                $this->setReference('membre_' . $nbmembre, $membre);
            }
        }
        for ($l = 1; $l <= 5; $l++) {
            $user = $this->getReference('user_' . $faker->numberBetween(1, 30));
            $membre = $this->getReference('membre_' . $faker->numberBetween(1, 10));
            $president = new President();
            $president->setMembre($membre);
            $president->setImageName($faker->imageUrl());
            $president->setContenue($faker->text);
            $president->setUser($user);
            $p = $faker->dateTimeBetween('-' . mt_rand(1, 365) . ' days');
            $president->setDebutedAt($p);
            $president->setEtat(1);
            $days = (new \DateTime())->diff($p)->days;
            $president->setFinedAt($faker->dateTimeBetween('-' . $days . ' days'));
            $manager->persist($president);
        }

        for ($nbpostAjeutchim = 1; $nbpostAjeutchim <= 33; $nbpostAjeutchim++) {
            $user = $this->getReference('user_' . $faker->numberBetween(1, 30));
            $postAjeutchim = new PostAjeutchim();
            if ($nbpostAjeutchim == 33) {
                $postAjeutchim->setName("Parrain");
            } elseif ($nbpostAjeutchim == 1) {
                $postAjeutchim->setName("Président");
            } elseif ($nbpostAjeutchim == 2) {
                $postAjeutchim->setName("Vice-présidente");
            } elseif ($nbpostAjeutchim == 3) {
                $postAjeutchim->setName("Secrétaire général");
            } elseif ($nbpostAjeutchim == 4) {
                $postAjeutchim->setName("Secrétaire général adjoint");
            } elseif ($nbpostAjeutchim == 5) {
                $postAjeutchim->setName("Secrétaire chargé a l'organisation");
            } elseif ($nbpostAjeutchim == 6) {
                $postAjeutchim->setName("Secrétaire chargé a l'organisation adjoint 1");
            } elseif ($nbpostAjeutchim == 7) {
                $postAjeutchim->setName("Secrétaire chargé a l'organisation adjoint 2");
            } elseif ($nbpostAjeutchim == 8) {
                $postAjeutchim->setName("Trésorier");
            } elseif ($nbpostAjeutchim == 9) {
                $postAjeutchim->setName("Trésorier adjoint");
            } elseif ($nbpostAjeutchim == 10) {
                $postAjeutchim->setName("Secrétaire chargé aux affaires exterieures ");
            } elseif ($nbpostAjeutchim == 11) {
                $postAjeutchim->setName("Secrétaire chargé aux affaires exterieures adjoint");
            } elseif ($nbpostAjeutchim == 12) {
                $postAjeutchim->setName("Secrétaire chargé a l'information et à la communication");
            } elseif ($nbpostAjeutchim == 13) {
                $postAjeutchim->setName("Secrétaire chargé a l'information et à la communication adjoint");
            } elseif ($nbpostAjeutchim == 14) {
                $postAjeutchim->setName("Secrétaire chargé aux affaires sociales");
            } elseif ($nbpostAjeutchim == 15) {
                $postAjeutchim->setName("Secrétaire chargé aux affaires sociales adjoint");
            } elseif ($nbpostAjeutchim == 16) {
                $postAjeutchim->setName("Secrétaire chargé aux affaires culturelles");
            } elseif ($nbpostAjeutchim == 17) {
                $postAjeutchim->setName("Secrétaire chargé aux affaires culturelles adjoint 1");
            } elseif ($nbpostAjeutchim == 18) {
                $postAjeutchim->setName("Secrétaire chargé aux affaires culturelles adjoint 2");
            } elseif ($nbpostAjeutchim == 19) {
                $postAjeutchim->setName("Responsable des femmes");
            } elseif ($nbpostAjeutchim == 20) {
                $postAjeutchim->setName("Responsable des femmes adjoint 1");
            } elseif ($nbpostAjeutchim == 21) {
                $postAjeutchim->setName("Responsable des femmes adjoint 2");
            } elseif ($nbpostAjeutchim == 22) {
                $postAjeutchim->setName("Maître de séance");
            } elseif ($nbpostAjeutchim == 23) {
                $postAjeutchim->setName("Maître de séance adjoint");
            } elseif ($nbpostAjeutchim == 24) {
                $postAjeutchim->setName("Commissaire au compte");
            } elseif ($nbpostAjeutchim == 25) {
                $postAjeutchim->setName("Commissaire au compte adjoint");
            } elseif ($nbpostAjeutchim == 26) {
                $postAjeutchim->setName("Secrétaire chargé de la relation avec la jeunesse rurale");
            } elseif ($nbpostAjeutchim == 27) {
                $postAjeutchim->setName("Secrétaire chargé de la relation avec la jeunesse rurale adjoint");
            } elseif ($nbpostAjeutchim == 28) {
                $postAjeutchim->setName("Secrétaire chargé des études adjoint");
            } elseif ($nbpostAjeutchim == 29) {
                $postAjeutchim->setName("Secrétaire chargé des études");
            } elseif ($nbpostAjeutchim == 30) {
                $postAjeutchim->setName("Secrétaire chargé de l'entrepreneuriat");
            } elseif ($nbpostAjeutchim == 31) {
                $postAjeutchim->setName("Secrétaire chargé de l'entrepreneuriat adjoint");
            } elseif ($nbpostAjeutchim == 32) {
                $postAjeutchim->setName("Conseillers");
            }
            $postAjeutchim->setDescription($faker->realText(50));
            $postAjeutchim->setUser($user);
            $manager->persist($postAjeutchim);
        }
        
        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
    public function getDependencies()
    {
        return [
            UserFixtures::class,
            CategorieFixtures::class
        ];
    }
}