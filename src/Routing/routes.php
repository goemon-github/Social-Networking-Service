<?php
namespace src\Routing;

use src\Helpers\DatabaseHeler;
use src\Helpers\ValidationHelper;
use src\Response\HTTPRender;
use src\Response\Render\HTMLRender;
use src\Response\Render\JSONRender;


return  [

    // login
    'login' => function(): HTTPRender{
        return  new HTMLRender('page/login');
    },
    'modallogin' => function(): HTTPRender{
        return  new HTMLRender('component/modalLogin');
    },
    // home
    'home' => function(): HTTPRender{
        return  new HTMLRender('page/home');
    }
];
// routing
// logout
// profile/userID
// notifications
// messages
// messages/messageID
