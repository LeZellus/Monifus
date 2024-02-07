<?php

namespace App\Form;

use App\Entity\Monster;
use App\Repository\MonsterRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\Autocomplete\Form\AsEntityAutocompleteField;
use Symfony\UX\Autocomplete\Form\BaseEntityAutocompleteType;

#[AsEntityAutocompleteField]
class MonsterAutocompleteField extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'label' => 'Choix du monstre',
            'class' => Monster::class,
            'placeholder' => 'Sélectionnez un Monstre',
            'choice_label' => 'name',
            'tom_select_options' => [
                'maxOptions' => null,
                'closeAfterSelect' => true,
                'loadingClass' => 'Chargement ...'
            ],
            'query_builder' => function(MonsterRepository $monsterRepository) {
                return $monsterRepository->createQueryBuilder('monster');
            },
        ]);
    }

    public function getParent(): string
    {
        return BaseEntityAutocompleteType::class;
    }
}
