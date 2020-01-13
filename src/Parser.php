<?php

namespace Differ\Parser;

use Symfony\Component\Yaml\Yaml;

function getDataParser($configFormat)
{
    $formats = [
        'yaml' => fn ($config) => Yaml::parse($config, Yaml::PARSE_OBJECT_FOR_MAP),
        'yml' => fn ($config) => Yaml::parse($config, Yaml::PARSE_OBJECT_FOR_MAP),
        'json' => fn ($config) => json_decode($config, false, 512, JSON_THROW_ON_ERROR)
    ];

    if (!array_key_exists($configFormat, $formats)) {
        throw new \Exception('Unsupported file format');
    }
    return $formats[$configFormat];
}
