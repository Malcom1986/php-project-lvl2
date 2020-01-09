<?php

namespace Differ\Ast;

$nodeTypes = [
    [
        'type' => 'nested',
        'predicate' => fn ($tree1, $tree2, $key) => is_object($tree1->$key) && is_object($tree2->$key),


    ]
];
