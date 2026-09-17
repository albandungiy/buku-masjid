<?php

namespace App\Rules\Distributions;

use App\Models\Book;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class NotExceedBalance implements ValidationRule
{
    public function __construct(protected ?Book $book) {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!$this->book) {
            return;
        }

        if ((float) $value > $this->book->getBalance()) {
            $fail(__('validation.distribution.amount.not_exceed_balance', ['balance' => format_number($this->book->getBalance())]));
        }
    }
}
