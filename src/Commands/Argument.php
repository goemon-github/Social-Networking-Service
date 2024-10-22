<?php

namespace Commands;

class Argument {
    private string $argument;
    private string $description = '';
    private string $required =  true;
    private string $allowAsShort = false;

    public function __construct(string $argument){
       $this->argument = $argument; 
    }

    public function getArgument(): string {
        return $this-> argument;
    }

    public function getDescription(): string {
        return $this->description; 
    }

    public function description(string $description): self {
        $this->description = $description;
        return $this;
    }

    public function isRequired(): bool {
        return $this-> required;
    }

    public function required(bool $required): self {
        $this->required = $required;
        return $this;
    }

    public function isShortAllowed(): bool {
        return $this->allowAsShort;
    }

    public function allowAsShort(bool $allowAsShort): self {
        $this->allowAsShort = $allowAsShort;
        return $this;
    }
}