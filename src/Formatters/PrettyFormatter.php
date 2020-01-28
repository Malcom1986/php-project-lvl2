<?php

namespace Differ\Formatters\PrettyFormatter;

use function Differ\Node\{getName, getType, getNewValue, getOldValue, getChildren};
use function Funct\Strings\times;

const START_OFFSET = 2;

function formatPretty($tree)
{
    $formatters = [
        'added' => function ($acc, $node, $offset) {
            $name = getName($node);
            $newValue = getNewValue($node);
            $formatedNewValue = stringifyValue($newValue, $offset);
            $indention = times(' ', $offset + START_OFFSET);
            $acc[] = "{$indention}+ {$name}: {$formatedNewValue}";
            return $acc;
        },
        'deleted' => function ($acc, $node, $offset) {
            $name = getName($node);
            $oldValue = getOldValue($node);
            $formatedOldValue = stringifyValue($oldValue, $offset);
            $indention = times(' ', $offset + START_OFFSET);
            $acc[] = "{$indention}- {$name}: {$formatedOldValue}";
            return $acc;
        },
        'unchanged' => function ($acc, $node, $offset) {
            $name = getName($node);
            $oldValue = getOldValue($node);
            $formatedOldValue = stringifyValue($oldValue, $offset);
            $indention = times(' ', $offset + START_OFFSET);
            $acc[] = "{$indention}  {$name}: {$formatedOldValue}";
            return $acc;
        },
        'changed' => function ($acc, $node, $offset) {
            $name = getName($node);
            $indention = times(' ', $offset + START_OFFSET);
            $oldValue = getOldValue($node);
            $formatedOldValue = stringifyValue($oldValue, $offset);
            $newValue = getNewValue($node);
            $formatedNewValue = stringifyValue($newValue, $offset);
            $acc[] = "{$indention}+ {$name}: {$formatedNewValue}";
            $acc[] = "{$indention}- {$name}: {$formatedOldValue}";
            return $acc;
        },
        'nested' => function ($acc, $node, $offset, $func) {
            $name = getName($node);
            $indention = times(' ', $offset + START_OFFSET);
            $children = getChildren($node);
            $offset += 4;
            $formatedChildren = $func($children, $offset);
            $acc[] = "{$indention}  {$name}: {$formatedChildren}";
            return $acc;
        }
    ];

    $formatPretty = function ($tree, $offset = 0) use (&$formatPretty, $formatters) {
        $formatted = array_reduce($tree, function ($acc, $node) use ($formatters, $formatPretty, $offset) {
            $nodeType = getType($node);
            $formatNode = $formatters[$nodeType];
            return $formatNode($acc, $node, $offset, $formatPretty);
        }, []);
        $string = implode("\n", $formatted);
        $indention = times(' ', $offset);
        return "{\n{$string}\n{$indention}}";
    };

    return $formatPretty($tree);
}

function stringifyValue($value, $offset)
{
    $valueType = \gettype($value);
    switch ($valueType) {
        case 'object':
            return stringifyObject($value, $offset);
        case 'boolean':
            return json_encode($value);
        case 'array':
            return implode(',', $value);
        default:
            return $value;
    }
}

function stringifyObject($object, $offset)
{
    $indention = times(' ', $offset + 4);
    $formated = collect($object)->map(function ($value, $key) use ($indention) {
        return "{$indention}   {$key}: {$value}";
    })->all();
    $string = implode("\n", $formated);
    return "{\n{$string}\n{$indention}}";
}
