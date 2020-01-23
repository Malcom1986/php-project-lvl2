<?php

namespace Differ\Ast;

use function Funct\Collection\union;
use function Differ\Node\{createNode, createLeaf};

function buildAst($data1, $data2)
{
    $types = [
        [
            'predicate' => fn ($tree1, $tree2, $key) => !isset($tree1->$key),
            'createNode' => fn ($data1, $data2, $key) => createLeaf($key, 'added', $data2->$key, null)
        ],
        [
            'predicate' => fn ($tree1, $tree2, $key) => !isset($tree2->$key),
            'createNode' => fn ($data1, $data2, $key) => createLeaf($key, 'deleted', null, $data1->$key)
        ],
        [
            'predicate' => fn ($tree1, $tree2, $key) => is_object($tree1->$key) && is_object($tree2->$key),
            'createNode' => function ($data1, $data2, $key) {
                $children = buildAst($data1->$key, $data2->$key);
                return createNode($key, $children);
            }
        ],
        [
            'predicate' => fn ($tree1, $tree2, $key) => $tree1->$key !== $tree2->$key,
            'createNode' => fn ($data1, $data2, $key) => createLeaf($key, 'changed', $data2->$key, $data1->$key)
        ],
        [
            'predicate' => fn ($tree1, $tree2, $key) => $tree1->$key === $tree2->$key,
            'createNode' => fn ($data1, $data2, $key) => createLeaf($key, 'unchanged', $data2->$key, $data1->$key)
        ]
    ];

    $keys = union(getObjectKeys($data1), getObjectKeys($data2));
    return array_map(function ($key) use ($data1, $data2, $types) {
        $nodeType = find($types, fn ($type) => $type['predicate']($data1, $data2, $key));
        $createNode = $nodeType['createNode'];
        return $createNode($data1, $data2, $key);
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
