<?php
declare(strict_types=1);

namespace App\Tests;

use App\Entity\Categorie;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class CategorieUnitTest extends TestCase
{
    public function testIsTrue()
    {
        $categorie = new Categorie();
        $user = new User();
        $categorie->setName('nom')
            ->setSlug('slug')
            ->setUser($user);

        $this->assertTrue($categorie->getName() === 'nom');
        $this->assertTrue($categorie->getSlug() === 'slug');
        $this->assertTrue($categorie->getUser() === $user);
    }

    public function testIsFalse()
    {
        $categorie = new Categorie();
        $user = new User();
        $categorie->setName('nom')
            ->setSlug('slug')
            ->setUser($user);

        $this->assertFalse($categorie->getName() === 'false');
        $this->assertFalse($categorie->getSlug() === 'false');
        $this->assertFalse($categorie->getUser() === new User());
    }

    public function testIsEmpty()
    {
        $categorie = new Categorie();

        $this->assertEmpty($categorie->getName());
        $this->assertEmpty($categorie->getSlug());
        $this->assertEmpty($categorie->getUser());
    }
}