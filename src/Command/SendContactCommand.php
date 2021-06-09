<?php

declare(strict_types=1);

namespace App\Command;

use App\Repository\ContactRepository;
use App\Repository\UserRepository;
use App\Service\ContactService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;

class SendContactCommand extends Command
{

    private $contactRepository;
    private $mailer;
    private $contactService;
    private $userRepository;
    protected static $defaultName = 'app:send-contact';



    /**
     *  __Construct method
     * @param $contactRepository
     * @param $mailer
     * @param $contactService
     * @param $userRepository
     */
    public function __construct(
        ContactRepository $contactRepository,
        MailerInterface $mailer,
        ContactService $contactService,
        UserRepository $userRepository
    ) {
        $this->contactRepository = $contactRepository;
        $this->mailer = $mailer;
        $this->contactService = $contactService;
        $this->userRepository = $userRepository;
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $toSend = $this->contactRepository->findBy(['isSend' => false]);
        $address = new Address($this->userRepository->findByUserAdmin()->getEmail(), $this->userRepository->findByUserAdmin()->getPseudo() . ' ' . $this->userRepository->findByUserAdmin()->getPseudo());
        foreach ($toSend as $mail) {
            $email = (new Email())
                ->from($mail->getEmail())
                ->to($address)
                ->subject('Nouveau message de ' . $mail->getNom())
                ->text($mail->getMessage());
            $this->mailer->send($email);
            $this->contactService->isSend($mail);
        }
        return Command::SUCCESS;
    }
}