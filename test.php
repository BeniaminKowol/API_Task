<?php
header('Content-Type: application/json; charset=utf-8');

require __DIR__ . '/Classes/Autoloader.php';
Autoloader::register();

echo (new Endpoint(@$_GET['cn'] ?: ''))->respond();
