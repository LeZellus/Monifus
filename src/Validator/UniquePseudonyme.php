<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute] class UniquePseudonyme extends Constraint
{
    public string $message = 'Le pseudonyme "{{ value }}" est déjà utilisé.';
}