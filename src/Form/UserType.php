<?php

namespace App\Form;

use App\Entity\Organization;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email')
            // ->add('roles')
            ->add('photo', FileType::class, [
                'required' => false,
                'mapped' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '4M',
                        'mimeTypes' => [
                            'image/*',
                        ],
                    ])
                ],
            ])
            ->add('phone')
            ->add('position')
            ->add('full_name')
            ->add('unit')
            // ->add('organization', EntityType::class, [
            //     'class' => Organization::class,
            //     'placeholder' => "Organization",
            //     'required' => true,
            //     'query_builder' => function (EntityRepository $er){
            //         $res = $er->createQueryBuilder('r')
            //         ->andWhere('r.name is not null');
            //         return $res;
            //     }
            // ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
