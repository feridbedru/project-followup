<?php

namespace App\Form;

use App\Entity\ActivityProgress;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class ActivityProgressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('content')
            ->add('completed_on', DateType::class, ['widget' => 'single_text'])
            ->add('rating')
            ->add('remark')
            ->add('file', FileType::class, array('data_class' => null,'required' => true))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ActivityProgress::class,
        ]);
    }
}
