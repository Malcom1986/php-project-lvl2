<?php

namespace Differ\genDiff;

use Exception;

function openFile($path)
{
    if (!file_exists($path)) {
        throw new \Exception("File {$path} does not exists\n");
    }
    return file_get_contents($path);
}

function genDiff($path1, $path2)
{
    try {
        $content1 = openFile($path1);
        $content2 = openFile($path2);
    } catch (\Exception $e) {
        echo $e -> getMessage();
        return;
    }
    echo getDifference($content1, $content2);
    return;
}

function getDifference($a, $b)
{
    $configData1 = json_decode($a, true);
    $configData2 = json_decode($b, true);
    $merged = array_merge($configData1, $configData2);
    $result = [];
    foreach ($merged as $key => $value) {
        if (array_key_exists($key, $configData2) && array_key_exists($key, $configData1)) {
            if ($value == $configData1[$key]) {
                $result["  {$key}"] = $value;
            } else {
                $result["+ {$key}"] = $value;
                $result["- {$key}"] = $configData2[$key];
            }
        } elseif (array_key_exists($key, $configData1) && !array_key_exists($key, $configData2)) {
            $result["- {$key}"] = $value;
        } else {
            $result["+ {$key}"] = $value;
        }
    }
    $string = '';
    foreach ($result as $key => $value) {
        $string .= "{$key} => {$value}\n";
    }
    return $string;
}
