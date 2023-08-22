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
                'required' => true,
                'attr' => [
                    "class" => "form-control",
                    'placeholder' => 'Entrez un mot à censurer'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => '<i class="fa-solid fa-virus-slash"></i>',
                'label_html' => true,
                'attr' => ["class" => "btn btn-primary addCensureWordBtn"]
            ]);

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Censure::class,
        ]);
    }
}
