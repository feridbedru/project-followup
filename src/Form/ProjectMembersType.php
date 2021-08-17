<?php

namespace App\Form;

use App\Entity\ProjectMembers;
use App\Entity\ProjectStructure;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\Query\Expr\Join;

class ProjectMembersType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('status', ChoiceType::class, [
                'choices' => [
                    'Active' => '1',
                    'Closed' => '2',
                ],
                'placeholder' => "Choose Status",
                'required' => true,
            ])
            ->add('user', EntityType::class, [
                'class' => User::class,
                'placeholder' => "Choose a user",
                'required' => true,
                'query_builder' => function (EntityRepository $er) {
                    $res = $er->createQueryBuilder('u')
                        // ->from('u')
                        // ->join('')
                        ->leftJoin(ProjectMembers::class, 'p', Join::WITH, 'p.user=u.id');
                    return $res;
                }
            ])
            ->add('role', EntityType::class, [
                'class' => ProjectStructure::class,
                'placeholder' => "Choose a role",
                'required' => true,
                'query_builder' => function (EntityRepository $er) {
                    $res = $er->createQueryBuilder('r')
                        ->andWhere('r.name is not NULL');
                    return $res;
                    dd($res);
                }
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ProjectMembers::class,
        ]);
    }
}
