<?php

namespace App\Controller;

use App\Repository\TechnoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GetTechnosController extends AbstractController
{
    private TechnoRepository $technoRepository;

    public function __construct(TechnoRepository $technoRepository)
    {
        $this->technoRepository = $technoRepository;
    }

    public function __invoke()
    {
        return $this->technoRepository->findAll();
    }
}
