<?php

namespace Differ\Tests;

use PHPUnit\Framework\TestCase;

use function Differ\GenDiff\genDiff;

class GenDiffTest extends TestCase
{
    public function testGenDiff()
    {
        $file1 = 'tests/fixtures/first.json';
        $file2 = 'tests/fixtures/second.json';
        $diff = genDiff($file1, $file2);
        $expected = file_get_contents('tests/fixtures/expected1');
        $this->assertEquals($expected, $diff);
    }
}
