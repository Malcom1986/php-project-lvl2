<?php

namespace Differ\Tests;

use PHPUnit\Framework\TestCase;

use function Differ\Node\{createNode, hasChildren, getChildren, getState, getValue, getOldValue};

class NodeTest extends TestCase
{
    public function testCreateNode()
    {
        $node = createNode('name', 'changed', 'John', 'Eva', [
            createNode('John', 'added', 'male'),
            createNode('Eva', 'deleted', 'female')]);
        $expected = [
            'key' => 'name',
            'state' => 'changed',
            'currentValue' => 'John',
            'oldValue' => 'Eva',
            'children' => [
                [
                    'key' => 'John',
                    'state' => 'added',
                    'currentValue' => 'male',
                    'oldValue' => null,
                    'children' => []
                ],
                [
                    'key' => 'Eva',
                    'state' => 'deleted',
                    'currentValue' => 'female',
                    'oldValue' => null,
                    'children' => []
                ]
            ]

        ];
        $this->assertEquals($expected, $node);
    }

    public function testHasChildren()
    {
        $node1 = createNode('number', 'added', 3);
        $node2 = createNode('name', null, null, null, createNode('John', 'added', 'male'));
        $this->assertFalse(hasChildren($node1));
        $this->assertTrue(hasChildren($node2));
    }

    public function testGetState()
    {
        $node1 = createNode('number', 'added', 3);
        $this->assertEquals('added', getState($node1));
    }

    public function testGetChildren()
    {
        $node = createNode('name', null, null, null, [
            createNode('John', 'added', 'male'),
            createNode('Eva', 'added', 'female')]);
        $children = [
            createNode('John', 'added', 'male'),
            createNode('Eva', 'added', 'female')
        ];
        $this->assertEquals($children, getChildren($node));
    }

    public function testGetValue()
    {
        $node = createNode('name', 'changed', 'John', 'Eva');
        $this->assertEquals('John', getValue($node));
    }

    public function testGetOldValue()
    {
        $node = createNode('name', 'changed', 'John', 'Eva');
        $this->assertEquals('Eva', getOldValue($node));
    }
}
