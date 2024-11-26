<?php


require_once __DIR__ . '/../../vendor/autoload.php';

use src\Database\MySQLWrapper;
use Faker\Factory as Faker;

$faker = Faker::create();
$sqli = new MySQLWrapper();
$result = $sqli->query('SELECT id  FROM users');


if($result) {
    $usersIds = $result->fetch_all(MYSQLI_ASSOC);
}

$stmt = $sqli->prepare("UPDATE users SET profile = ? WHERE id =  ?");

foreach($usersIds as $user){
    $id = (int) $user['id'];
    $profileText = $faker->realText(140);
    $stmt->bind_param('si', $profileText, $id);
    $stmt->execute();
}

$stmt->close();
$sqli->close();