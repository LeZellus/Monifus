<?php

namespace App\Validator\Constraints;
use Symfony\Component\Validator\Constraint;
#[\Attribute] class ValidImageExtension extends Constraint
{
    public string $message = 'Le fichier a une extension invalide ({{ extension }}). Seules les extensions suivantes sont autorisées: {{ allowed_extensions }}.';

    public array $allowedExtensions = ['jpg', 'png', 'gif', 'jpeg', 'webp'];
}