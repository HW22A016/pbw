<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreReserveRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // 整数かつ指定したリストと一致するか
            'location_id' => 'required|integer|in:1,2,3',
            'stance_id' => 'required|integer|in:1,2,3',
        ];
    }

    public function messages(): array
    {
        return[
            'location_id.required' => '探索先を選択してください。',
            'stance_id.required' => '戦闘方針を選択してください。',
        ];
    }
}
