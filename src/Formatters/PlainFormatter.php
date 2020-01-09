<?php

namespace Differ\Formatters\PlainFormatter;

use function Differ\Node\{getKey, getState, getValue, getOldValue, getChildren, hasChildren};

function formatPlain($diff)
{
    $formatPlain = function ($diff, $propNameStack) use (&$formatPlain) {
        return array_reduce($diff, function ($acc, $node) use (&$formatPlain, $propNameStack) {
            array_push($propNameStack, getKey($node));
            if (hasChildren($node)) {
                $acc .= $formatPlain(getChildren($node), $propNameStack);
            } else {
                $property = implode('.', $propNameStack);
                $value = is_object(getValue($node)) ? 'complex value' : getValue($node);
                switch (getState($node)) {
                    case 'changed':
                        $oldValue = is_object(getOldValue($node)) ? 'complex value' : getOldValue($node);
                        $acc .= "Property '{$property}' was changed. From '{$oldValue}' to '{$value}'\n";
                        break;
                    case 'added':
                        $acc .= "Property '{$property}' was added with value: '{$value}'\n";
                        break;
                    case 'deleted':
                        $acc .= "Property '{$property}' was removed\n";
                        break;
                }
                array_pop($propNameStack);
            }
            return $acc;
        }, '');
    };
    return $formatPlain($diff, []);
}
