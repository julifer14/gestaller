<?php

namespace App\Form;

use App\Entity\Article;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\{TextType, NumberType};
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Form\CategoriaType;
use App\Entity\Categoria;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        
        $builder
            ->add('nom',TextType::class, ['label' => 'Nom '])
            ->add('descripcio',TextType::class, ['label' => 'Descripció '])
            ->add('preu',NumberType::class, ['label' => 'Preu Unitari '])
           // ->add('iva',NumberType::class, ['label' => 'IVA '])
             ->add('iva',ChoiceType::class, [
                'choices' => [
                    '21%' => 0.21,
                    '0%' => 0
                ],])
            ->add('stock',NumberType::class, ['label' => 'Stock '])
            ->add('categoria', EntityType::class,
            [
                'label' => 'Categoria',
                'class' => Categoria::class,
                'choices' => $options['categories'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
            'categories'  => null,
        ]);
    }
}
