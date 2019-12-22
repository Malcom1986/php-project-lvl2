<?php

namespace Differ\Parser;

use Symfony\Component\Yaml\Yaml;

function getDataParser($dataFormat)
{
    $parseYaml = function ($inputData) {
        return Yaml::parse($inputData, Yaml::PARSE_OBJECT_FOR_MAP);
    };

    $parseJson = function ($inputData) {
        return $parsedData = json_decode($inputData, false, 512, JSON_THROW_ON_ERROR);
    };

    switch ($dataFormat) {
        case 'json':
            return $parseJson;
        case 'yaml':
        case 'yml':
            return $parseYaml;
        default:
            throw new \Exception("Unsupported format {$dataFormat}");
    }
}
