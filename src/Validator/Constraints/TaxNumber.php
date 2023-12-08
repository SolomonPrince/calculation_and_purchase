<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
#[\Attribute] class TaxNumber extends Constraint
{
    public string $message = 'The tax number "{{ value }}" is not valid.';

    public string $pattern = '/^(DE\d{9}|IT\d{11}|GR\d{9}|FR\d{2}\w{9})$/';
}