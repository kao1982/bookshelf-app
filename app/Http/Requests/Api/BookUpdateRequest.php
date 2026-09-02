<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BookUpdateRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'author' => ['required', 'string', 'max:255'],
            'isbn' => [
                'required',
                'string',
                'regex:/^([0-9]{10}|[0-9]{13})$/',
                Rule::unique('books', 'isbn')->ignore($this->route('book')),
            ],
            'genre_id' => ['required', 'integer', 'exists:genres,id'],
            'description' => ['nullable', 'string', 'max:1000'],
            'published_date' => ['required', 'date'],
            'image_url' => ['nullable', 'string', 'url', 'max:2048'],
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
            'isbn.regex' => 'ISBNは10桁または13桁の数字で入力してください',
            'isbn.unique' => 'このISBNはすでに登録されています',

            'genre_id.required' => 'ジャンルIDは必須項目です',
            'genre_id.integer' => 'ジャンルIDは整数で入力してください',
            'genre_id.exists' => '指定されたジャンルは存在しません',

            'description.string' => '概要は文字列で入力してください',
            'description.max' => '概要は1000文字以内で入力してください',

            'published_date.required' => '出版日は必須項目です',
            'published_date.date' => '出版日は正しい日付で入力してください',

            'image_url.string' => '画像URLは文字列で入力してください',
            'image_url.url' => '正しいURL形式で入力してください',
            'image_url.max' => '画像URLは2048文字以内で入力してください',
        ];
    }
}