<?php

namespace App\Form;

use App\Entity\SolicitudTarea;
use App\Entity\GestorUsuario;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class SolicitudTareaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('asunto', TextType::class)
            ->add('descripcion', TextareaType::class)
            ->add('fechaEntregaEstimada', DateTimeType::class, array(
                'placeholder' => array(
                    'year' => 'Año', 'month' => 'Mes', 'day' => 'Día',
                    'hour' => 'Hora', 'minute' => 'Minutos', 'second' => 'Segundos',
                )
            ))
            ->add('desarrollador', EntityType::class, array(
                'class' => GestorUsuario::class,
                'query_builder' => function (EntityRepository $er) use ($options) {
                    return $er->createQueryBuilder('gu')
                        ->leftJoin('gu.areaDesarrollo','da')
                        ->andWhere('gu.activo = :activo')
                        ->andWhere('da.id = :area_id')
                        ->setParameters(array(
                            'activo' => true, 'area_id' => $options['area']
                        ))
                        ->orderBy('gu.id', 'ASC');
                },
                'choice_label' => function ($desarrollador){
                    $label = $desarrollador->getUsername();
                    $label .= " - " . $desarrollador->getNombres();
                    $label .= " " . $desarrollador->getApellidos();

                    return rtrim($label, "-");
                },
                'placeholder' => 'Por Seleccionar.'
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SolicitudTarea::class,
            'area' => 0,
        ]);

        $resolver->setRequired('area');
        $resolver->setAllowedTypes('area', 'integer');
    }
}
