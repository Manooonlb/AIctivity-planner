<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;


class AgeValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        /* @var App\Validator\Age $constraint */

        if (null === $value || '' === $value) {
            return;
        }

        // Vérifier si la valeur est une instance de DateTime
        if (!$value instanceof \DateTime) {
            $this->context->buildViolation('La valeur doit être une date valide.')
                ->setParameter('{{ value }}', $value)
                ->addViolation();
            return;
        }
         // Calculer l'âge en années
         $now = new \DateTime();
         $age = $value->diff($now)->y;
 
         // Vérifier si l'âge est inférieur à l'âge minimum requis
         if ($age < $constraint->minimumAge) {
             $this->context->buildViolation($constraint->message)
                 ->setParameter('{{ value }}', $value->format('d/m/Y'))
                 ->setParameter('{{ minimumAge }}', $constraint->minimumAge)
                 ->addViolation();
         }
    }
}
