<?php

namespace Differ\GenDiff;

use function Differ\Parser\getDataParser;
use function Differ\Formatters\Format\formatOutput;
use function Differ\Node\createNode;

function openFile($path)
{
    $realPath = realpath($path);
    if (!file_exists($realPath)) {
        throw new \Exception("File {$path} does not exists\n");
    }
    return file_get_contents($realPath);
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
    return array_map(function ($key) use ($data1, $data2) {
        if (!array_key_exists($key, $data1)) {
            return createNode($key, 'added', $data2[$key]);
        } elseif (!array_key_exists($key, $data2)) {
            return createNode($key, 'deleted', $data1[$key]);
        } elseif (is_object($data1[$key]) && is_object($data2[$key])) {
            $mappedChildren = getDifference((array) $data1[$key], (array) $data2[$key]);
            return createNode($key, null, null, null, $mappedChildren);
        } elseif ($data1[$key] !== $data2[$key]) {
            return createNode($key, 'changed', $data2[$key], $data1[$key]);
        } else {
            return createNode($key, 'unchanged', $data1[$key]);
        };
    }, $keys);
}

function union($data1, $data2)
{
    return array_unique(array_merge($data1, $data2));
}
