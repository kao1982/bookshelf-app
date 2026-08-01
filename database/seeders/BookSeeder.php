<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Book;
use App\Models\User;
use App\Models\Genre;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first();
        $books = [
            [
                'title' => '吾輩は猫である',
                'author' => '夏目漱石',
                'isbn' => '9784101010014',
                'published_date' => '1905-01-01',
                'description' => '夏目漱石の代表作。',
                'image_number' => 1,
                'genres' => ['小説'],
            ],
            [
                'title' => '人を動かす',
                'author' => 'D・カーネギー',
                'isbn' => '9784422100524',
                'published_date' => '1936-10-01',
                'description' => '人間関係やコミュニケーションについて学べる本。',
                'image_number' => 2,
                'genres' => ['ビジネス', '自己啓発'],
            ],
            [
                'title' => 'リーダブルコード',
                'author' => 'Dustin Boswell',
                'isbn' => '9784873115658',
                'published_date' => '2012-06-23',
                'description' => '読みやすいコードを書くための技術書。',
                'image_number' => 3,
                'genres' => ['技術書'],
            ],
            [
                'title' => '7つの習慣',
                'author' => 'スティーブン・R・コヴィー',
                'isbn' => '9784863940246',
                'published_date' => '2013-08-30',
                'description' => '人生や仕事における習慣について学ぶ本。',
                'image_number' => 4,
                'genres' => ['ビジネス', '自己啓発'],
            ],
            [
                'title' => '坊っちゃん',
                'author' => '夏目漱石',
                'isbn' => '9784101010021',
                'published_date' => '1906-04-01',
                'description' => '夏目漱石の代表的な小説。',
                'image_number' => 5,
                'genres' => ['小説'],
            ],
            [
                'title' => 'サピエンス全史',
                'author' => 'ユヴァル・ノア・ハラリ',
                'isbn' => '9784309226712',
                'published_date' => '2016-09-08',
                'description' => '人類の歴史を解説する書籍。',
                'image_number' => 6,
                'genres' => ['歴史', '科学'],
            ],
            [
                'title' => 'Clean Code',
                'author' => 'Robert C. Martin',
                'isbn' => '9784048930598',
                'published_date' => '2017-12-18',
                'description' => '保守しやすいコードを書くための技術書。',
                'image_number' => 7,
                'genres' => ['技術書'],
            ],
            [
                'title' => '嫌われる勇気',
                'author' => '岸見一郎・古賀史健',
                'isbn' => '9784478025819',
                'published_date' => '2013-12-13',
                'description' => 'アドラー心理学をもとにした自己啓発書。',
                'image_number' => 8,
                'genres' => ['自己啓発'],
            ],
            [
                'title' => '火花',
                'author' => '又吉直樹',
                'isbn' => '9784163902302',
                'published_date' => '2015-03-11',
                'description' => '又吉直樹による小説。',
                'image_number' => 9,
                'genres' => ['小説'],
            ],
            [
                'title' => 'FACTFULNESS',
                'author' => 'ハンス・ロスリング',
                'isbn' => '9784822289607',
                'published_date' => '2019-01-11',
                'description' => 'データをもとに世界を見るための本。',
                'image_number' => 10,
                'genres' => ['ビジネス', '科学'],
            ],
            [
                'title' => 'コンテナ物語',
                'author' => 'マルク・レビンソン',
                'isbn' => '9784822251468',
                'published_date' => '2007-01-18',
                'description' => 'コンテナ輸送の歴史を解説する本。',
                'image_number' => 11,
                'genres' => ['ビジネス', '歴史'],
            ],
        ];
                foreach ($books as $bookData) {
                    $book = Book::firstOrCreate(
                        [
                            'isbn' => $bookData['isbn'],
                        ],
                        [
                            'title' => $bookData['title'],
                            'author' => $bookData['author'],
                            'published_date' => $bookData['published_date'],
                            'description' => $bookData['description'],
                            'image_url' => 'https://placehold.co/200x300/e2e8f0/475569?text=' . $bookData['image_number'],
                            'user_id' => $user->id,
                        ]
                    );

                    $genreIds = [];

                    foreach ($bookData['genres'] as $genreName) {
                        $genre = Genre::where('name', $genreName)->first();
                        $genreIds[] = $genre->id;
                    }

                    $book->genres()->sync($genreIds);
        }
    }
}
