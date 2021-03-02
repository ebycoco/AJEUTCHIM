<?php

namespace App\Controller;

use App\Entity\Bureau;
use App\Entity\President;
use App\Form\BureauType;
use App\Repository\BureauRepository;
use App\Repository\PresidentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/bureau")
 */
class BureauController extends AbstractController
{
    /**
     * @Route("/", name="bureau_index", methods={"GET"})
     */
    public function index(BureauRepository $bureauRepository): Response
    {
        return $this->render('bureau/index.html.twig', [
            'bureaus' => $bureauRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="bureau_new", methods={"GET","POST"})
     */
    public function new(Request $request, BureauRepository $bureauRepository, PresidentRepository $presidentRepository): Response
    {
        $noubeauPresident = $presidentRepository->findEncour(0);

        $membreBureau = $bureauRepository->findMembreBureau($noubeauPresident);

        // Voir s'il y a un nouveau président 
        if (empty($noubeauPresident)) {
            return $this->redirectToRoute('president_new');
        }
        $bureau = new Bureau();
        $form = $this->createForm(BureauType::class, $bureau);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $postSumi = $bureau->getPostAjeutchim();
            $membreSumi = $bureau->getMembre();
            $bureauPresi = $bureauRepository->findPostOcupe($noubeauPresident, $postSumi);
            $bureauPresiMembreOccupe = $bureauRepository->findPostMembreOccupe($noubeauPresident, $membreSumi);
            // verifie s'il y a un nouveau bureau qui n'est pas vide 
            if (empty($membreBureau)) {
                if ($bureau->getPostAjeutchim()->getName() == "Président") { 
                    $nompresi = $noubeauPresident[0]->getMembre()->getPrenom();
                    $nompresiEntrer = $bureau->getMembre()->getPrenom();
                    if ($nompresi == $nompresiEntrer ) { 
                        $entityManager = $this->getDoctrine()->getManager();
                        $bureau->setEtat(0);
                        $entityManager->persist($bureau);
                        $entityManager->flush();
                        $this->addFlash( 'success', 'Ajouter avec success !');
                    return $this->redirectToRoute('bureau_new');
                    }else {
                        $this->addFlash('warning','Ce n\'est pas le president !');
                    return $this->redirectToRoute('bureau_new');
                    }
                    
                }else {
                    $this->addFlash(
                        'warning',
                        'metter le president dabord !'
                    );
                    return $this->redirectToRoute('bureau_new');
                }
                
            } else {
                // verifie s'il le post existe dejà dans le nouveau bureau
                if ($bureauPresi) {
                    $this->addFlash(
                        'warning',
                        'Le post a été dejà choisir veuillez choisir un nouveau post !'
                    );
                    return $this->redirectToRoute('bureau_new');
                }
                // verifie s'il le membre existe dejà dans le nouveau bureau
                if ($bureauPresiMembreOccupe) {
                    $this->addFlash(
                        'warning',
                        'Ce Membre a été dejà choisir veuillez choisir un nouveau Membre !'
                    );
                    return $this->redirectToRoute('bureau_new');
                }
                $entityManager = $this->getDoctrine()->getManager();
                $bureau->setEtat(0);
                $entityManager->persist($bureau);
                $entityManager->flush();
                $this->addFlash(
                    'success',
                    'Ajouter avec success !'
                );
                return $this->redirectToRoute('bureau_new');
            }
        }

        return $this->render('bureau/new.html.twig', [
            'bureaus' => $membreBureau,
            'noubeauPresident' => $noubeauPresident,
            'bureau' => $bureau,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="bureau_show", methods={"GET"})
     */
    public function show(Bureau $bureau): Response
    {
        return $this->render('bureau/show.html.twig', [
            'bureau' => $bureau,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="bureau_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Bureau $bureau): Response
    {
        $form = $this->createForm(BureauType::class, $bureau);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('bureau_new');
        }

        return $this->render('bureau/edit.html.twig', [
            'bureau' => $bureau,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="bureau_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Bureau $bureau): Response
    {
        if ($this->isCsrfTokenValid('delete' . $bureau->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($bureau);
            $entityManager->flush();
        }

        return $this->redirectToRoute('bureau_new');
    }
}