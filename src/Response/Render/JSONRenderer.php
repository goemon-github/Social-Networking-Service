<?php

namespace src\Response\Render;

use src\Response\HTTPRenderer;

class JSONRenderer implements HTTPRenderer {
    private array $data;

    public function __construct(array $data){
        $this->data = $data;
    }

    public function getFields(): array{
        return [
            'Content-Type' => "application/json; charset=utf-8",
        ];    
    }

    public function getContent(): string{
       return json_encode($this->data, JSON_THROW_ON_ERROR);
    }
}