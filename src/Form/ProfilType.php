<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\Dropzone\Form\DropzoneType;

class ProfilType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('pseudonymeWebsite', TextType::class, [
                'attr' => ["class" => "form-input"],
                "label_attr" => ["class" => "form-label"]
            ])
            ->add('pseudonymeDofus', TextType::class, [
                'attr' => ["class" => "form-input"],
                "label_attr" => ["class" => "form-label"]
            ])
            ->add('profilePictureFile', DropzoneType::class, [
                'label' => 'Photo de couverture',
                'attr' => [
                    "class" => "form-input",
                    "placeholder" => "Glissez déposez ou chargez un fichier"
                ],
                'required' => false,
                "label_attr" => ["class" => "form-label"]
            ])
            ->add('coverPictureFile', DropzoneType::class, [
                'label' => 'Photo de profil',
                'attr' => [
                    "class" => "form-input",
                    "placeholder" => "Glissez déposez ou chargez un fichier"
                ],
                'required' => false,
                "label_attr" => ["class" => "form-label"]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Sauvegarder',
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
