<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\InvokableRule;
use App\Models\ContractType;
use Closure;

class AttachmentSLARule implements InvokableRule, DataAwareRule
{
    protected $data = [];

    protected $checkForFixedPrice;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($checkForFixedPrice = null)
    {
        $this->checkForFixedPrice = $checkForFixedPrice;
    }

    /**
     * Run the validation rule.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  \Closure  $fail
     * @return void
     */
    public function __invoke($attribute, $value, $fail)
    {
        $allow_to_add_slas = ContractType::where('id', isset($this->data['contractTypeId']) ? $this->data['contractTypeId'] : "")->first()?->allow_to_add_slas ?? false;
        if ($this->checkForFixedPrice) {
            if ($allow_to_add_slas && !$value) {
                $fail(":attribute is a required field");
            }
        } else {
            if ($allow_to_add_slas && !$value) {
                $fail(":attribute is a required field");
            }
        }
    }

    /**
     * Set the data under validation.
     *
     * @param array $data
     */
    public function setData($data): static
    {
        $this->data = $data;

        return $this;
    }
}
