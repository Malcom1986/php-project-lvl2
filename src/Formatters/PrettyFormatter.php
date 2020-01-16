<?php

namespace Differ\Formatters\PrettyFormatter;

use function Differ\Node\{getName, getType, getValue};
use function Differ\Functions\flatten_assoc;

function formatPretty($tree)
{
    $formatters = [
        'nested' => fn ($key, $value, $fn) => ["  {$key}" => $fn($value)],
        'added' => fn ($key, $value) => ["+ {$key}" => $value],
        'deleted' => fn ($key, $value) => ["- {$key}" => $value],
        'unchanged' => fn ($key, $value) => ["  {$key}" => $value],
        'changed' => fn ($key, $value) => ["- {$key}" => $value['old'], "+ {$key}" => $value['new']]
    ];

    $formatPretty = function ($tree) use ($formatters, &$formatPretty) {
        $formattedTree = array_map(function ($node) use ($formatters, &$formatPretty) {
            $nodeType = getType($node);
            $formatNode = $formatters[$nodeType];
            $key = getName($node);
            $value = getValue($node);
            $formatedValue = is_object($value) ? formatObject($value) : $value;
            return $formatNode($key, $formatedValue, $formatPretty);
        }, $tree);
        return flatten_assoc($formattedTree);
    };
    $json = json_encode($formatPretty($tree), JSON_PRETTY_PRINT);
    return str_replace(['"', ','], '', $json);
}

function formatObject($object)
{
    return collect($object)->mapWithKeys(function ($value, $key) {
        if (is_array($value)) {
            return ["  {$key}" => formatObject($value)];
        }
        return ["  {$key}" => $value];
    })->all();
}
