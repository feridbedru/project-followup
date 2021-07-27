<?php

namespace App\Form;

use App\Entity\ProjectResource;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ProjectResourceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('description')
            ->add('file', FileType::class, array('data_class' => null,'required' => true))
            ->add('status', ChoiceType::class, [
                'choices' => [
                    'Active' => '1',
                    'Closed' => '2',
                ],
                'placeholder' => "Choose Status",
                'required' => true,
            ])
            ->add('is_public')
            ->add('is_pinned')
            ->add('resource_type')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ProjectResource::class,
        ]);
    }
}
