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

class RoomType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // TITLE
            ->add('titre', TextType::class, [
                'label' => 'Book title'
            ])

            // AUTHOR
            ->add('auteur', TextType::class, [
                'label' => 'Author'
            ])

            // TOTAL PAGES
            ->add('total_pages', IntegerType::class, [
                'label' => 'Total pages'
            ])

            // TYPE (live / scheduled)
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'Live Session' => 'live',
                    'Scheduled' => 'scheduled',
                ],
                'label' => 'Room type'
            ])

            // MAX PARTICIPANTS
            ->add('max_participants', IntegerType::class, [
                'label' => 'Max participants'
            ])

            // GENRE
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
                ],
                'label' => 'Genre'
            ])

            // DESCRIPTION
            ->add('description', TextareaType::class, [
                'required' => false,
                'label' => 'Description'
            ])

            // IMAGE UPLOAD (IMPORTANT: NOT mapped because handled manually)
            ->add('image', FileType::class, [
                'required' => false,
                'mapped' => false,
                'label' => 'Book cover'
            ])

            // TAGS (IMPORTANT: handled manually in controller)
            ->add('tags', TextType::class, [
                'required' => false,
                'mapped' => false,
                'label' => 'Tags (handled manually)'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Room::class,
        ]);
    }
}