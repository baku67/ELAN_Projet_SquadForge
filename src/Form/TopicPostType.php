<?php

namespace App\Form;

use App\Entity\TopicPost;

use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TopicPostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('text', TextareaType::class, [
                'label' => 'Message',
                'required' => true,
                'attr' => [
                    "class" => "form-control",
                    'rows' => 1
                ]
            ])
            // ->add('publish_date')
            // ->add('user')
            // ->add('topic')
            ->add('submit', SubmitType::class, [
                'label' => 'Publier',
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
