<?php

namespace Differ\Test;

use PHPUnit\Framework\TestCase;

use function Differ\GenDiff\genDiff;

class GenDiffTest2 extends TestCase
{
    /**
     * @dataProvider genDiffProvider
     */
    public function testGenDiff($inputFormat, $outputFormat)
    {
        $pathToExpected = "tests/fixtures/expected.{$outputFormat}.txt";
        $expected = file_get_contents($pathToExpected);
        $filePath1 = "tests/fixtures/before.{$inputFormat}";
        $filePath2 = "tests/fixtures/after.{$inputFormat}";
        $actual = genDiff($filePath1, $filePath2, $outputFormat);
        $this->assertEquals($expected, $actual);
    }

    public function genDiffProvider()
    {
        return [
            'JsonInPrettyOut' => ['json', 'pretty'],
            'JsonInPlainOut' => ['json', 'plain'],
            'JsonInJsonOut' => ['json', 'json'],
            'YamlInPrettyOut' => ['yml', 'pretty'],
            'YamlInPlainOut' => ['yml', 'plain'],
            'YamlInJsonOut' => ['yml', 'json']
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
