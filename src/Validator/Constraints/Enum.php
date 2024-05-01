<?php
declare(strict_types=1);

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Attribute;

/**
 * @Annotation
 */
#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
class Enum extends Constraint
{
    public $message = 'The value "{{ string }}" is not a valid choice.';
    public $values = [];

    public function __construct(array $options)
    {
        parent::__construct($options);
        $this->values = $options['values'];
        $this->message = $options['message'] ?? $this->message;
    }

    public function getRequiredOptions(): array
    {
        return ['values'];
    }
}