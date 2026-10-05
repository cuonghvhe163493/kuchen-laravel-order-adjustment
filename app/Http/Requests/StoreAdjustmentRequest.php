<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAdjustmentRequest extends FormRequest
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
            'order_id' => ['required', 'integer', 'exists:orders,id'],
            'reason' => ['required', 'string', 'min:5', 'max:1000'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.order_item_id' => ['required', 'integer', 'exists:order_items,id'],
            'items.*.new_sku' => ['required', 'string', 'exists:product_variants,sku'],
            'items.*.new_quantity' => ['required', 'integer', 'min:1'],
        ];
    }

    /**
     * Custom message for validation errors.
     */
    public function messages(): array
    {
        return [
            'order_id.required' => 'Mã đơn hàng không được để trống.',
            'order_id.exists' => 'Đơn hàng không tồn tại trong hệ thống.',
            'reason.required' => 'Bắt buộc phải nhập lý do xin điều chỉnh.',
            'reason.min' => 'Lý do xin điều chỉnh phải có ít nhất 5 ký tự.',
            'items.required' => 'Cần có ít nhất một dòng mặt hàng để điều chỉnh.',
            'items.min' => 'Cần có ít nhất một dòng mặt hàng để điều chỉnh.',
            'items.*.order_item_id.required' => 'Thiếu thông tin dòng sản phẩm cần điều chỉnh.',
            'items.*.order_item_id.exists' => 'Dòng sản phẩm không tồn tại trong đơn hàng.',
            'items.*.new_sku.required' => 'Mã SKU mới không được để trống.',
            'items.*.new_sku.exists' => 'Mã SKU mới không tồn tại trong hệ thống sản phẩm KÜCHEN.',
            'items.*.new_quantity.required' => 'Số lượng mới không được để trống.',
            'items.*.new_quantity.integer' => 'Số lượng mới phải là số nguyên.',
            'items.*.new_quantity.min' => 'Số lượng mới phải là số nguyên dương (lớn hơn hoặc bằng 1).',
        ];
    }
}
