<?php

namespace App\Form;

use App\Entity\IngredientPerRecette;
use App\Entity\Ingredient;
use App\Entity\Recette;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IngredientPerRecetteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('qty', IntegerType::class,
                  [
                      'attr'=> [
                          'class'=> 'form-control rounded-pill bg-light'
                      ]
                  ])
            ->add('qty_pp', IntegerType::class,
                  [
                      'attr'=> [
                          'class'=> 'form-control rounded-pill bg-light'
                      ]
                  ])
            ->add('ingrediient',EntityType::class,['class'=>Ingredient::class,'label'=>'Ingrédient','attr'=>['class'=>'form-control rounded-pill bg-light mb-4']])
            ->add('unite', TextType::class,[
                'label'=>'Unité',
                'attr'=>[
                    'class'=>'form-control rounded-pill mb-4 bg-light'
                    ]])
            ->add('recette', EntityType::class,['class'=>Recette::class,'label'=>'Recette','attr'=>['class'=>'form-control rounded-pill bg-light mb-4']])
            ->add('submit', SubmitType::class, [
                'attr'=> [
                    'class'=> 'btn bg-dark lightColor',
                    'value' => 'Ajouter un ingrédient'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => IngredientPerRecette::class,
        ]);
    }
}
