<?php

namespace App\Form;

use App\Entity\Pressupost;
use App\Form\LiniaPressupostType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\{CollectionType, TextType, NumberType,DateType,ChoiceType,TextareaType};
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\{Vehicle, User};

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class PressupostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('any',NumberType::class, ['label' => 'form.any','disabled'=>True])
            ->add('tasca',TextareaType::class, ['label' => 'Tasca a realitzar '])
            ->add('data',DateType::class, ['label' => 'Data ',    'format' => 'dd MM yyyy','disabled'=>True])
           // ->add('iva',NumberType::class, ['label' => 'IVA '])
            ->add('iva',ChoiceType::class, [
                'choices' => [
                    '21%' => 0.21,
                    '0%' => 0
                ],])
            ->add('estat',ChoiceType::class, [
                'choices' => [
                    '✅ Acceptar' => True,
                    '❌ Rebutjat' => False
                ],])
            ->add('vehicle', EntityType::class,
            [
                'label' => 'Vehicle',
                'class' => Vehicle::class,
                
            ])
            ->add('treballador',EntityType::class,
            [
                'label' => 'Treballador',
                'class' => User::class,
            ])

            ->add('liniaPressuposts',CollectionType::class, [
                'entry_type' => LiniaPressupostType::class,
                'allow_add' => true,
                'allow_delete' => true,
            ])   
            
            ->add('totalLinies', HiddenType::class, /*['mapped' => false]*/)
            ->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
                $pressupost = $event->getData();
                $form = $event->getForm();
                $liniaPressupost = $form->get('liniaPressuposts')->getData();
                
            })
          ;
                
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Pressupost::class,
            'allow_extra_fields' => true
        ]);
    }
}
