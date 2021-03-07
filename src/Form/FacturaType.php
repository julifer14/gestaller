<?php

namespace App\Form;

use App\Entity\Factura;
use App\Entity\OrdreReparacio;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\{CollectionType, TextType, NumberType, DateType, ChoiceType, TextareaType};
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\{Vehicle, User};

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class FacturaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('any', NumberType::class, ['label' => 'form.any', 'disabled' => True])
            ->add('tasca', TextareaType::class, ['label' => 'Tasca a realitzar '])
            ->add('data', DateType::class, ['label' => 'Data ',    'format' => 'dd MM yyyy', 'disabled' => True])
            ->add('iva', ChoiceType::class, [
                'choices' => [
                    '21%' => 0.21,
                    '0%' => 0
                ],
            ])
            ->add('estat', ChoiceType::class, [
                'choices' => [
                    '❌ No pagada' => False,
                    '✅ Pagada' => True,

                ],
            ])
            ->add('formaPagament', ChoiceType::class, [
                
                'choices' => [
                    'No pagada' => null,
                    'Efectiu' => 0,
                    'Tarjeta' => 1,
                    'Xec' => 2

                ],
                'label' => 'Forma de pagament',
                 'required' => false
            ])
            ->add('quilometres', NumberType::class, ['label' => 'form.km'])
            //->add('total', NumberType::class, ['label' => 'form.total'])
            ->add('observacions', TextareaType::class, ['label' => 'Observacions ', 'required' => false,])
            ->add(
                'vehicle',
                EntityType::class,
                [
                    'label' => 'Vehicle',
                    'class' => Vehicle::class,

                ]
            )
            ->add(
                'treballador',
                EntityType::class,
                [
                    'label' => 'Treballador',
                    'class' => User::class,
                ]
            )
            /*->add(
                'ordre',
                EntityType::class,
                [
                    'label' => 'Ordre Reparació',
                    'class' => OrdreReparacio::class,
                    
                ]
            )*/
            ->add('liniaFacturas', CollectionType::class, [
                'entry_type' => LiniaFacturaType::class,
                'allow_add' => true,
                'allow_delete' => true,
            ])

            ->add('totalLinies', HiddenType::class, /*['mapped' => false]*/)
            ->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
                $factura = $event->getData();
                $form = $event->getForm();
                $liniaFactura = $form->get('liniaFacturas')->getData();
            });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Factura::class,
            'allow_extra_fields' => true
        ]);
    }
}
