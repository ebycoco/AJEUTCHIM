<?php

namespace App\Controller;

use App\Entity\Cotisation;
use App\Form\CotisationMembreType;
use App\Form\CotisationType;
use App\Repository\CotisationRepository;
use App\Repository\MembreRepository;
use App\Repository\MontantAnnuelleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/cotisation")
 */
class CotisationController extends AbstractController
{
    /**
     * @Route("/", name="cotisation_index", methods={"GET"})
     * @param CotisationRepository $cotisationRepository
     * @return Response
     */
    public function index(CotisationRepository $cotisationRepository, MembreRepository $membreRepository): Response
    {
        $montantTotal = $cotisationRepository->findCotisation();
        $membre = $membreRepository->findAll();
        $montantAdhesion = count($membre) * 500;
        $mont = 0;
        for ($i = 0; $i < count($montantTotal); $i++) {
            $calculmontant = $montantTotal[$i]->getMontantTotalPaye();
            $mont = $mont + $calculmontant;
        }
        return $this->render('cotisation/index.html.twig', [
            'montantAdhesion' => $montantAdhesion,
            'mont' => $mont,
            'cotisations' => $cotisationRepository->findCotisation(),
        ]);
    }

    /**
     * @Route("/historique", name="cotisation_historique", methods={"GET"})
     * @param CotisationRepository $cotisationRepository
     * @return Response
     */
    public function historique(CotisationRepository $cotisationRepository): Response
    {
        return $this->render('cotisation/historique.html.twig', [
            'cotisations' => $cotisationRepository->findCotisation(),
        ]);
    }

    /**
     * @Route("/membrecotisation", name="cotisation_membrecotisation", methods={"GET"})
     * @param CotisationRepository $cotisationRepository
     * @return Response
     */
    public function membrecotisation(CotisationRepository $cotisationRepository): Response
    {
        return $this->render('cotisation/membrecotisation.html.twig', [
            'cotisations' => $cotisationRepository->findCotisation(),
        ]);
    }
    /**
     * @Route("/membrecotisation/{id}", name="cotisation_membreco", methods={"GET"})
     * @param CotisationRepository $cotisationRepository
     * @return Response
     */
    public function membreco(CotisationRepository $cotisationRepository, Cotisation $cotisation): Response
    {
        $membre = $cotisation->getMembre();
        return $this->render('cotisation/membrecotisation.html.twig', [
            'cotisations' => $cotisationRepository->findCo($membre),
        ]);
    }

    /**
     * 
     * @Route("/solde", name="cotisation_solde", methods={"GET"}) 
     * @param CotisationRepository $cotisationRepository
     * @return Response
     */
    public function solde(CotisationRepository $cotisationRepository): Response
    {
        return $this->render('cotisation/solde.html.twig', [
            'cotisations' => $cotisationRepository->findSolde(),
        ]);
    }

    /**
     * 
     * @Route("/encour", name="cotisation_encour", methods={"GET"}) 
     * @param CotisationRepository $cotisationRepository
     * @return Response
     */
    public function encours(CotisationRepository $cotisationRepository): Response
    {
        return $this->render('cotisation/encour.html.twig', [
            'cotisations' => $cotisationRepository->findEncour(),
        ]);
    }

    /**
     * @Route("/new", name="cotisation_new", methods={"GET","POST"})
     *
     * @param Request $request
     * @param CotisationRepository $cotisationRepository
     * @return Response
     */
    public function new(Request $request, CotisationRepository $cotisationRepository): Response
    {
        $cotisation = new Cotisation();
        $form = $this->createForm(CotisationType::class, $cotisation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $membrepayant = $cotisation->getMembre();
            $montanRest = $cotisationRepository->findOnMembre($membrepayant);
            if (!empty($montanRest)) {
                $rest = $montanRest[count($montanRest) - 1]->getResteMontant();
            }
            if (!empty($montanRest) && $rest > 0) {
                // on essai d'ajouter une personne qui a commencer de payer
                $this->addFlash(
                    'danger',
                    'Le Membre a dejà payer un fois veuillez modifier  !'
                );
                return $this->redirectToRoute('cotisation_encour');
            } elseif (!empty($montanRest) && $rest == 0) {
                // verifie s' il a soldé l'an passeé
                if (empty($montanRest)) {
                } else {
                    // il a soldé l' an passé
                    $lan = $cotisationRepository->findOnMembre($membrepayant);
                    $anneePassee = $lan[count($lan) - 1]->getAnnee();
                    $cetteAnnee = $cotisation->getAnnee();
                    if ($anneePassee == $cetteAnnee) {
                        $this->addFlash(
                            'warning',
                            'Veuillez changer l\'annee car la personne vient de soldé l\'année entrer !'
                        );
                        return $this->redirectToRoute('cotisation_new');
                    }
                    $montanAnnue = $cotisation->getMontantAnnuelle()->getMontant();
                    $montantEntrer = $cotisation->getMontant();
                    if ($montantEntrer > $montanAnnue) {
                        $this->addFlash(
                            'danger',
                            'Le montant entré depasse la montant qui reste a payer !'
                        );
                        return $this->redirectToRoute('cotisation_new');
                    }
                    $montantApaye = $montanAnnue - $montantEntrer;

                    $entityManager = $this->getDoctrine()->getManager();
                    $cotisation->setResteMontant($montantApaye);
                    $cotisation->setMontantTotalPaye($montantEntrer);
                    $cotisation->setNeplus(false);
                    $cotisation->setStatus(0);
                    $entityManager->persist($cotisation);
                    $entityManager->flush();
                    $this->addFlash(
                        'success',
                        'Ajouter avec success !'
                    );
                    $neplus = $lan[count($lan) - 1]->getNeplus();
                    $IdPass = $lan[count($lan) - 1]->getId();
                    if ($neplus == false) {
                        return $this->redirectToRoute('cotisation_confirme', ['id' => $IdPass]);
                    }
                }
            } else {

                if (empty($montanRest)) {
                    // on ajoute une nouvelle personne 
                    $montanAnnue = $cotisation->getMontantAnnuelle()->getMontant();
                    $montantEntrer = $cotisation->getMontant();
                    if ($montantEntrer > $montanAnnue) {
                        $this->addFlash(
                            'danger',
                            'Le montant entré depasse la montant qui reste a payer !'
                        );
                        return $this->redirectToRoute('cotisation_new');
                    }
                    $montantApaye = $montanAnnue - $montantEntrer;
                    $entityManager = $this->getDoctrine()->getManager();
                    $cotisation->setResteMontant($montantApaye);
                    $cotisation->setMontantTotalPaye($montantEntrer);
                    $cotisation->setStatus(0);
                    $cotisation->setNeplus(0);
                    $entityManager->persist($cotisation);
                    $entityManager->flush();
                } else {
                }
            }

            $this->addFlash(
                'success',
                'Ajouter avec success !'
            );
            return $this->redirectToRoute('cotisation_encour');
        }

        return $this->render('cotisation/new.html.twig', [
            'cotisation' => $cotisation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="cotisation_show", methods={"GET"})
     *
     * @param Cotisation $cotisation
     * @return Response
     */
    public function show(Cotisation $cotisation): Response
    {
        return $this->render('cotisation/show.html.twig', [
            'cotisation' => $cotisation,
        ]);
    }




    /**
     * @Route("/{id}/confirme", name="cotisation_confirme", methods={"GET","POST"})
     *
     * @param Request $request
     * @param Cotisation $cotisation
     * @return Response
     */
    public function confirme(Request $request, Cotisation $cotisation): Response
    {
        $form = $this->createForm(CotisationMembreType::class, $cotisation);
        $form->handleRequest($request);
        $entityManager = $this->getDoctrine()->getManager();
        $cotisation->setNeplus(true);
        $cotisation->setStatus(true);
        $entityManager->persist($cotisation);
        $entityManager->flush();
        return $this->redirectToRoute('cotisation_solde');
    }


    /**
     * @Route("/{id}/edit", name="cotisation_edit", methods={"GET","POST"})
     *
     * @param Request $request
     * @param Cotisation $cotisation
     * @param CotisationRepository $cotisationRepository
     * @return Response
     */
    public function edit(Request $request, Cotisation $cotisation, CotisationRepository $cotisationRepository): Response
    {
        $form = $this->createForm(CotisationMembreType::class, $cotisation);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $membrepayant = $cotisation->getMembre();
            $dernier = $cotisationRepository->findOnMembre($membrepayant);
            $montanRest = $dernier[count($dernier) - 1]->getResteMontant();
            $derniermontanTotalPaye = $dernier[count($dernier) - 1]->getMontantTotalPaye();
            $montantEntrer = $cotisation->getMontant();

            if ($montantEntrer > $montanRest && $montanRest == 0) {

                if ($derniermontanTotalPaye < $montantEntrer) {
                    $idpass = $cotisation->getId();
                    $this->addFlash(
                        'warning',
                        'Le montant entré depasse la montant qui reste a payer !'
                    );
                    return $this->redirectToRoute('cotisation_edit', ['id' => $idpass]);
                }
                // soldé mais corrige pour une erreur 
                $montantApaye = $derniermontanTotalPaye - $montantEntrer;
                $entityManager = $this->getDoctrine()->getManager();
                $cotisation->setResteMontant($montantEntrer);
                $cotisation->setMontantTotalPaye($montantApaye);
                $cotisation->setNeplus(false);
                $cotisation->setStatus(0);
                $entityManager->persist($cotisation);
                $entityManager->flush();
                $this->addFlash(
                    'success',
                    'Ajouter avec success !'
                );
                return $this->redirectToRoute('cotisation_encour');
            }
            if ($montanRest - $montantEntrer == 0) {
                // il solde
                $montantApaye = $montanRest - $montantEntrer;
                $montanTotalPaye = $derniermontanTotalPaye + $montantEntrer;
                $entityManager = $this->getDoctrine()->getManager();
                $cotisation->setResteMontant($montantApaye);
                $cotisation->setMontantTotalPaye($montanTotalPaye);
                $cotisation->setStatus(1);
                $entityManager->persist($cotisation);
                $entityManager->flush();
                $this->addFlash(
                    'success',
                    'Ajouter avec success !'
                );
                return $this->redirectToRoute('cotisation_encour');
            } else {
                // on mette jour sa cotisation
                if ($montanRest < $montantEntrer) {
                    $idpass = $cotisation->getId();
                    $this->addFlash(
                        'warning',
                        'Le montant entré depasse la montant qui reste a payer !'
                    );
                    return $this->redirectToRoute('cotisation_edit', ['id' => $idpass]);
                }
                $montantApaye = $montanRest - $montantEntrer;
                $montanTotalPaye = $derniermontanTotalPaye + $montantEntrer;
                $entityManager = $this->getDoctrine()->getManager();
                $cotisation->setResteMontant($montantApaye);
                $cotisation->setMontantTotalPaye($montanTotalPaye);
                $entityManager->persist($cotisation);
                $entityManager->flush();
                $this->addFlash(
                    'success',
                    'Ajouter avec success !'
                );
                return $this->redirectToRoute('cotisation_encour');
            }
        }

        return $this->render('cotisation/edit.html.twig', [
            'cotisation' => $cotisation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="cotisation_delete", methods={"DELETE"})
     *
     * @param Request $request
     * @param Cotisation $cotisation
     * @return Response
     */
    public function delete(Request $request, Cotisation $cotisation): Response
    {
        if ($this->isCsrfTokenValid('delete' . $cotisation->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($cotisation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('cotisation_index');
    }
}