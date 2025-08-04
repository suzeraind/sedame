<?php

namespace App\Core\Contracts;

interface IMiddleware
{
    public function handle(): bool;
}
