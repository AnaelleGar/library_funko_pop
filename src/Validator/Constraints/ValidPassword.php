<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\RegexValidator;

/**
 * @Annotation
 */
class ValidPassword extends Regex
{
    public $message = 'Password does not match requirements.';
    public $pattern = '^(?=.*[A-Z])(?=.*[a-z])(?=.*\d).{9,}$';
    public $htmlPattern = false;

    /**
     * ValidPassword constructor.
     */
    public function __construct()
    {
        parent::__construct($this->pattern);
    }

    /**
     * @return string
     */
    public function validatedBy(): string
    {
        return RegexValidator::class;
    }

    /**
     * @return array
     */
    public function getRequiredOptions(): array
    {
        return [];
    }
}