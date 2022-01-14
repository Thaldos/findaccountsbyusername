<?php

use Thaldos\FindAccountsByUsername\UrlsService;

require __DIR__ . '/../vendor/autoload.php';

$urlsService = new UrlsService();
header('Content-Type: application/json; charset=utf-8');
echo json_encode($urlsService->getUrls());
