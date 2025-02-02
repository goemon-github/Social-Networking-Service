<?php
namespace src\Helpers;

class CrossSiteForgeryProtection{
    public static function getToken(){
        return $_SESSION['csrf_token'];
    }
}