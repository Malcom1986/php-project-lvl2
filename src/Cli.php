<?php

namespace Gendiff\Cli;

use Docopt;

function run()
{
    $doc = <<<DOC
    Generate diff.

    Usage:
      gendiff (-h|--help)
      gendiff (-v|--version)

    Options:
      -h --help                     Show this screen
      -v --version                  Show version
    DOC;

    $params = [
        'version' => 'v0.0.0'
    ];

    $args = Docopt::handle($doc, $params);
}
