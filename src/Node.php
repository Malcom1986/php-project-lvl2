<?php

namespace Differ\Node;

function createNode($name, $type, $value)
{
    return [
        'name' => $name,
        'type' => $type,
        'value' => $value,
    ];
}

function getType($node)
{
    return $node['type'];
}

function getValue($node)
{
    return $node['value'];
}

function getName($node)
{
    return $node['name'];
}
