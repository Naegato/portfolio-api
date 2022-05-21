<?php

namespace App\Controller;

use App\Repository\ToolRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GetToolController extends AbstractController
{
    private ToolRepository $toolRepository;

    public function __construct(ToolRepository $toolRepository)
    {
        $this->toolRepository = $toolRepository;
    }

    public function __invoke(int $id)
    {
        return $this->toolRepository->findById($id);
    }
}
