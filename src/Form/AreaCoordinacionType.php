<?php

namespace App\Form;

use App\Entity\AreaCoordinacion;
use App\Entity\GestorUsuario;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class AreaCoordinacionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('area', TextType::class)
            ->add('coordinador', EntityType::class, array(
                'class' => GestorUsuario::class,
                'choice_label' => function ($coordinador){
                    $label = $coordinador->getUsername();
                    $label .= " - " . $coordinador->getNombres();
                    $label .= " " . $coordinador->getApellidos();

                    return rtrim($label, "-");
                }
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => AreaCoordinacion::class,
        ]);
    }
}
