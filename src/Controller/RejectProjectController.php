<?php

namespace App\Controller;

use App\Entity\Depense;
use App\Entity\RejectProject;
use App\Form\RejectProjectType;
use App\Repository\RejectProjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/reject/project')]
class RejectProjectController extends AbstractController
{
    #[Route('/', name: 'reject_project_index', methods: ['GET'])]
    public function index(RejectProjectRepository $rejectProjectRepository): Response
    {
        return $this->render('reject_project/index.html.twig', [
            'reject_projects' => $rejectProjectRepository->findAll(),
        ]);
    }

    #[Route('/new/{id}', name: 'reject_project_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $rejectProject = new RejectProject();
        $form = $this->createForm(RejectProjectType::class, $rejectProject);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($rejectProject);
            $entityManager->flush();

            return $this->redirectToRoute('reject_project_index');
        }

        return $this->render('reject_project/new.html.twig', [
            'reject_project' => $rejectProject,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/reject/{id}', name: 'reject_project_reject', methods: ['GET', 'POST'])]
    public function reject(Request $request,Depense $depense): Response
    {
        $rejectProject = new RejectProject();
        $form = $this->createForm(RejectProjectType::class, $rejectProject);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $rejectProject->setDepense($depense);
            $depense->setRejeter(true);
            $depense->setConfirme(false);
            $rejectProject->setUser($this->getUser());
            $entityManager->persist($rejectProject);
            $entityManager->flush();
            $this->addFlash('info','Vous venez de rejeter le projet !');
            return $this->redirectToRoute('confirme_presi');
        }

        return $this->render('reject_project/new.html.twig', [
            'reject_project' => $rejectProject,
            'depense'=> $depense,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'reject_project_show', methods: ['GET'])]
    public function show(RejectProject $rejectProject): Response
    {
        return $this->render('reject_project/show.html.twig', [
            'reject_project' => $rejectProject,
        ]);
    }

    #[Route('/{id}/edit', name: 'reject_project_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, RejectProject $rejectProject): Response
    {
        $form = $this->createForm(RejectProjectType::class, $rejectProject);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('reject_project_index');
        }

        return $this->render('reject_project/edit.html.twig', [
            'reject_project' => $rejectProject,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'reject_project_delete', methods: ['DELETE'])]
    public function delete(Request $request, RejectProject $rejectProject): Response
    {
        if ($this->isCsrfTokenValid('delete'.$rejectProject->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($rejectProject);
            $entityManager->flush();
        }

        return $this->redirectToRoute('reject_project_index');
    }
}