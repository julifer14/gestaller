<?php

namespace App\Form;

use App\Entity\OrdreReparacio;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\{PercentType, CheckboxType, NumberType, DateType, DateTimeType, ChoiceType, TextareaType};
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\{Vehicle, User, Pressupost};
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class OrdreReparacioType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('any', NumberType::class, ['label' => 'Any', 'disabled' => True])
            ->add('tasca', TextareaType::class, ['label' => 'Tasca'])
            ->add('dataCreacio', DateType::class, ['label' => 'Data ',    'disabled' => True])
            ->add('iva', ChoiceType::class, [
                'choices' => [
                    '21%' => 0.21,
                    '0%' => 0
                ],
            ])
            ->add('dataEntrada', DateTimeType::class, ['label' => 'Data/Hora Entrada ', 'disabled' => True])
            ->add('dataSortida', DateTimeType::class, [
                'label' => 'Data/Hora Sortida ',  'required' => False
            ])
            ->add('autoritzacio', CheckboxType::class, ['label' => 'Autoritza la reparació'])
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
            ->add('estat', ChoiceType::class, [
                'choices' => [
                    '✅ Oberta' => False,
                    '❌ Tancada' => True,


                ],
            ])
            /*->add(
                'treballador',
                EntityType::class,
                [
                    'label' => 'Treballador',
                    'class' => User::class,
                ]
            )*/
            ->add(
                'pressupost',
                EntityType::class,
                [
                    'required' => false,
                    'label' => 'Pressupost',
                    'class' => Pressupost::class,

                ]
            )
            ->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
                $ordre = $event->getData();
                $form = $event->getForm();
                $dataEntrada = $form->get('dataEntrada')->getData();
                $dataSortida = $form->get('dataSortida')->getData();
                dump($dataEntrada);
                dump($dataSortida);
                dump($dataSortida>$dataEntrada);
                exit;
                
            })
          ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => OrdreReparacio::class,
        ]);
    }
}
