<?php

namespace App\Form;

use App\Entity\Censure;
use App\Repository\CensureRepository;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use App\Entity\Topic;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\NotBlank;

class TopicType extends AbstractType
{


    private $censureRepository;

    public function __construct(CensureRepository $censureRepository)
    {
        $this->censureRepository = $censureRepository;
    }




    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre',
                'required' => true,
                'attr' => ["class" => "form-control"],
                'constraints' => [
                    new Callback([$this, 'validateTextInput']),
                    new NotBlank([
                        'message' => 'Le titre ne peut pas être vide.',
                    ]),
                    new Length([
                        'max' => 250,
                        'maxMessage' => 'Le titre ne peut pas faire plus de 250 caractères.',
                    ]),
                ],
            ])
            ->add('firstMsg', TextareaType::class, [
                'label' => 'Introduction',
                'required' => true,
                'attr' => [
                    "class" => "form-control", 
                    'rows' => 3
                ],
                'constraints' => [
                    // new Callback([$this, 'validateTextInput']),
                    new Length([
                        'max' => 1000,
                        'maxMessage' => 'Le premier message ne peut pas faire plus de 1000 caractères.',
                    ]),
                ],
                
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
            'data_class' => Topic::class,
        ]);
    }





    public function validateTextInput($value, ExecutionContextInterface $context)
    {
        // Extraction des mots (array) depuis collection Censures
        $words = $this->extractWordsFromCollection();

        // Conversion de l'input en lowerCase pour comparaison non sensible à la casse
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
