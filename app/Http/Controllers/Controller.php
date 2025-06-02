<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * Fire Error To Mobile
     *
     * @param [type] $msg
     *
     * @return void
     */
    public function fireErrorMobile($msg)
    {
        return response(['error_msg' => $msg], 400);
    }

    /**
     * Validate the given request with the given rules.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  array  $rules
     * @param  array  $messages
     * @param  array  $customAttributes
     * @return array
     *
     * @throws ValidationException
     */
    public function validateMobile(Request $request, array $rules, array $messages = [], array $customAttributes = [])
    {
        $validator = $this->getValidationFactory()->make($request->all(), $rules, $messages, $customAttributes);

        if ($validator->fails()) {
            throw new \Illuminate\Validation\ValidationException($validator, new JsonResponse([
                'error_msg' => implode("\n", $validator->getMessageBag()->all()),
            ], 422));
            // throw new \Illuminate\Validation\ValidationException($validator, new JsonResponse($validator->errors()->getMessages(), 422));
            // throw \Illuminate\Validation\ValidationException::withMessages($validator->errors()->getMessages());
        }
        return $validator->validated();
    }
}
