<?php

namespace Differ\Parser;

use Symfony\Component\Yaml\Yaml;

function getDataParser($dataFormat)
{
    $parseYaml = function ($inputData) {
        return Yaml::parse($inputData);
    };

    $parseJson = function ($inputData) {
        $parsedData = json_decode($inputData, true);
        if (is_null($parsedData)) {
            throw new \Exception('Json is not valid');
        }
        return $parsedData;
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
