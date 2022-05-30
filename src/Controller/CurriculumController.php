<?php

namespace App\Controller;

use App\Entity\Curriculum;
use App\Entity\File;
use App\Form\CurriculumType;
use App\Repository\CurriculumRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/curriculum')]
class CurriculumController extends AbstractController
{
    #[Route('/', name: 'app_curriculum')]
    public function index(CurriculumRepository $curriculumRepository, CurriculumType $curriculumType , Request $request, SluggerInterface $slugger, EntityManagerInterface $entityManager): Response
    {
        $cvList = $curriculumRepository->findAll();

        if (count($cvList) > 1) {
//            return $this->redirectToRoute('app_default');
            dd('erreur');
        }

        $cv = count($cvList) == 0 ? new Curriculum() : $cvList[0];

        $form = $this->createForm(CurriculumType::class,$cv);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('fileTemp')->getData();

            if ($file) {
                $pdf = new File();
                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();
                $pdf
                    ->setExtension($file->guessExtension())
                    ->setName($safeFilename)
                    ->setSize($file->getSize());

                try {
                    $file
                        ->move(
                            $this->getParameter('file_directory'),
                            $newFilename
                        )
                    ;

                } catch (FileException $e) {
                    throw $e;
                }

                $pdf->setPath('uploads/'.$newFilename);
                if ($cv->getFile()) {
                    unlink($cv->getFile()->getPath());
                    $entityManager->remove($cv->getFile());
                }
                $cv->setFile($pdf);
                $entityManager->persist($cv);

            } else {
                if ($cv->getFile()) {
                    unlink($cv->getFile()->getPath());
                    $clone = $cv->getFile();
                    $entityManager->remove($cv);
                    $entityManager->remove($clone);
                }
            }

            $entityManager->flush();

            return $this->redirectToRoute('app_curriculum');
        }

        return $this->render('curriculum/index.html.twig', [
            'cv' => $cv,
            'form' => $form->createView(),
        ]);
    }
}
