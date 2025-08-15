<?php

namespace App\Http\Requests\Backend\Branch;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class StoreWarehouseRequest extends FormRequest
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
        $id = '';
        if($this->id){
            $id = $this->id;
        }
        return [
            'id' => 'required|unique:lck_warehouse,id,'.$id,
        ];
    }
    public function messages()
    {
        return [
            'name.required' => 'Vui lòng nhập tên bàn',
           /* 'name.unique' => 'Tên bàn đã tồn tại. Vui lòng nhập tên khác',*/
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw (new ValidationException($validator))
            ->errorBag($this->errorBag)
            ->redirectTo($this->getRedirectUrl());
    }
}
