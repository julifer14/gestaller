<?php

namespace App\Form;

use App\Entity\Pressupost;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\{TextType, NumberType,DateType,ChoiceType,TextareaType};
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Vehicle;

class PressupostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('any',NumberType::class, ['label' => 'Any ','disabled'=>True])
            ->add('tasca',TextareaType::class, ['label' => 'Tasca a realitzar '])
            ->add('data',DateType::class, ['label' => 'Data ',    'format' => 'dd MM yyyy',])
           // ->add('iva',NumberType::class, ['label' => 'IVA '])
            ->add('iva',ChoiceType::class, [
                'choices' => [
                    '21%' => 0.21,
                    '0%' => 0
                ],])
            ->add('estat',ChoiceType::class, [
                'choices' => [
                    'Acceptar' => True,
                    'Rebutjat' => False
                ],])
            ->add('vehicle', EntityType::class,
            [
                'label' => 'Vehicle',
                'class' => Vehicle::class,
                
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Pressupost::class,
        ]);
    }
}
