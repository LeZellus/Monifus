<?php

namespace App\Form;

use App\Entity\Price;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PriceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('resource', ResourceAutocompleteField::class)
            ->add('priceOne', TextType::class, [
                'label' => 'Prix lot de 1',
                'attr' => [
                    "placeholder" => "100"
                ],
            ])
            ->add('priceTen', TextType::class, [
                'label' => 'Prix lot de 10',
                'attr' => [
                    "placeholder" => "1 000"
                ],
            ])
            ->add('priceHundred', TextType::class, [
                'label' => 'Prix lot de 100',
                'attr' => [
                    "placeholder" => "10 000"
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => "Ajouter"
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Price::class,
        ]);
    }
}
