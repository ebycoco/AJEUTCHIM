<?php

namespace App\Controller\Admin;

use App\Entity\Bilan;
use App\Entity\Versement;
use App\Form\VersementType;
use App\Repository\BilanRepository;
use App\Repository\VersementRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/versement")
 */
class VersementController extends AbstractController
{
    /**
     * @Route("/", name="versement_index", methods={"GET"})
     */
    public function index(VersementRepository $versementRepository): Response
    {
        return $this->render('admin/versement/index.html.twig', [
            'versements' => $versementRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="versement_new", methods={"GET","POST"})
     */
    public function new(Request $request, BilanRepository $bilanRepository): Response
    {
        $versement = new Versement();
        $bilan = new Bilan();
        $jouj = new DateTime('now');
        $annee = $jouj->format(date('Y'));
        $form = $this->createForm(VersementType::class, $versement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $versement->setAn($annee);
            $entityManager->persist($versement);
            $entityManager->flush();
            //on insert dans la table bilan 
            if ($bilanRepository->findAll() == null) {
               
                $bilan->setVersement($versement->getMontant());
                $bilan->setAnnee($annee);
                $entityManager->persist($bilan);
                $entityManager->flush();
            } else { 
                $nombreBilan = $bilanRepository->findAll(); 
                $dernierAnnee=$nombreBilan[count($nombreBilan) - 1]->getAnnee(); 
                if ($dernierAnnee == $annee) { 
                    $IdPass = $nombreBilan[count($nombreBilan) - 1]->getId();
                    return $this->redirectToRoute('bilan_ajourV', ['id' => $IdPass]);
                } else { 
                    $bilan->setVersement($versement->getMontant());
                    $bilan->setAnnee($annee);
                    $entityManager->persist($bilan);
                    $entityManager->flush();
                } 
            } 

            return $this->redirectToRoute('versement_index');
        }

        return $this->render('admin/versement/new.html.twig', [
            'versement' => $versement,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="versement_show", methods={"GET"})
     */
    public function show(Versement $versement): Response
    {
        return $this->render('admin/versement/show.html.twig', [
            'versement' => $versement,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="versement_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Versement $versement): Response
    {
        $form = $this->createForm(VersementType::class, $versement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('versement_index');
        }

        return $this->render('admin/versement/edit.html.twig', [
            'versement' => $versement,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="versement_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Versement $versement): Response
    {
        if ($this->isCsrfTokenValid('delete'.$versement->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($versement);
            $entityManager->flush();
        }

        return $this->redirectToRoute('versement_index');
    }
}