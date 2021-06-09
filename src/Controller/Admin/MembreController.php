<?php

namespace App\Controller\Admin;

use App\Entity\Bilan;
use App\Entity\Membre;
use App\Entity\User;
use App\Form\MembreType;
use App\Repository\BilanRepository;
use App\Repository\MembreRepository;
use App\Repository\UserRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Constraints\Length;

/**
 * @Route("/admin/membre")
 */
class MembreController extends AbstractController
{
    /**
     * @Route("/", name="membre_index", methods={"GET"})
     */
    public function index(MembreRepository $membreRepository, UserRepository $users): Response
    {
        return $this->render('admin/membre/index.html.twig', [
            'membres' => $membreRepository->findMembre(),
            'users' => $users->findByUser(),
            'derniermembres' => $membreRepository->dernierMembreAjouter(),
        ]);
    }

    /**
     * @Route("/new", name="membre_new", methods={"GET","POST"})
     */
    public function new(Request $request, BilanRepository $bilanRepository, UserPasswordEncoderInterface $passwordEncoder, UserRepository $userRepository): Response
    {
        $membre = new Membre();
        $bilan = new Bilan();
        $user = new User();
        $form = $this->createForm(MembreType::class, $membre);
        $form->handleRequest($request);
        $jouj = new DateTime('now');
        $annee = $jouj->format(date('Y'));

        if ($form->isSubmitted() && $form->isValid()) {
            $email = "Ajeutchim" . mt_rand(1, 999) . "@gmail.com";
            $users = $userRepository->findAll();
            for ($i = 0; $i < count($users); $i++) {
                $emailbase = $users[$i]->getEmail();
                if ($email == $emailbase) {
                    $emailentre = "Ajeutchim" . mt_rand(1, 999) . "@gmail.com";
                }
            }
            $entityManager = $this->getDoctrine()->getManager();
            $membre->setReferenceAjeutchim('AJEUT' . mt_rand(99, 999) . 'CHIM');
            $membre->setAnnee($annee);
            $membre->setEmail($email);
            $membre->setAdhesion(500);
            $membre->setUser($this->getUser());
            $entityManager->persist($membre);
            $pass = "123456";
            $user->setEmail($email);
            $user->setMembre($membre);
            $user->setNom($form->get('nom')->getData());
            $user->setPrenom($form->get('prenom')->getData());
            $user->setVille($form->get('ville')->getData());
            $user->setContact($form->get('contact')->getData());
            $user->setProfession($form->get('profession')->getData());
            $user->setRoles(['ROLE_MEMBRE']);
            $user->setIsVerified(1);
            $user->setMatricule($form->getData()->getReferenceAjeutchim());
            $mot = explode(" ", $form->getData()->getPrenom());
            $pseudo = $mot[count($mot) - 1];
            $user->setPseudo($pseudo . mt_rand(10, 99));
            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $pass
                )
            );
            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash('success', ' Un nouveau membre a été ajouter, avec pour matricule ' . $membre->getReferenceAjeutchim());
            //on insert dans la table bilan 
            if ($bilanRepository->findAll() == null) {

                $bilan->setAdhesion(500);
                $bilan->setAnnee($annee);
                $entityManager->persist($bilan);
                $entityManager->flush();
            } else {
                $nombreBilan = $bilanRepository->findAll();
                $dernierAnnee = $nombreBilan[count($nombreBilan) - 1]->getAnnee();
                if ($dernierAnnee == $annee) {
                    $IdPass = $nombreBilan[count($nombreBilan) - 1]->getId();
                    return $this->redirectToRoute('bilan_ajourA', ['id' => $IdPass]);
                } else {
                    $bilan->setAdhesion(500);
                    $bilan->setAnnee($annee);
                    $entityManager->persist($bilan);
                    $entityManager->flush();
                }
            }
            return $this->redirectToRoute('membre_index');
        }

        return $this->render('admin/membre/new.html.twig', [
            'membre' => $membre,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="membre_show", methods={"GET"})
     */
    public function show(Membre $membre): Response
    {
        return $this->render('admin/membre/show.html.twig', [
            'membre' => $membre,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="membre_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Membre $membre): Response
    {
        $form = $this->createForm(MembreType::class, $membre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('membre_index');
        }

        return $this->render('admin/membre/edit.html.twig', [
            'membre' => $membre,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="membre_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Membre $membre): Response
    {
        if ($this->isCsrfTokenValid('delete' . $membre->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($membre);
            $entityManager->flush();
        }

        return $this->redirectToRoute('membre_index');
    }
}