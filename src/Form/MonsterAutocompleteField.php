<?php

namespace App\Form;

use App\Entity\Monster;
use App\Repository\MonsterRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\Autocomplete\Form\AsEntityAutocompleteField;
use Symfony\UX\Autocomplete\Form\ParentEntityAutocompleteType;

#[AsEntityAutocompleteField]
class MonsterAutocompleteField extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'class' => Monster::class,
            'placeholder' => 'Sélectionnez un Monstre',
            'choice_label' => 'name',
            'tom_select_options' => [
                'maxOptions' => null,
                'closeAfterSelect' => true
            ],
            'query_builder' => function(MonsterRepository $monsterRepository) {
                return $monsterRepository->createQueryBuilder('monster');
            },
            //'security' => 'ROLE_SOMETHING',
        ]);
    }

    public function getParent(): string
    {
        return ParentEntityAutocompleteType::class;
    }
}
