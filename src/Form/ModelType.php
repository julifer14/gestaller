<?php

namespace App\Form;

use App\Entity\Model;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\{TextType, NumberType};
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Marca;

class ModelType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            
            ->add('Marca', EntityType::class,
            [
                'label' => 'Marca',
                'class' => Marca::class,
                'choices' => $options['marques'],
            ])
            ->add('nom',TextType::class, ['label' => 'Model '])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Model::class,
            'marques'  => null,
        ]);
    }
}
