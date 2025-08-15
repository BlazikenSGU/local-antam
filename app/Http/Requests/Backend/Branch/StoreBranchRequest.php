<?php

namespace App\Http\Requests\Backend\Branch;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Http\FormRequest;

class StoreBranchRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {

        return [
            'name' => 'required',
        ];
    }
    public function messages()
    {
        return [
            'name.required' => 'Vui lòng nhập tên chi nhánh',
            'name.unique' => 'Tên chi nhánh đã tồn tại. Vui lòng nhập tên khác',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw (new ValidationException($validator))
            ->errorBag($this->errorBag)
            ->redirectTo($this->getRedirectUrl());
    }
}
