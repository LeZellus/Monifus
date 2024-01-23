<?php

namespace App\Form;

use App\Entity\Sale;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SaleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('resource', ResourceAutocompleteField::class, [
                'attr' => ['id' => 'tom-select'],
                // autres options du champ
            ])
            ->add('buyPrice', IntegerType::class, [
                'attr' => ["class" => "form-input"],
                "label_attr" => ["class" => "form-label"]
            ])
            ->add('sellPrice', IntegerType::class, [
                'attr' => ["class" => "form-input"],
                "label_attr" => ["class" => "form-label"],
                "required" => false
            ])
            ->add('buyDate', DateTimeType::class, [
                'attr' => ["class" => "form-input"],
                "label_attr" => ["class" => "form-label"],
                'data' => new \DateTime(), // Définit la date et l'heure actuelles comme valeur par défaut
                'widget' => 'single_text',
            ])
            ->add('sellDate', DateTimeType::class, [
                'attr' => ["class" => "form-input"],
                "label_attr" => ["class" => "form-label"],
                "required" => false
            ])
            ->add('isSell', CheckboxType::class, [
                'attr' => ["class" => "form-checkbox"],
                "label_attr" => ["class" => "form-label"],
                "required" => false
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
