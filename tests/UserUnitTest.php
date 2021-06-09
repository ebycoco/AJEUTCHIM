<?php
declare(strict_types=1);
namespace App\Tests;

use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserUnitTest extends TestCase
{
    public function testIsTrue()
    {
        $user = new User();
        $user->setEmail('test@gmail.com')
            ->setPrenom('prenom')
            ->setNom('nom')
            ->setContact('contact')
            ->setMatricule('matricule')
            ->setPseudo('pseudo')
            ->setVille('ville')
            ->setPassword('password')
            ->setProfession('profession');

        $this->assertTrue($user->getEmail() === 'test@gmail.com');
        $this->assertTrue($user->getPrenom() === 'prenom');
        $this->assertTrue($user->getNom() === 'nom');
        $this->assertTrue($user->getContact() === 'contact');
        $this->assertTrue($user->getMatricule() === 'matricule');
        $this->assertTrue($user->getVille() === 'ville');
        $this->assertTrue($user->getPassword() === 'password');
        $this->assertTrue($user->getProfession() === 'profession');
    }

    public function testIsFalse()
    {
        $user = new User();
        $user->setEmail('test@gmail.com')
            ->setPrenom('prenom')
            ->setNom('nom')
            ->setContact('contact')
            ->setMatricule('matricule')
            ->setPseudo('pseudo')
            ->setVille('ville')
            ->setPassword('password')
            ->setProfession('profession');

        $this->assertFalse($user->getEmail() === 'false@gmail.com');
        $this->assertFalse($user->getPrenom() === 'false');
        $this->assertFalse($user->getNom() === 'false');
        $this->assertFalse($user->getContact() === 'false');
        $this->assertFalse($user->getMatricule() === 'false');
        $this->assertFalse($user->getVille() === 'false');
        $this->assertFalse($user->getPassword() === 'false');
        $this->assertFalse($user->getProfession() === 'false');
    }

    public function testIsEmpty()
    {
        $user = new User();

        $this->assertEmpty($user->getEmail());
        $this->assertEmpty($user->getPrenom());
        $this->assertEmpty($user->getNom());
        $this->assertEmpty($user->getContact());
        $this->assertEmpty($user->getMatricule());
        $this->assertEmpty($user->getVille());
        $this->assertEmpty($user->getPassword());
        $this->assertEmpty($user->getProfession());
    }
}