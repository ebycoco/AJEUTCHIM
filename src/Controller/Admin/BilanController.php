<?php

namespace App\Controller\Admin;

use App\Entity\Annee;
use App\Entity\Bilan;
use App\Entity\BilanAnneeSearch;
use App\Entity\Depense;
use App\Form\AnneeChoixType;
use App\Form\BilanAnneeSearchType;
use App\Form\BilanType;
use App\Repository\AdhesionRepository;
use App\Repository\AutredepenseRepository;
use App\Repository\BilanRepository;
use App\Repository\CotisationRepository;
use App\Repository\DecaisementRepository;
use App\Repository\DepenseRepository;
use App\Repository\MembreRepository;
use App\Repository\VersementRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/bilan')]
class BilanController extends AbstractController
{
    #[Route('/', name: 'bilan_index', methods: ['GET', 'POST'])]
    public function index(Request $request, BilanRepository $bilanRepository): Response
    {
        $jouj = new DateTime('now');
        $annee = $jouj->format(date('Y'));
        $bilan = new Bilan();

        (int) $anne = $bilan->getAnnee();
        if ((int) $anne == 0) {
            $bilans = $bilanRepository->findAnnee($annee);
        } elseif ((int) $anne < 2020) {
            $bilans = $bilanRepository->findAll();
        } else {
            $bilans = $bilanRepository->findAnnee($anne);
        }
        return $this->render('admin/bilan/bilan.html.twig', [
            'bilans' => $bilans,
            'bilan' => $bilan,
        ]);
    }
    #[Route('/history', name: 'bilan_history', methods: ['GET', 'POST'])]
    public function history(Request $request, BilanRepository $bilanRepository): Response
    {
        $bilans = $bilanRepository->findAll();
        return $this->render('admin/bilan/history.html.twig', [
            'bilans' => $bilans,
        ]);
    }

    #[Route('/new', name: 'bilan_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $bilan = new Bilan();
        $form = $this->createForm(BilanType::class, $bilan);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($bilan);
            $entityManager->flush();

            return $this->redirectToRoute('bilan_index');
        }

        return $this->render('admin/bilan/new.html.twig', [
            'bilan' => $bilan,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'bilan_show', methods: ['GET'])]
    public function show(Bilan $bilan): Response
    {
        return $this->render('admin/bilan/show.html.twig', [
            'bilan' => $bilan,
        ]);
    }

    #[Route('/{id}/ajour', name: 'bilan_ajour', methods: ['GET', 'POST'])]
    public function ajour(Bilan $bilan, DecaisementRepository $decaisementRepository, AutredepenseRepository $autredepenseRepository): Response
    {
        $jouj = new DateTime('now');
        $annee = $jouj->format(date('Y'));
        $depenseSanFrais = $decaisementRepository->findAllDepense($annee);
        $autredepense = $autredepenseRepository->findAllAutreDepense($annee);
        $autredepenses = 0;
        for ($p = 0; $p < count($autredepense); $p++) {
            $montantAutreDepen = $autredepense[$p]->getMontant();
            $autredepenses = $autredepenses + $montantAutreDepen;
        }
        $depenses = 0;
        $frais = 0;
        for ($p = 0; $p < count($depenseSanFrais); $p++) {
            $montantDepen = $depenseSanFrais[$p]->getMontant();
            $montantDepenFrais = $depenseSanFrais[$p]->getFrais();
            $depenses = $depenses + $montantDepen;
            $frais = $frais + $montantDepenFrais;
        }
        $depenBilan = $depenses + $frais + $autredepenses;
        $bilan->setDepense($depenBilan);
        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute('depense_index');
    }
    #[Route('/{id}/ajourautre', name: 'bilan_ajourautre', methods: ['GET', 'POST'])]
    public function ajourautre(Bilan $bilan, AutredepenseRepository $autredepenseRepository, DecaisementRepository $decaisementRepository): Response
    {
        $jouj = new DateTime('now');
        $annee = $jouj->format(date('Y'));
        $autredepense = $autredepenseRepository->findAllAutreDepense($annee);
        $autredepenses = 0;
        for ($p = 0; $p < count($autredepense); $p++) {
            $montantAutreDepen = $autredepense[$p]->getMontant();
            $autredepenses = $autredepenses + $montantAutreDepen;
        }
        $depenseSanFrais = $decaisementRepository->findAllDepense($annee);
        $depenses = 0;
        $frais = 0;
        for ($p = 0; $p < count($depenseSanFrais); $p++) {
            $montantDepen = $depenseSanFrais[$p]->getMontant();
            $montantDepenFrais = $depenseSanFrais[$p]->getFrais();
            $depenses = $depenses + $montantDepen;
            $frais = $frais + $montantDepenFrais;
        }
        $depenBilan = $autredepenses + $depenses + $frais;
        $bilan->setDepense($depenBilan);
        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute('autredepense_index');
    }

    #[Route('/{id}/ajourA', name: 'bilan_ajourA', methods: ['GET', 'POST'])]
    public function ajourA(Bilan $bilan, MembreRepository $membreRepository, AdhesionRepository $adhesionRepository): Response
    {
        $jouj = new DateTime('now');
        $annee = $jouj->format(date('Y'));
        $membre = $membreRepository->findAllmembre($annee);
        $adhesion = $adhesionRepository->findAll();
        $adhesionAnnuelle = $adhesion[count($adhesion) - 1]->getMontant();
        $montantmembreAnnuelle = count($membre) * $adhesionAnnuelle;
        $bilan->setAdhesion($montantmembreAnnuelle);
        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute('membre_index');
    }

    #[Route('/{id}/ajourC', name: 'bilan_ajourC', methods: ['GET', 'POST'])]
    public function ajourC(Bilan $bilan, CotisationRepository $cotisationRepository, SessionInterface $session): Response
    {
        $jouj = new DateTime('now');
        $annee = $jouj->format(date('Y'));
        $cotisation = $cotisationRepository->findAllcotisation($annee);

        $mont = 0;
        for ($i = 0; $i < count($cotisation); $i++) {
            $calculmontant = $cotisation[$i]->getMontantTotalPaye();
            $mont = $mont + $calculmontant;
        }
        $bilan->setCotisation($mont);
        $this->getDoctrine()->getManager()->flush();
        $membres = $session->get('cotise');
        $cotisation = $cotisationRepository->findAllcotisationmembre($annee);
        if ($membres == 0) {
            $this->addFlash('success', 'Ajouter avec success !');
            unset($membres);
            return $this->redirectToRoute('cotisation_solde');
        }
        return $this->redirectToRoute('cotisation_encour');
    }
    #[Route('/{id}/ajourV', name: 'bilan_ajourV', methods: ['GET', 'POST'])]
    public function ajourV(Bilan $bilan, VersementRepository $versementRepository): Response
    {
        $jouj = new DateTime('now');
        $annee = $jouj->format(date('Y'));
        $versement = $versementRepository->findAllversement($annee);
        $mont = 0;
        for ($i = 0; $i < count($versement); $i++) {
            $calculmontant = $versement[$i]->getMontant();
            $mont = $mont + $calculmontant;
        }
        $bilan->setVersement($mont);
        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute('versement_index');
    }

    #[Route('/{id}/edit', name: 'bilan_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Bilan $bilan): Response
    {
        $form = $this->createForm(BilanType::class, $bilan);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('bilan_index');
        }

        return $this->render('admin/bilan/edit.html.twig', [
            'bilan' => $bilan,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'bilan_delete', methods: ['DELETE'])]
    public function delete(Request $request, Bilan $bilan): Response
    {
        if ($this->isCsrfTokenValid('delete' . $bilan->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($bilan);
            $entityManager->flush();
        }

        return $this->redirectToRoute('bilan_index');
    }
}