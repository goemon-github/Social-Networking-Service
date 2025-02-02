<?php

namespace src\Database;

use src\Helpers\Settings;

class DatabaseManager {
    protected static array $mysqliConnections = [];

    public static function getMysqliConnection(string $connectionName = 'dafault'): MySQLWrapper {
        if(!isset(static::$mysqliConnections[$connectionName])){
            static::$mysqliConnections[$connectionName] = new MySQLWrapper();
        }
        return static::$mysqliConnections[$connectionName];
    }
}