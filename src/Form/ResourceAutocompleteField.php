<?php

namespace App\Form;

use App\Entity\Resource;
use App\Repository\ResourceRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\Autocomplete\Form\AsEntityAutocompleteField;
use Symfony\UX\Autocomplete\Form\BaseEntityAutocompleteType;

#[AsEntityAutocompleteField]
class ResourceAutocompleteField extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'class' => Resource::class,
            'placeholder' => 'Sélectionnez une ressource',
            'choice_label' => 'name',
            'tom_select_options' => [
                'maxOptions' => null,
                'loadingClass' => 'Chargement ...'
            ],
            'query_builder' => function(ResourceRepository $resourceRepository) {
                return $resourceRepository->createQueryBuilder('resource');
            },
            //'security' => 'ROLE_SOMETHING',
        ]);
    }

    public function getParent(): string
    {
        return BaseEntityAutocompleteType::class;
    }
}
