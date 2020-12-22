<?php

namespace AwemaPL\Xml\User\Sections\Ceneosources\Http\Requests\Rules;

use AwemaPL\Xml\Client\XmlClient;
use Illuminate\Contracts\Validation\Rule;

class ValidUrl implements Rule
{
    private $message;

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $this->message = (new XmlClient(['url' =>$value, 'download_before' =>false]))->ceneo()->fail();
        return empty($this->message);
    }

    /**
     * Get the validation error message.
     *
     * @return string|array
     */
    public function message()
    {
        return $this->message;
    }
}
