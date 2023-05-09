<?php

namespace App\Http\Requests\Api;

use App\Traits\Api\ResponseTrait;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class UpdateEmployeeRequest extends FormRequest
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
            'id'=>'required|numeric|exists:employees,id',
            'name' => 'required|max:100',
            'date_of_birth' => 'date_format:Y-m-d',
            'age' => 'numeric|min:15|max:100',
            'blood_type' => 'in:A,B,AB,O',
            'postal_code' => 'regex:/\b\d{5}\b/',
            'address' => 'max:100',
            'phone_number' => 'regex:/(01)[0-9]{9}/',
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
