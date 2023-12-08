<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class TaxNumberValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof TaxNumber) {
            throw new \InvalidArgumentException(sprintf('The constraint must be an instance of %s.', TaxNumber::class));
        }

        // Check if the tax number matches the specified format
        if (!preg_match($constraint->pattern, $value)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value)
                ->addViolation();
        }
    }
}