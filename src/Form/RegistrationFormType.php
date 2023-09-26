<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Regex;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'attr' => ['class' => 'form-control'],
                'required' => true,
                'constraints' => [
                    new Email(),
                ],
            ])
            ->add('pseudo', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'required' => true,
                // Pas de caractères spéciaux
                'constraints' => [
                    new Regex([
                        'pattern' => '/^[a-zA-Z0-9-_]+$/',
                        'message' => 'Votre pseudo ne doit être composé que de lettres, chiffres et "-" ou "_".',
                    ]),
                ],
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'Vous devez accepter les conditions d\utilisation pour vous inscrire.',
                    ]),
                ],
                'attr' => ['class' => 'form-check-input'],
                // 'required' => true,
                'label' => 'Accéptez les termes d\'agrément ',
            ])
            ->add('plainPassword', RepeatedType::class, [
                'mapped' => false,
                'type' => PasswordType::class,
                'invalid_message' => 'The password fields must match.',
                'options' => ['attr' => ['class' => 'password-field form-control']],
                'required' => true,
                'first_options'  => ['label' => 'Mot de passe'],
                'second_options' => ['label' => 'Comfirmez le mot de passe'],
                'required' => true,
                'constraints' => [
                    new Length([
                        'min' => 10,
                        'minMessage' => 'Le mots de passe doit être au moins de 10 caractères.',
                    ]),
                    new Regex([
                        'pattern' => '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{10,}$/',
                        'message' => 'Le mot de passe doit contenir au moins 1 minuscule, 1 majuscule, 1 chiffre et 1 caractère spécial.',
                    ]),
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'S\'inscrire',
                'attr' => [
                    "class" => "btn btn-primary registerBtn", 
                    "disabled" => true
                ],
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
