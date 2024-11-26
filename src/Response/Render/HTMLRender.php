<?php

namespace src\Response\Render;

use Exception;
use src\Response\HTTPRender;

class HTMLRender implements HTTPRender {

    private string $viewFile;
    private array $data;
    private array $files;

    public function __construct(string $viewFile, array $data = []){
        $this->viewFile = $viewFile;
        $this->data = $data; 
        $this->files = ['home','profile', 'notification', 'like', 'follow'];
    }


    public function getFields(): array{
        return [
            'Content-Type' => 'text/html; charset=utf-8',
        ];
    }

    public function getContet(): string{
       $viewPath = $this->getViewPath($this->viewFile);
        if(!file_exists($viewPath)){
            throw new \Exception("View file {$viewPath} does not exist.");
        }

        /* 
        if(!isset($this->data) && in_array(basename($this->viewFile), $this->files)){
            $this->data = ['path'=> basename($this->viewFile)];
        } else {
            throw new \Exception("View file {$this->viewFile} does not exist.");
        }
            */

        ob_start();

        extract($this->data);
        require $viewPath;

        return $this->getHeader() . ob_get_clean() . $this->getFooter();
    }

    private function getViewPath(string $path): string{
        return sprintf("%s/%s/Views/%s.php", __DIR__, '../../', $path);
    }

    private function getHeader(): string{
        ob_start();
        require $this->getViewPath('layout/header');
        return ob_get_clean();
    }

    private function getFooter(): string{
        ob_start();
        require $this->getViewPath('layout/footer');
        return ob_get_clean();
    }


}