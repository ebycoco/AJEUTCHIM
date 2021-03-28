<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\EditUserType;
use App\Entity\MediaUtilisateur;
use App\Repository\DonRepository;
use App\Form\MediaUtilisateurType;
use App\Repository\UserRepository;
use App\Form\EditUserConnecterType;
use App\Repository\MembreRepository;
use App\Repository\AdhesionRepository;
use App\Repository\VersementRepository;
use App\Repository\CotisationRepository;
use App\Repository\DecaisementRepository;
use App\Repository\AutredepenseRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\MediaUtilisateurRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/admin",name="admin_")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     *
     * @return Response
     */
    public function index(CotisationRepository $cotisationRepository, MembreRepository $membreRepository, DecaisementRepository $decaisementRepository, VersementRepository $versementRepository, DonRepository $donRepository, AutredepenseRepository $autredepenseRepository, AdhesionRepository $adhesionRepository): Response
    {
        $montantTotal = $cotisationRepository->findCotisation();
        $don = $donRepository->findAllDon();
        $depenseSanFrais = $decaisementRepository->findAll();
        $versement = $versementRepository->findAll();
        $autredepense = $autredepenseRepository->findAll();
        $membre = $membreRepository->findAll();
        $adhesion = $adhesionRepository->findAll();
        $adhesionAnnuelle = $adhesion[count($adhesion) - 1]->getMontant();
        $montantAdhesion = count($membre) * $adhesionAnnuelle;
        $mont = 0;
        for ($i = 0; $i < count($montantTotal); $i++) {
            $calculmontant = $montantTotal[$i]->getMontantTotalPaye();
            $mont = $mont + $calculmontant;
        }
        $donpersonnelle = 0;
        for ($i = 0; $i < count($don); $i++) {
            $calculdon = $don[$i]->getMontantTotalPaye();
            $donpersonnelle = $donpersonnelle + $calculdon;
        }
        $depenses = 0;
        $frais = 0;
        for ($p = 0; $p < count($depenseSanFrais); $p++) {
            $montantDepen = $depenseSanFrais[$p]->getMontant();
            $montantDepenFrais = $depenseSanFrais[$p]->getFrais();
            $depenses = $depenses + $montantDepen;
            $frais = $frais + $montantDepenFrais;
        }
        $verse = 0;
        for ($p = 0; $p < count($versement); $p++) {
            $montant = $versement[$p]->getMontant();
            $verse = $verse + $montant;
        }
        $autrede = 0;
        for ($d = 0; $d < count($autredepense); $d++) {
            $montant = $autredepense[$d]->getMontant();
            $autrede = $autrede + $montant;
        }
        return $this->render('admin/index.html.twig', [
            'montantAdhesion' => $montantAdhesion,
            'frais' => $frais,
            'depenses' => $depenses,
            'donpersonnelle' => $donpersonnelle,
            'mont' => $mont,
            'verse' => $verse,
            'autrede' => $autrede,
            'cotisations' => $cotisationRepository->findCotisation(),
        ]);
    }
    /**
     * @Route("/utilisateur", name="utilisateur", methods={"GET"})
     */
    public function user(UserRepository $users, MediaUtilisateurRepository $media): Response
    {
        return $this->render('admin/user/users.html.twig', [
            'users' => $users->findAll(),
        ]);
    }

    /**
     * @Route("/utilisateur/profile/{id}", name="utilisateur_profile")
     */
    public function userprofil(User $user, Request $request): Response
    {
        $form = $this->createForm(EditUserConnecterType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash('success', 'Ajouter avec success !');
            $IdPass = $this->getUser()->getId();
            return $this->redirectToRoute('admin_utilisateur_profile', ['id' => $IdPass]);
        }

        return $this->render('admin/user/profil.html.twig', [
            'userForm' => $form->createView(),
        ]);
    }
    /**
     * @Route("/utilisateur/modifier/{id}", name="utilisateur_edit")
     */
    public function edituser(User $user, Request $request): Response
    {
        $form = $this->createForm(EditUserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $user->setIsVerified(true);
            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash('success', 'Ajouter avec success !');
            return $this->redirectToRoute('admin_utilisateur');
        }
        return $this->render('admin/user/edituser.html.twig', [
            'userForm' => $form->createView(),
        ]);
    }
}