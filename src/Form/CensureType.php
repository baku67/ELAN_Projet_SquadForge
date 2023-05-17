<?php

namespace App\Form;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use App\Entity\Censure;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CensureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('word', TextType::class, [
                // 'label' => 'Message',
                'required' => true,
                'attr' => [
                    "class" => "form-control",
                    'placeholder' => 'Entrez le mot à censurer'
                    // 'rows' => 1
                ]
            ])
            ->add('submit', SubmitType::class, [
                // 'label' => 'Publier',
                'label' => '<i class="fa-solid fa-paper-plane"></i>',
                'label_html' => true,
                'attr' => ["class" => "btn btn-primary"]
            ]);
            // ->add('creation_date')
            // ->add('user')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Censure::class,
        ]);
    }
}
