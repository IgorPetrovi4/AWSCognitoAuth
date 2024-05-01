<?php
declare(strict_types=1);

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class DecimalPrecisionValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof DecimalPrecision) {
            throw new UnexpectedTypeException($constraint, DecimalPrecision::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!is_string($value)) {
            $this->context->buildViolation($constraint->invalidMessage)
                ->setParameter('{{ value }}', (string) $value)
                ->addViolation();

            return;
        }

        if (!preg_match('/^\d+(\.\d{1,3})?$/', $value, $matches)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value)
                ->addViolation();
        }

        if ($value > $constraint->max) {
            $this->context->buildViolation($constraint->maxMessage)
                ->setParameter('{{ value }}', $value)
                ->setParameter('{{ limit }}', (string) $constraint->max)
                ->addViolation();
        }
    }
}