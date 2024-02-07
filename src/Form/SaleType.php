<?php

namespace App\Form;

use App\Entity\Sale;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SaleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('resource', ResourceAutocompleteField::class)
            ->add('buyPrice', NumberType::class, [
                'label' => 'Prix d\'achat',
                'attr' => [
                    "placeholder" => "Prix d'achat en kamas"
                ],
                "required" => true,
            ])
            ->add('sellPrice', NumberType::class, [
                'label' => 'Prix de vente',
                'attr' => [
                    "placeholder" => "Prix de vente en kamas"
                ],
                "required" => false
            ])
            ->add('buyDate', DateTimeType::class, [
                'label' => 'Date d\'achat',
                'attr' => [
                    "placeholder" => "Date d'achat"
                ],
                'data' => new \DateTime(), // Défini la date et l'heure actuelles comme valeur par défaut
                'widget' => 'single_text',
                "required" => true,
            ])
            ->add('sellDate', DateTimeType::class, [
                'label' => 'Date de vente',
                'attr' => [
                    "placeholder" => "Date de vente"
                ],
                "required" => false
            ])
            ->add('isSell', CheckboxType::class, [
                'label' => 'Vendu',
                "required" => false
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Ajouter',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sale::class,
        ]);
    }
}
