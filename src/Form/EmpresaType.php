<?php

namespace App\Form;

use App\Entity\Empresa;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Form\FormEvents;

class EmpresaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('direccio')
            ->add('codipostal')
            ->add('ciutat')
            ->add('provincia')
            ->add('logo',FileType::class, [
                'label' => 'Logo',

                // unmapped means that this field is not associated to any entity property
                'mapped' => false,

                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,

                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Una imatge siusplau',
                    ])
                ],
            ])
            ->add('telefon')
            ->add('nif')
            ->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
                
                $empresa = $event->getData();
                $form = $event->getForm();
                $logoFile = $form->get('logo')->getData();
                if ($logoFile) {
                    try {
                        $logoFile->move(
                            'images/',
                            $logoFile->getClientOriginalName()
                        );
                    } catch (FileException $e) {
                        // ... handle exception if something happens during file upload
                    }
    
                    // updates the 'brochureFilename' property to store the PDF file name
                    // instead of its contents
                    $empresa->setLogo($logoFile->getClientOriginalName());
                }
                
            })
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Empresa::class,
        ]);
    }
}
