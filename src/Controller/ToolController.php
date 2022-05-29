<?php

namespace App\Controller;

use App\Entity\File;
use App\Entity\Tool;
use App\Form\ToolType;
use App\Repository\ToolRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/tool')]
class ToolController extends AbstractController
{
    #[Route('/', name: 'app_tool')]
    public function index(ToolRepository $toolRepository): Response
    {
        return $this->render('tool/index.html.twig', [
            'tools' => $toolRepository->findAll(),
        ]);
    }

    #[Route('/insertion',name: 'app_tool_insertion')]
    public function toolInsertion(Request $request,EntityManagerInterface $entityManager, SluggerInterface $slugger): Response {

        $tool = new Tool();
        $form =$this->createForm(ToolType::class, $tool);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('imageTemp')->getData();

            if ($file) {
                $image = new File();
                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();
//                dd($image,$originalFilename,$safeFilename,$newFilename);
//                 Move the file to the directory where brochures are stored
                $image->setExtension($file->guessExtension());
                $image->setName($safeFilename);
                $image->setSize($file->getSize());
                try {
                    $file->move(
                        $this->getParameter('file_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    throw $e;
                }

                $image->setPath('uploads/'.$newFilename);

//                dd($image);
                $tool->setImage($image);
            }
            $entityManager->persist($tool);
            $entityManager->flush();
            return $this->redirectToRoute('app_tool');
        }

//        dd($form->createView());

        return  $this->render('tool/insert.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/modification',name: 'app_tool_modification')]
    public function toolModification(Request $request,EntityManagerInterface $entityManager, SluggerInterface $slugger, ToolRepository $toolRepository): Response {

        $toolId = intval($request->get('toolId'));

        if ($toolId === 0) {
            return $this->redirectToRoute('app_default');
        }

        $tool = $toolRepository->findById($toolId);

        if ($tool === null) {
            return $this->redirectToRoute('app_default');
        }

        $form =$this->createForm(ToolType::class, $tool);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('imageTemp')->getData();

            if ($file) {
                if ($tool->getImage() !== null) {
                    unlink($tool->getImage()->getPath());
                    $entityManager->remove($tool->getImage());
                }
                $image = new File();
                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

                $image->setExtension($file->guessExtension());
                $image->setName($safeFilename);
                $image->setSize($file->getSize());
                try {
                    $file->move(
                        $this->getParameter('file_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    throw $e;
                }

                $image->setPath('uploads/'.$newFilename);

                $tool->setImage($image);
            }

            $entityManager->persist($tool);
            $entityManager->flush();

            return $this->redirectToRoute('app_tool');
        }

        return  $this->render('tool/modif.html.twig', [
            'tool' => $tool,
            'form' => $form->createView()
        ]);
    }

    #[Route('/image_delete',name: 'app_tool_modification_image_delete')]
    public function toolImageDelete(Request $request,EntityManagerInterface $entityManager, SluggerInterface $slugger, ToolRepository $toolRepository): Response {

        $toolId = intval($request->get('toolId'));
        $toolImage = $request->get('toolImage');

        if ($toolId === 0) {
            return $this->redirectToRoute('app_default');
        }

        $tool = $toolRepository->findById($toolId);

        if ($tool->getImage()->getPath() !== $toolImage) {
            return $this->redirectToRoute('app_default');
        }

        unlink($toolImage);
        $entityManager->remove($tool->getImage());
        $tool->setImage(null);

        $entityManager->persist($tool);
        $entityManager->flush();

        return  $this->redirectToRoute('app_tool_modification', [
            'toolId' => $tool->getId(),
        ], 307);
    }
}
