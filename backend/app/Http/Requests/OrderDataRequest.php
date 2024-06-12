<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderDataRequest extends FormRequest
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
        return [
//            'application_number' => 'required',
//            'publication_date' => 'required',
//            'invention_title' => 'required',
//            'user_name' => 'required',
//            'user_phone' => 'required',
//            'user_email' => 'required',
//            'service' => 'required',
//            'service_fee' => 'required',
//            'late_service_fee' =>'nullable',
//            'official_fee_extension' =>'nullable',
//            'official_fee' => 'required',
//            'translation_quantity' => 'required',
//            'translation_fee' => 'required',
//            'total' => 'required',
//            'link' => 'required',
//            'reference' => 'nullable',
//            'renewal_fee' => 'nullable',
//            'pct_entry_fee' => 'nullable',
//            'examination_fee' => 'nullable',
//            'priority_fee' => 'nullable',
//            'priority_length' => 'nullable',
//            'late_official_fee' => 'nullable',
        ];
    }
}
