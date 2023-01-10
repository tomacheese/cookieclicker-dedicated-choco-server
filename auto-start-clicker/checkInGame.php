<?php
$steam_username = "book000";

function isRunningCookieClicker()
{
    exec("tasklist | find \"Cookie Clicker.exe\" /c", $output, $ret);
    return trim($output[count($output)-1]) != "0";
}

date_default_timezone_set("Asia/Tokyo");
if (isRunningCookieClicker()) {
    echo date("Y-m-d H:i:s ") . "Cookie Clicker is running\n";
    if (file_exists(__DIR__ . "/nextStartGame")) {
        unlink(__DIR__ . "/nextStartGame");
    }
    exit;
}

$xml = file_get_contents("https://steamcommunity.com/id/$steam_username?xml=1&" . date("YmdHis"));
preg_match("/<onlineState>(.+)<\/onlineState>/", $xml, $matches);
if ($matches[1] == "in-game") {
    echo date("Y-m-d H:i:s ") . "In game\n";
    if (file_exists(__DIR__ . "/nextStartGame")) {
        unlink(__DIR__ . "/nextStartGame");
    }
    exit;
}
echo date("Y-m-d H:i:s ") . "Not in game\n";

if (!file_exists(__DIR__ . "/nextStartGame")) {
    touch(__DIR__ . "/nextStartGame");
    echo date("Y-m-d H:i:s ") . "Next to Start Game";
    exit;
}

echo date("Y-m-d H:i:s ") . "Game start...\n";
exec("start \"\" steam://rungameid/1454400", $output, $ret);
if ($ret !== 0) {
    echo date("Y-m-d H:i:s ") . "start command failed\n";
    exit;
}

$count = 0;
while (true) {
    if (isRunningCookieClicker()) {
        break; // Cookie Clicker is running
    }
    echo "Cookie Clicker is not running...\n";
    sleep(1);
    $count++;
    if ($count >= 10) {
        echo date("Y-m-d H:i:s ") . "start failed\n";
        system("taskkill /IM steam.exe /F /T", $output);
        exit;
    }
}

sleep(3);

// system(__DIR__ . "/minimalize-CookieClicker.vbs");

echo date("Y-m-d H:i:s ") . "started\n";
unlink(__DIR__ . "/nextStartGame");