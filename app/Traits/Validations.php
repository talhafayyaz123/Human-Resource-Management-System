<?php

namespace App\Traits;

use Illuminate\Support\Facades\Validator;

trait Validations
{

    /**
     * This method is used to validate the parsed information.
     * @param  array  $request
     * @param array $rules
     *
     */
    public function validateMessage($request, $rules, $message = [])
    {
        if (empty($message)) {
            $validator = Validator::make((array) $request, $rules);
        } else {
            $validator = Validator::make((array) $request, $rules, $message);
        }
        if ($validator->fails()) {
            return ['status' => false, 'errors' => $validator->messages()->get('*')];
        } else {
            return ['status' => true];
        }
    }
}
