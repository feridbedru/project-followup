<?php

namespace App\Form;

use App\Entity\Program;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class ProgramType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('stakeholders')
            ->add('amount')
            ->add('start_date', DateType::class, ['widget' => 'single_text'])
            ->add('end_date', DateType::class, ['widget' => 'single_text'])
            ->add('objective')
            ->add('program_manager')
            ->add('status', ChoiceType::class, [
                'choices' => [
                    'Active' => '1',
                    'Pending' => '2',
                    'Closed' => '3',
                ],
                'placeholder' => "Choose Status",
                'required' => true,
                'attr' => ['class' => 'mx-5']
            ])
            ->add('currency')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Program::class,
        ]);
    }
}
