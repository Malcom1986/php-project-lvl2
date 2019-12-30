<?php

namespace Differ\Tests;

use PHPUnit\Framework\TestCase;

use function Differ\GenDiff\genDiff;

class GenDiffTest extends TestCase
{
    public function testGenDiffwithJson()
    {
        $file1 = 'tests/fixtures/first.json';
        $file2 = 'tests/fixtures/second.json';
        $diff = genDiff($file1, $file2, 'pretty');
        $expected = file_get_contents('tests/fixtures/expectedFlat');
        $this->assertEquals($expected, $diff);
    }

    public function testGenDiffWithYaml()
    {
        $file1 = 'tests/fixtures/before.yaml';
        $file2 = 'tests/fixtures/after.yaml';
        $diff = genDiff($file1, $file2, 'pretty');
        $expected = file_get_contents('tests/fixtures/expectedFlat');
        $this->assertEquals($expected, $diff);
    }

    public function testGenDiffWithNonexistentFile()
    {
        $this->expectException(\Exception::class);
        gendiff('notExistingFile1', 'notExistingFile2', 'pretty');
    }

    public function testGenDiffWithIncorrectJson()
    {
        $this->expectException(\Exception::class);
        $file1 = 'tests/fixtures/incorrect.json';
        $file2 = 'tests/fixtures/second.json';
        gendiff($file1, $file2, 'pretty');
    }

    public function testGenDiffWithUnsupportedFormat()
    {
        $this->expectException(\Exception::class);
        $file1 = 'tests/fixtures/unsupported.txt';
        $file2 = 'tests/fixtures/unsupported.txt';
        gendiff($file1, $file2, 'pretty');
    }

    public function testGenDiffRecursiveJson()
    {
        $file1 = 'tests/fixtures/before.json';
        $file2 = 'tests/fixtures/after.json';
        $diff = genDiff($file1, $file2, 'pretty');
        $expected = file_get_contents('tests/fixtures/expected');
        $this->assertEquals($expected, $diff);
    }

    public function testGenDiffRecursiveYaml()
    {
        $file1 = 'tests/fixtures/beforeRecursive.yml';
        $file2 = 'tests/fixtures/afterRecursive.yml';
        $diff = genDiff($file1, $file2, 'pretty');
        $expected = file_get_contents('tests/fixtures/expected');
        $this->assertEquals($expected, $diff);
    }

    public function testGenDiffWithPlainOutput()
    {
        $file1 = 'tests/fixtures/beforeRecursive.yml';
        $file2 = 'tests/fixtures/afterRecursive.yml';
        $diff = genDiff($file1, $file2, 'plain');
        $expected = file_get_contents('tests/fixtures/expectedPlain');
        $this->assertEquals($expected, $diff);
    }

    public function testGenDiffWithUnsupportedOutput()
    {
        $this->expectException(\Exception::class);
        $file1 = 'tests/fixtures/beforeRecursive.yml';
        $file2 = 'tests/fixtures/afterRecursive.yml';
        gendiff($file1, $file2, 'text');
    }

    public function testGenDiffWithJsonOutput()
    {
        $file1 = 'tests/fixtures/beforeRecursive.yml';
        $file2 = 'tests/fixtures/afterRecursive.yml';
        $diff = genDiff($file1, $file2, 'json');
        $expected = file_get_contents('tests/fixtures/expectedJson');
        $this->assertEquals($expected, $diff);
    }
}
