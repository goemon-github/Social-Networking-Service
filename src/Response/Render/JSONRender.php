<?php

namespace src\Response\Render;

use src\Response\HTTPRender;

class JSONRender implements HTTPRender {
    private array $data;

    public function __construct(array $data){
        $this->data = $data;
    }

    public function getFields(): array{
        return [
            'Content-Type' => "application/json; charset=utf-8",
        ];    
    }

    public function getContet(): string{
       return json_encode($this->data, JSON_THROW_ON_ERROR);
    }
}