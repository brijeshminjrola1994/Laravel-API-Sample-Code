<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;
use App\Traits\Api\ResponseTrait;

class LoginRequest extends FormRequest
{

    /**
     * Response trait to handle return responses.
     */
    use ResponseTrait;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'login' => 'required_if:login_type,email|max:255',
            'authorization_code' => 'required_if:login_type,apple',
            'access_token' => 'required_if:login_type,line',
            'email' => 'max:255|email|required_if:login_type,email',
            'login_type' => 'required|in:email,line,apple',
            'password' => 'required_if:login_type,email|min:6|max:20',
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator $validator
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();
        $errorResponseArray = [];
        $errorDataArray = [];
        foreach ($errors as $errorData) {
            $errorDataArray['status'] = "10001";
            $errorDataArray['title'] = $errorData[0];
            $errorDataArray['detail'] = null;
            $errorDataArray['source'] = null;
            $errorResponseArray[] = $errorDataArray;
        }
        throw new HttpResponseException($this->sendErrorResponse('Some required fields are missing', $errorResponseArray, Response::HTTP_BAD_REQUEST));
    }
}
