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
            'column_name' => 'account_name'
        ],
        [
            'data_type' => 'string',
            'column_name' => 'display_name'
        ],
        [
            'data_type' => 'string',
            'column_name' => 'password'
        ],
        [
            'data_type' => 'string',
            'column_name' => 'email'
        ],
        [
            'data_type' => 'string',
            'column_name' => 'image_url'
        ]
    ];

    public function createRowData(): array{
        $faker = Faker::create('ja_JP');
        $faker->addProvider(new \Smknstd\FakerPicsumImages\FakerPicsumImagesProvider($faker));
        $addUserCount = 1;
        $users = [];
        for($i = 0; $i < $addUserCount; $i++){
            $users[] = [

                # account_name
                $faker->username(),
                # display_name
                $faker->name(),
                # pass
                password_hash($faker->password(), PASSWORD_DEFAULT),
                # email
                #$faker->email(),
                #sprintf('user%02d@example.com', $i + 1),
                $faker->unique()->safeEmail(),
                # image_url
                $faker->imageUrl(200, 200)
            ];
        }
        $users[] = array_values($this->guestUserData());

        return $users;    
    }

    public function guestUserData(): array {
        return [
            'account_name' => 'guest',
            'display_name' => 'ゲストユーザー',
            'password' => password_hash('guest_pass', PASSWORD_DEFAULT),
            'email' => 'guest@example.com',
            'image_url' => 'https://picsum.photos/200'
        ];
    }
}
