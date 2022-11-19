<?php
error_reporting(-1);
ini_set("display_errors", 1);
chdir(__DIR__);

function downloadFile($url, $path)
{
    $ch = curl_init($url);
    $fp = fopen($path, 'wb');
    curl_setopt($ch, CURLOPT_FILE, $fp);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_exec($ch);
    curl_close($ch);
    fclose($fp);
}

$json = file_get_contents("https://jquake.net/data/versions.json");
$data = json_decode($json, true);
$latest_version = $data["latest"];

if (file_exists("version") && file_get_contents("version") == $latest_version) {
    echo "You are already using the latest version of JQuake ($latest_version).\n";
    exit;
}

exec("tasklist | find \"JQuake.exe\" /c", $output, $ret);
if (trim($output[count($output)-1]) != "0") {
    system("taskkill /IM JQuake.exe /F");
}

echo "Downloading JQuake $latest_version...\n";
$bit = (PHP_INT_SIZE == 4) ? "32bit" : "64bit";
$bit64Index = null;
foreach ($data["history"][$latest_version]["builds"]["windows"] as $i => $build) {
    if ($build["arch"] == $bit) {
        $bit64Index = $i;
        break;
    }
}
if ($bit64Index === null) {
    echo "Could not find a $bit build for JQuake $latest_version.\n";
    exit;
}
$url = $data["history"][$latest_version]["builds"]["windows"][$bit64Index]["download"];
$filename = basename($url);
downloadFile($url, __DIR__ . "/" . $filename);
// file_put_contents(__DIR__ . "/" . $filename, file_get_contents($url));

if (!file_exists(__DIR__ . "/app/")) {
    mkdir(__DIR__ . "/app/");
}

$zip = new ZipArchive();
$res = $zip->open(__DIR__ . "/" . $filename);
if ($res === true) {
    $zip->extractTo("app/");
    $zip->close();
} else {
    echo "failed to open zip file\n";
    exit(1);
}

file_put_contents("version", $latest_version);

echo "Creating startup shortcut\n";
system("create-shortcut.vbs");

echo "jQuake $latest_version install done\n";
