<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use App\Validator\UniquePseudonyme;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'attr' => [
                    "placeholder" => "exemple@exemple.fr"
                ],
            ])
            ->add('pseudonymeWebsite', TextType::class, [
                'label' => 'Pseudonyme du site',
                'attr' => [
                    "placeholder" => "Mathéo"
                ],
            ])
            ->add('pseudonymeDofus', TextType::class, [
                'label' => 'Pseudonyme sur Dofus',
                'attr' => [
                    "placeholder" => "XxRamboPLxX"
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                'label' => 'Mot de passe',
                'mapped' => false,
                'attr' => ["placeholder" => "**************", 'autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Renseignez un mot de passe',
                    ]),
                    new Length([
                        'min' => 6,
                        'max' => 4096, // max length allowed by Symfony for security reasons
                        'minMessage' => 'Votre mot de passe doit contenir minimum {{ limit }} caractères',
                        'maxMessage' => 'Votre mot de passe doit contenir maximum {{ limit }} caractères',
                    ]),
                    new Regex([
                        'pattern' => '/[a-zA-Z]/',
                        'message' => 'Votre mot de passe doit contenir une lettre'
                    ]),
                    new Regex([
                        'pattern' => '/\d/',
                        'message' => 'Votre mot de passe doit contenir un chiffre'
                    ]),
                    new Regex([
                        'pattern' => '/[!@#$%^&*()_+\-=\[\]{};:|,.<>\/?]/',
                        'message' => 'Votre mot de passe doit contenir un caractère spécial'
                    ])
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Inscription',
                'attr' => ['class' => 'form-submit w-full']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
