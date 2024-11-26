<?php
namespace src\Hepers;

use src\Database\MySQLWrapper;
use Exception;

class DatabaseHeler {
    public static function getTimelinePosts(): array {
       $mysqli = new MySQLWrapper();

        $query = 
        'SELECT 
        posts.user_id, 
        posts.content,
        posts.created_at, 
        users.user_name 
        FROM posts 
        JOIN users ON   users.id = posts.user_id
        ORDER BY posts.created_at DESC ';

        $result = $mysqli->query($query);
        $posts =  $result->fetch_all(MYSQLI_ASSOC);

        return $posts;
    }

}