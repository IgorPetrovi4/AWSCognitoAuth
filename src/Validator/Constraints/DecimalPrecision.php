<?php
declare(strict_types=1);

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Attribute;

/**
 * @Annotation
 */
#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
class DecimalPrecision extends Constraint
{
    public $message = 'The value "{{ value }}" should have up to 3 decimal places.';
    public $invalidMessage = 'The value "{{ value }}" should be a valid number.';
    public $maxMessage = 'The value "{{ value }}" should not be greater than {{ limit }}.';
    public $max = 999999999999999.999;

    public function __construct($options = null)
    {
        parent::__construct($options);

        if (is_array($options)) {
            $this->message = $options['message'] ?? $this->message;
            $this->invalidMessage = $options['invalidMessage'] ?? $this->invalidMessage;
            $this->maxMessage = $options['maxMessage'] ?? $this->maxMessage;
            $this->max = $options['max'] ?? $this->max;
        }
    }
}