<?php

namespace App\Form;

use App\Entity\AreaCoordinacion;
use App\Entity\GestorUsuario;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class AreaCoordinacionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('area', TextType::class)
            ->add('coordinador', EntityType::class, array(
                'class' => GestorUsuario::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('gu')
                        ->andWhere('gu.activo = :activo')
                        ->setParameter('activo', true)
                        ->orderBy('gu.id', 'ASC');
                },
                'choice_label' => function ($coordinador){
                    $label = $coordinador->getUsername();
                    $label .= " - " . $coordinador->getNombres();
                    $label .= " " . $coordinador->getApellidos();

                    return rtrim($label, "-");
                },
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
