<?php

namespace App\Form;

use App\Entity\Monitor;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MonitorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('resource', ResourceAutocompleteField::class)
            ->add('pricePer1', NumberType::class, [
                'label' => 'Prix du lot de 1',
                'attr' => [
                    'placeholder' => '100'
                ]
            ])
            ->add('pricePer10', NumberType::class, [
                'label' => 'Prix du lot de 10',
                'attr' => [
                    'placeholder' => '1000'
                ]
            ])
            ->add('pricePer100', NumberType::class, [
                'label' => 'Prix du lot de 100',
                'attr' => [
                    'placeholder' => '10000'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Ajouter',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Monitor::class,
        ]);
    }
}
