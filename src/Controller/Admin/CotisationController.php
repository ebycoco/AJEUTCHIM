<?php

namespace App\Controller\Admin;

use App\Entity\Bilan;
use App\Entity\Cotisation;
use App\Form\CotisationMembreType;
use App\Form\CotisationType;
use App\Repository\AdhesionRepository;
use App\Repository\AutredepenseRepository;
use App\Repository\BilanRepository;
use App\Repository\CotisationRepository;
use App\Repository\DecaisementRepository;
use App\Repository\DonRepository;
use App\Repository\MembreRepository;
use App\Repository\MontantAnnuelleRepository;
use App\Repository\VersementRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

/**
 * @Route("/admin/cotisation")
 */
class CotisationController extends AbstractController
{
    /**
     * @Route("/", name="cotisation_index", methods={"GET"})
     * @param CotisationRepository $cotisationRepository
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
        return $this->render('admin/cotisation/index.html.twig', [
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
     * @Route("/historique", name="cotisation_historique", methods={"GET"})
     * @param CotisationRepository $cotisationRepository
     * @return Response
     */
    public function historique(CotisationRepository $cotisationRepository): Response
    {
        return $this->render('admin/cotisation/historique.html.twig', [
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
        return $this->render('admin/cotisation/membrecotisation.html.twig', [
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
        return $this->render('admin/cotisation/membrecotisation.html.twig', [
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
        return $this->render('admin/cotisation/solde.html.twig', [
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
        return $this->render('admin/cotisation/encour.html.twig', [
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
    public function new(Request $request, CotisationRepository $cotisationRepository, BilanRepository $bilanRepository, MontantAnnuelleRepository $montantAnnuelleRepository, SessionInterface $session): Response
    {
        $cotisation = new Cotisation();
        $bilan = new Bilan();
        $form = $this->createForm(CotisationType::class, $cotisation);
        $form->handleRequest($request);
        $jouj = new DateTime('now');
        $annee = $jouj->format(date('Y'));
        $membrecotise = $session->get('cotise');
        if ($form->isSubmitted() && $form->isValid()) {
            $montanAnnuel = $montantAnnuelleRepository->findAll();
            $montanAnnuelle = $montanAnnuel[count($montanAnnuel) - 1]->getMontant();

            $membrepayant = $cotisation->getMembre();
            $montanRest = $cotisationRepository->findOnMembre($membrepayant);
            if (!empty($montanRest)) {
                $rest = $montanRest[count($montanRest) - 1]->getResteMontant();
            }
            if (!empty($montanRest) && $rest > 0) {
                // on essai d'ajouter une personne qui a commencer de payer
                $this->addFlash('danger', 'Le Membre a dejà payer un fois veuillez modifier !');
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
                        $this->addFlash('warning', 'Veuillez changer l\'annee car la personne vient de soldé l\'année entrer !');
                        return $this->redirectToRoute('cotisation_new');
                    }
                    $montantEntrer = $cotisation->getMontant();
                    if ($montantEntrer > $montanAnnuelle) {
                        $this->addFlash('danger', 'Le montant entré depasse la montant qui reste a payer !');
                        return $this->redirectToRoute('cotisation_new');
                    }
                    $montantApaye = $montanAnnuelle - $montantEntrer;
                    $entityManager = $this->getDoctrine()->getManager();
                    $cotisation->setResteMontant($montantApaye);
                    $cotisation->setMontantTotalPaye($montantEntrer);
                    $cotisation->setMontantannuelle($montanAnnuelle);
                    $cotisation->setNeplus(false);
                    $cotisation->setStatus(0);
                    $cotisation->setAn($annee);
                    $entityManager->persist($cotisation);
                    $entityManager->flush();
                    //on insert dans la table bilan 
                    if ($bilanRepository->findAll() == null) {

                        $bilan->setCotisation($cotisation->getMontantTotalPaye());
                        $bilan->setAnnee($annee);
                        $entityManager->persist($bilan);
                        $entityManager->flush();
                    } else {
                        $nombreBilan = $bilanRepository->findAll();
                        $dernierAnnee = $nombreBilan[count($nombreBilan) - 1]->getAnnee();
                        if ($dernierAnnee == $annee) {
                            $IdPass = $nombreBilan[count($nombreBilan) - 1]->getId();
                            $membrecotise = $montanRest;
                            dd('jjj');
                            $session->set('cotise', $membrecotise);
                            return $this->redirectToRoute('bilan_ajourC', ['id' => $IdPass]);
                        } else {
                            $bilan->setCotisation($cotisation->getMontantTotalPaye());
                            $bilan->setAnnee($annee);
                            $entityManager->persist($bilan);
                            $entityManager->flush();
                        }
                    }

                    $this->addFlash('success', 'Ajouter avec success !');
                    $neplus = $lan[count($lan) - 1]->getNeplus();
                    $IdPass = $lan[count($lan) - 1]->getId();
                    if ($neplus == false) {
                        return $this->redirectToRoute('cotisation_confirme', ['id' => $IdPass]);
                    }
                }
            } else {

                if (empty($montanRest)) {
                    // on ajoute une nouvelle personne 
                    $montantEntrer = $cotisation->getMontant();
                    if ($montantEntrer > $montanAnnuelle) {
                        $this->addFlash('danger', 'Le montant entré depasse la montant qui reste a payer !');
                        return $this->redirectToRoute('cotisation_new');
                    }
                    $montantApaye = $montanAnnuelle - $montantEntrer;
                    $entityManager = $this->getDoctrine()->getManager();
                    $cotisation->setResteMontant($montantApaye);
                    $cotisation->setMontantTotalPaye($montantEntrer);
                    $cotisation->setMontantannuelle($montanAnnuelle);
                    $cotisation->setStatus(0);
                    $cotisation->setAn($annee);
                    $cotisation->setNeplus(0);
                    $entityManager->persist($cotisation);
                    $entityManager->flush();

                    //on insert dans la table bilan 
                    if ($bilanRepository->findAll() == null) {

                        $bilan->setCotisation($cotisation->getMontantTotalPaye());
                        $bilan->setAnnee($annee);
                        $entityManager->persist($bilan);
                        $entityManager->flush();
                    } else {
                        $nombreBilan = $bilanRepository->findAll();
                        $dernierAnnee = $nombreBilan[count($nombreBilan) - 1]->getAnnee();
                        if ($dernierAnnee == $annee) {
                            $IdPass = $nombreBilan[count($nombreBilan) - 1]->getId();
                            if ($montantEntrer == $montanAnnuelle) {
                                $membrecotise = 0;
                            } else {
                                $membrecotise = $montantApaye;
                            }
                            $session->set('cotise', $membrecotise);
                            return $this->redirectToRoute('bilan_ajourC', ['id' => $IdPass]);
                        } else {
                            $bilan->setCotisation($cotisation->getMontantTotalPaye());
                            $bilan->setAnnee($annee);
                            $entityManager->persist($bilan);
                            $entityManager->flush();
                        }
                    }
                } else {
                }
            }
            if ($montantEntrer == 5000) {
                $this->addFlash('success', 'Ajouter avec success !');
                return $this->redirectToRoute('cotisation_solde');
            }

            $this->addFlash('success', 'Ajouter avec success !');
            return $this->redirectToRoute('cotisation_encour');
        }

        return $this->render('admin/cotisation/new.html.twig', [
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
        return $this->render('admin/cotisation/show.html.twig', [
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
    public function edit(Request $request, Cotisation $cotisation, CotisationRepository $cotisationRepository, BilanRepository $bilanRepository, MontantAnnuelleRepository $montantAnnuelleRepository, SessionInterface $session): Response
    {
        $bilan = new Bilan();
        $jouj = new DateTime('now');
        $annee = $jouj->format(date('Y'));
        $form = $this->createForm(CotisationMembreType::class, $cotisation);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //$matriculeEntrer = $form->get('mont')->getData();
            $montanAnnuel = $montantAnnuelleRepository->findAll();
            $montanAnnuelle = $montanAnnuel[count($montanAnnuel) - 1]->getMontant();
            $membrepayant = $cotisation->getMembre();
            $dernier = $cotisationRepository->findOnMembre($membrepayant);
            $montanRest = $dernier[count($dernier) - 1]->getResteMontant();
            $derniermontanTotalPaye = $dernier[count($dernier) - 1]->getMontantTotalPaye();
            (int) $montantEntrer = $request->request->get('mont');
            if ($montantEntrer <= 99) {
                $idpass = $cotisation->getId();
                $this->addFlash('warning', 'Le montant minimun est : 100 f !');
                return $this->redirectToRoute('cotisation_edit', ['id' => $idpass]);
            }
            if ($montantEntrer > $montanRest && $montanRest == 0) {
                if ($derniermontanTotalPaye < $montantEntrer) {
                    $idpass = $cotisation->getId();
                    $this->addFlash('warning', 'Le montant entré depasse la montant qui reste a payer !');
                    return $this->redirectToRoute('cotisation_edit', ['id' => $idpass]);
                }
                // soldé mais corrige pour une erreur 
                $montantApaye = $derniermontanTotalPaye - $montantEntrer;
                $entityManager = $this->getDoctrine()->getManager();
                $cotisation->setResteMontant($montantEntrer);
                $cotisation->setMontantTotalPaye($montantApaye);
                $cotisation->setMontantannuelle($montanAnnuelle);
                $cotisation->setNeplus(false);
                $cotisation->setStatus(0);
                $entityManager->persist($cotisation);
                $entityManager->flush();
                //on insert dans la table bilan 
                if ($bilanRepository->findAll() == null) {
                    $bilan->setCotisation($cotisation->getMontantTotalPaye());
                    $bilan->setAnnee($annee);
                    $entityManager->persist($bilan);
                    $entityManager->flush();
                } else {
                    $nombreBilan = $bilanRepository->findAll();
                    $dernierAnnee = $nombreBilan[count($nombreBilan) - 1]->getAnnee();
                    if ($dernierAnnee == $annee) {
                        $IdPass = $nombreBilan[count($nombreBilan) - 1]->getId();
                        $membrecotise = $cotisation->getResteMontant();
                        $session->set('cotise', $membrecotise);
                        return $this->redirectToRoute('bilan_ajourC', ['id' => $IdPass]);
                    } else {
                        $bilan->setCotisation($cotisation->getMontantTotalPaye());
                        $bilan->setAnnee($annee);
                        $entityManager->persist($bilan);
                        $entityManager->flush();
                    }
                }
                if ($montantEntrer == $montanAnnuelle) {
                    $this->addFlash('success', 'Ajouter avec success !');
                    return $this->redirectToRoute('cotisation_solde');
                }
                $this->addFlash('success', 'Ajouter avec success !');
                return $this->redirectToRoute('cotisation_encour');
            }
            if ($montanRest - $montantEntrer == 0) {
                // il solde
                $montantApaye = $montanRest - $montantEntrer;
                $montanTotalPaye = $derniermontanTotalPaye + $montantEntrer;
                $entityManager = $this->getDoctrine()->getManager();
                $cotisation->setResteMontant($montantApaye);
                $cotisation->setMontantTotalPaye($montanTotalPaye);
                $cotisation->setMontantannuelle($montanAnnuelle);
                $cotisation->setStatus(1);
                $entityManager->persist($cotisation);
                $entityManager->flush();
                //on insert dans la table bilan 
                if ($bilanRepository->findAll() == null) {
                    $bilan->setCotisation($cotisation->getMontantTotalPaye());
                    $bilan->setAnnee($annee);
                    $entityManager->persist($bilan);
                    $entityManager->flush();
                } else {
                    $nombreBilan = $bilanRepository->findAll();
                    $dernierAnnee = $nombreBilan[count($nombreBilan) - 1]->getAnnee();
                    if ($dernierAnnee == $annee) {
                        $IdPass = $nombreBilan[count($nombreBilan) - 1]->getId();
                        $membrecotise = $cotisation->getResteMontant();
                        $session->set('cotise', $membrecotise);
                        return $this->redirectToRoute('bilan_ajourC', ['id' => $IdPass]);
                    } else {
                        $bilan->setCotisation($cotisation->getMontantTotalPaye());
                        $bilan->setAnnee($annee);
                        $entityManager->persist($bilan);
                        $entityManager->flush();
                    }
                }
                if ($montantEntrer == $montanAnnuelle) {
                    $this->addFlash('success', 'Ajouter avec success !');
                    return $this->redirectToRoute('cotisation_solde');
                }
                $this->addFlash('success', 'Ajouter avec success !');
                return $this->redirectToRoute('cotisation_encour');
            } else {
                // on mette jour sa cotisation
                if ($montanRest < $montantEntrer) {
                    $idpass = $cotisation->getId();
                    $this->addFlash('warning', 'Le montant entré depasse la montant qui reste a payer !');
                    return $this->redirectToRoute('cotisation_edit', ['id' => $idpass]);
                }
                $montantApaye = $montanRest - $montantEntrer;
                $montanTotalPaye = $derniermontanTotalPaye + $montantEntrer;
                $entityManager = $this->getDoctrine()->getManager();
                $cotisation->setResteMontant($montantApaye);
                $cotisation->setMontantTotalPaye($montanTotalPaye);
                $cotisation->setMontantannuelle($montanAnnuelle);
                $entityManager->persist($cotisation);
                $entityManager->flush();
                //on insert dans la table bilan 
                if ($bilanRepository->findAll() == null) {
                    $bilan->setCotisation($cotisation->getMontantTotalPaye());
                    $bilan->setAnnee($annee);
                    $entityManager->persist($bilan);
                    $entityManager->flush();
                } else {
                    $nombreBilan = $bilanRepository->findAll();
                    $dernierAnnee = $nombreBilan[count($nombreBilan) - 1]->getAnnee();
                    if ($dernierAnnee == $annee) {
                        $IdPass = $nombreBilan[count($nombreBilan) - 1]->getId();
                        $membrecotise = $montantEntrer;
                        $session->set('cotise', $membrecotise);
                        return $this->redirectToRoute('bilan_ajourC', ['id' => $IdPass]);
                    } else {
                        $bilan->setCotisation($cotisation->getMontantTotalPaye());
                        $bilan->setAnnee($annee);
                        $entityManager->persist($bilan);
                        $entityManager->flush();
                    }
                }
                if ($montantEntrer == $montanAnnuelle) {
                    $this->addFlash('success', 'Ajouter avec success !');
                    return $this->redirectToRoute('cotisation_solde');
                }
                $this->addFlash('success', 'Ajouter avec success !');
                return $this->redirectToRoute('cotisation_encour');
            }
        }
        return $this->render('admin/cotisation/edit.html.twig', [
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