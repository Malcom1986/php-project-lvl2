<?php

namespace Differ\Tests;

use PHPUnit\Framework\TestCase;

use function Differ\Node\{createNode, getType, getValue, getName};

class NodeTest extends TestCase
{
    public function testCreateNode()
    {
        $node = createNode('names', 'nested', [
            createNode('John', 'added', 'male'),
            createNode('Eva', 'deleted', 'female')]);
        

        $expected = [
            'name' => 'names',
            'type' => 'nested',
            'value' => [
                [
                    'name' => 'John',
                    'type' => 'added',
                    'value' => 'male',
                ],
                [
                    'name' => 'Eva',
                    'type' => 'deleted',
                    'value' => 'female',
                ]
            ]
        ];

        $this->assertEquals($expected, $node);
    }


    public function testGetType()
    {
        $node1 = createNode('number', 'added', 3);
        $this->assertEquals('added', getType($node1));
    }

    
    public function testGetValue()
    {
        $node = createNode('name', 'changed', ['new' => 'John','old' => 'Eva']);
        $expected = [
            'new' => 'John',
            'old' => 'Eva'
        ];

        $this->assertEquals($expected, getValue($node));
    }

    public function testGetName()
    {
        $node = createNode('host', 'unchanged', '127.0.0.0');
        $this->assertEquals('host', getName($node));
    }
}
