<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RejectAdjustmentRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'rejected_reason' => ['required', 'string', 'min:5', 'max:1000'],
        ];
    }

    /**
     * Custom message for validation errors.
     */
    public function messages(): array
    {
        return [
            'rejected_reason.required' => 'Bắt buộc phải nhập lý do từ chối yêu cầu.',
            'rejected_reason.min' => 'Lý do từ chối phải có ít nhất 5 ký tự.',
            'rejected_reason.max' => 'Lý do từ chối không được vượt quá 1000 ký tự.',
        ];
    }
}
