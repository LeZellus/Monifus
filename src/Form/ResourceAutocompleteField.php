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
            'label' => 'Choix de la ressource',
            'class' => Resource::class,
            'placeholder' => 'Sélectionnez une ressource',
            'choice_label' => 'name',
            'tom_select_options' => [
                'maxOptions' => null,
                'closeAfterSelect' => true,
                'loadingClass' => 'Chargement ...',
            ],
            'query_builder' => function(ResourceRepository $resourceRepository) {
                return $resourceRepository->createQueryBuilder('resource');
            },
        ]);
    }

    public function getParent(): string
    {
        return BaseEntityAutocompleteType::class;
    }
}
