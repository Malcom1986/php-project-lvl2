<?php

namespace Differ\Node;

function createNode($name, $children = [])
{
    return [
        'name' => $name,
        'type' => 'nested',
        'children' => $children,
        'newValue' => null,
        'oldValue' => null
    ];
}

function createLeaf($name, $type, $newValue, $oldValue)
{
    return [
        'name' => $name,
        'type' => $type,
        'newValue' => $newValue,
        'oldValue' => $oldValue,
        'children' => null
    ];
}

function getType($node)
{
    return $node['type'];
}

function getName($node)
{
    return $node['name'];
}

function getNewValue($node)
{
    return $node['newValue'];
}

function getOldValue($node)
{
    return $node['oldValue'];
}

function getChildren($node)
{
    return $node['children'];
}
