<?php

namespace App\Controller;

use App\Entity\File;
use App\Entity\Project;
use App\Entity\ProjectImage;
use App\Form\ProjectType;
use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

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
    public function projectInsertion(Request $request, SluggerInterface $slugger, EntityManagerInterface $entityManager): Response
    {
        $project = new Project();
        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $technos = $form->get('technosTemp')->getData();
            foreach ($technos as $techno) {
                if ($techno !== null) {
                    $project->addTechno($techno);
                }
            }
            $tools = $form->get('toolsTemp')->getData();
            foreach ($tools as $tool) {
                if ($tool !== null) {
                    $project->addTool($tool);
                }
            }

            $images = $form->get('imagesTemp')->getData();

            foreach ($images as $image) {
                if ($image) {
                    $ProjectImage = new ProjectImage();
                    $file = new File();
                    $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                    // this is needed to safely include the file name as part of the URL
                    $safeFilename = $slugger->slug($originalFilename);
                    $newFilename = $safeFilename.'-'.uniqid().'.'.$image->guessExtension();
//                dd($image,$originalFilename,$safeFilename,$newFilename);
//                 Move the file to the directory where brochures are stored
                    $file->setExtension($image->guessExtension());
                    $file->setName($safeFilename);
                    $file->setSize($image->getSize());
                    try {
                        $image->move(
                            $this->getParameter('file_directory'),
                            $newFilename
                        );
                    } catch (FileException $e) {
                        throw $e;
                    }

                    $file->setPath('uploads/'.$newFilename);
                    $ProjectImage->setImage($file);
                    $project->addImage($ProjectImage);
                    $entityManager->persist($ProjectImage);
                }
            }

            $overview = $form->get('overviewTemp')->getData();

            if ($overview) {
                $imageOverview = new File();
                $originalFilename = pathinfo($overview->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$overview->guessExtension();
//                dd($image,$originalFilename,$safeFilename,$newFilename);
//                 Move the file to the directory where brochures are stored
                $imageOverview->setExtension($overview->guessExtension());
                $imageOverview->setName($safeFilename);
                $imageOverview->setSize($overview->getSize());
                try {
                    $overview->move(
                        $this->getParameter('file_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    throw $e;
                }

                $imageOverview->setPath('uploads/'.$newFilename);

                $project->setOverview($imageOverview);
            }

            $entityManager->persist($project);
            $entityManager->flush();
            return $this->redirectToRoute('app_project');
        }

        return $this->render('project/insert.html.twig', [
           'form' => $form->createView()
        ]);
    }

    #[Route('/modification', name: 'app_project_modification')]
    public function projectModification(Request $request, SluggerInterface $slugger, EntityManagerInterface $entityManager, ProjectRepository $projectRepository):Response {

        $projectId = intval($request->get('projectId'));

        if ($projectId === 0) {
            return $this->redirectToRoute('app_default');
        }

        $project = $projectRepository->findById($projectId);

        if ($project === null) {
            return $this->redirectToRoute('app_default');
        }

        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);

//        dd($project,$form->createView());

        return $this->render('project/modif.html.twig', [
            'form' => $form->createView(),
            'project' => $project,
        ]);
    }
}
