<?php 
namespace src\Database\DataAccess;

use src\Database\DataAccess\Interfaces\UserDAO;
use src\Database\DataAccess\Implemetations\UserDAOImpl;
use src\Database\DataAccess\Interfaces\PostDAO;
use src\Database\DataAccess\Implemetations\PostDAOImpl;

class DAOFactory {

    public static function getUserDAO(): UserDAO{
        return new UserDAOImpl();
    }

    public static function getPostDAO(): PostDAO{
        return new PostDAOImpl();
    }
}