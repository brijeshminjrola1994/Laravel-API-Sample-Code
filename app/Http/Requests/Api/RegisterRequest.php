<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;
use App\Traits\Api\ResponseTrait;

class RegisterRequest extends FormRequest {

    /**
     * Response trait to handle return responses.
     */
    use ResponseTrait;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules() {
        return [
            'login'=>'required|max:255',
            'email' => 'max:255|email|unique:users|requiredif:login_type,email',
            'password' => 'required|min:6|max:20',
            'password_confirmation' => 'required|same:password',
            'login_type' => 'required|in:email,line,apple',
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
    protected function failedValidation(Validator $validator) {
        $errors = (new ValidationException($validator))->errors();
        $errorResponseArray = [];
        $errorDataArray = [];
        foreach ($errors as $errorData){
            $errorDataArray['status'] = "10001";
            $errorDataArray['title'] = $errorData[0];
            $errorDataArray['detail'] = null;
            $errorDataArray['source'] = null;
            $errorResponseArray[] = $errorDataArray;
        }
        throw new HttpResponseException($this->sendErrorResponse('Some required fields are missing', $errorResponseArray, Response::HTTP_BAD_REQUEST));
    }

}
