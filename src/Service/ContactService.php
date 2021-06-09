<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Contact;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class ContactService
{
    private $manager;
    private $flash;


    /**
     *  __Construct method
     * @param $manager
     * @param $flash
     */
    public function __construct(EntityManagerInterface $manager, FlashBagInterface $flash)
    {
        $this->manager = $manager;
        $this->flash = $flash;
    }
    public function persistContact(Contact $contact): void
    {
        $contact->setIsSend(false);

        $this->manager->persist($contact);
        $this->manager->flush();
        $this->flash->add('success', 'votre message est bien envoyé, merci.');
    }
    public function isSend(Contact $contact): void
    {
        $contact->setIsSend(true);
        $this->manager->persist($contact);
        $this->manager->flush();
    }
}