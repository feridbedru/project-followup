<?php

namespace App\Form;

use App\Entity\ProjectActivity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class ProjectActivityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('description')
            ->add('due_date', DateType::class, ['widget' => 'single_text'])
            ->add('display_order')
            ->add('can_be_concurrent')
            ->add('weight')
            ->add('milestone')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ProjectActivity::class,
        ]);
    }
}
