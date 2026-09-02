<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BookRequest extends FormRequest
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
                'nullable',
                'regex:/^([0-9]{10}|[0-9]{13})$/',Rule::unique('books', 'isbn')->ignore($this->route('book')),
            ],
            'published_date' => ['nullable', 'date'],
            'description' => ['nullable', 'string'],
            'image_url' => ['nullable', 'url', 'max:2048'],
            'genres' => ['required', 'array', 'min:1'],
            'genres.*' => ['exists:genres,id'],
        ];
    }

    public function messages(): array
{
    return [
        'title.required' => 'タイトルは入力必須です',
        'title.string' => 'タイトルは文字列で入力してください',
        'title.max' => 'タイトルは255文字以内で入力してください',
        'author.required' => '著者名は入力必須です',
        'author.string' => '著者名は文字列で入力してください',
        'author.max' => '著者名は255文字以内で入力してください',
        'isbn.required' => 'ISBNは入力必須です',
        'isbn.regex' => 'ISBNは10桁または13桁の数字で入力してください',
        'isbn.unique' => 'このISBNは既に登録されています',
        'published_date.required' => '出版日は入力必須です',
        'published_date.date' => '正しい日付を入力してください',
        'description.string' => '説明は文字列で入力してください',
        'image_url.url' => '正しいURL形式で入力してください',
        'image_url.max' => '画像URLは2048文字以内で入力してください',
        'genres.required' => 'ジャンルを1つ以上選択してください',
        'genres.array' => 'ジャンルの形式が正しくありません',
        'genres.min' => 'ジャンルを1つ以上選択してください',
        'genres.*.exists' => '選択したジャンルが存在しません',
    ];
}
}