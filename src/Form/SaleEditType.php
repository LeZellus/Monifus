<?php

namespace App\Form;

use App\Entity\Resource;
use App\Entity\Sale;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SaleEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('resource', EntityType::class, [
                'class' => Resource::class,
                'choice_label' => 'id',
            ])
            ->add('isSell')
            ->add('buyPrice')
            ->add('sellPrice')
            ->add('buyDate')
            ->add('sellDate')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sale::class,
        ]);
    }
}
