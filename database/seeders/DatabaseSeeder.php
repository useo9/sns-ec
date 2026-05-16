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

        // テストユーザー（講師確認用）
        User::factory()->create([
            'name'  => 'テストユーザー',
            'email' => 'test@example.com',
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
            $product->tags()->attach($tags->shuffle()->take(rand(1, 3))->pluck('id'));
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
            $post->tags()->attach($tags->shuffle()->take(rand(1, 3))->pluck('id'));
        });

        // 多国籍ユーザー10人分のダミーデータ
        $this->seedInternationalUsers($tags);
    }

    private function seedInternationalUsers($tags): void
    {
        $internationalData = [
            [
                'user'     => ['name' => '山田 凛', 'email' => 'rin.yamada@example.com'],
                'products' => [
                    ['title' => 'LEVI\'S 517 フレアデニム 70s USA製', 'description' => '70年代のアメリカ製フレアデニム。ヴィンテージらしいフェードが全体に入っています。', 'price' => 14800, 'brand' => "LEVI'S", 'size' => '28', 'category' => 'ボトムス', 'condition' => 3],
                    ['title' => 'Wrangler 11MWZ ストレートデニム', 'description' => 'ラングラーの定番モデル。ウエスタンスタッチのディテールが渋い。', 'price' => 6500, 'brand' => 'Wrangler', 'size' => '30', 'category' => 'ボトムス', 'condition' => 4],
                    ['title' => 'Lee 101-B デニムジャケット ワンウォッシュ', 'description' => 'リーのアーカイブモデル。ワンウォッシュ加工でやや落ち着いたインディゴに。', 'price' => 9200, 'brand' => 'Lee', 'size' => 'M', 'category' => 'アウター', 'condition' => 2],
                ],
                'posts' => [
                    ['body' => '70sのLEVI\'S 517、ようやく出品しました。フレアシルエットが今の気分に合いすぎる。', 'link_product' => 0],
                    ['body' => '古着屋で掘り出し物を発見。デニムは語る、とはよく言ったもの。', 'link_product' => null],
                    ['body' => 'ヴィンテージデニム沼にどっぷりはまってます。今週だけで3本増えた…', 'link_product' => null],
                ],
            ],
            [
                'user'     => ['name' => '김지수 (Kim Jisoo)', 'email' => 'jisoo.kim@example.com'],
                'products' => [
                    ['title' => 'Carhartt WIP OG Active Jacket ブラック M', 'description' => '카하트 WIP의 정통 액티브 재킷. 상태 양호합니다.', 'price' => 11000, 'brand' => 'Carhartt WIP', 'size' => 'M', 'category' => 'アウター', 'condition' => 2],
                    ['title' => 'Stüssy 8 Ball Tee ホワイト L', 'description' => '스투시 에이트볼 티셔츠. 프린트 선명, 거의 미착용 상태.', 'price' => 4800, 'brand' => 'Stüssy', 'size' => 'L', 'category' => 'トップス', 'condition' => 2],
                    ['title' => 'Nike ACG 90s ナイロンジャケット グレー', 'description' => '90년대 나이키 ACG 나일론 재킷. 빈티지 감성 넘침.', 'price' => 8900, 'brand' => 'Nike', 'size' => 'L', 'category' => 'アウター', 'condition' => 3],
                ],
                'posts' => [
                    ['body' => '오늘 카하트 재킷 업로드했어요~ 스트릿 룩에 잘 어울리는 아이템입니다.', 'link_product' => 0],
                    ['body' => '홍대 빈티지샵 투어 다녀왔어요. 한국 빈티지 씬 요즘 너무 좋아지고 있음.', 'link_product' => null],
                    ['body' => '스투시 x 카하트 믹스 코디. 스트릿 베이직은 역시 이 조합.', 'link_product' => 1],
                ],
            ],
            [
                'user'     => ['name' => '陈浩然 (Chen Haoran)', 'email' => 'haoran.chen@example.com'],
                'products' => [
                    ['title' => 'Supreme Box Logo Tee 黒 M 2019', 'description' => '2019年シーズンのボックスロゴ。未着用保管品、タグ付き。', 'price' => 32000, 'brand' => 'Supreme', 'size' => 'M', 'category' => 'トップス', 'condition' => 1],
                    ['title' => 'The North Face Nuptse 1996 ダウン ネイビー L', 'description' => '96年製ヌプシ。フィルパワー健在、ダウン抜け少なめです。', 'price' => 28000, 'brand' => 'The North Face', 'size' => 'L', 'category' => 'アウター', 'condition' => 3],
                    ['title' => 'BAPE Shark Full Zip Hoodie カモ XL', 'description' => 'BAPEのシャークフーディ。カモ柄、ファスナー動作良好。', 'price' => 22000, 'brand' => 'A BATHING APE', 'size' => 'XL', 'category' => 'トップス', 'condition' => 3],
                ],
                'posts' => [
                    ['body' => '上海的古着市场越来越有意思了！今天入手的Supreme真品，开心。', 'link_product' => 0],
                    ['body' => 'Supreme box logo总算到手了，状态完美，有兴趣的可以联系我。', 'link_product' => 0],
                    ['body' => 'Nuptse 1996是经典中的经典，每年冬天都想再入一件。', 'link_product' => 1],
                ],
            ],
            [
                'user'     => ['name' => 'Jake Morrison', 'email' => 'jake.morrison@example.com'],
                'products' => [
                    ['title' => 'Pendleton Woolen Shirt XL USA Made', 'description' => 'Classic Pendleton wool shirt in great condition. Made in USA, thick and warm.', 'price' => 7200, 'brand' => 'Pendleton', 'size' => 'XL', 'category' => 'トップス', 'condition' => 3],
                    ['title' => 'Filson Mackinaw Cruiser Jacket 42', 'description' => 'Heavy-duty Filson cruiser in original plaid. Some wear but super durable still.', 'price' => 19800, 'brand' => 'Filson', 'size' => 'XL', 'category' => 'アウター', 'condition' => 4],
                    ['title' => 'Red Wing 875 Moc Toe ブーツ 9D', 'description' => 'Work-worn Red Wings with great patina. Resoled once, plenty of life left.', 'price' => 13500, 'brand' => 'Red Wing', 'size' => '27cm', 'category' => 'その他', 'condition' => 4],
                ],
                'posts' => [
                    ['body' => 'Just listed my Pendleton wool shirt — perfect for layering season. American workwear never goes out of style.', 'link_product' => 0],
                    ['body' => 'Thrifted in Portland last weekend. Found some gems at the bins. US vintage scene is alive and well.', 'link_product' => null],
                    ['body' => 'Red Wings finally up for sale. These boots have been everywhere with me. Time to pass them on.', 'link_product' => 2],
                ],
            ],
            [
                'user'     => ['name' => '박민준 (Park Minjun)', 'email' => 'minjun.park@example.com'],
                'products' => [
                    ['title' => 'Polo Ralph Lauren ニットセーター クルーネック M', 'description' => '폴로 랄프로렌 니트. 보풀 없이 깨끗한 상태입니다.', 'price' => 5500, 'brand' => 'Ralph Lauren', 'size' => 'M', 'category' => 'トップス', 'condition' => 2],
                    ['title' => 'Tommy Hilfiger フラッグロゴ コーチジャケット L', 'description' => '90s 타미힐피거 코치 재킷. 로고 자수 선명, 세탁 완료.', 'price' => 7800, 'brand' => 'Tommy Hilfiger', 'size' => 'L', 'category' => 'アウター', 'condition' => 3],
                    ['title' => 'Nautica セーリングジャケット ネイビー XL', 'description' => '90s 노티카 세일링 재킷. 바람막이 기능 살아있고 상태 굿.', 'price' => 9600, 'brand' => 'Nautica', 'size' => 'XL', 'category' => 'アウター', 'condition' => 3],
                ],
                'posts' => [
                    ['body' => '90s 아메카지 감성 너무 좋아... 타미힐피거랑 노티카 조합은 진리.', 'link_product' => 1],
                    ['body' => '이번 주 빈티지 하울! 폴로 니트를 겨우 건졌습니다. 상태 대박.', 'link_product' => 0],
                    ['body' => '빈티지 아메카지는 역시 봄이 제철. 코치 재킷 시즌 시작이에요.', 'link_product' => null],
                ],
            ],
            [
                'user'     => ['name' => '渡辺 颯太', 'email' => 'sota.watanabe@example.com'],
                'products' => [
                    ['title' => 'COMME des GARÇONS SHIRT ストライプシャツ S', 'description' => 'コムデギャルソンシャツのアーカイブ。ストライプ柄、状態良好です。', 'price' => 18000, 'brand' => 'COMME des GARÇONS', 'size' => 'S', 'category' => 'トップス', 'condition' => 2],
                    ['title' => 'Yohji Yamamoto ウールスラックス ブラック M', 'description' => 'ヨウジヤマモトのスラックス。上質なウール素材、シルエットが美しい。', 'price' => 24000, 'brand' => 'Yohji Yamamoto', 'size' => 'M', 'category' => 'ボトムス', 'condition' => 2],
                    ['title' => 'Issey Miyake PLEATS PLEASE プリーツパンツ グレー', 'description' => 'イッセイミヤケのプリーツプリーズ。シワになりにくく扱いやすいです。', 'price' => 15000, 'brand' => 'Issey Miyake', 'size' => '3', 'category' => 'ボトムス', 'condition' => 2],
                ],
                'posts' => [
                    ['body' => '日本のデザイナーズブランドを少しずつ集めています。ヨウジのスラックスはマストハブ。', 'link_product' => 1],
                    ['body' => 'コムデギャルソンのシャツを出品しました。アーカイブ好きにぜひ。', 'link_product' => 0],
                    ['body' => 'プリーツプリーズは本当に機能的でよくできてる。旅行にも重宝します。', 'link_product' => 2],
                ],
            ],
            [
                'user'     => ['name' => 'Sofia Marchetti', 'email' => 'sofia.marchetti@example.com'],
                'products' => [
                    ['title' => 'Levi\'s 501 高腰デニム ショーツ W26', 'description' => 'Pantaloncini di jeans Levi\'s 501 vintage tagliati a mano. Perfetti per l\'estate.', 'price' => 4200, 'brand' => "Levi's", 'size' => 'W26', 'category' => 'ボトムス', 'condition' => 3],
                    ['title' => 'Versace ヴィンテージ シルクスカーフ', 'description' => 'Foulard di seta Versace vintage. Stampa barocca, colori vivaci e intatti.', 'price' => 12000, 'brand' => 'Versace', 'size' => 'FREE', 'category' => 'その他', 'condition' => 2],
                    ['title' => 'Moschino 90s ロゴプリント Tシャツ', 'description' => 'T-shirt Moschino anni \'90 con logo. Pezzi così non se ne trovano più.', 'price' => 8800, 'brand' => 'Moschino', 'size' => 'S', 'category' => 'トップス', 'condition' => 3],
                ],
                'posts' => [
                    ['body' => 'Ho trovato questi Levi\'s 501 tagliati a un mercatino. Pronti per l\'estate! 🌞', 'link_product' => 0],
                    ['body' => 'Il vintage italiano è unico. Oggi ho aggiunto un foulard Versace alla mia collezione.', 'link_product' => 1],
                    ['body' => 'La moda vintage non muore mai — cambia solo forma. 💫 Moschino 90s forever.', 'link_product' => 2],
                ],
            ],
            [
                'user'     => ['name' => '中村 葵', 'email' => 'aoi.nakamura@example.com'],
                'products' => [
                    ['title' => 'Patagonia レトロX フリースジャケット M ブラック', 'description' => 'パタゴニアのレトロXフリース。ボア状の内側がふかふかで暖かい。', 'price' => 16000, 'brand' => 'Patagonia', 'size' => 'M', 'category' => 'アウター', 'condition' => 2],
                    ['title' => 'L.L.Bean ハンティング モックネック フリース', 'description' => 'エルエルビーンのアウトドアフリース。肉厚でアウターとしても使えます。', 'price' => 5800, 'brand' => 'L.L.Bean', 'size' => 'L', 'category' => 'アウター', 'condition' => 3],
                    ['title' => 'Columbia ウィンドブレーカー ネイビー×レッド S', 'description' => 'コロンビアの90sウィンドブレーカー。色の組み合わせが最高にかわいい。', 'price' => 4900, 'brand' => 'Columbia', 'size' => 'S', 'category' => 'アウター', 'condition' => 3],
                ],
                'posts' => [
                    ['body' => 'アウトドア系古着が最近マイブーム。パタゴニアのレトロXはやっぱり名作。', 'link_product' => 0],
                    ['body' => '山にも街にも似合うフリース。L.L.Beanのこのモックネックが特にお気に入り。', 'link_product' => 1],
                    ['body' => 'コロンビアのウィンドブレーカー出品しました！カラーブロック好きな方にどうぞ。', 'link_product' => 2],
                ],
            ],
            [
                'user'     => ['name' => 'Léa Dubois', 'email' => 'lea.dubois@example.com'],
                'products' => [
                    ['title' => 'A.P.C. セーラーボーダー カットソー S', 'description' => 'T-shirt marinière A.P.C. en coton, rayures bleues et blanches. État impeccable.', 'price' => 7500, 'brand' => 'A.P.C.', 'size' => 'S', 'category' => 'トップス', 'condition' => 2],
                    ['title' => 'Isabel Marant エトワール リネンシャツ ホワイト 36', 'description' => 'Chemise en lin Isabel Marant Étoile. Légère et respirante, parfaite pour l\'été.', 'price' => 13000, 'brand' => 'Isabel Marant', 'size' => '36', 'category' => 'トップス', 'condition' => 2],
                    ['title' => 'Sandro パリ テーパードトラウザー クリーム M', 'description' => 'Pantalon Sandro Paris en tissu crème. Coupe élégante, jambes fuselées.', 'price' => 10800, 'brand' => 'Sandro', 'size' => 'M', 'category' => 'ボトムス', 'condition' => 2],
                ],
                'posts' => [
                    ['body' => 'Le style vintage parisien c\'est avant tout : une marinière, un jean et des escarpins. Simple et efficace. 🥐', 'link_product' => 0],
                    ['body' => 'J\'ai enfin trouvé la chemise en lin parfaite pour cet été. Isabel Marant ne déçoit jamais.', 'link_product' => 1],
                    ['body' => 'Un beau pantalon crème pour l\'été — Sandro fait vraiment du bon travail sur les coupes. 🌿', 'link_product' => 2],
                ],
            ],
            [
                'user'     => ['name' => 'Marco Oliveira', 'email' => 'marco.oliveira@example.com'],
                'products' => [
                    ['title' => 'Dickies 874 ワークパンツ カーキ 32×30', 'description' => 'Calça de trabalho Dickies 874 em ótimo estado. Cor cáqui clássica.', 'price' => 5200, 'brand' => 'Dickies', 'size' => 'W32', 'category' => 'ボトムス', 'condition' => 3],
                    ['title' => 'Champion リバースウィーブ クルーネック スウェット グレー L', 'description' => 'Moletom Champion reverse weave, cinza, em excelente estado. Muito confortável.', 'price' => 8400, 'brand' => 'Champion', 'size' => 'L', 'category' => 'トップス', 'condition' => 2],
                    ['title' => 'Vans Old Skool ブラック×ホワイト 27.5cm', 'description' => 'Tênis Vans Old Skool preto e branco, usado poucas vezes. Solado em bom estado.', 'price' => 6800, 'brand' => 'Vans', 'size' => '27.5cm', 'category' => 'その他', 'condition' => 3],
                ],
                'posts' => [
                    ['body' => 'O streetwear clássico nunca sai de moda. Dickies + Champion é minha fórmula favorita. 🛹', 'link_product' => 0],
                    ['body' => 'Finalmente postei o moletom Champion que estava guardado. Reverse weave é intemporal!', 'link_product' => 1],
                    ['body' => 'Vans Old Skool à venda — essas foram minhas companheiras por muito tempo. 🤙', 'link_product' => 2],
                ],
            ],
        ];

        // 追加タグ
        $extraTags = collect([
            Tag::firstOrCreate(['name' => 'デザイナーズ']),
            Tag::firstOrCreate(['name' => 'アウトドア']),
            Tag::firstOrCreate(['name' => 'ストリート']),
            Tag::firstOrCreate(['name' => 'ワークウェア']),
            Tag::firstOrCreate(['name' => 'スケート']),
        ]);
        $allTags = $tags->concat($extraTags)->unique('id')->values();

        foreach ($internationalData as $data) {
            $user = User::factory()->create($data['user']);

            $userProducts = collect();
            foreach ($data['products'] as $productData) {
                $product = Product::factory()->create(array_merge($productData, [
                    'user_id' => $user->id,
                    'status'  => 1,
                ]));
                $product->tags()->attach($allTags->shuffle()->take(rand(1, 3))->pluck('id'));
                $userProducts->push($product);
            }

            foreach ($data['posts'] as $postData) {
                $linkedProduct = null;
                if (isset($postData['link_product']) && $postData['link_product'] !== null) {
                    $linkedProduct = $userProducts->get($postData['link_product']);
                }
                $post = Post::factory()->create([
                    'user_id'    => $user->id,
                    'product_id' => $linkedProduct?->id,
                    'body'       => $postData['body'],
                ]);
                $post->tags()->attach($allTags->shuffle()->take(rand(1, 2))->pluck('id'));
            }
        }
    }
}
