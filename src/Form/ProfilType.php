<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
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
                'label' => 'Pseudonyme du site',
            ])
            ->add('pseudonymeDofus', TextType::class, [
                'label' => 'Pseudonyme sur Dofus',
            ])
            ->add('profilePictureFile', DropzoneType::class, [
                'label' => 'Photo de profil',
                'attr' => [
                    "placeholder" => "Glissez déposez ou chargez un fichier",
                    "class" => "rounded-lg"
                ],
                'required' => false,
            ])
            ->add('coverPictureFile', DropzoneType::class, [
                'label' => 'Photo de couverture',
                'attr' => [
                    "placeholder" => "Glissez déposez ou chargez un fichier"
                ],
                'required' => false,
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Enregistrer',
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
