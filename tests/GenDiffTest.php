<?php

namespace Differ\Tests;

use Exception;
use PHPUnit\Framework\TestCase;

use function Differ\GenDiff\genDiff;

class GenDiffTest extends TestCase
{
    public function testGenDiffwithJson()
    {
        $file1 = 'tests/fixtures/first.json';
        $file2 = 'tests/fixtures/second.json';
        $diff = genDiff($file1, $file2);
        $expected = file_get_contents('tests/fixtures/expected1');
        $this->assertEquals($expected, $diff);
    }

    public function testGenDiffWithYaml()
    {
        $file1 = 'tests/fixtures/before.yaml';
        $file2 = 'tests/fixtures/after.yaml';
        $diff = genDiff($file1, $file2);
        $expected = file_get_contents('tests/fixtures/expected1');
        $this->assertEquals($expected, $diff);
    }
}
