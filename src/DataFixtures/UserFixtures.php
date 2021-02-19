<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Faker;

class UserFixtures extends Fixture
{

    /**
     * @var UserPasswordEncoderInterface
     */
    private UserPasswordEncoderInterface $userPasswordEncoder;

    public function __construct(UserPasswordEncoderInterface $userPasswordEncoder)
    {
        $this->userPasswordEncoder = $userPasswordEncoder;
    }

    /**
     * Undocumented function
     *
     * @param ObjectManager $manager
     * @return void
     */
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');
        for ($nbUsers = 1; $nbUsers <= 30; $nbUsers++) {
            $user = new User();
            if ($nbUsers === 1) {
                $user->setEmail("brouyaoeric7@gmail.com");
                $user->setRoles(['ROLE_ADMIN']);
                $user->setIsVerified(1);
                $user->setPseudo("Ebychoco");
            } else {
                $user->setEmail($faker->email);
                $user->setRoles(['ROLE_USER']);
                $user->setIsVerified($faker->numberBetween(0, 1));
                $user->setPseudo($faker->name);
            }
            $user->setPassword($this->userPasswordEncoder->encodePassword($user, "123456"));
            $manager->persist($user);
            $this->setReference('user_' . $nbUsers, $user);
        }
        $manager->flush();
    }
}