<?php

namespace App\Form;

use App\Entity\TopicPost;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;


class TopicPostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('text', TextType::class, [
                'required' => true,
                'attr' => [
                    "class" => "form-control",
                    'placeholder' => 'Votre message...'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le message ne peut pas être vide.',
                    ]),
                    new Length([
                        'max' => 1000,
                        'maxMessage' => 'Le message ne peut pas faire plus de 1000 caractères.',
                    ]),
                ],
            ])
            ->add('submit', SubmitType::class, [
                // 'label' => 'Publier',
                'label' => '<i class="fa-solid fa-paper-plane"></i>',
                'label_html' => true,
                'attr' => ["class" => "btn btn-primary"]
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TopicPost::class,
        ]);
    }
}
