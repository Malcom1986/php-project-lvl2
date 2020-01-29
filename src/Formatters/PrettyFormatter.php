<?php

namespace Differ\Formatters\PrettyFormatter;

use function Differ\Node\{getName, getType, getNewValue, getOldValue, getChildren};
use function Funct\Strings\times;
use function Funct\Collection\flatten;

function formatPretty($tree)
{
    $formatters = [
        'added' => function ($node, $offset) {
            $name = getName($node);
            $newValue = getNewValue($node);
            $formatedNewValue = stringifyValue($newValue, $offset);
            $indention = times(' ', $offset + 2);
            return "{$indention}+ {$name}: {$formatedNewValue}";
        },
        'deleted' => function ($node, $offset) {
            $name = getName($node);
            $oldValue = getOldValue($node);
            $formatedOldValue = stringifyValue($oldValue, $offset);
            $indention = times(' ', $offset + 2);
            return"{$indention}- {$name}: {$formatedOldValue}";
        },
        'unchanged' => function ($node, $offset) {
            $name = getName($node);
            $oldValue = getOldValue($node);
            $formatedOldValue = stringifyValue($oldValue, $offset);
            $indention = times(' ', $offset + 2);
            return "{$indention}  {$name}: {$formatedOldValue}";
        },
        'changed' => function ($node, $offset) {
            $name = getName($node);
            $indention = times(' ', $offset + 2);
            $oldValue = getOldValue($node);
            $formatedOldValue = stringifyValue($oldValue, $offset);
            $newValue = getNewValue($node);
            $formatedNewValue = stringifyValue($newValue, $offset);
            return ["{$indention}+ {$name}: {$formatedNewValue}", "{$indention}- {$name}: {$formatedOldValue}"];
        },
        'nested' => function ($node, $offset, $func) {
            $name = getName($node);
            $indention = times(' ', $offset + 2);
            $children = getChildren($node);
            $offset += 4;
            $formatedChildren = $func($children, $offset);
            return "{$indention}  {$name}: {$formatedChildren}";
        }
    ];

    $formatPretty = function ($tree, $offset = 0) use (&$formatPretty, $formatters) {
        $formattedTree = array_map(function ($node) use ($formatters, $formatPretty, $offset) {
            $nodeType = getType($node);
            $formatNode = $formatters[$nodeType];
            return $formatNode($node, $offset, $formatPretty);
        }, $tree);
        $treeAsString = implode("\n", flatten($formattedTree));
        $indention = times(' ', $offset);
        return "{\n{$treeAsString}\n{$indention}}";
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
    $formatedObject = collect($object)->map(function ($value, $key) use ($indention) {
        if (is_object($value)) {
            $offset += 4;
            $formatedChildren = stringifyObject($value, $offset);
            return "{$indention}   {$key}: {$formatedChildren}";
        }
        return "{$indention}   {$key}: {$value}";
    })->all();
    $objectAsString = implode("\n", $formatedObject);
    return "{\n{$objectAsString}\n{$indention}}";
}
