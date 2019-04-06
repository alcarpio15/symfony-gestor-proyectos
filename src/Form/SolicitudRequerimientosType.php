<?php

namespace App\Form;

use App\Entity\SolicitudRequerimientos;
use App\Entity\AreaCoordinacion;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class SolicitudRequerimientosType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('asunto', TextType::class)
            ->add('descripcion', TextareaType::class, array(
                'label' => 'Descripción',
                'required' => false,
            ))
            ->add('procedenciaDepartamento', TextType::class, array(
                'label' => 'Departamento de Origen',
                'required' => false,
            ))
            ->add('procedenciaTelefono', TextType::class, array(
                'label' => 'Teléfono de Contacto',
                'required' => false,
            ))
            ->add('procedenciaEmail', EmailType::class, array(
                'label' => 'Email de Contacto',
                'required' => false,
            ))
            ->add('area', EntityType::class, array(
                'class' => AreaCoordinacion::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('ac')
                        ->orderBy('ac.id', 'ASC');
                },
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SolicitudRequerimientos::class,
        ]);
    }
}
