<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'app_default')]
    public function index(): Response
    {
        return $this->render('default/index.html.twig', [
            'route' => [
                'Projects List' => 'app_project',
                'Project Insertion' => 'app_project_insertion',
                'Technos List' => 'app_techno',
                'Techno Insertion' => 'app_techno_insertion',
                'Tools List' => 'app_tool',
                'Tool Insertion' => 'app_tool_insertion',
            ],
        ]);
    }
}
