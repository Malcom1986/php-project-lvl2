<?php

namespace Differ\Formatters\PlainFormatter;

function formatPlain($diff)
{
    $result = '';
    $recursive = function ($diff, $propertyNameStack) use ($result, &$recursive) {
        foreach ($diff as $key => $value) {
            array_push($propertyNameStack, $key);
            if (array_key_exists('child', $value)) {
                $result .= $recursive($value['child'], $propertyNameStack);
            } else {
                $property = stringifyProperty($propertyNameStack);
                switch ($value['state']) {
                    case 'changed':
                        $oldValue = stringifyValue($value['oldValue']);
                        $newValue = stringifyValue($value['newValue']);
                        $result .= "Property {$property} was changed. From {$oldValue} to {$newValue}\n";
                        break;
                    case 'added':
                        $newValue = stringifyValue($value['value']);
                        $result .= "Property {$property} was added with value: {$newValue}\n";
                        break;
                    case 'deleted':
                        $result .= "Property {$property} was removed\n";
                        break;
                }
            }
            array_pop($propertyNameStack);
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
