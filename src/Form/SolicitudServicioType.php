<?php

namespace App\Form;

use App\Entity\SolicitudServicio;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SolicitudServicioType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('asunto', TextType::class)
            ->add('codigoDocumento', TextType::class, array(
                'label' => 'Oficio de Servicio'
            ))
            ->add('descripcion', TextareaType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SolicitudServicio::class,
        ]);
    }
}
