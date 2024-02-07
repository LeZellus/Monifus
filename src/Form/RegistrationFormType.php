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
                'attr' => [
                    "class" => "form-input",
                    "placeholder" => "exemple@exemple.fr"
                ],
                "label_attr" => ["class" => "form-label"]
            ])
            ->add('pseudonymeWebsite', TextType::class, [
                'attr' => [
                    "class" => "form-input",
                    "placeholder" => "Mathéo"
                ],
                "label_attr" => ["class" => "form-label"]
            ])
            ->add('pseudonymeDofus', TextType::class, [
                'attr' => [
                    "class" => "form-input",
                    "placeholder" => "XxRamboPLxX"
                ],
                "label_attr" => [
                    "class" => "form-label"
                ]
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => ["class" => "form-input", "placeholder" => "**************", 'autocomplete' => 'new-password'],
                "label_attr" => ["class" => "form-label"],
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
                'attr' => ['class' => 'form-submit']
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
