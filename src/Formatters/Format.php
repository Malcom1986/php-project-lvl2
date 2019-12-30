<?php

namespace Differ\Formatters\Format;

use function Differ\Formatters\PlainFormatter\formatPlain;
use function Differ\Formatters\PrettyFormatter\formatPretty;
use function Differ\Formatters\JsonFormatter\formatJson;

function formatOutput($format, $diff)
{
    switch ($format) {
        case 'pretty':
            return formatPretty($diff);
        case 'plain':
            return formatPlain($diff);
        case 'json':
            return formatJson($diff);
        default:
            throw new \Exception("Unsupported output format {$format}");
            break;
    }
}
