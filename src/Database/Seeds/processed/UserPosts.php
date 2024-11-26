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
        $faker =  Faker::create();
        $userIds = $this->getUsersIds(); 

        $data = [];

        foreach($userIds as $row) {
            error_log(print_r(gettype($row['id'])));
            if(isset($row['id'])){
                $data[] = [
                    $row['id'],
                    $faker->text(140)
                ];
            }
        }

        error_log(print_r($data, true));
        return $data;
    }

    private function getUsersIds(): array {
        $mysqli = new MySQLWrapper();

        $query = 'SELECT id FROM users order by id ASC limit 10';
        $result = $mysqli->query($query);

        $data = $result->fetch_all(MYSQLI_ASSOC);

        $userIds = array_map(function ($row) {
            $row['id'] = intval($row['id']);
            return $row;
        }, $data);

        return $userIds;
    }
}
