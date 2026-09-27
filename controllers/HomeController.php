<?php

namespace Controller;

class HomeController
{
    public function index(): void
    {
        include __DIR__ . '/../views/home.php';
    }
}