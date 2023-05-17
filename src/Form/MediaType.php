<?php

namespace App\Form;

use App\Entity\Censure;
use App\Repository\CensureRepository;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

use App\Entity\Media;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MediaType extends AbstractType
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
                ],
            ])
            ->add('url', FileType::class, [
                'label' => 'Choisissez un fichier',
                'required' => true, 
                'attr' => ["class" => "form-control"]
            ])
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
