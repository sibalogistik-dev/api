<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class Base64Image implements ValidationRule
{
    protected array $allowed;
    protected int $maxSize;

    public function __construct(array $allowed = ['jpeg', 'jpg', 'png', 'webp'], int $maxSize = 2097152)
    {
        $this->allowed = $allowed;
        $this->maxSize = $maxSize;
    }

    /**
     * @param \Closure(string): void $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($value === null) return;

        if (!preg_match('/^data:image\/(\w+);base64,/', $value, $m)) {
            $fail("The {$attribute} format is invalid.");
            return;
        }

        $ext = strtolower($m[1]);

        if (!in_array($ext, $this->allowed)) {
            $fail("The " . $attribute . " type is not allowed.");
            return;
        }

        $base64 = substr($value, strpos($value, ',') + 1);
        $binary = base64_decode($base64, false);

        if (!$binary) {
            $fail("The " . $attribute . " cannot be decoded.");
            return;
        }

        if (strlen($binary) > $this->maxSize) {
            $fail("The " . $attribute . " is too large.");
            return;
        }
    }
}
