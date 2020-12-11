<?php

namespace App\Form;

use App\Entity\Vehicle;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\{TextType,  EmailType, TelType,NumberType};
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Form\ClientType;
use App\Entity\Client;
use App\Entity\Model;


class VehicleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        
        $clients = $options['clients'];
        $models = $options['models'];
       
        $builder
            ->add('matricula',TextType::class, ['label' => 'Matricula '])
            /*->add('marca',TextType::class, ['label' => 'Marca '])*/
            ->add('model',EntityType::class, 
                [
                    'label' => 'Model ',
                    'class' => Model::class,
                    'choices'=>$models,
                ]
            )
            ->add('kilometres',NumberType::class, ['label' => 'Quilòmetres '])
            ->add('client', EntityType::class,
                [
                    'label' => 'Client',
                    'class' => Client::class,
                    'choices' => $clients,
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Vehicle::class,
            'clients' => null,
            'models' => null,
        ]);
    }
}
