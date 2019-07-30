<?php
$directory = "../files/protected/webapps/";
$default = "../files/default.zip";
if (isset($_POST["create"])) {
    // We need to create the app
    $sources = get("app-sources");
    $appName = get("app-name");
    $appDesc = get("app-desc");
    $appColor = get("app-color");
    $appIcon = get("app-icon");
    $appLayout = get("app-layout");
    $id = id();
    mkdir($directory . $id);
    if (unzipSources($id, $sources)) {
        createApp($id, $appName, $appDesc, $appColor, $appIcon, $appLayout);
        echo zipToURL($id);
    } else {
        echo "Failed Unpacking.";
    }
} else {
    echo "No Parameters";
}

function unzipSources($id, $sources)
{
    global $directory, $default;
    $zipFile = ($sources === "" || !(startsWith($sources, "http") && endsWith($sources, ".zip"))) ? $default : download($id, $sources);
    $extPath = $directory . $id . "/webapp";
    mkdir($extPath);
//    $zip = new ZipArchive;
//    $res = $zip->open($zipFile);
//    if ($res === true) {
//
//        $zip->extractTo($extPath);
//        $zip->close();
//        return true;
//    } else {
//        return false;
//    }
    shell_exec("unzip ".$zipFile." -d ".$extPath);
    return true;
}

function createApp($id, $name, $desc, $color, $icon, $layout)
{
    global $directory;
    $appDir = $directory . $id . "/webapp/";
    // Index Replacements
    replace($appDir . "index.html", "AppName", $name);
    replace($appDir . "index.html", "AppDescription", $desc);
    replace($appDir . "index.html", "#FFFFFF", $color);
    replace($appDir . "index.html", "images/icon.png", $icon);
    replace($appDir . "index.html", "images/icon_apple.png", $icon);
    replace($appDir . "index.html", "<!--Your App Body-->", $layout);
    // Offline Replacements
    replace($appDir . "resources/offline.html", "AppName", $name);
    replace($appDir . "resources/offline.html", "AppDescription", $desc);
    replace($appDir . "resources/offline.html", "#FFFFFF", $color);
    // CSS Replacements
    replace($appDir . "css/app.css", "#FFFFFF", $color);
    // Manifest
    replace($appDir . "resources/manifest.json", "AppName", $name);
    replace($appDir . "resources/manifest.json", "AppDescription", $desc);
    replace($appDir . "resources/manifest.json", "#FFFFFF", $color);
    replace($appDir . "resources/manifest.json", "../images/icon.png", $icon);
}

function zipToURL($id)
{
    global $directory;
    $urlHeader = "data:application/zip;base64,";
    $appDir = $directory . $id . "/webapp/";
    $zipFile = $directory . $id . "/webapp.zip";
    $rootPath = realpath($appDir);
    $zip = new ZipArchive();
    $zip->open($zipFile, ZipArchive::CREATE | ZipArchive::OVERWRITE);

    /** @var SplFileInfo[] $files */
    $files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($rootPath),
        RecursiveIteratorIterator::LEAVES_ONLY
    );

    foreach ($files as $name => $file) {
        if (!$file->isDir()) {
            $filePath = $file->getRealPath();
            $relativePath = substr($filePath, strlen($rootPath) + 1);
            $zip->addFile($filePath, $relativePath);
        }
    }
    $zip->close();
    return $urlHeader . base64_encode(file_get_contents($zipFile));
}

function replace($file, $toReplace, $replacement)
{
    if ($toReplace !== $replacement) {
        $read = file_get_contents($file);
        $read = str_replace($toReplace, $replacement, $read);
        file_put_contents($file, $read);
    }
}

function download($id, $sources)
{
    global $directory;
    $path = $directory . $id . "/template.zip";
    file_put_contents($path, fopen($sources, 'r'));
    return $path;
}

function startsWith($string, $startString)
{
    $len = strlen($startString);
    return (substr($string, 0, $len) === $startString);
}

function endsWith($string, $endString)
{
    $len = strlen($endString);
    if ($len == 0) {
        return true;
    }
    return (substr($string, -$len) === $endString);
}

function id()
{
    global $directory;
    $random = rand(1000000, 10000000);
    if (file_exists($directory . $random)) return id();
    return $random;
}

function get($n)
{
    if (isset($_POST[$n])) {
        return $_POST[$n];
    }
    return "";
}