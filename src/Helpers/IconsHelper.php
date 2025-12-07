<?php
namespace src\Helpers;


class IconsHelper {

    static function getIcon(string $name): string {
        $icon = include(__DIR__ .'/../Config/icons.php');
        return $icon[$name] ?? 'not found';
    }

}