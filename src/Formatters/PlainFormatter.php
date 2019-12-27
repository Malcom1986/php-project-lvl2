<?php

namespace Differ\Formatters\PlainFormatter;

function formatPlain($diff)
{
    $result = '';
    $recursive = function ($diff, $stack) use ($result, &$recursive) {
        foreach ($diff as $key => $value) {
            array_push($stack, $key);
            if (array_key_exists('child', $value)) {
                $result .= $recursive($value['child'], $stack);
            } else {
                switch ($value['state']) {
                    case 'changed':
                        $result .= "Property " . stringifyProperty($stack) . " was changed. From " . stringifyValue($value['oldValue']) . " to " . stringifyValue($value['newValue']) . "\n";
                        break;
                    case 'added':
                        $result .= "Property " . stringifyProperty($stack) . " was added with value: "  . stringifyValue($value['value']) . "\n";
                        break;
                    case 'deleted':
                        $result .= "Property " . stringifyProperty($stack) . " was removed" . "\n";
                        break;
                }
            }
            array_pop($stack);
        }
        return $result;
    };
    return $recursive($diff, []);
}


function stringifyValue($value)
{
    if (is_object($value)) {
        return "'complex value'";
    }
    return "'{$value}'";
}

function stringifyProperty(array $property)
{
    $propertyName = implode('.', $property);
    return "'{$propertyName}'";
}
