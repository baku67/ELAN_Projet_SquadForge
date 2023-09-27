<?php

namespace App\Form;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Entity\Candidature;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class CandidatureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('text', TextareaType::class, [
                'label' => 'Introduction <span class="requiredStar"><i class="fa-solid fa-star-of-life"></i></span>',
                'label_html' => true,
                'required' => true,
                'attr' => [
                    "class" => "form-control",
                    'placeholder' => 'Votre message...',
                    'rows' => 5,
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Vous devez entrer un message ne peut pas être vide.',
                    ]),
                    new Length([
                        'min' => 1,
                        'max' => 1500,
                        'maxMessage' => 'Le message ne peut pas faire moins de 1 caractères.',
                        'maxMessage' => 'Le message ne peut pas faire plus de 1500 caractères.',
                    ]),
                ],
            ])

            ->add('submit', SubmitType::class, [
                'label' => 'Envoyer&nbsp;<i class="fa-solid fa-paper-plane"></i>',
                'label_html' => true,
                'attr' => [
                    "class" => "candidatureSubmitBg"
                ]
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Candidature::class,
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            // important part; unique key
            'csrf_token_id'   => 'form_intention',
        ]);
    }
}
