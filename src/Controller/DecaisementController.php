<?php

namespace App\Controller;

use App\Entity\Decaisement;
use App\Entity\Depense;
use App\Form\DecaisementType;
use App\Repository\AdhesionRepository;
use App\Repository\CotisationRepository;
use App\Repository\DecaisementRepository;
use App\Repository\MembreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/decaisement")
 */
class DecaisementController extends AbstractController
{
    /**
     * @Route("/", name="decaisement_index", methods={"GET"})
     */
    public function index(DecaisementRepository $decaisementRepository): Response
    {
        return $this->render('decaisement/index.html.twig', [
            'decaisements' => $decaisementRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new/{id}", name="decaisement_new", methods={"GET","POST"})
     */
    public function new(Request $request, Depense $depense, MembreRepository $membreRepository, CotisationRepository $cotisationRepository, DecaisementRepository $decaisementRepository): Response
    {
        $montantTotal = $cotisationRepository->findCotisation();
        $depenseSanFrais = $decaisementRepository->findAll();
        $membre = $membreRepository->findAll();
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
        // on calcule ce qu'il reste en caise
        $montCaise = ($montantAdhesion + $mont) - ($depenses + $frais);

        if (empty($depense->getMontanpaye())) {
            $montantRestant = $depense->getMontant();
        } else {
            $montantRestant = $depense->getMontant() - $depense->getMontanpaye();
        }
        $decaisement = new Decaisement();
        $form = $this->createForm(DecaisementType::class, $decaisement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if ($montantRestant < $decaisement->getMontant()) {
                $this->addFlash(
                    'danger',
                    'Le montant entré depasse la montant qui reste a payer !'
                );
                return $this->redirectToRoute('depense_index');
            }

            if ($decaisement->getMontant() > $montCaise) {
                $this->addFlash(
                    'warning',
                    'Vous n\'avez pas assez d\'argent dans la caise !'
                );
                return $this->redirectToRoute('depense_index');
            } 
            $entityManager = $this->getDoctrine()->getManager();
            $decaisement->setUser($this->getUser());
            $decaisement->setDepense($depense);
            if (empty($depense->getMontanpaye())) {
                $depense->setMontanpaye($decaisement->getMontant());
            } else {
                $montantRestant = $decaisement->getMontant() + $depense->getMontanpaye();
                $depense->setMontanpaye($montantRestant);
            }
            $entityManager->persist($decaisement);
            $entityManager->flush();

            $this->addFlash(
                'success',
                'Ajouter avec success !'
            );
            return $this->redirectToRoute('depense_index');
        }

        return $this->render('decaisement/new.html.twig', [
            'decaisement' => $decaisement,
            'depense' => $depense,
            'montantRestant' => $montantRestant,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="decaisement_show", methods={"GET"})
     */
    public function show(Decaisement $decaisement): Response
    {
        return $this->render('decaisement/show.html.twig', [
            'decaisement' => $decaisement,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="decaisement_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Decaisement $decaisement): Response
    {
        $form = $this->createForm(DecaisementType::class, $decaisement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('decaisement_index');
        }

        return $this->render('decaisement/edit.html.twig', [
            'decaisement' => $decaisement,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="decaisement_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Decaisement $decaisement): Response
    {
        if ($this->isCsrfTokenValid('delete' . $decaisement->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($decaisement);
            $entityManager->flush();
        }

        return $this->redirectToRoute('decaisement_index');
    }
}