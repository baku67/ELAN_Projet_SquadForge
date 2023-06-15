<?php

namespace App\Form;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use App\Entity\Candidature;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CandidatureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('text', TextareaType::class, [
                'required' => true,
                'attr' => [
                    "class" => "form-control",
                    'placeholder' => 'Votre message...',
                    'rows' => 3,
                ]
            ])
            // ->add('groupQuestions', CollectionType::class, [
            //     'entry_type' => GroupQuestionsType::class,
            //     'allow_add' => true,
            //     'by_reference' => false,
            // ])
            ->add('submit', SubmitType::class, [
                'label' => 'Envoyer&nbsp;<i class="fa-solid fa-paper-plane"></i>',
                'label_html' => true,
                'attr' => ["class" => "btn btn-primary"]
            ]);
            // ->add('creation_date')
            // ->add('groupe')
            // ->add('user')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Candidature::class,
        ]);
    }
}
