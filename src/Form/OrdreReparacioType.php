<?php

namespace App\Form;

use App\Entity\OrdreReparacio;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrdreReparacioType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('any')
            ->add('tasca')
            ->add('dataCreacio')
            ->add('iva')
            ->add('dataEntrada')
            ->add('dataSortida')
            ->add('autoritzacio')
            ->add('combustible')
            ->add('quilometres')
            ->add('vehicle')
            ->add('treballador')
            ->add('pressupost')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => OrdreReparacio::class,
        ]);
    }
}
