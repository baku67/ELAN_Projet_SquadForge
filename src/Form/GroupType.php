<?php

namespace App\Form;

use App\Entity\Censure;
use App\Repository\CensureRepository;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use App\Entity\Group;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Validator\Constraints\File;

class GroupType extends AbstractType
{

    public function __construct(private CensureRepository $censureRepository)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre',
                'required' => true,
                'attr' => [
                    "class" => "form-control",
                    'placeholder' => 'Entrez le nom de la team...',
                ],
                'constraints' => [
                    new Callback([$this, 'validateTextInput']),
                    new NotBlank([
                        'message' => 'Le titre ne peut pas être vide.',
                    ]),
                    new Length([
                        'max' => 1000,
                        'maxMessage' => 'Le titre ne peut pas faire plus de 250 caractères.',
                    ]),
                ],
            ])
            ->add('description', TextareaType::class, [
                'required' => true,
                'attr' => [
                    "class" => "form-control",
                    'placeholder' => 'Entrez une présentation de la team...',
                    'rows' => 3
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'La description ne peut pas être vide.',
                    ]),
                    new Length([
                        'max' => 1000,
                        'maxMessage' => 'Le description ne peut pas faire plus de 2500 caractères.',
                    ]),
                ],
            ])
            ->add('nbrPlaces', IntegerType::class, [
                'label' => "Places",
                "required" => false,
                'attr' => [
                    "class" => "form-control nbrPlaceInput",
                    'placeholder' => '2',
                    'value' => '3',
                    'min' => '2',
                    // TODO: A adapter selon jeu:
                    'max' => '6',
                ],
                // TODO: A adapter selon jeu:
                'constraints' => [
                    new Range([
                        'min' => 2,
                        'max' => 6,
                        'minMessage' => 'Le minimum de places est de 2}.',
                        'maxMessage' => 'Le nombre de place ne peut pas excéder 6.',
                    ]),
                ],
            ])
            ->add('restriction_18', CheckboxType::class, [
                'label' => "Restriction d'âge",
                'required' => false,
                'attr' => [
                    "class" => "form-check-input",
                ],
            ])
            ->add('restriction_mic', CheckboxType::class, [
                'label' => "Micro obligatoire",
                'required' => false,
                'attr' => [
                    "class" => "form-check-input"
                ],
            ])
            ->add('restriction_imgProof', CheckboxType::class, [
                'label' => "Autoriser pièce jointe: ",
                'required' => false,
                'attr' => [
                    "class" => "form-check-input"
                ],
            ])
            ->add('restriction_lang', ChoiceType::class, [
                'label' => 'Langue',
                'required' => false,
                'choices' => [
                    'French' => 'fr',
                    'English' => 'en',
                    'German' => 'ge',
                ],
                'placeholder' => '-- Pas de restriction', // Optional: Add a placeholder option
                'attr' => [
                    // "value" => "",
                    "class" => "form-control",
                ],
            ])
            ->add('status', CheckboxType::class, [
                'label' => "Publique",
                'required' => false,
                'attr' => [
                    "class" => "form-check-input"
                ],
            ])

            ->add('imgUrl', FileType::class, [
                'label' => 'Illustration de la team:',
                'required' => false, 
                'attr' => ["class" => "form-control"],
                'constraints' => [
                    new File([
                        'maxSize' => '5M', // Maximum file size
                        'mimeTypes' => [
                            'image/png', // Allowed image formats
                            'image/jpeg',
                            'image/jpg',
                            'image/gif',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid image.',
                    ]),
                ],
            ])

            // ajouter logo/img (vérif taille et dimension + aperçu ici)

            // ajouter init Status (si ne pas publier la team tout de suite, default: public)

            ->add('submit', SubmitType::class, [
                'label' => 'Créer',
                'attr' => ["class" => "btn btn-primary"]
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Group::class,
        ]);
    }


    public function validateTextInput($value, ExecutionContextInterface $context)
    {
        // Extract words from the entity collection
        $words = $this->extractWordsFromCollection();

        // Convert the text input value to lowercase for case-insensitive comparison
        $lowercaseValue = strtolower($value);

        foreach ($words as $word) {
            if (strpos($lowercaseValue, strtolower($word)) !== false) {
                $context->buildViolation('The word "{{ word }}" is not allowed.')
                    ->setParameter('{{ word }}', $word)
                    ->addViolation();
            }
        }
    }


    private function extractWordsFromCollection(): array
    {
        $censureCollection = $this->censureRepository->findAll();
        $words = [];

        foreach ($censureCollection as $item) {
            if ($item instanceof Censure) {
                $words[] = $item->getWord();
            }
        }

        return $words;
    }
}
