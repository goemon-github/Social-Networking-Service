<?php
namespace src\Helpers;

use src\Models\Post;
use src\Database\DataAccess\DAOFactory;

class PostHelper {

    static public function getCommentCount(int $post_id): int {
        $postDAO = DAOFactory::getPostDAO();
        $commentCount = $postDAO->getCommentCount($post_id);
        return $commentCount;
    }
}