<?php

namespace App\Form;

use App\Entity\ProjectStructure;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ProjectStructureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $project = $options['project'];
        $builder
            ->add('name')
            ->add('description')
            ->add('one_person_only')
            ->add('reports_to', EntityType::class, [
                'class' => ProjectStructure::class,
                'placeholder' => "Reports to",
                'required' => false,
                'query_builder' => function (EntityRepository $er) use($project){
                    $res = $er->createQueryBuilder('r')
                    ->andWhere('r.project = :project')
                    ->setParameter('project', $project);
                    return $res;
                }
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired('project');
        $resolver->setDefaults([
            'data_class' => ProjectStructure::class,
        ]);
    }
}
