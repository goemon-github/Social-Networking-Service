<?php

namespace src\Response;

interface HTTPRender {
    public function getFields(): array;
    public function getContet(): string;
}