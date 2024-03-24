<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ValidImageExtensionValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof ValidImageExtension) {
            throw new UnexpectedTypeException($constraint, ValidImageExtension::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        $extension = strtolower(pathinfo($value->getClientOriginalName(), PATHINFO_EXTENSION));

        if (!in_array($extension, $constraint->allowedExtensions)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ extension }}', $extension)
                ->setParameter('{{ allowed_extensions }}', implode(', ', $constraint->allowedExtensions))
                ->addViolation();
        }
    }
}