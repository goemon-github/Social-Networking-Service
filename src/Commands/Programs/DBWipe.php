<?php

namespace Commands\Programs;

use Commands\AbstractCommand;
use Commands\Argument;

use Database\MySQLWrapper;
use Exception;
use Helpers\Settings;

class DBWipe extends AbstractCommand {
    protected static ?string $alias = 'dbwipe';

    public static function getArguments(): array
    {
        return [
            (new Argument(('backup')))->description('backup vefore wiping database')->required(false)->allowAsShort(true),
        ];
    }


    public function execute(): int {
        $backup = $this->getArgumentValue('backup');

        if($backup !== false){
            $this->log('staring backup .......');
            $this->createBackup();
            $this->log('backup success......');
        }

        $this->log('Wiping the database.........');
        $this->wipeDatabase();
        $this->log('Database wiped successfully');

        return 0;
    }

    private function createBackup(string $backup): void{
        $username = $username??Settings::env('DATABASE_USER');
        $password = $password??Settings::env('DATABASE_USER_PASSWORD');
        $dbname = $dbname??Settings::env('DATABASE_NAME');
        $backupFile = $backup ? $backup : 'backup.sql';

        $command = "mysqldump -u $username -p$password $dbname > $backupFile";
        system($command, $result);

        if($result != 0) {
            throw new Exception('Failed to create backup');
        }
    }

    private function wipeDatabase(): void{
        $db = new MySQLWrapper();
        $dbname = $db->getDatabaseName();

        // データベースを削除して再作成
        $db->query("DROP DATABSE $dbname");
        $db->query("CREATE DATABASE $dbname");
    }
}