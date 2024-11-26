<?php

namespace src\Database\Seeds;

require_once __DIR__ . '/../../../vendor/autoload.php';

use src\Database\AbstractSeeder;
use Faker\Factory as Faker;


class UserSeeder extends AbstractSeeder {
    protected ?string $tableName = "users";
    protected array  $tableColumns = [
        [
            'data_type' => 'string',
            'column_name' => 'user_name'
        ],
        [
            'data_type' => 'string',
            'column_name' => 'password'
        ],
        [
            'data_type' => 'string',
            'column_name' => 'email'
        ]
    ];

    public function createRowData(): array{
        $faker = Faker::create();
        $users = [];
        for($i = 0; $i < 10; $i++){
            $users[] = [

                # user_name
                $faker->name(),
                # pass
                password_hash($faker->password(), PASSWORD_DEFAULT),
                # email
                $faker->email(),
            ];
        }

        return $users;    
    }
}