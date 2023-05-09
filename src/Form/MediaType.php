<?php

namespace App\Form;

use App\Entity\Media;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MediaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre',
                'required' => true,
                'attr' => ["class" => "form-control"]
            ])
            // ->add('file', FileType::class, [
            //     'label' => 'Choisissez un fichier',
            //     'required' => true, 
            //     'attr' => ["class" => "form-control"]
            // ])
            // ->add('publish_date')
            // ->add('url')
            // ->add('status')
            // ->add('validated')
            // ->add('user')
            // ->add('game')
            ->add('submit', SubmitType::class, [
                'label' => 'Publier',
                'attr' => ["class" => "btn btn-primary"]
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Media::class,
        ]);
    }
}
