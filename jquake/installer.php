<?php
$json = file_get_contents("https://jquake.net/data/versions.json");
$data = json_decode($json, true);
$latest_version = $data["latest"];

$url = $data["history"][$latest_version]["builds"]["windows"][0]["download"];
$filename = basename($url);
file_put_contents(__DIR__ . "/" . $filename, file_get_contents($url));

if (!file_exists(__DIR__ . "/app/")) {
    mkdir(__DIR__ . "/app/");
}

$zip = new ZipArchive;
$res = $zip->open(__DIR__ . "/" . $filename);
if ($res === true) {
    $zip->extractTo("app/");
    $zip->close();
} else {
    echo "failed to open zip file\n";
    exit(1);
}

file_put_contents("version", $latest_version);

echo "install done\n";