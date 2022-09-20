<?php

namespace App\Form;

use App\Entity\Course;
use App\Entity\Recette;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class CourseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date_course', DateType::class,[
                'label'=>'Date',
                'attr'=>[
                    'class'=>'form-control rounded-pill mb-4 bg-light'
                    ]])
            ->add('name', TextType::class,[
                'label'=>'Nom',
                'required' => false,
                'attr'=>[
                    'class'=>'form-control rounded-pill mb-4 bg-light'
                    ]])
            // ->add('recette', EntityType::class,[
            //     'class'=>Recette::class,
            //     'mapped' => false,
            //     'label'=>'Recette',
            //     'attr'=>['class'=>'form-control rounded-pill bg-light mb-4']])
            // ->add('qty', IntegerType::class,[
            //     'label'=>'Nombre de personnes',
            //     'attr'=>[
            //         'class'=>'form-control rounded-pill mb-4 bg-light',
            //         'type' => 'number'
            //         ]])
            // ->add('user', EntityType::class,[
            //     'class'=>User::class,
            //     'mapped' => false,
            //     'label'=>'Ingredient',
            //     'attr'=>['class'=>'form-control rounded-pill bg-light mb-4']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Course::class,
        ]);
    }
}
