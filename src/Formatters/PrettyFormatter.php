<?php

namespace Differ\Formatters\PrettyFormatter;

use function Differ\Node\{getKey, getState, getValue, getOldValue, getChildren, hasChildren};

function formatPretty($diff)
{
    $formatPretty = function ($diff) use (&$formatPretty) {
        return array_reduce($diff, function ($acc, $node) use (&$formatPretty) {
            $key = getKey($node);
            $value = getValue($node);
            if (hasChildren($node)) {
                $acc["  {$key}"] = $formatPretty(getChildren($node));
            } else {
                switch (getState($node)) {
                    case 'unchanged':
                        $acc["  {$key}"] = $value;
                        break;
                    case 'changed':
                        $acc["- {$key}"] = getOldValue($node);
                        $acc["+ {$key}"] = $value;
                        break;
                    case 'deleted':
                        $acc["- {$key}"] = $value;
                        break;
                    case 'added':
                        $acc["+ {$key}"] = $value;
                        break;
                }
            }
            return $acc;
        }, []);
    };
    $formatted = $formatPretty($diff);
    return str_replace(['"', ','], '', json_encode($formatted, JSON_PRETTY_PRINT));
}
