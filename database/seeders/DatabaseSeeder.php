<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\Product;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // 管理者ユーザー
        User::factory()->create([
            'name'     => '管理者',
            'email'    => 'admin@example.com',
            'is_admin' => true,
        ]);

        // ユーザー3人（パスワードはすべて "password"）
        $users = collect([
            User::factory()->create(['name' => '田中 花子', 'email' => 'hanako@example.com']),
            User::factory()->create(['name' => '鈴木 太郎', 'email' => 'taro@example.com']),
            User::factory()->create(['name' => '佐藤 美咲', 'email' => 'misaki@example.com']),
        ]);

        // タグ5つ
        $tags = collect([
            Tag::create(['name' => '古着']),
            Tag::create(['name' => 'ビンテージ']),
            Tag::create(['name' => '90s']),
            Tag::create(['name' => 'アメカジ']),
            Tag::create(['name' => 'ストリート']),
        ]);

        // 商品5件
        $products = collect([
            Product::factory()->create([
                'user_id'     => $users->get(0)->id,
                'title'       => 'LEVI\'S 501 デニムジャケット 90s',
                'description' => '90年代製のリーバイスデニムジャケット。程よいヴィンテージ感が魅力です。',
                'price'       => 8500,
                'brand'       => 'LEVI\'S',
                'size'        => 'M',
                'category'    => 'アウター',
                'condition'   => 3,
            ]),
            Product::factory()->create([
                'user_id'     => $users->get(1)->id,
                'title'       => 'Carhartt ダック地ワークパンツ',
                'description' => 'カーハートの定番ワークパンツ。使い込まれた味のある色落ちが特徴。',
                'price'       => 5800,
                'brand'       => 'Carhartt',
                'size'        => 'L',
                'category'    => 'ボトムス',
                'condition'   => 4,
            ]),
            Product::factory()->create([
                'user_id'     => $users->get(2)->id,
                'title'       => 'Champion リバースウィーブ スウェット 80s',
                'description' => '80年代製リバースウィーブ。首元・袖のリブもしっかり残っています。',
                'price'       => 12000,
                'brand'       => 'Champion',
                'size'        => 'XL',
                'category'    => 'トップス',
                'condition'   => 2,
            ]),
            Product::factory()->create([
                'user_id'     => $users->get(0)->id,
                'title'       => 'Ralph Lauren ポロシャツ 紺 L',
                'description' => 'ラルフローレンの定番ポロ。ネイビーカラーが上品で使いやすいです。',
                'price'       => 3200,
                'brand'       => 'Ralph Lauren',
                'size'        => 'L',
                'category'    => 'トップス',
                'condition'   => 3,
            ]),
            Product::factory()->create([
                'user_id'     => $users->get(1)->id,
                'title'       => 'Dickies ペインターパンツ ベージュ',
                'description' => 'ディッキーズのペインターパンツ。ゆったりシルエットで今っぽい着こなしに。',
                'price'       => 4500,
                'brand'       => 'Dickies',
                'size'        => 'M',
                'category'    => 'ボトムス',
                'condition'   => 3,
            ]),
        ]);

        // 商品にタグを付与（各商品に1〜3つ）
        $products->each(function (Product $product) use ($tags) {
            $product->tags()->attach($tags->random(rand(1, 3))->pluck('id'));
        });

        // 投稿5件（一部に商品リンク）
        $posts = collect([
            Post::factory()->create([
                'user_id'    => $users->get(0)->id,
                'product_id' => $products->get(0)->id,
                'body'       => '90sのリーバイスデニムジャケットを出品しました！程よいアタリが最高です。ぜひチェックしてみてください🧥',
            ]),
            Post::factory()->create([
                'user_id'    => $users->get(2)->id,
                'product_id' => $products->get(2)->id,
                'body'       => '80sのチャンピオン リバースウィーブ、状態良好です。この年代のスウェットは生地の厚みが全然違います。',
            ]),
            Post::factory()->create([
                'user_id'    => $users->get(2)->id,
                'product_id' => null,
                'body'       => '今日は下北沢で古着巡り。掘り出し物を見つけたときの興奮がたまらない。',
            ]),
            Post::factory()->create([
                'user_id'    => $users->get(0)->id,
                'product_id' => $products->get(4)->id,
                'body'       => 'ディッキーズのペインターパンツ出品中です。ゆるっとした感じが今の気分にぴったり。',
            ]),
            Post::factory()->create([
                'user_id'    => $users->get(1)->id,
                'product_id' => null,
                'body'       => 'アメカジコーデ。カーハートのジャケットにリーバイスの501。やっぱりこの組み合わせは最強。',
            ]),
        ]);

        // 投稿にタグを付与（各投稿に1〜3つ）
        $posts->each(function (Post $post) use ($tags) {
            $post->tags()->attach($tags->random(rand(1, 3))->pluck('id'));
        });
    }
}
