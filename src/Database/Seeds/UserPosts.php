<?php

namespace src\Database\Seeds;

require_once __DIR__ . '/../../../vendor/autoload.php';

use src\Database\MySQLWrapper;
use src\Database\AbstractSeeder;
use Faker\Factory as Faker;

class UserPosts extends AbstractSeeder {

    protected ?string $tableName = 'posts';
    protected array $tableColumns = [
        [
            "data_type" => 'int',
            'column_name' => 'user_id'
        ],
        [
            "data_type" => 'string',
            'column_name' => 'content'
        ],
    ];

    public function createRowData(): array{
        #$faker = Faker::create('ja_JP');
        $userIds = $this->getUsersIds(); 

        $data = [];

        foreach($userIds as $row) {
            if(!isset($row['id'])){
                continue;
            }

            $postCount = random_int(1, 3);
            for($i = 0; $i < $postCount; $i++){
                $data[] = [
                    $row['id'],
                    #$faker->text(140),
                    $this->generateJapanesePostContent(),
                ];
            }
        }
        return $data;
    }

    private function getUsersIds(): array {
        $mysqli = new MySQLWrapper();

        #$query = 'SELECT id FROM users order by id ASC limit 10';
        $query = 'SELECT id FROM users order by id ASC';
        $result = $mysqli->query($query);

        $data = $result->fetch_all(MYSQLI_ASSOC);

        $userIds = array_map(function ($row) {
            $row['id'] = intval($row['id']);
            return $row;
        }, $data);

        return $userIds;
    }

    public function generateJapanesePostContent(): string {
        $subjects = ['コーヒー', 'ラーメン', '散歩', '映画', '読書', 'PHP', 'SQL', '開発', '音楽', 'カレー'];
        $places = ['渋谷', '新宿', '近所のカフェ', '公園', '家', '図書館'];
        $feelings = ['落ち着いた', '楽しかった', '集中できた', 'また行きたい', 'ちょっと疲れた'];


        $posts = [
            '今日は' . $subjects[array_rand($subjects)] . 'を楽しんだ。',
            $places[array_rand($places)] . 'でのんびりしていた。' . $feelings[array_rand($feelings)] . '。',
            '最近' . $subjects[array_rand($subjects)] . 'にハマっている。',
            $subjects[array_rand($subjects)] . 'をしていたら時間があっという間だった。',
            $subjects[array_rand($subjects)] . 'のことを考えている。',
        ];

        return $posts[array_rand($posts)];
    }
}
