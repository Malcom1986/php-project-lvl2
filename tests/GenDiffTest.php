<?php

namespace Differ\Tests;

use PHPUnit\Framework\TestCase;

use function Differ\GenDiff\genDiff;

class GenDiffTest extends TestCase
{
    /**
     * @dataProvider genDiffProvider
     */
    public function testGenDiff($filePath1, $filePath2, $format, $pathToExpected)
    {
        $expected = file_get_contents($pathToExpected);
        $actual = genDiff($filePath1, $filePath2, $format);
        $this->assertEquals($expected, $actual);
    }

    public function genDiffProvider()
    {
        return [
            'JsonInPrettyOut' => [
                'tests/fixtures/before.json',
                'tests/fixtures/after.json',
                'pretty',
                'tests/fixtures/expectedPretty'
            ],
            'JsonInPlainOut' => [
                'tests/fixtures/before.json',
                'tests/fixtures/after.json',
                'plain',
                'tests/fixtures/expectedPlain'
            ],
            'JsonInJsonOut' => [
                'tests/fixtures/before.json',
                'tests/fixtures/after.json',
                'json',
                'tests/fixtures/expectedJson'
            ],
            'YamlInPrettyOut' => [
                'tests/fixtures/before.yml',
                'tests/fixtures/after.yml',
                'pretty',
                'tests/fixtures/expectedPretty'
            ],
            'YamlInPlainOut' => [
                'tests/fixtures/before.yml',
                'tests/fixtures/after.yml',
                'plain',
                'tests/fixtures/expectedPlain'
            ],
            'YamlInJsonOut' => [
                'tests/fixtures/before.yml',
                'tests/fixtures/after.yml',
                'json',
                'tests/fixtures/expectedJson'
            ]
        ];
    }

    public function testGenDiffWithNonexistentFile()
    {
        $this->expectException(\Exception::class);
        genDiff('notExistingFile1', 'notExistingFile2', 'plain');
    }

    public function testGenDiffWithUnsupportedInput()
    {
        $this->expectException(\Exception::class);
        genDiff('tests/fixtures/file.txt', 'tests/fixtures/file.txt', 'pretty');
    }

    public function testGenDiffWithUnsupportedOutput()
    {
        $this->expectException(\Exception::class);
        genDiff('tests/fixtures/before.yml', 'tests/fixtures/after.yml', 'pdf');
    }
}
