<?php

namespace App\Form;

use App\Entity\Monitor;
use App\Entity\Resource;
use App\Entity\Stock;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MonitorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('resource', ResourceAutocompleteField::class)
            ->add('pricePer1', IntegerType::class, [
                'attr' => ["class" => "form-input"],
                "label_attr" => ["class" => "form-label"]
            ])
            ->add('pricePer10', IntegerType::class, [
                'attr' => ["class" => "form-input"],
                "label_attr" => ["class" => "form-label"]
            ])
            ->add('pricePer100', IntegerType::class, [
                'attr' => ["class" => "form-input"],
                "label_attr" => ["class" => "form-label"]
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Monitor::class,
        ]);
    }
}
