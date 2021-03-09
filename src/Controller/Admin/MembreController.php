<?php

namespace App\Controller\Admin;

use App\Entity\Bilan;
use App\Entity\Membre;
use App\Form\MembreType;
use App\Repository\BilanRepository;
use App\Repository\MembreRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/membre")
 */
class MembreController extends AbstractController
{
    /**
     * @Route("/", name="membre_index", methods={"GET"})
     */
    public function index(MembreRepository $membreRepository): Response
    {
        return $this->render('admin/membre/index.html.twig', [
            'membres' => $membreRepository->findMembre(),
        ]);
    }

    /**
     * @Route("/new", name="membre_new", methods={"GET","POST"})
     */
    public function new(Request $request, BilanRepository $bilanRepository): Response
    {
        $membre = new Membre();
        $bilan = new Bilan();
        $form = $this->createForm(MembreType::class, $membre);
        $form->handleRequest($request); 
        $jouj = new DateTime('now');
        $annee = $jouj->format(date('Y'));

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $membre->setReferenceAjeutchim('AJEUT' . mt_rand(99, 999) . 'CHIM');
            $membre->setAnnee($annee);
            $membre->setAdhesion(500);
            $entityManager->persist($membre);
            $entityManager->flush();

             //on insert dans la table bilan 
             if ($bilanRepository->findAll() == null) {
               
                $bilan->setAdhesion(500);
                $bilan->setAnnee($annee);
                $entityManager->persist($bilan);
                $entityManager->flush();
            } else { 
                $nombreBilan = $bilanRepository->findAll(); 
                $dernierAnnee=$nombreBilan[count($nombreBilan) - 1]->getAnnee(); 
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