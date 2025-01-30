<?php

namespace App\Form;

use App\Entity\User;
use App\Validator\OrganizationExist;
use App\Validator\User\ValidPesel;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class, [
                'constraints' => [
                    new Assert\NotBlank(),
                ]
            ])
            ->add('secondName', TextType::class)
            ->add('lastName', TextType::class, [
                'constraints' => [
                    new Assert\NotBlank(),
                ]
            ])
            ->add('email', EmailType::class, [
                'constraints' => [
                    new Assert\NotBlank(),
                ]
            ])
            ->add('pesel', TextType::class, [
                'constraints' => [
                    new Assert\NotBlank(),
                    new ValidPesel(),
                ]
            ])
            ->add('password', TextType::class, [
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\PasswordStrength()
                ]
            ])
            ->add('organizationIds', CollectionType::class, [
                'constraints'  => [
                    new OrganizationExist()
                ],
                'mapped'       => false,
                'allow_add'    => true,
                'allow_delete' => true,
                'entry_type'   => TextType::class,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class'         => User::class,
            'csrf_protection'    => false,
            'allow_extra_fields' => true,
        ]);
    }
}
