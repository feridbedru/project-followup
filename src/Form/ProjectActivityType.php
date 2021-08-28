<?php

namespace App\Form;

use App\Entity\ProjectActivity;
use App\Entity\ProjectMilestone;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ProjectActivityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $project = $options['project'];
        $builder
            ->add('title')
            ->add('description')
            ->add('is_active')
            ->add('display_order')
            ->add('weight')
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
            ->add('parent', EntityType::class, [
                'class' => ProjectActivity::class,
                'placeholder' => "Choose parent",
                'required' => false,
                'query_builder' => function (EntityRepository $er) use($project){
                    $res = $er->createQueryBuilder('d')
                        ->andWhere('d.project = :project')
                        ->setParameter('project', $project);
                    return $res;
                }
            ])
            ->add('start_date', DateType::class, ['widget' => 'single_text'])
            ->add('end_date', DateType::class, ['widget' => 'single_text']);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired('project');
        $resolver->setDefaults([
            'data_class' => ProjectActivity::class,
        ]);
    }
}
