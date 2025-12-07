<?php
namespace src\Models;

use src\Models\Interfaces\Model;
use src\Models\Traits\GenericModel;
use src\Models\DateTimeStamp;

class Like implements Model {
    use GenericModel;
    /** 
     * id
     * user_id
     * post_id
    */
    public function __construct(
        private int $id,
        private int $user_id,
        private int $post_id,
    ){}
        


}