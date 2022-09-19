<?php

namespace App\Form;

use App\Entity\Recette;
use App\Entity\ingredientPerRecette;
use App\Entity\Source;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('search', TextType::class, [
                'label'=>'Rechercher une recette',
                'required'=> false,
                'attr'=> [
                    'class'=> 'form-control rounded-pill bg-light',
                    'placeholder' => 'Rechercher une recette'
                ]
            ])
            // ->add('ingredient', EntityType::class, [
            //     'label'=>'Quel ingrédient ?',
            //     'required'=> false,
            //     'class'=> ingredientPerRecette::class,
            //     'attr'=> ['class'=> 'form-control bg-light']
            // ])
            // ->add('source', EntityType::class, [
            //     'label'=>'Origine de la recette',
            //     'required'=> false,
            //     'class'=> Source::class,
            //     'attr'=> ['class'=> 'form-control bg-light']
            // ])            
            // ->add('recette', TextType::class, [
            //     'required'=> false,
            //     'attr'=> ['class'=> 'form-control rounded-pill bg-light']
            // ])

            ->add('Filtrer', SubmitType::class, [
                'attr'=> [
                    'class'=> 'btn bg-dark lightColor'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
