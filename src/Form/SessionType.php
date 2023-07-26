<?php

namespace App\Form;

use App\Entity\GroupSession;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
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
                'attr' => ['min' => ( new \DateTime() )->format('Y-m-d H:i:s')]
            ])
            ->add('dateEnd', DateTimeType::class, [
                'date_label' => 'Fin',
                'data' => new \DateTime(),
                'required' => true,
                'attr' => ['min' => ( new \DateTime() )->format('Y-m-d H:i:s')]
            ])
            ->add('title', TextType::class, [
                'label' => 'Titre',
                'required' => true,
                'attr' => ["class" => "form-control"],
                // 'constraints' => [
                //     new Callback([$this, 'validateTextInput']),
                // ],
            ])
            ->add('comfirmNeeded')
            // ->add('team')

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
