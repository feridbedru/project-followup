<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // ->add('username')
            ->add('email')
            // ->add('roles')
            // ->add('password')
            // ->add('isActive')
            // ->add('lastLogin')
            // ->add('confirmToken')
            // ->add('created_at')
            // ->add('status')
            ->add('photo')
            ->add('phone')
            ->add('position')
            ->add('full_name')
            // ->add('created_by')
            // ->add('userGroup')
            ->add('unit')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
