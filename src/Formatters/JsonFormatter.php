<?php

namespace Differ\Formatters\JsonFormatter;

use function Differ\Node\{getKey, getState, getValue, getOldValue, getChildren, hasChildren};

function formatJson($diff)
{
    $formatJson = function ($diff) use (&$formatJson) {
        return array_reduce($diff, function ($acc, $node) use (&$formatJson) {
            $key = getKey($node);
            $state = getState($node);
            if (hasChildren($node)) {
                $acc[$key] = $formatJson(getChildren($node));
            } else {
                switch ($state) {
                    case 'changed':
                        $acc[$key] = [
                            'state' => $state,
                            'value' => getValue($node),
                            'oldValue' => getOldValue($node)
                        ];
                        break;
                    default:
                        $acc[$key] = [
                            'state' => $state,
                            'value' => getValue($node)
                        ];
                        break;
                }
            }
            return $acc;
        }, []);
    };
    return json_encode($formatJson($diff), JSON_PRETTY_PRINT);
}
