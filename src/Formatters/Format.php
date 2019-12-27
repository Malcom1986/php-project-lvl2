<?php

namespace Differ\Formatters\Format;

use function Differ\Formatters\PlainFormatter\formatPlain;
use function Differ\Formatters\PrettyFormatter\formatPretty;

function formatOutput($format, $diff)
{
    switch ($format) {
        case 'pretty':
            return formatPretty($diff);
        case 'plain':
            return formatPlain($diff);
        default:
            throw new \Exception("Unsupported output format {$format}");
            break;
    }
}
