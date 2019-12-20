<?php

namespace Differ\GenDiff;

use function Differ\Parser\getDataParser;

function openFile($path)
{
    if (!file_exists($path)) {
        throw new \Exception("File {$path} does not exists\n");
    }
    return file_get_contents($path);
}

function genDiff($path1, $path2)
{
    $dataFormat = pathinfo($path1, PATHINFO_EXTENSION);

    try {
        $content1 = openFile($path1);
        $content2 = openFile($path2);
    } catch (\Exception $e) {
        echo $e->getMessage();
        return;
    }

    try {
        $parse = getDataParser($dataFormat);
    } catch (\Exception $e) {
        echo $e->getMessage();
        return;
    }

    $configData1 = $parse($content1);
    $configData2 = $parse($content2);
    return getDifference($configData1, $configData2);
}

function getDifference($configData1, $configData2)
{
    $merged = array_merge($configData1, $configData2);
    $result = [];
    foreach ($merged as $key => $value) {
        if (array_key_exists($key, $configData2) && array_key_exists($key, $configData1)) {
            if ($value == $configData1[$key]) {
                $result["  {$key}"] = $value;
            } else {
                $result["+ {$key}"] = $value;
                $result["- {$key}"] = $configData1[$key];
            }
        } elseif (array_key_exists($key, $configData1) && !array_key_exists($key, $configData2)) {
            $result["- {$key}"] = $value;
        } else {
            $result["+ {$key}"] = $value;
        }
    }
    $string = '';
    foreach ($result as $key => $value) {
        $string .= " {$key} => {$value}\n";
    }
    return "{\n{$string}}\n";
}
