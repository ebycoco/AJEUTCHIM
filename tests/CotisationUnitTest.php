<?php
declare(strict_types=1);
namespace App\Tests;

use App\Entity\Cotisation;
use App\Entity\User;
use DateTime;
use PHPUnit\Framework\TestCase;

class CotisationUnitTest extends TestCase
{
    public function testIsTrue()
    {
        $cotisation = new Cotisation();
        $user = new User();
        $datetime = new DateTime();
        $cotisation->setMontant(100.10)
            ->setAnnee($datetime)
            ->setMontantTotalPaye(10.10)
            ->setStatus(0)
            ->setNeplus(false)
            ->setAn('2021')
            ->setCouleurProfile('couleur')
            ->setResteMontant(0.0);

        $this->assertTrue($cotisation->getMontant() === 100.10);
        $this->assertTrue($cotisation->getAnnee() === $datetime);
        $this->assertTrue($cotisation->getMontantTotalPaye() === 10.10);
        $this->assertTrue($cotisation->getStatus() === 0);
        $this->assertTrue($cotisation->getNeplus() === false);
        $this->assertTrue($cotisation->getAn() === '2021');
        $this->assertTrue($cotisation->getCouleurProfile() === 'couleur');
        $this->assertTrue($cotisation->getResteMontant() === 0.0);
    }

    public function testIsFalse()
    {
        $cotisation = new Cotisation();
        $datetime = new DateTime();
        $cotisation->setMontant(100.10)
            ->setAnnee($datetime)
            ->setMontantTotalPaye(10.10)
            ->setStatus(0)
            ->setNeplus(false)
            ->setAn('2021')
            ->setCouleurProfile('couleur')
            ->setResteMontant(0.0);

        $this->assertFalse($cotisation->getMontant() === 200.20);
        $this->assertFalse($cotisation->getAnnee() === new DateTime());
        $this->assertFalse($cotisation->getMontantTotalPaye() === 10.20);
        $this->assertFalse($cotisation->getStatus() === 1);
        $this->assertFalse($cotisation->getNeplus() === true);
        $this->assertFalse($cotisation->getAn() === 'false');
        $this->assertFalse($cotisation->getCouleurProfile() === 'false');
        $this->assertFalse($cotisation->getResteMontant() === 10.02);
    }

    public function testIsEmpty()
    {
        $cotisation = new Cotisation();

        $this->assertEmpty($cotisation->getMontant());
        $this->assertEmpty($cotisation->getAnnee());
        $this->assertEmpty($cotisation->getMontantTotalPaye());
        $this->assertEmpty($cotisation->getStatus());
        $this->assertEmpty($cotisation->getNeplus());
        $this->assertEmpty($cotisation->getAn());
        $this->assertEmpty($cotisation->getCouleurProfile());
        $this->assertEmpty($cotisation->getResteMontant());
    }
}