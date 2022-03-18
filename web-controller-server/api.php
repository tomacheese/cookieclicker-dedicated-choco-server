<?php
// ?action=status
// ?action=start
// ?action=stop
error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', 1);
header('Content-Type: application/json');

if (!isset($_GET["action"])) {
    http_response_code(400);
    exit(json_encode([
        "status" => false,
        "message" => "No action specified"
    ]));
}

$action = $_GET["action"];

if ($action == "status") {
    exec("powershell " . __DIR__ . "\\screenshot.ps1 " . __DIR__ . "\\screenshot.png", $output, $ret);
    if ($ret !== 0) {
        http_response_code(500);
        exit(json_encode([
            "status" => false,
            "message" => "screenshot command failed",
            "output" => $output
        ]));
    }

    exec("tasklist | find \"Cookie Clicker.exe\" /c", $output, $ret);
    if (trim($output[count($output)-1]) === "0") {
        exit(json_encode([
            "status" => true,
            "running" => false,
            "message" => "Cookie Clicker is not running",
            "output" => $output
        ]));
    }

    $metrics = [];
    if (file_exists(__DIR__ . "/metrics.json")) {
        $metrics = json_decode(file_get_contents(__DIR__ . "/metrics.json"), true);
    }

    exit(json_encode([
        "status" => true,
        "running" => true,
        "message" => "Cookie Clicker is running",
        "metrics" => $metrics
    ]));
}
if ($action == "start") {
    exec("start \"\" steam://rungameid/1454400", $output, $ret);
    if ($ret !== 0) {
        http_response_code(500);
        exit(json_encode([
            "status" => false,
            "message" => "start command failed"
        ]));
    }
    http_response_code(200);
    exit(json_encode([
        "status" => true,
        "message" => "Cookie Clicker started"
    ]));
}
if ($action == "stop") {
    exec("taskkill /IM \"Cookie Clicker.exe\"", $output, $ret);
    if ($ret !== 0) {
        http_response_code(500);
        exit(json_encode([
            "status" => false,
            "message" => "taskkill command failed"
        ]));
    }
    http_response_code(200);
    exit(json_encode([
        "status" => true,
        "message" => "Cookie Clicker stopped"
    ]));
}
if ($action == "pc-restart") {
    exec("shutdown /r /t 15", $output, $ret);
    if ($ret !== 0) {
        http_response_code(500);
        exit(json_encode([
            "status" => false,
            "message" => "shutdown restart command failed"
        ]));
    }
    http_response_code(200);
    exit(json_encode([
        "status" => true,
        "message" => "Executed the shutdown command. The computer will restart after 15 seconds."
    ]));
}

http_response_code(400);
exit(json_encode([
    "status" => false,
    "message" => "Unknown action"
]));