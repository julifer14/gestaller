<?php

namespace App\Form;

use App\Entity\Client;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\{TextType,  EmailType, TelType,NumberType};
use Symfony\Component\Form\Extension\Core\Type\CountryType;

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
            ->add('pais',CountryType::class, [  'label' => 'Pais ',   
                                                'preferred_choices' => ['ES'] ])

            ->add('telefon',TelType::class, ['label' => 'Telefon '])
            ->add('email', EmailType::class)
            ->add('DNI')
        ;

        //afegir event
        /*
        function dni($dni){
  $letra = substr($dni, -1);
  $numeros = substr($dni, 0, -1);
  $valido;
  if (substr("TRWAGMYFPDXBNJZSQVHLCKE", $numeros%23, 1) == $letra && strlen($letra) == 1 && strlen ($numeros) == 8 ){
    $valido=true;
  }else{
    $valido=false;
  }
}
dni('73547889F'); // $valido=true;
dni('73547889T'); // $valido=false;
dni('7354788M'); // $valido=false;
*/
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Client::class,
        ]);
    }
}
