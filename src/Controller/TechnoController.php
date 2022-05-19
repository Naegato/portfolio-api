<?php

namespace App\Controller;

use App\Entity\File;
use App\Entity\Techno;
use App\Form\TechnoType;
use App\Repository\TechnoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/techno')]
class TechnoController extends AbstractController
{
    #[Route('/', name: 'app_techno')]
    public function index(TechnoRepository $technoRepository): Response
    {
        return $this->render('techno/index.html.twig', [
            'technos' => $technoRepository->findAll(),
        ]);
    }

    #[Route('/insertion',name: 'app_techno_insertion')]
    public function technoInsertion(Request $request,EntityManagerInterface $entityManager, SluggerInterface $slugger): Response {

        $techno = new Techno();
        $form = $this->createForm(TechnoType::class, $techno);
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
                $techno->setImage($image);
            }
            $entityManager->persist($techno);
            $entityManager->flush();
            return $this->redirectToRoute('app_techno');
        }

//        dd($form->createView());

        return  $this->render('techno/insert.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/modification',name: 'app_techno_modification')]
    public function technoModification(Request $request,EntityManagerInterface $entityManager, SluggerInterface $slugger, TechnoRepository $technoRepository): Response {

        $technoId = intval($request->get('technoId'));

        if ($technoId === 0) {
            return $this->redirectToRoute('app_default');
        }

        $techno = $technoRepository->findById($technoId);

        if ($techno === null) {
            return $this->redirectToRoute('app_default');
        }

        $form =$this->createForm(TechnoType::class, $techno);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('imageTemp')->getData();

            if ($file) {
                if ($techno->getImage() !== null) {
                    unlink($techno->getImage()->getPath());
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

                $techno->setImage($image);
            }

            $entityManager->persist($techno);
            $entityManager->flush();

            return $this->redirectToRoute('app_techno');
        }

        return  $this->render('techno/modif.html.twig', [
            'techno' => $techno,
            'form' => $form->createView()
        ]);
    }

    #[Route('/image_delete',name: 'app_techno_modification_image_delete')]
    public function technoImageDelete(Request $request,EntityManagerInterface $entityManager, TechnoRepository $technoRepository): Response {

        $technoId = intval($request->get('technoId'));
        $technoImage = $request->get('technoImage');

        if ($technoId === 0) {
            return $this->redirectToRoute('app_default');
        }

        $techno = $technoRepository->findById($technoId);

        if ($techno->getImage()->getPath() !== $technoImage) {
            return $this->redirectToRoute('app_default');
        }

        unlink($technoImage);
        $techno->setImage(null);

        $entityManager->persist($techno);
        $entityManager->flush();

        return  $this->redirectToRoute('app_techno_modification', [
            'technoId' => $techno->getId(),
        ], 307);
    }
}
