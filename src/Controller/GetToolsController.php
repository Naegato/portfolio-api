<?php

namespace App\Controller;

use App\Repository\ToolRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GetToolsController extends AbstractController
{
    private ToolRepository $toolRepository;

    public function __construct(ToolRepository $toolRepository)
    {
        $this->toolRepository = $toolRepository;
    }

    public function __invoke()
    {
        return $this->toolRepository->findAll();
    }
}
