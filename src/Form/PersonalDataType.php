<?php

namespace App\Form;

use App\Entity\Classe;
use App\Entity\Server;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PersonalDataType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('contact', EmailType::class, [
                'label' => 'Email de contact',
                'attr' => [
                    'placeholder' => 'monifus.contact@gmail.com'
                ],
                'required' => false
            ])
            ->add('description', TextType::class, [
                'label' => 'Description',
                'attr' => [
                    'placeholder' => 'Joueur depuis 14 ans, j\'adore faire des métiers principalement'
                ],
                'required' => false
            ])
            ->add('classe', EntityType::class, [
                'label' => 'Votre classe',
                'class' => Classe::class,
                'choice_label' => 'name',
                'required' => false
            ])
            ->add('server', EntityType::class, [
                'label' => 'Votre serveur',
                'class' => Server::class,
                'choice_label' => 'name',
                'required' => false
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
