<?php

namespace Differ\Formatters\PrettyFormatter;

use function Differ\Node\{getName, getType, getNewValue, getOldValue, getChildren};

function formatPretty($tree)
{
    $formatters = [
        'added' => function ($acc, $node) {
            $key = getName($node);
            $newValue = getNewValue($node);
            $formatedNewValue = is_object($newValue) ? formatObject($newValue) : $newValue;
            $acc["+ {$key}"] = $formatedNewValue;
            return $acc;
        },
        'deleted' => function ($acc, $node) {
            $key = getName($node);
            $oldValue = getOldValue($node);
            $formatedOldValue = is_object($oldValue) ? formatObject($oldValue) : $oldValue;
            $acc["- {$key}"] = $formatedOldValue;
            return $acc;
        },
        'unchanged' => function ($acc, $node) {
            $key = getName($node);
            $oldValue = getOldValue($node);
            $formatedOldValue = is_object($oldValue) ? formatObject($oldValue) : $oldValue;
            $acc["  {$key}"] = $formatedOldValue;
            return $acc;
        },
        'changed' => function ($acc, $node) {
            $key = getName($node);
            $oldValue = getOldValue($node);
            $formatedOldValue = is_object($oldValue) ? formatObject($oldValue) : $oldValue;
            $newValue = getNewValue($node);
            $formatedNewValue = is_object($newValue) ? formatObject($newValue) : $newValue;
            $acc["- {$key}"] = $formatedOldValue;
            $acc["+ {$key}"] = $formatedNewValue;
            return $acc;
        },
        'nested' => function ($acc, $node, $func) {
            $key = getName($node);
            $children = getChildren($node);
            $acc["  {$key}"] = $func($children);
            return $acc;
        }
    ];

    $formatPretty = function ($tree) use (&$formatPretty, $formatters) {
        return array_reduce($tree, function ($acc, $node) use ($formatters, $formatPretty) {
            $nodeType = getType($node);
            $formatNode = $formatters[$nodeType];
            return $formatNode($acc, $node, $formatPretty);
        }, []);
    };

    $formatedTree = $formatPretty($tree);
    $json = json_encode($formatedTree, JSON_PRETTY_PRINT);
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
