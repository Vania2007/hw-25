<?php

function rscandir($base = '', &$data = array())
{
    $array = array_diff(scandir($base), array('.', '..'));
    foreach ($array as $value) {
        if (is_dir($base . $value)) {
            $data[] = $base . $value . '/';
            $data = rscandir($base . $value . '/', $data);
        } elseif (is_file($base . $value)) {
            $data[] = $base . $value;
        }
    }
    return $data;
}

$paths = rscandir("./site/");
header('Content-Type: text/html; charset=utf-8');
date_default_timezone_set("Europe/Kiev");
$archive_dir = "backups/";
$backupName = $archive_dir . date('d.m.Y_H-i-s') . ".zip";
$zip = new ZipArchive;
if ($zip->open($backupName, ZipArchive::CREATE) === true) {
    foreach ($paths as $key => $value) {
        if (!is_dir($value)) {
            echo "Додано в архів файл: " . $value . " розмір: " . filesize($value) . " байт\n";
            $zip->addFile($value, str_replace('./site/', '', $value));
        }
    }
    $zip->close();
    echo 'Файли додані в архів: ' . $backupName;
} else {
    echo "Помилка! Архів не відкрився :(";
}
