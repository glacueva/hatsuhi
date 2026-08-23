<?php

namespace App\Rules;

use App\Models\Tag;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class TagValidationRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  Closure(string, ?string=): PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $userId = auth()->user()->id;

        if (is_array($value)) {
            $validCount = Tag::where('user_id', $userId)
                ->whereIn('id', $value)
                ->count();

            if ($validCount !== count($value)) {
                $fail('app.validation.tags_invalid');
            }
        }
    }
}
