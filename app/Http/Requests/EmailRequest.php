<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class EmailRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return match($this->method()){
            'POST' => $this->send(),
            'GET' => $this->search()
        };
    }

    public function search()
    {
        return [
            's' => 'nullable',
        ];
    }

    public function send()
    {
        return [
            'name' => 'required',
            'sender' => 'required',
            'recipient' => 'required',
            'subject' => 'required',
            'body' => 'required',
            'file' => 'required'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'errors' => $validator->errors(),
            'status' => true
        ], 422));
    }

}
