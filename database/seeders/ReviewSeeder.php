<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Review;
use App\Models\User;
use App\Models\Book;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $reviews = [
            [
                'user_id' => 1,
                'book_id' => 1,
                'rating' => 5,
                'comment' => '猫の視点で描かれる世界観が新鮮で、ユーモアのある文章を楽しめました。',
            ],
            [
                'user_id' => 2,
                'book_id' => 1,
                'rating' => 4,
                'comment' => '夏目漱石らしい表現の美しさを感じられる、印象に残る作品でした。',
            ],
            [
                'user_id' => 3,
                'book_id' => 1,
                'rating' => 5,
                'comment' => '時代を超えて読まれる理由が分かる、魅力的な文学作品です。',
            ],
            [
                'user_id' => 4,
                'book_id' => 2,
                'rating' => 5,
                'comment' => '人との関わり方について多くの気づきを得られる一冊でした。',
            ],
            [
                'user_id' => 5,
                'book_id' => 2,
                'rating' => 4,
                'comment' => '仕事や日常生活で実践できる考え方が分かりやすくまとめられています。',
            ],
            [
                'user_id' => 1,
                'book_id' => 2,
                'rating' => 5,
                'comment' => 'コミュニケーションの大切さを改めて考えるきっかけになりました。',
            ],
            [
                'user_id' => 2,
                'book_id' => 3,
                'rating' => 5,
                'comment' => 'コードを読みやすく書く重要性を理解できる、実践的な技術書でした。',
            ],
            [
                'user_id' => 3,
                'book_id' => 3,
                'rating' => 4,
                'comment' => '具体例が多く、開発時に意識すべきポイントが学べました。',
            ],
            [
                'user_id' => 4,
                'book_id' => 3,
                'rating' => 5,
                'comment' => 'エンジニアとして成長するために何度も読み返したい本です。',
            ],
            [
                'user_id' => 5,
                'book_id' => 4,
                'rating' => 5,
                'comment' => '習慣を見直し、自分の行動を変えるきっかけになりました。',
            ],
            [
                'user_id' => 1,
                'book_id' => 4,
                'rating' => 4,
                'comment' => '仕事や人生への向き合い方について深く考えさせられる内容でした。',
            ],
            [
                'user_id' => 2,
                'book_id' => 5,
                'rating' => 4,
                'comment' => '明治時代の雰囲気や主人公の性格が魅力的に描かれた作品でした。',
            ],
            [
                'user_id' => 3,
                'book_id' => 5,
                'rating' => 5,
                'comment' => 'ユーモアのある展開で、最後まで楽しく読むことができました。',
            ],
            [
                'user_id' => 4,
                'book_id' => 6,
                'rating' => 5,
                'comment' => '人類の歴史を幅広い視点から学ぶことができ、とても興味深かったです。',
            ],
            [
                'user_id' => 5,
                'book_id' => 6,
                'rating' => 5,
                'comment' => '歴史と科学を結びつけた説明が分かりやすく、知識が深まりました。',
            ],
            [
                'user_id' => 1,
                'book_id' => 6,
                'rating' => 4,
                'comment' => '世界の見方が変わるような内容で、新しい視点を得られました。',
            ],
            [
                'user_id' => 2,
                'book_id' => 7,
                'rating' => 5,
                'comment' => 'きれいなコードを書くための考え方を学べる素晴らしい技術書です。',
            ],
            [
                'user_id' => 3,
                'book_id' => 7,
                'rating' => 4,
                'comment' => 'プログラムの品質について考える良いきっかけになりました。',
            ],
            [
                'user_id' => 4,
                'book_id' => 7,
                'rating' => 5,
                'comment' => 'チーム開発でも役立つ知識が多く、とても参考になりました。',
            ],
            [
                'user_id' => 5,
                'book_id' => 8,
                'rating' => 5,
                'comment' => '物事の考え方を変えるきっかけになる、印象深い一冊でした。',
            ],
            [
                'user_id' => 1,
                'book_id' => 8,
                'rating' => 4,
                'comment' => '心理学の考え方を通して、自分自身を見つめ直すことができました。',
            ],
            [
                'user_id' => 2,
                'book_id' => 8,
                'rating' => 5,
                'comment' => '前向きに生きるためのヒントが多く得られる内容でした。',
            ],
            [
                'user_id' => 3,
                'book_id' => 9,
                'rating' => 4,
                'comment' => '人間関係や夢を追う姿が描かれていて、心に残る作品でした。',
            ],
            [
                'user_id' => 4,
                'book_id' => 9,
                'rating' => 5,
                'comment' => '登場人物の感情が伝わってきて、物語に引き込まれました。',
            ],
            [
                'user_id' => 5,
                'book_id' => 9,
                'rating' => 3,
                'comment' => '独特な雰囲気のある作品で、好みは分かれますが印象に残りました。',
            ],
            [
                'user_id' => 1,
                'book_id' => 10,
                'rating' => 5,
                'comment' => '思い込みで判断していたことに気付かされる内容でした。',
            ],
            [
                'user_id' => 2,
                'book_id' => 10,
                'rating' => 4,
                'comment' => 'データを基に世界を見る大切さを学ぶことができました。',
            ],
            [
                'user_id' => 3,
                'book_id' => 10,
                'rating' => 5,
                'comment' => '難しいテーマを分かりやすく説明していて、とても勉強になりました。',
            ],
            [
                'user_id' => 4,
                'book_id' => 11,
                'rating' => 4,
                'comment' => '物流の歴史を知ることができ、普段の生活を見る視点が変わりました。',
            ],
            [
                'user_id' => 5,
                'book_id' => 11,
                'rating' => 5,
                'comment' => 'コンテナが世界経済に与えた影響を知ることができる興味深い本でした。',
            ],
            [
                'user_id' => 3,
                'book_id' => 4,
                'rating' => 3,
                'comment' => '内容は少し難しく感じましたが、長期的な目標や考え方を見直す良い機会になりました。',
            ],
            [
                'user_id' => 1,
                'book_id' => 11,
                'rating' => 4,
                'comment' => '歴史とビジネスのつながりを楽しく学べる一冊でした。',
            ],
        ];

        foreach ($reviews as $review) {
        Review::create($review);
        }
    }
}