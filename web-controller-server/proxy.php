<?php


$metrics = file_get_contents("php://input");
file_put_contents("metrics.json", $metrics);

$config = json_decode(file_get_contents(__DIR__ . "/config.json"), true);

$response = file_get_contents("https://api.mackerelio.com/api/v0/services/CookieClicker/tsdb", false, stream_context_create([
    "http" => [
        "method" => "POST",
        "header" => "Content-Type: application/json\r\n" .
            "X-Api-Key: " . $config["mackerel-api-key"] . "\r\n",
        "content" => $metrics,
        "ignore_errors" => true
    ],
]));
preg_match('/^HTTP\/1\.[01] (\d{3}) (.*)$/', $http_response_header[0], $matches);
$status_code = $matches[1];
$status_message = $matches[2];

if ($status_code == 200) {
    file_put_contents(__DIR__ . "/proxy.log", "OK\n", FILE_APPEND);
    echo "OK";
} else {
    file_put_contents(__DIR__ . "/proxy.log", "NG\n", FILE_APPEND);
    file_put_contents(__DIR__ . "/proxy.log", $response, FILE_APPEND);
    file_put_contents(__DIR__ . "/proxy.log", print_r($http_response_header, true), FILE_APPEND);
    http_response_code(500);
    echo "NG";
}

