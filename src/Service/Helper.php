<?php

namespace App\Service;

use Symfony\Component\Validator\ConstraintViolationList;

class Helper
{
    function violationsToArray(ConstraintViolationList $violationsList, $propertyPath = null)
    {
        $output = array();

        foreach ($violationsList as $violation) {
            $output[$violation->getPropertyPath()][] = $violation->getMessage();
        }

        if (null !== $propertyPath) {
            if (array_key_exists($propertyPath, $output)) {
                $output = array($propertyPath => $output[$propertyPath]);
            } else {
                return array();
            }
        }
        
        return $output;
    }
}