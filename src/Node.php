<?php

namespace Differ\Node;

function createNode($key, $state, $value, $oldValue = null, $children = [])
{
    return [
        'key' => $key,
        'state' => $state,
        'currentValue' => $value,
        'oldValue' => $oldValue,
        'children' => $children
    ];
}

function hasChildren($node)
{
    return count(getChildren($node)) > 0;
}

function getChildren($node)
{
    return $node['children'];
}

function getState($node)
{
    return $node['state'];
}

function getValue($node)
{
    return $node['currentValue'];
}

function getOldValue($node)
{
    return $node['oldValue'];
}

function getKey($node)
{
    return $node['key'];
}
