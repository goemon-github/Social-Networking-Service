<?php

namespace src\Commands\Programs;

use src\Commands\AbstractCommand;
use src\Commands\Argument;
use src\Database\MySQLWrapper;
use src\Database\Seeder;

class Seed extends AbstractCommand {

    protected static ?string $alias = 'seed';

    public static function getArguments(): array{
       return [] ;
    }

    public function execute(): int {
        #$argument = $this->getArgumentValue('update');

        $this->runAllSeeds();
        return 0;
    }

    public function runAllSeeds(): void {
        $directoryPath = __DIR__ . '/../../Database/Seeds';

        // ディレクトリをスキャンして全てのファイルを取得します
        $files = scandir($directoryPath);
        
        foreach($files as $file){
            if (pathinfo($file, PATHINFO_EXTENSION) === 'php') {
                // ファイル名からクラス名を抽出します
                $className = 'src\Database\Seeds\\' . pathinfo($file, PATHINFO_FILENAME);
                
                // シードファイルをインクルードします
                include_once $directoryPath . "/" . $file;

                if(class_exists($className) && is_subclass_of($className, Seeder::class)) {
                    $seeder = new $className(new MySQLWrapper());
                    $seeder->seed();
                }else {
                    throw new \Exception('Seeder must be a class that subclasses the seeder interface');

                }
            }
        }
    }

    public function updateSeeds(): void {

    }

}
