<?php

namespace Differ\GenDiff;

use function Differ\Parser\getDataParser;
use function Differ\Renderer\render;

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
    $content1 = openFile($path1);
    $content2 = openFile($path2);
    $parseContent = getDataParser($dataFormat);
    $configData1 = $parseContent($content1);
    $configData2 = $parseContent($content2);
    $difference = getDifference($configData1, $configData2);
    return render($difference);
}

function getDifference($configData1, $configData2)
{
    $diff = [];
    foreach ($configData1 as $key => $value) {
        if (array_key_exists($key, $configData2) && is_object($value) && is_object($configData2->$key)) {
            $diff[$key] = [
                'child' => getDifference($value, $configData2->$key)
            ];
        } else {
            if (array_key_exists($key, $configData2)) {
                if ($value === $configData2->$key) {
                    $diff[$key] = [
                        'state' => 'notModified',
                        'value' => $value
                    ];
                } else {
                    $diff[$key] = [
                        'state' => 'modified',
                        'oldValue' => $value,
                        'newValue' => $configData2->$key
                    ];
                }
            } else {
                $diff[$key] = [
                    'state' => 'deleted',
                    'value' => $value
                ];
            }
        }
        foreach ($configData2 as $key => $value) {
            if (!array_key_exists($key, $configData1)) {
                $diff[$key] = [
                    'state' => 'added',
                    'value' => $value
                ];
            }
        }
    }
    return $diff;
}
