<?php

namespace App\Form;

use App\Entity\ActivityUser;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class ActivityUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('assignment_description')
            ->add('start_date', DateType::class, ['widget' => 'single_text'])
            ->add('end_date', DateType::class, ['widget' => 'single_text'])
            ->add('user')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ActivityUser::class,
        ]);
    }
}
