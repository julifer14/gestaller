<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\{TextType, NumberType, DateType, ChoiceType, TextareaType, EmailType};
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, ['label' => 'Nom'])
            ->add('cognom', TextType::class, ['label' => 'Cognoms'])
            ->add('dni', TextType::class, ['label' => 'DNI'])
            ->add('telefon', TextType::class, ['label' => 'Telefon'])
            ->add('email', EmailType::class, ['label' => 'Correu','disabled' => True])
            /*->add('roles',ChoiceType::class, [
            'choices' => [
                'mecanic' => 'Mecanic',
                'administratiu' => 'Administratiu',
                'director' => 'Director'
            ],])*/
            
            ->add('roles', CollectionType::class, [
                'entry_type'   => ChoiceType::class,
                'entry_options'  => [
                    'choices'  => [
                        'Mecanic' => 'ROLE_MECANIC',
                        'Administratiu'     => 'ROLE_ADMIN',
                        'Director'    => 'ROLE_DIRECTOR',
                    ],
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
