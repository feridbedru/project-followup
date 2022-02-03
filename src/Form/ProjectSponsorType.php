<?php

namespace App\Form;

use App\Entity\Organization;
use App\Entity\ProjectSponsor;
use App\Entity\SponsorshipType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ProjectSponsorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type', EntityType::class, [
                'class' => SponsorshipType::class,
                'placeholder' => "Choose type",
                'required' => true,
                'query_builder' => function (EntityRepository $er){
                    $res = $er->createQueryBuilder('t')
                        ->andWhere('t.name is not null');
                    return $res;
                }
            ])
            ->add('organization', EntityType::class, [
                'class' => Organization::class,
                'placeholder' => "Choose organization",
                'required' => true,
                'query_builder' => function (EntityRepository $er){
                    $res = $er->createQueryBuilder('t')
                        ->andWhere('t.name is not null');
                    return $res;
                }
            ])
            ->add('additional_info')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ProjectSponsor::class,
        ]);
    }
}
