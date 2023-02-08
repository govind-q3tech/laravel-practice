<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class MinWordsRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    private $max_words;
    public function __construct($max_words = 150)
    {
        $this->max_words = $max_words;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return str_word_count($value) > $this->max_words;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'This cannot be less than '.$this->max_words.' words.';
    }
}
