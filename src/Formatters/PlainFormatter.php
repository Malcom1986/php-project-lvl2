<?php

namespace Differ\Formatters\PlainFormatter;

use function Differ\Node\{getName, getType, getNewValue, getOldValue, getChildren};
use function Funct\Collection\{flatten, compact};

function formatPlain($tree)
{
    $formatters = [
        'unchanged' => fn () => null,
        'added' => function ($node, $parents) {
            $nodeName = getName($node);
            $propertyName = buildPropertyName($nodeName, $parents);
            $newValue = getNewValue($node);
            $newValuePlain = stringifyValue($newValue);
            return "Property '{$propertyName}' was added with value: '{$newValuePlain}'";
        },
        'deleted' => function ($node, $parents) {
            $nodeName = getName($node);
            $propertyName = buildPropertyName($nodeName, $parents);
            return "Property '{$propertyName}' was removed";
        },
        'changed' => function ($node, $parents) {
            $nodeName = getName($node);
            $propertyName = buildPropertyName($nodeName, $parents);
            $oldValue = getOldValue($node);
            $newValue = getNewValue($node);
            $newValuePlain = stringifyValue($newValue);
            return "Property '{$propertyName}' was changed. From '{$oldValue}' to '{$newValuePlain}'";
        },
        'nested'  => function ($node, $parents, $func) {
            $children = getChildren($node);
            $parents[] = getName($node);
            return $func($children, $parents);
        }
    ];

    $formatPlain = function ($tree, $parents = []) use ($formatters, &$formatPlain) {
        return array_map(function ($node) use ($formatters, $parents, &$formatPlain) {
            $nodeType = getType($node);
            $formatNode = $formatters[$nodeType];
            return $formatNode($node, $parents, $formatPlain);
        }, $tree);
    };
    $parts = compact(flatten($formatPlain($tree)));
    return implode("\n", $parts);
}

function isComplexValue($value)
{
    return is_object($value) || is_array($value);
}

function buildPropertyName($nodeName, $parents)
{
    return implode('.', [...$parents, $nodeName]);
}

function stringifyValue($value)
{
    return isComplexValue($value) ? 'complex value' : $value;
}
