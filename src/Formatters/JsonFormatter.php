<?php

namespace Differ\Formatters\JsonFormatter;

use function Differ\Node\{getName, getType, getValue};
use function Differ\Functions\flatten_assoc;

function formatJson($tree)
{
    $formatters = [
        'nested' => fn ($value, $fn) => $fn($value),
        'added' => fn ($value) => $value,
        'deleted' => fn ($value) => $value,
        'unchanged' => fn ($value) => $value,
        'changed' => fn ($value) => $value
    ];

    $formatJson = function ($tree) use (&$formatJson, $formatters) {
        $formattedTree = array_map(function ($node) use (&$formatJson, $formatters) {
            $type = getType($node);
            $key = getName($node);
            $value = getValue($node);
            $formatValue = $formatters[$type];
            $formatedValue = $formatValue($value, $formatJson);
            return [$key => ['type' => $type, 'value' => $formatedValue]];
        }, $tree);
        return flatten_assoc($formattedTree);
    };
    return json_encode($formatJson($tree), JSON_PRETTY_PRINT);
}
