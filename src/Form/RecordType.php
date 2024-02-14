<?php

namespace App\Form;

use App\Entity\Record;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RecordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('monster', MonsterAutocompleteField::class)
            ->add('time', TimeType::class, [
                'label' => 'Temps du record',
                'with_seconds' => true,
            ])
            ->add('videoLink', UrlType::class, [
                'label' => 'Lien de la vidéo',
                'attr' => [
                    'placeholder' => 'https://youtube.com/',
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Créer',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Record::class,
        ]);
    }
}
