<?php

namespace App\Form;

use App\Entity\Monster;
use App\Entity\Record;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RecordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('monster', MonsterAutocompleteField::class, [
                'attr' => ['id' => 'tom-select'],
                // autres options du champ
            ])
            ->add('time', TimeType::class, [
                'attr' => ["class" => "form-input"],
                "label_attr" => ["class" => "form-label"],
                'with_seconds' => true,
            ])
            ->add('videoLink', UrlType::class, [
                'attr' => ["class" => "form-input"],
                "label_attr" => ["class" => "form-label"]
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
