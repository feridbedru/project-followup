<?php

namespace App\Form;

use App\Entity\ProjectVersion;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProjectVersionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('version')
            ->add('description')
            ->add('released_date')
            // ->add('created_at')
            // ->add('project')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ProjectVersion::class,
        ]);
    }
}
