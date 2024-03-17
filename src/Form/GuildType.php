<?php

namespace App\Form;

use App\Entity\Guild;
use App\Entity\Server;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\Dropzone\Form\DropzoneType;

class GuildType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom de la guilde',
                'required' => true
            ])
            ->add('description', TextType::class, [
                'label' => 'Description',
                'required' => true
            ])
            ->add('discordUrl', TextType::class, [
                'label' => 'Lien discord',
                'required' => false
            ])
            ->add('websiteUrl', TextType::class, [
                'label' => 'Lien site',
                'required' => false
            ])
            ->add('server', EntityType::class, [
                'label' => 'Votre serveur',
                'class' => Server::class,
                'choice_label' => 'name',
            ])
            ->add('blasonPictureFile', DropzoneType::class, [
                'label' => 'Image du blason',
                'attr' => [
                    "placeholder" => "Glissez déposez ou chargez un fichier",
                    "class" => "rounded-lg"
                ],
                'required' => false,
            ])
            ->add('coverPictureFile', DropzoneType::class, [
                'label' => 'Image de couverture',
                'attr' => [
                    "placeholder" => "Glissez déposez ou chargez un fichier"
                ],
                'required' => false,
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Description'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Guild::class,
        ]);
    }
}
