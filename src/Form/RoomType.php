<?php

namespace App\Form;

use App\Entity\Room;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Positive;
use Symfony\Component\Validator\Constraints\Range;

class RoomType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class, [
                'label' => 'Book title',
                'attr' => ['placeholder' => 'e.g. The Brothers Karamazov']
            ])
            ->add('auteur', TextType::class, [
                'label' => 'Author',
                'attr' => ['placeholder' => 'e.g. Fyodor Dostoevsky']
            ])
            ->add('totalPages', IntegerType::class, [
                'label' => 'Total pages',
                'attr' => ['placeholder' => 'e.g. 520', 'min' => 1]
            ])
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'Live Session' => 'live',
                    'Scheduled' => 'scheduled',
                ],
                'label' => 'Room type',
                'expanded' => true,
                'multiple' => false,
                'data' => 'live'
            ])
            ->add('maxParticipants', IntegerType::class, [
                'label' => 'Max participants',
                'attr' => ['placeholder' => 'e.g. 15', 'min' => 2, 'max' => 50]
            ])
            ->add('genre', ChoiceType::class, [
                'choices' => [
                    'Classic' => 'Classic',
                    'Sci-Fi' => 'Sci-Fi',
                    'Fantasy' => 'Fantasy',
                    'Dystopian' => 'Dystopian',
                    'Romance' => 'Romance',
                    'Philosophy' => 'Philosophy',
                    'Drama' => 'Drama',
                    'Adventure' => 'Adventure',
                    'Fiction' => 'Fiction',
                    'Non-Fiction' => 'Non-Fiction',
                ],
                'label' => 'Genre',
                'placeholder' => 'Select a genre'
            ])
            ->add('description', TextareaType::class, [
                'required' => false,
                'label' => 'Description (optional)',
                'attr' => ['rows' => 4, 'placeholder' => 'What will you discuss in this reading room?']
            ])
            
            ->add('imageFile', FileType::class, [
                'mapped' => false,
                'required' => false,
                'label' => 'Book cover',
                'constraints' => [
                    new File([
                        'maxSize' => '5M',
                        'mimeTypes' => ['image/jpeg', 'image/png', 'image/webp', 'image/jpg'],
                        'mimeTypesMessage' => 'Please upload a valid image (JPEG, PNG, WEBP)'
                    ])
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Room::class,
            'csrf_protection' => true,
        ]);
    }
}