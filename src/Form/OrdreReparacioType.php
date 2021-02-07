<?php

namespace App\Form;

use App\Entity\OrdreReparacio;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\{PercentType,CheckboxType, NumberType, DateType,DateTimeType, ChoiceType, TextareaType};
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\{Vehicle, User, Pressupost};

class OrdreReparacioType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('any', NumberType::class, ['label' => 'Any', 'disabled' => True])
            ->add('tasca', TextareaType::class, ['label' => 'Tasca'])
            ->add('dataCreacio', DateType::class, ['label' => 'Data ',    'format' => 'dd MM yyyy', 'disabled' => True])
            ->add('iva', ChoiceType::class, [
                'choices' => [
                    '21%' => 0.21,
                    '0%' => 0
                ],
            ])
            ->add('dataEntrada', DateTimeType::class, ['label' => 'Data Entrada ', 'disabled' => True])
            ->add('dataSortida'/*, DateTimeType::class, ['label' => 'Data Sortida ']*/)
            ->add('autoritzacio',CheckboxType::class,['label'=>'Autoritza la reparació'])
            ->add('combustible', PercentType::class, ['label' => 'Combustible'])
            ->add('quilometres', NumberType::class, ['label' => 'Quilòmetres',])
            ->add(
                'vehicle',
                EntityType::class,
                [
                    'label' => 'Vehicle',
                    'class' => Vehicle::class,

                ]
            )
            ->add('estat',ChoiceType::class, [
                'choices' => [
                    '✅ Obert' => False,
                    '❌ Tancat' => True,
                    
                    
                ],])
            /*->add(
                'treballador',
                EntityType::class,
                [
                    'label' => 'Treballador',
                    'class' => User::class,
                ]
            )*/
            ->add(
                'pressupost'/*,
                EntityType::class,
                [
                    'required' => false,
                    'label' => 'Pressupost',
                    'class' => Pressupost::class,
                    'placeholder' => false,
                    'empty_data'  => null
                ]*/
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => OrdreReparacio::class,
        ]);
    }
}
