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

    private const IMG_JACKET = 'https://images.unsplash.com/photo-1618354691373-d851c5c3a990';
    private const IMG_HOODIE = 'https://images.unsplash.com/photo-1556821840-3a63f15732ce';
    private const IMG_TEE    = 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab';
    private const IMG_DENIM  = 'https://images.unsplash.com/photo-1594938298603-c8148c4b4b6a';
    private const IMG_SHOES  = 'https://images.unsplash.com/photo-1542291026-7eec264c27ff';
    private const IMG_SCARF  = 'https://images.unsplash.com/photo-1612336307429-8a898d10e223';
    private const IMG_OUTFIT = 'https://images.unsplash.com/photo-1523381210434-271e8be1f52b';

    public function run(): void
    {
        // 管理者
        User::factory()->create([
            'name'     => '管理者',
            'email'    => 'admin@example.com',
            'is_admin' => true,
        ]);

        // テストユーザー（講師確認用）
        User::factory()->create([
            'name'  => 'テストユーザー',
            'email' => 'test@example.com',
        ]);

        $tags = collect([
            Tag::create(['name' => '古着']),
            Tag::create(['name' => 'ビンテージ']),
            Tag::create(['name' => '90s']),
            Tag::create(['name' => 'アメカジ']),
            Tag::create(['name' => 'ストリート']),
            Tag::create(['name' => 'デザイナーズ']),
            Tag::create(['name' => 'ワークウェア']),
        ]);

        // 5ヶ国のダミーユーザー（各3商品・3投稿）
        $usersData = [

            // 🇯🇵 日本人
            [
                'user'     => ['name' => '山田 凛', 'email' => 'rin.yamada@example.com'],
                'products' => [
                    ['title' => "LEVI'S 517 フレアデニム 70s USA製", 'description' => '70年代のアメリカ製フレアデニム。全体にヴィンテージらしいフェードが入っています。', 'price' => 14800, 'brand' => "LEVI'S", 'size' => '28', 'category' => 'ボトムス', 'condition' => 3, 'image' => self::IMG_DENIM],
                    ['title' => 'Champion リバースウィーブ スウェット グレー XL', 'description' => '80年代製リバースウィーブ。首元・袖のリブもしっかり残っています。', 'price' => 12000, 'brand' => 'Champion', 'size' => 'XL', 'category' => 'トップス', 'condition' => 2, 'image' => self::IMG_HOODIE],
                    ['title' => 'Lee 101-B デニムジャケット ワンウォッシュ M', 'description' => 'リーのアーカイブモデル。ワンウォッシュ加工でやや落ち着いたインディゴに。', 'price' => 9200, 'brand' => 'Lee', 'size' => 'M', 'category' => 'アウター', 'condition' => 2, 'image' => self::IMG_JACKET],
                ],
                'posts' => [
                    ['body' => "70sのLEVI'S 517、ようやく出品しました。フレアシルエットが今の気分に合いすぎる。デニムは語る。", 'product_index' => 0],
                    ['body' => 'チャンピオンのリバースウィーブは生地の厚みが違います。着込むほど味が出てくるのがたまらない。出品中です！', 'product_index' => 1],
                    ['body' => 'リーのデニムジャケットを出品しました。ワンウォッシュ加工でやや落ち着いたインディゴが渋い仕上がりです。', 'product_index' => 2],
                ],
            ],

            // 🇰🇷 韓国人
            [
                'user'     => ['name' => '김지수 (Kim Jisoo)', 'email' => 'jisoo.kim@example.com'],
                'products' => [
                    ['title' => 'Carhartt WIP OG Active Jacket ブラック M', 'description' => '카하트 WIP의 정통 액티브 재킷. 상태 양호합니다.', 'price' => 11000, 'brand' => 'Carhartt WIP', 'size' => 'M', 'category' => 'アウター', 'condition' => 2, 'image' => self::IMG_JACKET],
                    ['title' => 'Stüssy 8 Ball Tee ホワイト L', 'description' => '스투시 에이트볼 티셔츠. 프린트 선명, 거의 미착용 상태.', 'price' => 4800, 'brand' => 'Stüssy', 'size' => 'L', 'category' => 'トップス', 'condition' => 2, 'image' => self::IMG_TEE],
                    ['title' => 'Nike ACG 90s ナイロンジャケット グレー L', 'description' => '90년대 나이키 ACG 나일론 재킷. 빈티지 감성 넘칩니다.', 'price' => 8900, 'brand' => 'Nike', 'size' => 'L', 'category' => 'アウター', 'condition' => 3, 'image' => self::IMG_JACKET],
                ],
                'posts' => [
                    ['body' => '오늘 카하트 재킷 업로드했어요~ 스트릿 룩에 딱인 아이템이에요. 상태도 굉장히 좋아요!', 'product_index' => 0],
                    ['body' => '스투시 에이트볼 티 입고 홍대 빈티지샵 투어 다녀왔어요. 프린트 선명해서 상태 진짜 대박입니다.', 'product_index' => 1],
                    ['body' => '나이키 ACG 나일론 재킷, 90년대 빈티지 감성 폭발🔥 사이즈 L이에요. 관심 있으신 분 연락주세요!', 'product_index' => 2],
                ],
            ],

            // 🇨🇳 中国人
            [
                'user'     => ['name' => '陈浩然 (Chen Haoran)', 'email' => 'haoran.chen@example.com'],
                'products' => [
                    ['title' => 'Supreme Box Logo Tee 黒 M 2019', 'description' => '2019年シーズンのボックスロゴ。未着用保管品、タグ付き。', 'price' => 32000, 'brand' => 'Supreme', 'size' => 'M', 'category' => 'トップス', 'condition' => 1, 'image' => self::IMG_TEE],
                    ['title' => 'The North Face Nuptse 1996 ダウン ネイビー L', 'description' => '96年製ヌプシ。フィルパワー健在、ダウン抜け少なめです。', 'price' => 28000, 'brand' => 'The North Face', 'size' => 'L', 'category' => 'アウター', 'condition' => 3, 'image' => self::IMG_JACKET],
                    ['title' => 'BAPE Shark Full Zip Hoodie カモ XL', 'description' => 'BAPEのシャークフーディ。カモ柄、ファスナー動作良好。', 'price' => 22000, 'brand' => 'A BATHING APE', 'size' => 'XL', 'category' => 'トップス', 'condition' => 3, 'image' => self::IMG_HOODIE],
                ],
                'posts' => [
                    ['body' => 'Supreme Box Logo终于到手了！2019年款，全新未穿，正品保证，有意向的朋友欢迎联系我。', 'product_index' => 0],
                    ['body' => 'Nuptse 1996是经典中的经典，每到冬天都是最保暖最百搭的选择。现在出给有缘人！', 'product_index' => 1],
                    ['body' => 'BAPE鲨鱼卫衣迷彩款上架啦🦈 穿上街回头率极高，现在决定出清，感兴趣的来聊！', 'product_index' => 2],
                ],
            ],

            // 🇺🇸 アメリカ人
            [
                'user'     => ['name' => 'Jake Morrison', 'email' => 'jake.morrison@example.com'],
                'products' => [
                    ['title' => 'Pendleton Woolen Shirt XL USA Made', 'description' => 'Classic Pendleton wool shirt in great condition. Made in USA, thick and warm.', 'price' => 7200, 'brand' => 'Pendleton', 'size' => 'XL', 'category' => 'トップス', 'condition' => 3, 'image' => self::IMG_TEE],
                    ['title' => 'Filson Mackinaw Cruiser Jacket 42', 'description' => 'Heavy-duty Filson cruiser in original plaid. Some wear but still incredibly durable.', 'price' => 19800, 'brand' => 'Filson', 'size' => 'XL', 'category' => 'アウター', 'condition' => 4, 'image' => self::IMG_JACKET],
                    ['title' => 'Red Wing 875 Moc Toe ブーツ 9D', 'description' => 'Work-worn Red Wings with great patina. Resoled once, plenty of life left.', 'price' => 13500, 'brand' => 'Red Wing', 'size' => '27cm', 'category' => 'その他', 'condition' => 4, 'image' => self::IMG_SHOES],
                ],
                'posts' => [
                    ['body' => 'Just listed my Pendleton wool shirt — classic American workwear that never goes out of style. Built to last forever.', 'product_index' => 0],
                    ['body' => 'Filson cruiser jacket up for sale. Built like a tank, been with me through rain and snow for years.', 'product_index' => 1],
                    ['body' => "Red Wings finally up for grabs. These boots have taken me everywhere. Time to pass them on to someone who'll love them. 🥾", 'product_index' => 2],
                ],
            ],

            // 🇮🇹 イタリア人
            [
                'user'     => ['name' => 'Sofia Marchetti', 'email' => 'sofia.marchetti@example.com'],
                'products' => [
                    ['title' => "Levi's 501 高腰デニム ショーツ W26", 'description' => "Pantaloncini di jeans Levi's 501 vintage tagliati a mano. Perfetti per l'estate.", 'price' => 4200, 'brand' => "Levi's", 'size' => 'W26', 'category' => 'ボトムス', 'condition' => 3, 'image' => self::IMG_DENIM],
                    ['title' => 'Versace ヴィンテージ シルクスカーフ FREE', 'description' => 'Foulard di seta Versace vintage. Stampa barocca, colori vivaci e intatti.', 'price' => 12000, 'brand' => 'Versace', 'size' => 'FREE', 'category' => 'その他', 'condition' => 2, 'image' => self::IMG_SCARF],
                    ['title' => 'Moschino 90s ロゴプリント Tシャツ S', 'description' => "T-shirt Moschino anni '90 con logo originale. Pezzi così non se ne trovano più.", 'price' => 8800, 'brand' => 'Moschino', 'size' => 'S', 'category' => 'トップス', 'condition' => 3, 'image' => self::IMG_TEE],
                ],
                'posts' => [
                    ['body' => "Ho trovato questi Levi's 501 tagliati a un mercatino vintage. Perfetti per l'estate, ora li metto in vendita!", 'product_index' => 0],
                    ['body' => 'Foulard Versace vintage in seta pura — colori vivaci e stampa barocca intatta. Un pezzo unico da collezione. 🌸', 'product_index' => 1],
                    ['body' => "T-shirt Moschino anni '90 con logo originale. Pezzi così non si trovano più facilmente, approfittate! 💫", 'product_index' => 2],
                ],
            ],
        ];

        foreach ($usersData as $userData) {
            $user = User::factory()->create($userData['user']);

            $userProducts = collect();
            foreach ($userData['products'] as $productData) {
                $image = $productData['image'];
                unset($productData['image']);
                $product = Product::factory()->create(array_merge($productData, [
                    'user_id'    => $user->id,
                    'status'     => 1,
                    'image_path' => $image,
                ]));
                $product->tags()->attach($tags->shuffle()->take(rand(1, 3))->pluck('id'));
                $userProducts->push($product);
            }

            foreach ($userData['posts'] as $postData) {
                $product = $userProducts->get($postData['product_index']);
                $post = Post::factory()->create([
                    'user_id'    => $user->id,
                    'product_id' => $product->id,
                    'body'       => $postData['body'],
                    'image_path' => self::IMG_OUTFIT,
                ]);
                $post->tags()->attach($tags->shuffle()->take(rand(1, 2))->pluck('id'));
            }
        }
    }
}
