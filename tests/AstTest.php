<?php

namespace Differ\Tests;

use PHPUnit\Framework\TestCase;

use function Differ\Ast\buildAst;

class AstTest extends TestCase
{
    public function testBuildAst()
    {
        $content1 = file_get_contents('tests/fixtures/json/before.json');
        $content2 = file_get_contents('tests/fixtures/json/after.json');
        $data1 = json_decode($content1);
        $data2 = json_decode($content2);
        $actual = buildAst($data1, $data2);
        $expected = [
            [
                'name' => 'common',
                'type' => 'nested',
                'value' => [
                    [
                        'name' => 'setting1',
                        'type' => 'unchanged',
                        'value' => 'Value 1'
                    ],
                    [
                        'name' => 'setting2',
                        'type' => 'deleted',
                        'value' => 200
                    ],
                    [
                        'name' => 'setting3',
                        'type' => 'unchanged',
                        'value' => true
                    ],
                    [
                        'name' => 'setting6',
                        'type' => 'deleted',
                        'value' => (object) ['key' => 'value']
                    ],
                    [
                        'name' => 'setting4',
                        'type' => 'added',
                        'value' => 'blah blah'
                    ],
                    [
                    'name' => 'setting5',
                    'type' => 'added',
                    'value' => (object) ['key5' => 'value5']
                    ]
                ]
            ],
            [
                'name' => 'group1',
                'type' => 'nested',
                'value' => [
                    [
                        'name' => 'baz',
                        'type' => 'changed',
                        'value' => [
                            'old' => 'bas',
                            'new' => 'bars'
                        ]
                    ],
                    [
                        'name' => 'foo',
                        'type' => 'unchanged',
                        'value' => 'bar'
                    ]
                ]
            ],
            [
                'name' => 'group2',
                'type' => 'deleted',
                'value' => (object) ['abc' => 12345]
            ],
            [
                'name' => 'group3',
                'type' => 'added',
                'value' => (object) ['fee' => 100500]
            ]
        ];

        $this->assertEquals($expected, $actual);
    }
}
