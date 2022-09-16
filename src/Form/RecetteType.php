<?php

namespace App\Form;

use App\Entity\Recette;
use App\Entity\ingredientPerRecette;
use App\Entity\Source;
use App\Entity\Course;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class RecetteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class,[
                'label'=>'Nom',
                'attr'=>[
                    'class'=>'form-control rounded-pill mb-4 bg-light'
                    ]])
            ->add('nb_personnes', IntegerType::class,[
                'label'=>'Nombre de personnes',
                'attr'=>[
                    'class'=>'form-control rounded-pill mb-4 bg-light',
                    'type' => 'number'
                    ]])
            ->add('description', TextareaType::class,[
                'label'=>'Description',
                'attr'=>[
                    'class'=>'form-control bg-light mb-4', 
                    'rows'=>'10'
                    ]])
            ->add('ingredientPerRecette', EntityType::class,[
                'class'=>ingredientPerRecette::class,
                'mapped' => false,
                'label'=>'Ingredient',
                'attr'=>['class'=>'form-control rounded-pill bg-light mb-4']])
            ->add('source', EntityType::class,[
                'class'=>Source::class,
                'label'=>'Source',
                'attr'=>['class'=>'form-control rounded-pill bg-light mb-4']])
            // ->add('courses', EntityType::class,[
            //     'class'=>Course::class,
            //     'mapped' => false,
            //     'label'=>'Liste de course',
            //     'attr'=>['class'=>'form-control mb-4']])
            ->add('image', FileType::class,[
                'label'=>'Image (optionnel)',
                'mapped' => false,
                'required' => false,
                'constraints'=>[
                    new File([
                         'maxSize'=> '2M',
                         'maxSizeMessage'=> 'Votre fichier est trop lourd !',
                         'mimeTypes'=> [
                             'image/jpeg',
                             'image/png'
                         ],
                         'mimeTypesMessage'=> 'Veuillez uploader une image (jpg ou png)!'
                             ] )],
                'attr'=>
                    ['class'=>'form-control rounded-pill bg-light mb-4 darkColor']
                  ])
            // ->add('submit', SubmitType::class, [
            //     'attr'=> [
            //         'class'=> 'btn bg-dark lightColor'
            //     ]
            // ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recette::class,
        ]);
    }
}
