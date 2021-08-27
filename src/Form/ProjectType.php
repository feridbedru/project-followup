<?php

namespace App\Form;

use App\Entity\OrganizationUnit;
use App\Entity\Project;
use App\Entity\Program;
use App\Entity\Objective;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
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
            ->add('unit', EntityType::class, [
                'class' => OrganizationUnit::class,
                'placeholder' => "Choose Accountable unit",
                'required' => true,
                'query_builder' => function (EntityRepository $er) {
                    $res = $er->createQueryBuilder('u')
                        ->andWhere('u.name is not NULL');
                    return $res;
                }
                ])
            ->add('project_manager', EntityType::class, [
                'class' => User::class,
                'placeholder' => "Choose Manager",
                'required' => true,
                'query_builder' => function (EntityRepository $er) {
                    $res = $er->createQueryBuilder('u')
                        ->andWhere('u.full_name is not NULL');
                    return $res;
                }
            ])
            ->add('planned_value')
            ->add('objective', EntityType::class, [
                'class' => Objective::class,
                'placeholder' => "Choose Objective",
                'required' => true,
                'query_builder' => function (EntityRepository $er) {
                    $res = $er->createQueryBuilder('o')
                        ->andWhere('o.name is not NULL');
                    return $res;
                }
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Project::class,
        ]);
    }
}
