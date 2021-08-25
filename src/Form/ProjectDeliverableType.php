<?php

namespace App\Form;

use App\Entity\ProjectDeliverable;
use App\Entity\ProjectMilestone;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;

class ProjectDeliverableType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $project = $options['project'];
        $builder
            ->add('title')
            ->add('description')
            ->add('milestone', EntityType::class, [
                'class' => ProjectMilestone::class,
                'placeholder' => "Choose milestone",
                'required' => true,
                'query_builder' => function (EntityRepository $er) use($project){
                    $res = $er->createQueryBuilder('d')
                        ->andWhere('d.project = :project')
                        ->setParameter('project', $project);
                    return $res;
                }
            ])
            ->add('percentage')
            ->add('planned_delivery_date', DateType::class, ['widget' => 'single_text'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired('project');
        $resolver->setDefaults([
            'data_class' => ProjectDeliverable::class,
        ]);
    }
}
