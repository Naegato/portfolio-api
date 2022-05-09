<?php

namespace App\Form;

use App\Entity\Project;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProjectInsertionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class,[
                'row_attr' => [
                    'class' => 'col-lg-3 m-2 col-md-5  col-12 '
                ]
            ])
            ->add('description', TextType::class,[
                'row_attr' => [
                    'class' => 'col-lg-3 m-2 col-md-5  col-12 '
                ]
            ])
            ->add('dateStart', DateTimeType::class,[
                'row_attr' => [
                    'class' => 'col-lg-3 m-2 col-md-5  col-12 '
                ]
            ])
            ->add('dateEnd', DateTimeType::class,[
                'row_attr' => [
                    'class' => 'col-lg-3 m-2 col-md-5  col-12 '
                ]
            ])
            ->add('duration',IntegerType::class,[
                'row_attr' => [
                    'class' => 'col-lg-3 m-2 col-md-5  col-12 '
                ]
            ])
            ->add('overview',null,[
                'row_attr' => [
                    'class' => 'col-lg-3 m-2 col-md-5  col-12 '
                ]
            ])
            ->add('technos',null,[
                'row_attr' => [
                    'class' => 'col-lg-3 m-2 col-md-5  col-12 '
                ]
            ])
            ->add('tools',null,[
                'row_attr' => [
                    'class' => 'col-lg-3 m-2 col-md-5  col-12 '
                ]
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
