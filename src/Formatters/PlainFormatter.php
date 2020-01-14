<?php

namespace Differ\Formatters\PlainFormatter;

use function Differ\Node\{getName, getType, getValue};
use function Funct\Collection\{flatten, compact};

function formatPlain($tree)
{
    $formatters = [
        'unchanged' => fn () => null,
        'nested'  => fn ($name, $value, $fn, $stack) => $fn($value, $stack),
        'added' => fn ($name, $value) => "Property '{$name}' was added with value: '{$value}'",
        'deleted' => fn ($name) => "Property '{$name}' was removed",
        'changed' => fn ($name, $value) => "Property '{$name}' was changed. From '{$value['old']}' to '{$value['new']}'"
    ];
    
    $formatPlain = function ($tree, $propNameStack) use (&$formatPlain, $formatters) {
        return array_map(function ($node) use (&$formatPlain, $formatters, $propNameStack) {
            array_push($propNameStack, getName($node));
            $formatNode = $formatters[getType($node)];
            $property = implode('.', $propNameStack);
            $value = is_object(getValue($node)) ? 'complex value' : getValue($node);
            $result = $formatNode($property, $value, $formatPlain, $propNameStack);
            array_pop($propNameStack);
            return $result;
        }, $tree);
    };
    $result = compact(flatten($formatPlain($tree, [])));
    return implode("\n", $result);
}
