<?php

namespace App\Form;

use App\Entity\ProjectDeliverable;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class ProjectDeliverableType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('description')
            ->add('milestone')
            ->add('delivery_date', DateType::class, ['widget' => 'single_text'])
            ->add('percentage')
            ->add('planned_delivery_date', DateType::class, ['widget' => 'single_text'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ProjectDeliverable::class,
        ]);
    }
}
