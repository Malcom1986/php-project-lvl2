<?php

namespace Differ\GenDiff;

use function Differ\Parser\getDataParser;
use function Differ\Formatters\Format\formatOutput;
use function Differ\Ast\buildAst;

function genDiff($filePath1, $filePath2, $format)
{
    $fileFormat1 = pathinfo($filePath1, PATHINFO_EXTENSION);
    $fileFormat2 = pathinfo($filePath2, PATHINFO_EXTENSION);
    $content1 = readFile($filePath1);
    $content2 = readFile($filePath2);
    $parseConfig1 = getDataParser($fileFormat1);
    $parseConfig2 = getDataParser($fileFormat2);
    $data1 = $parseConfig1($content1);
    $data2 = $parseConfig2($content2);
    $ast = buildAst($data1, $data2);
    return formatOutput($format, $ast);
}

function readFile($filePath)
{
    $realPath = realpath($filePath);
    if (!file_exists($realPath)) {
        throw new \Exception("File {$filePath} does not exists");
    }
    return file_get_contents($realPath);
}
