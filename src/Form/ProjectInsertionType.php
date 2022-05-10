<?php

namespace App\Form;

use App\Entity\File;
use App\Entity\Project;
use App\Repository\TechnoRepository;
use App\Repository\ToolRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProjectInsertionType extends AbstractType
{
    private TechnoRepository $technoRepository;

    public function __construct(TechnoRepository $technoRepository, ToolRepository $toolRepository)
    {
        $this->technoRepository = $technoRepository;
        $this->toolRepository = $toolRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $technosRepo = $this->technoRepository->findAll();
        $technos = ['NONE' => null];
        foreach ($technosRepo as $techno) {
            $technos[$techno->getName()] = $techno;
        }

        $toolsRepo = $this->toolRepository->findAll();
        $tools = ['NONE' => null];
        foreach ($toolsRepo as $tool) {
            $tools[$tool->getName()] = $tool;
        }

        $builder
            ->add('name', TextType::class)
            ->add('description', TextType::class)
            ->add('dateStart', DateTimeType::class)
            ->add('dateEnd', DateTimeType::class)
            ->add('duration',IntegerType::class)
            ->add('overviewTemp', FileType::class, [
                'label' => 'Image',
                'required' => false,
            ])
            ->add('technosTemp', ChoiceType::class, [
                'multiple' => true,
                'required' => false,
                'choices' => $technos,
                'label' => 'Technologies',
            ])
            ->add('toolsTemp', ChoiceType::class, [
                'multiple' => true,
                'required' => false,
                'choices' => $tools,
                'label' => 'Tools',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Project::class,
        ]);
    }
}
