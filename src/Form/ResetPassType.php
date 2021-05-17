<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Form\Extension\Core\Type\{TextType, ChoiceType, EmailType, PasswordType};

class ResetPassType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('plainPassword', PasswordType::class, [
                'label' => 'Nouveau mot de passe',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le mot de passe ne peux pas être vide',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'le mot de passe doit être formé d\'au moins {{ limit }} caractères',
                        'max' => 4096,
                    ]),
                ],
            ])

            ->add('plainPassword2', PasswordType::class, [
                'label' => 'Répéter votre mot de passe',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le mot de passe ne peux pas être vide',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'le mot de passe doit être formé d\'au moins {{ limit }} caractères',
                        'max' => 4096,
                    ]),
                ],
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
            $resolver->setDefaults([
                'label' => false,
            ]);
    }
}


