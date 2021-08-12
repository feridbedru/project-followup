<?php

namespace App\Form;

use App\Entity\EmailTemplate;
use App\Entity\ProjectStructure;
use App\Repository\EmailTemplateRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ProjectEmailType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('projectStructure', EntityType::class, [
                'class' => ProjectStructure::class,
                'placeholder' => "Choose role",
                'required' => true,
                'query_builder' => function (EntityRepository $er) {
                    $res = $er->createQueryBuilder('s')
                        ->andWhere('s.name is not NULL');
                    return $res;
                }
            ])
            ->add('emailTemplate', EntityType::class, [
                'class' => EmailTemplate::class,
                'placeholder' => "Choose template",
                'required' => true,
                'query_builder' => function (EntityRepository $er) {
                    $res = $er->createQueryBuilder('t')
                        ->andWhere('t.name is not NULL');
                    return $res;
                }
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => EmailTemplate::class,
        ]);
    }
}
