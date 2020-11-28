<?php

namespace App\Form;

use App\Entity\Client;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\{TextType,  EmailType, TelType,NumberType};

class ClientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom',TextType::class, ['label' => 'Nom '])
            ->add('cognoms',TextType::class, ['label' => 'Cognoms '])
            ->add('adreca',TextType::class, ['label' => 'Adreça '])
            ->add('cp',NumberType::class, ['label' => 'Codi Postal '])
            ->add('ciutat',TextType::class, ['label' => 'Ciutat '])
            ->add('pais',TextType::class, ['label' => 'Pais '])

            ->add('telefon',TelType::class, ['label' => 'Telefon '])
            ->add('email', EmailType::class)
            ->add('DNI')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Client::class,
        ]);
    }
}
