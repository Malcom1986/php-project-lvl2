<?php

namespace Differ\Cli;

use Docopt;

use function Differ\genDiff\genDiff;

function run()
{
    $doc = <<<DOC
    Generate diff.

    Usage:
      gendiff (-h|--help)
      gendiff (-v|--version)
      gendiff [--format <fmt>] <file1> <file2>

    Options:
      -h --help                     Show this screen
      -v --version                  Show version
      --format <fmt>                Report format [default: pretty]
    DOC;

    $params = [
        'version' => 'v0.0.0'
    ];

    $args = Docopt::handle($doc, $params);
    ['<file1>' => $path1, '<file2>' => $path2, '--format' => $format] = $args;
    $output = genDiff($path1, $path2, $format);
    echo "\n", $output, "\n";
    return;
}
