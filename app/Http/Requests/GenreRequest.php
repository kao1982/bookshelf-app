<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GenreRequest extends FormRequest
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
            // ジャンル名は必須、文字列、50文字以内
            // 編集時は、現在編集中のジャンル自身を重複チェックから除外する
            'name' => [
                'required',
                'string',
                'max:50',
                Rule::unique('genres', 'name')->ignore($this->route('genre')),
            ],
        ];
    }
    /**
     * バリデーションエラーメッセージ
     */
    public function messages(): array
    {
        return [
            // ジャンル名が未入力の場合
            'name.required' => 'ジャンル名を入力してください',

            // ジャンル名が文字列ではない場合
            'name.string' => 'ジャンル名は文字列で入力してください',

            // ジャンル名が50文字を超えた場合
            'name.max' => 'ジャンル名は50文字以内で入力してください',

            // すでに同じジャンル名が登録されている場合
            'name.unique' => 'このジャンル名はすでに登録されています',
        ];
    }
}
