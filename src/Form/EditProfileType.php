<?php
// src/Form/EditProfileType.php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;

class EditProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('prenom', TextType::class, [
                'label' => 'Prénom',
                'constraints' => [
                    new NotBlank(['message' => 'Le prénom est obligatoire.']),
                    new Length(['max' => 255]),
                ],
                'attr' => ['placeholder' => 'Votre prénom'],
            ])
            ->add('nom', TextType::class, [
                'label' => 'Nom',
                'constraints' => [
                    new NotBlank(['message' => 'Le nom est obligatoire.']),
                    new Length(['max' => 255]),
                ],
                'attr' => ['placeholder' => 'Votre nom'],
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'constraints' => [
                    new NotBlank(['message' => "L'email est obligatoire."]),
                    new Email(['message' => 'Adresse email invalide.']),
                ],
                'attr' => ['placeholder' => 'votre@email.com'],
            ])
            ->add('bio', TextareaType::class, [
                'label' => 'Biographie',
                'required' => false,
                'constraints' => [
                    new Length([
                        'max' => 1000,
                        'maxMessage' => 'La bio ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                ],
                'attr' => [
                    'placeholder' => 'Parlez-nous de vous…',
                    'rows' => 5,
                    'maxlength' => 1000,
                ],
            ])
            ->add('imageFile', FileType::class, [
                'label'    => 'Photo de profil',
                'mapped'   => false,   
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize'             => '2M',
                        'mimeTypes'           => ['image/jpeg', 'image/png', 'image/webp'],
                        'mimeTypesMessage'    => 'Formats acceptés : JPG, PNG, WEBP (2 Mo max).',
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
