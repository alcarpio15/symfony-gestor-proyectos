<?php

namespace App\Form;

use App\Entity\GestorUsuario;
use App\Entity\AreaCoordinacion;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;


class GestorUsuarioType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class)
            ->add('username', TextType::class)
            ->add('plainPassword', RepeatedType::class, array(
                'type' => PasswordType::class,
                'required' => false,
                'first_options'  => array('label' => 'Contraseña'),
                'second_options' => array('label' => 'Repite la Contraseña'),
            ))
            ->add('nombres', TextType::class)
            ->add('apellidos', TextType::class)
            ->add('areaDesarrollo', EntityType::class, array(
                'class' => AreaCoordinacion::class,
                'required' => false,
                'placeholder' => 'Sin Area',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('ac')
                        ->orderBy('ac.id', 'ASC');
                },
                'label' => 'Area como Desarrollador',
            ))
            ->add('remove_roles',CheckboxType::class, array(
                'mapped' => false,
                'required' => false,
                'label' => '¿Desea Remover Roles?'
            ))
            ->add('roles_options', ChoiceType::class, array(
                'choices'  => array(
                    'Administrador' => 'ROLE_ADMIN',
                    'Director' => 'ROLE_DIRECT',
                    'Coordinador General' => 'ROLE_CORDGN',
                    'Desarrollador' => 'ROLE_DEVLPR',
                    'Analista' => 'ROLE_ANALST'
                ),
                'mapped' => false,
                'required' => false,
                'multiple' => true,
                'expanded' => true,
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => GestorUsuario::class,
        ]);
    }
}