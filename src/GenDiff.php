<?php

namespace Differ\GenDiff;

use function Differ\Parser\getDataParser;
use function Differ\Formatters\Format\formatOutput;

function openFile($path)
{
    if (!file_exists($path)) {
        throw new \Exception("File {$path} does not exists\n");
    }
    return file_get_contents($path);
}

function genDiff($path1, $path2, $format)
{
    $dataFormat = pathinfo($path1, PATHINFO_EXTENSION);
    $content1 = openFile($path1);
    $content2 = openFile($path2);
    $parseContent = getDataParser($dataFormat);
    $configData1 = $parseContent($content1);
    $configData2 = $parseContent($content2);
    $difference = getDifference((array) $configData1, (array) $configData2);
    return formatOutput($format, $difference);
}

function getDifference($data1, $data2)
{
    $keys = union(array_keys($data1), array_keys($data2));
    $result = [];
    foreach ($keys as $key) {
        if (!array_key_exists($key, $data1)) {
            $result[$key] = ['state' => 'added', 'value' => $data2[$key]];
        } elseif (!array_key_exists($key, $data2)) {
            $result[$key] = ['state' => 'deleted', 'value' => $data1[$key]];
        } elseif (is_object($data1[$key]) && is_object($data2[$key])) {
            $result[$key] = ['child' => getDifference((array) $data1[$key], (array) $data2[$key])];
        } elseif ($data1[$key] !== $data2[$key]) {
            $result[$key] = ['state' => 'changed', 'oldValue' => $data1[$key], 'newValue' => $data2[$key]];
        } elseif ($data1[$key] === $data2[$key]) {
            $result[$key] = ['state' => 'unchanged', 'value' => $data1[$key]];
        }
    }
    return $result;
}

function union($data1, $data2)
{
    return array_unique(array_merge($data1, $data2));
}
