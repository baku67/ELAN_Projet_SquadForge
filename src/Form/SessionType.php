<?php

namespace App\Form;

use App\Entity\GroupSession;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SessionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dateStart', DateTimeType::class, [
                'date_label' => 'Début',
                'data' => new \DateTime(),
                'required' => true,
                'html5' => false,
                'widget' => 'single_text',
                'attr' => ['min' => ( new \DateTime() )->format('Y-m-d H:i:s'), 'id' => 'date_start_picker']
            ])
            ->add('dateEnd', DateTimeType::class, [
                'date_label' => 'Fin',
                'data' => new \DateTime(),
                'required' => true,
                'html5' => false,
                'widget' => 'single_text',
                'attr' => ['min' => ( new \DateTime() )->format('Y-m-d H:i:s'), 'id' => 'date_end_picker']
            ])
            ->add('title', TextType::class, [
                'label' => 'Titre',
                'required' => true,
                'attr' => ["class" => "form-control", 'id' => 'testtttt'],
            ])
            ->add('comfirmNeeded', CheckboxType::class, [
                'label' => 'Comfirmation requise',
                'required' => false,
            ])

            ->add('submit', SubmitType::class, [
                'label' => 'Publier',
                'attr' => ["class" => "btn btn-primary"]
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => GroupSession::class,
        ]);
    }
}