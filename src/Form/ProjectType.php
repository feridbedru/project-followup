<?php

namespace App\Form;

use App\Entity\Project;
use App\Entity\Program;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ProjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('program', EntityType::class, [
                'class' => Program::class,
                'placeholder' => "Choose Program",
                'required' => false,
                'query_builder' => function (EntityRepository $er) {
                    $res = $er->createQueryBuilder('p')
                        ->andWhere('p.name is not NULL');
                    return $res;
                }
            ])
            ->add('description')
            ->add('currency')
            ->add('amount')
            ->add('start_date', DateType::class, ['widget' => 'single_text'])
            ->add('end_date', DateType::class, ['widget' => 'single_text'])
            ->add('stakeholder')
            ->add('outcome')
            ->add('baseline')
            ->add('unit')
            ->add('project_manager')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Project::class,
        ]);
    }
}
