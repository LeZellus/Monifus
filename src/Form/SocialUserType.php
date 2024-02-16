<?php

namespace App\Form;

use App\Entity\Classe;
use App\Entity\Server;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SocialUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('youtubeUrl', TextType::class, [
                'required' => false,
                'label' => "Profil Youtube",
                'attr' => [
                    'placeholder' => 'https://www.youtube.com/@Monifus'
                ]
            ])
            ->add('twitterUrl', TextType::class, [
                'required' => false,
                'label' => "Profil Twitter",
                'attr' => [
                    'placeholder' => 'https://twitter.com/MonifusApp'
                ]
            ])
            ->add('ankamaUrl', TextType::class, [
                'required' => false,
                'label' => "Profil Ankama",
                'attr' => [
                    'placeholder' => 'https://account.ankama.com/fr/profil-ankama/petdenone-1545'
                ]
            ])
            ->add('twitchUrl', TextType::class, [
                'required' => false,
                'label' => "Profil Twitch",
                'attr' => [
                    'placeholder' => 'https://www.twitch.tv/lezellus'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => "Sauvegarder",
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
