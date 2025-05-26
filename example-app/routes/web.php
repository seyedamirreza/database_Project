<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $pdo = new PDO("mysql:host=localhost;dbname=example_app", "root", "");
    $data = $pdo->query("SELECT * FROM users")
        ->fetchAll();
dd($data);
});
