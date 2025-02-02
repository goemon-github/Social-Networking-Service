<?php

namespace src\Response;

interface HTTPRenderer {
    public function getFields(): array;
    public function getContent(): string;
}