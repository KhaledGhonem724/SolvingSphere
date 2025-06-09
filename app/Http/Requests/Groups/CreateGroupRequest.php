<?php

namespace App\Http\Requests\Groups;

use Illuminate\Foundation\Http\FormRequest;

class CreateGroupRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'short_name' => 'required|string|max:50|unique:groups,short_name',
            'description' => 'nullable|string|max:1000',
            'visibility' => 'required|in:public,private',
            'join_policy' => 'required|in:free,invite_only,apply_approve',
            'max_members' => 'nullable|integer|min:2|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'اسم المجموعة مطلوب',
            'short_name.required' => 'الاسم المختصر مطلوب',
            'short_name.unique' => 'هذا الاسم المختصر مستخدم بالفعل',
            'visibility.required' => 'نوع رؤية المجموعة مطلوب',
            'join_policy.required' => 'سياسة الانضمام مطلوبة',
        ];
    }
} 