<?php

namespace App\Controller\Admin;

use App\Entity\Autredepense;
use App\Entity\Bilan;
use App\Form\AutredepenseType;
use App\Repository\AutredepenseRepository;
use App\Repository\BilanRepository;
use App\Repository\CotisationRepository;
use App\Repository\DecaisementRepository;
use App\Repository\MembreRepository;
use App\Repository\VersementRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/autredepense')]
class AutredepenseController extends AbstractController
{
    #[Route('/', name: 'autredepense_index', methods: ['GET'])]
    public function index(AutredepenseRepository $autredepenseRepository): Response
    {
        return $this->render('admin/autredepense/index.html.twig', [
            'autredepenses' => $autredepenseRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'autredepense_new', methods: ['GET', 'POST'])]
    public function new(Request $request, MembreRepository $membreRepository, CotisationRepository $cotisationRepository, DecaisementRepository $decaisementRepository, VersementRepository $versementRepository, AutredepenseRepository $autredepenseRepository, BilanRepository $bilanRepository): Response
    {
        $montantTotal = $cotisationRepository->findCotisation();
        $depenseSanFrais = $decaisementRepository->findAll();
        $versement = $versementRepository->findAll();
        $membre = $membreRepository->findAll();
        $autredepense = $autredepenseRepository->findAll();
        $montantAdhesion = count($membre) * 500;
        // on calcule les cotisations
        $mont = 0;
        for ($i = 0; $i < count($montantTotal); $i++) {
            $calculmontant = $montantTotal[$i]->getMontantTotalPaye();
            $mont = $mont + $calculmontant;
        }
        // on calcule les depenses
        $depenses = 0;
        $frais = 0;
        for ($p = 0; $p < count($depenseSanFrais); $p++) {
            $montantDepen = $depenseSanFrais[$p]->getMontant();
            $montantDepenFrais = $depenseSanFrais[$p]->getFrais();
            $depenses = $depenses + $montantDepen;
            $frais = $frais + $montantDepenFrais;
        }
        // on calcule les versements
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
        // on calcule ce qu'il reste en caise
        $montCaise = ($montantAdhesion + $mont + $verse) - ($depenses + $frais + $autrede);
        $autredepense = new Autredepense();
        $bilan = new Bilan();
        $form = $this->createForm(AutredepenseType::class, $autredepense);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $montantEntrer = $form->get('montant')->getData();
            if ($montantEntrer > $montCaise) {
                $this->addFlash(
                    'warning',
                    'Vous n\'avez pas assez d\'argent dans la caise !'
                );
                return $this->redirectToRoute('autredepense_index');
            }
            $jouj = new DateTime('now');
            $annee = $jouj->format(date('Y'));
            $autredepense->setUser($this->getUser());
            $autredepense->setAnnee($annee);
            $entityManager->persist($autredepense);
            $entityManager->flush();
            //on insert dans la table bilan 
            if ($bilanRepository->findAll() == null) {

                $bilan->setDepense($autredepense->getMontant());
                $bilan->setAnnee($annee);
                $entityManager->persist($bilan);
                $entityManager->flush();
            } else {
                $nombreBilan = $bilanRepository->findAll();
                $dernierAnnee = $nombreBilan[count($nombreBilan) - 1]->getAnnee();
                if ($dernierAnnee == $annee) {
                    $IdPass = $nombreBilan[count($nombreBilan) - 1]->getId();
                    return $this->redirectToRoute('bilan_ajourautre', ['id' => $IdPass]);
                } else {
                    $bilan->setDepense($autredepense->getMontant());
                    $bilan->setAnnee($annee);
                    $entityManager->persist($bilan);
                    $entityManager->flush();
                }
            }
            $this->addFlash('success', 'Ajouter avec success !');
            return $this->redirectToRoute('autredepense_index');
        }

        return $this->render('admin/autredepense/new.html.twig', [
            'autredepense' => $autredepense,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'autredepense_show', methods: ['GET'])]
    public function show(Autredepense $autredepense): Response
    {
        return $this->render('admin/autredepense/show.html.twig', [
            'autredepense' => $autredepense,
        ]);
    }

    #[Route('/{id}/edit', name: 'autredepense_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Autredepense $autredepense): Response
    {
        $form = $this->createForm(AutredepenseType::class, $autredepense);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('autredepense_index');
        }

        return $this->render('admin/autredepense/edit.html.twig', [
            'autredepense' => $autredepense,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'autredepense_delete', methods: ['DELETE'])]
    public function delete(Request $request, Autredepense $autredepense): Response
    {
        if ($this->isCsrfTokenValid('delete' . $autredepense->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($autredepense);
            $entityManager->flush();
        }

        return $this->redirectToRoute('autredepense_index');
    }
}