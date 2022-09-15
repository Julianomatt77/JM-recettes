<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, [
                'label'=>"Nom d'utilisateur",
                'attr'=> ['class'=> 'form-control rounded-pill bg-light']
            ])
            ->add('email', EmailType::class, [
                'attr'=> [
                    'class'=> 'form-control rounded-pill bg-light'
            ]
            ])
            // ->add('agreeTerms', CheckboxType::class, [
            //     'mapped' => false,
            //     'constraints' => [
            //         new IsTrue([
            //             'message' => 'You should agree to our terms.',
            //         ]),
            //     ],
            // ])            
            ->add('plainPassword', RepeatedType::class, [
                'mapped' => false,
                'label'=>'Mot de passe',
                'type'=>PasswordType::class,
                'invalid_message' => 'Veuillez vérifier votre mot de passe',
                'options' =>[
                    'attr'=> [
                    'class' =>'password-field form-control rounded-pill bg-light my-2'
                ]],
                'required' => true,
                'first_options' => ['label' => 'Mot de Passe'],
                'second_options' => ['label' => 'Veuillez confirmer votre mot de Passe'],            
            ])
            ->add('submit', SubmitType::class, [
                'attr'=> [
                    'class'=> 'btn btn-lg bg-dark lightColor'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
