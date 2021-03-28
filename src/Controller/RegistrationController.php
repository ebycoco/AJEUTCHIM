<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\DesactiveRepository;
use App\Repository\MembreRepository;
use App\Security\EmailVerifier;
use App\Security\UsersAuthenticator;
use App\Repository\UserRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AbstractController
{
    private $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }

    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, UsersAuthenticator $authenticator, MembreRepository $membreRepository, UserRepository $userRepository, DesactiveRepository $desactiveRepository): Response
    {
        $desactive = $desactiveRepository->findAll();
        if ($desactive == null) {
            $etat = false;
        } else {
            $etat = $desactive[count($desactive) - 1]->getEtat();
        }

        // voir si le d'inscription est desactive
        if ($etat == false) {
            $this->addFlash('warning', 'Le lien est desactive !');
            return $this->redirectToRoute('app_home');
        }
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager = $this->getDoctrine()->getManager();
            $membre = $membreRepository->findAll();
            $users = $userRepository->findAll();
            $voire = $form->get('matricule')->getData();

            $peut = null;
            for ($i = 0; $i < count($membre); $i++) {
                $matricul = $membre[$i]->getReferenceAjeutchim();
                if ($voire == $matricul) {
                    $peut = $matricul;
                }
            }

            $peutvoir = null;
            for ($i = 0; $i < count($users); $i++) {
                $matricule = $users[$i]->getMatricule();
                if ($voire == $matricul) {
                    $peutvoir = $matricule;
                }
            }
            if (count($membre) > 0 and !$peut) {
                $this->addFlash('warning', 'Votre matricule est invalid !');
                return $this->redirectToRoute('app_register');
            }
            if (count($membre) > 0 and $voire == null) {
                $this->addFlash('warning', 'Votre matricule SVP !');
                return $this->redirectToRoute('app_register');
            }
            if (count($membre) > 0 and $peut == $peutvoir) {
                $this->addFlash('warning', 'Votre matricule existe déja !');
                return $this->redirectToRoute('app_register');
            }

            $entityManager->persist($user);
            $entityManager->flush();

            // generate a signed url and email it to the user
            $this->emailVerifier->sendEmailConfirmation(
                'app_verify_email',
                $user,
                (new TemplatedEmail())
                    ->from(new Address('ajeutchim@gmail.com', 'AJEUTCHIM service'))
                    ->to($user->getEmail())
                    ->subject('Please Confirm your Email')
                    ->htmlTemplate('registration/confirmation_email.html.twig')
            );
            // do anything else you need here, like send an email

            return $guardHandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $authenticator,
                'main' // firewall name in security.yaml
            );
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/verify/email", name="app_verify_email")
     */
    public function verifyUserEmail(Request $request, UserRepository $userRepository): Response
    {
        $id = $request->get('id');

        if (null === $id) {
            return $this->redirectToRoute('app_register');
        }

        $user = $userRepository->find($id);

        if (null === $user) {
            return $this->redirectToRoute('app_register');
        }

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $user);
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $exception->getReason());

            return $this->redirectToRoute('app_register');
        }

        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        $this->addFlash('success', 'Your email address has been verified.');

        return $this->redirectToRoute('admin_index');
    }
}