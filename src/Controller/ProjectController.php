<?php

namespace App\Controller;

use App\Entity\Project;
use App\Form\ProjectInsertionType;
use App\Repository\ProjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/project')]
class ProjectController extends AbstractController
{
    #[Route('/', name: 'app_project')]
    public function index(ProjectRepository $projectRepository): Response
    {
        return $this->render('project/index.html.twig', [
            'projects' => $projectRepository->findAll(),
        ]);
    }

    #[Route('/insertion', name: 'app_project_insertion')]
    public function projectInsertion(Request $request): Response
    {
        $project = new Project();
        $form = $this->createForm(ProjectInsertionType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Code
        }

        return $this->render('project/insert.html.twig', [
           'form' => $form->createView()
        ]);
    }
}
