<?php

namespace App\Controller\Admin;

use App\Entity\Desactive; 
use App\Form\DesactiveType;
use App\Repository\DesactiveRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/desactiver')]
class DesactiveController extends AbstractController
{
    #[Route('/', name: 'desactive_index', methods: ['GET'])]
    public function index(DesactiveRepository $desactiveRepository): Response
    {
        
       $desactive=$desactiveRepository->findAll();
       $mainte=new DateTime("now");  
       if (!empty($desactive)) {
        $debut = $desactive[count($desactive) - 1]->getDebut();
        $fin = $desactive[count($desactive) - 1]->getFin();
        $interval = $debut->diff($fin);
        $reste = $interval->format('%D'); 
        if ($fin > $mainte ) { 
            $activee = 1;
        } else { 
            $activee = 0;
        }
       }else {
        $activee = 0;
       } 
        
        return $this->render('admin/desactive/index.html.twig', [
            'desactives' => $desactiveRepository->findAll(),
            'activee'=>$activee,
            'reste'=>$reste,
        ]);
    }

    #[Route('/new', name: 'desactive_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $mainte=new DateTime("now");
        $desactive = new Desactive();
        $form = $this->createForm(DesactiveType::class, $desactive);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $debut = $desactive->getDebut();
            $fin = $desactive->getFin(); 
            if ($debut > $fin) { 
                $this->addFlash('warning','La date du debut est trop grande a celle de la fin !');
                return $this->redirectToRoute('desactive_index');
            } 
            $entityManager = $this->getDoctrine()->getManager(); 
            $entityManager->persist($desactive);
            $entityManager->flush();

            return $this->redirectToRoute('desactive_index');
        }

        return $this->render('admin/desactive/new.html.twig', [
            'desactive' => $desactive,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'desactive_show', methods: ['GET'])]
    public function show(Desactive $desactive): Response
    {
        return $this->render('admin/desactive/show.html.twig', [
            'desactive' => $desactive,
        ]);
    }

    #[Route('/{id}/edit', name: 'desactive_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Desactive $desactive): Response
    {
        $form = $this->createForm(DesactiveType::class, $desactive);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush(); 
            return $this->redirectToRoute('desactive_index');
        }

        return $this->render('admin/desactive/edit.html.twig', [
            'desactive' => $desactive,
            'form' => $form->createView(),
        ]);
    } 

    #[Route('/{id}', name: 'desactive_delete', methods: ['DELETE'])]
    public function delete(Request $request, Desactive $desactive): Response
    {
        if ($this->isCsrfTokenValid('delete'.$desactive->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($desactive);
            $entityManager->flush();
        }

        return $this->redirectToRoute('desactive_index');
    }
}