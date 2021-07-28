<?php

namespace App\Form;

use App\Entity\ProjectDeliverable;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProjectDeliverableType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('description')
            ->add('delivery_date')
            ->add('payable_amount')
            ->add('percentage')
            ->add('planned_delivery_date')
            ->add('payment_currency')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ProjectDeliverable::class,
        ]);
    }
}
