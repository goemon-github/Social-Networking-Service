<?php

namespace src\Database\Seeds;

require_once __DIR__ . '/../../../vendor/autoload.php';

use src\Database\AbstractSeeder;
use Faker\Factory as Faker;
use src\Database\MySQLWrapper;

class AddProfileUserSeeder extends AbstractSeeder {
    protected ?string $tableName = "users";
    protected array  $tableColumns = [
        [
            'data_type' => 'string',
            'column_name' => 'profile'
        ],
        [
            // where用
            'data_type' => 'int',
            'column_name' => 'id'
        ],
    ];

    public function createRowData(): array{
        $faker = Faker::create();
        $sqli = new MySQLWrapper();
        $result = $sqli->query('SELECT COUNT(id) AS count FROM users');

        if($result) {
            $row = $result->fetch_assoc();
            $count = $row['count'];
        }

        $data = [];
        for($i = 0; $i < $count; $i++){
            $data[] = [
                # profile 
                $faker->realText(140),
                #id
                $i
            ];
        }
        return $data;    
    }


}