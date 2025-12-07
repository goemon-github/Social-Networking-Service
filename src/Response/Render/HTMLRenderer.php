<?php

namespace src\Response\Render;

use Exception;
use src\Helpers\Authenticate;
use src\Response\HTTPRenderer;

class HTMLRenderer implements HTTPRenderer{

    private string $viewFile;
    private array $data;
    private array $files;

    public function __construct(string $viewFile, array $data = []){
        $this->viewFile = $viewFile;
        $this->data = $data; 
        // 
        $this->files = ['home','profile', 'notification', 'like', 'follow'];
    }


    public function getFields(): array{
        return [
            'Content-Type' => 'text/html; charset=UTF-8',
        ];
    }

    public function getContent(): string{
       $viewPath = $this->getViewPath($this->viewFile);
        if(!file_exists($viewPath)){
            throw new \Exception("View file {$viewPath} does not exist.");
        }


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
        $user = Authenticate::getAuthenticatedUser();
        require $this->getViewPath('layout/header');
        require $this->getViewPath('component/message-boxes');
        return ob_get_clean();
    }

    private function getFooter(): string{
        ob_start();
        require $this->getViewPath('layout/footer');
        return ob_get_clean();
    }

}