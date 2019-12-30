<?php

namespace Differ\Formatters\JsonFormatter;

function formatJson($diff)
{
    $mapped = array_map(function ($value) {
        if (array_key_exists('child', $value)) {
            return $value['child'];
        }
        return $value;
    }, $diff);
    return json_encode($mapped, JSON_PRETTY_PRINT);
}
