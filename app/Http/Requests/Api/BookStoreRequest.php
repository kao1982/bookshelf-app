<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class BookStoreRequest extends FormRequest
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
        // タイトル：必須、文字列、255文字以内
        'title' => 'required|string|max:255',

        // 著者名：必須、文字列、255文字以内
        'author' => 'required|string|max:255',

        // ジャンルID：必須、genresテーブルに存在するID
        'genre_id' => 'required|integer|exists:genres,id',

        // 概要：任意、文字列、1000文字以内
        'description' => 'nullable|string|max:1000',

        // ISBN：必須、文字列、booksテーブル内で重複不可
        'isbn' => [
            'required',
            'string',
            'regex:/^([0-9]{10}|[0-9]{13})$/',
            'unique:books,isbn',
        ],

        // 出版日：必須、日付形式
        'published_date' => 'required|date',

        // 画像URL：任意、URL形式、2048文字以内
        'image_url' => 'nullable|url|max:2048',

        // 登録者ID：必須、usersテーブルに存在するID
        'user_id' => 'required|integer|exists:users,id',
    ];
    }
    /**
     * バリデーションエラーメッセージ
     */
    public function messages(): array
    {
        return [
            'title.required' => 'タイトルは必須項目です',
            'title.string' => 'タイトルは文字列で入力してください',
            'title.max' => 'タイトルは255文字以内で入力してください',

            'author.required' => '著者名は必須項目です',
            'author.string' => '著者名は文字列で入力してください',
            'author.max' => '著者名は255文字以内で入力してください',

            'isbn.required' => 'ISBNは必須項目です',
            'isbn.string' => 'ISBNは文字列で入力してください',
            'isbn.regex' => 'ISBNは10桁又は13桁の数字で入力してください',
            'isbn.unique' => 'このISBNはすでに登録されています',

            'genre_id.required' => 'ジャンルIDは必須項目です',
            'genre_id.integer' => 'ジャンルIDは整数で入力してください',
            'genre_id.exists' => '指定されたジャンルは存在しません',

            'published_date.required' => '出版日は必須項目です',
            'published_date.date' => '出版日は正しい日付で入力してください',

            'description.string' => '概要は文字列で入力してください',
            'description.max' => '概要は1000文字以内で入力してください',

            'image_url.string' => '画像URLは文字列で入力してください',
            'image_url.max' => '画像URLは2048文字以内で入力してください',
            'image_url.url' => '正しいURL形式で入力してください',

            'user_id.required' => '登録者IDは必須項目です',
            'user_id.integer' => '登録者IDは整数で入力してください',
            'user_id.exists' => '指定された登録者は存在しません',
        ];
    }
}
