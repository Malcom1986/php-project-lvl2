<?php

namespace Differ\GenDiff;

use function Differ\Parser\getDataParser;
use function Differ\Formatters\Format\formatOutput;
use function Differ\Ast\buildAst;

function genDiff($path1, $path2, $format)
{
    $configFormat = pathinfo($path1, PATHINFO_EXTENSION);
    $config1 = openFile($path1);
    $config2 = openFile($path2);
    $parseConfig = getDataParser($configFormat);
    $data1 = $parseConfig($config1);
    $data2 = $parseConfig($config2);
    $ast = buildAst($data1, $data2);
    return formatOutput($format, $ast);
}

function openFile($path)
{
    $realPath = realpath($path);
    if (!file_exists($realPath)) {
        throw new \Exception("File {$path} does not exists\n");
    }
    return file_get_contents($realPath);
}
