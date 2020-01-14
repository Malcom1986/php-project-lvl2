<?php

namespace Differ\Formatters\Format;

use function Differ\Formatters\PlainFormatter\formatPlain;
use function Differ\Formatters\PrettyFormatter\formatPretty;
use function Differ\Formatters\JsonFormatter\formatJson;

function formatOutput($format, $diff)
{
    $formatters = [
        'pretty' => fn ($diff) => formatPretty($diff),
        'plain' => fn ($diff) => formatPlain($diff),
        'json' => fn ($diff) => formatJson($diff)
    ];
    
    if (!array_key_exists($format, $formatters)) {
        throw new \Exception("Unsupported output format {$format}");
    }
    return $formatters[$format]($diff);
}
