<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class BookIndexRequest extends FormRequest
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
            // キーワード：入力がなくてもOK、文字列、255文字以内
            'keyword' => 'nullable|string|max:255',

            // ジャンルID：入力がなくてもOK、整数、genresテーブルに存在するID
            'genre_id' => 'nullable|integer|exists:genres,id',

            // ページ番号：入力がなくてもOK、整数、1以上
            'page' => 'nullable|integer|min:1',

            // 1ページあたりの件数：入力がなくてもOK、整数、10～100
            'per_page' => 'nullable|integer|between:10,100',

            // 並び順：入力がなくてもOK、指定された4種類のみ
            'sort' => 'nullable|in:latest,oldest,title,rating',

            // 並び順：入力がなくてもOK、指定された値のみ
            'sort' => 'nullable|in:latest,oldest,title,rating',
        ];
    }
    /**
     * バリデーションエラーメッセージ
     */
    public function messages(): array
    {
        return [
            'keyword.string' => 'キーワードは文字列で入力してください',
            'keyword.max' => 'キーワードは255文字以内で入力してください',

            'genre_id.integer' => 'ジャンルIDは整数で指定してください。',
            'genre_id.exists' => '指定されたジャンルIDは存在しません。',

            'page.integer' => 'ページ番号は整数で指定してください。',
            'page.min' => 'ページ番号は1以上の数値を指定してください。',

            'per_page.integer' => 'ページあたり件数は整数で指定してください。',
            'per_page.between' => 'ページあたり件数は10件から100件の間で指定してください。',
        ];
    }
}
