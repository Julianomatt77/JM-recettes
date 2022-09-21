<?php

namespace App\Form;

use App\Entity\CourseRecette;
use App\Entity\Recette;
use App\Entity\Course;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class CourseRecetteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('qty', ChoiceType::class,[
                'label'=>'Couverts',
                'choices' => [
                    '1' => 1,
                    '2' => 2,
                    '3' => 3,
                    '4' => 4,
                    '5' => 5,
                    '6' => 6,
                    '7' => 7,
                    '8' => 8,
                    '9' => 9,
                    '10' => 10,
                    '15' => 15,
                    '20' => 20,
                    '30' => 30,
                    '40' => 40,
                    ],
                    'attr'=> [
                        'class'=> 'form-control rounded-pill bg-light'
                    ]
                  ])
            ->add('course', EntityType::class,[
                'class'=>Course::class,
                'label'=>'Liste de course',
                'attr'=>[
                    'class'=>'form-control rounded-pill bg-light mb-4'
                    ]
                ])
            ->add('recette', EntityType::class,[
                'class'=>Recette::class,
                'label'=>'Recette',
                'attr'=>[
                    'class'=>'form-control rounded-pill bg-light mb-4'
                    ]
                ])
            // ->add('submit', SubmitType::class, [
            // 'label' => "Ajouter à la liste de course",
            // 'attr'=> [
            //     'class'=> 'btn border bg-dark yellowFont rounded-pill my-3',
            //     // 'href' => "{ path('cart_add', {'id': product.id}) }}",
            //     'type' => 'button'
            // ]
            // ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CourseRecette::class,
        ]);
    }
}
