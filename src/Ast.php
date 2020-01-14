<?php

namespace Differ\Ast;

use function Funct\Collection\union;
use function Differ\Node\createNode;

function buildAst($data1, $data2)
{
    $types = [
        [
            'type' => 'added',
            'predicate' => fn ($tree1, $tree2, $key) => !isset($tree1->$key),
            'process' => fn ($value1, $value2) => $value2
        ],
        [
            'type' => 'deleted',
            'predicate' => fn ($tree1, $tree2, $key) => !isset($tree2->$key),
            'process' => fn ($value1) => $value1
        ],
        [
            'type' => 'nested',
            'predicate' => fn ($tree1, $tree2, $key) => is_object($tree1->$key) && is_object($tree2->$key),
            'process' => fn ($value1, $value2) => buildAst($value1, $value2)
        ],
        [
            'type' => 'changed',
            'predicate' => fn ($tree1, $tree2, $key) => $tree1->$key !== $tree2->$key,
            'process' => fn ($value1, $value2) => ['old' => $value1, 'new' => $value2]
        ],
        [
            'type' => 'unchanged',
            'predicate' => fn ($tree1, $tree2, $key) => $tree1->$key === $tree2->$key,
            'process' => fn($value1) => $value1
        ]
    ];

    $keys = union(getObjectKeys($data1), getObjectKeys($data2));
    return array_map(function ($key) use ($data1, $data2, $types) {
        $nodeType = find($types, fn ($type) => $type['predicate']($data1, $data2, $key));
        ['type' => $type, 'process' => $process] = $nodeType;
        $value = $process($data1->$key ?? null, $data2->$key ?? null);
        return createNode($key, $type, $value);
    }, array_values($keys));
}

function getObjectKeys($tree)
{
    return array_keys(get_object_vars($tree));
}

function find($array, $func)
{
    return collect($array)->first($func);
}
