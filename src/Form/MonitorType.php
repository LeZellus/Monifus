<?php

namespace App\Form;

use App\Entity\Monitor;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MonitorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('resource', ResourceAutocompleteField::class)
            ->add('prices', CollectionType::class, [
                'entry_type' => PriceType::class,
                'label' => false,
                'entry_options' => [
                    'label' => false,
                    'attr' => [
                        'class' => 'item grid md:grid-cols-3 gap-4'
                    ]
                ],
                'allow_add' => true,
                'by_reference' => false,
                'required' => false,
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
