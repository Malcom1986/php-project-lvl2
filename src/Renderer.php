<?php

namespace Differ\Renderer;

function render($diff)
{
    $result = [];
    foreach ($diff as $key => $value) {
        switch ($value['state']) {
            case 'notModified':
                $result[] = "   {$key}: {$value['value']}";
                break;
            case 'modified':
                $result[] = "- {$key}: {$value['oldValue']}";
                $result[] = "+ {$key}: {$value['newValue']}";
                break;
            case 'deleted':
                $result[] = "- {$key}: {$value['value']}";
                break;
            case 'added':
                $result[] = "+ {$key}: {$value['value']}";
                break;
        }
    }
    $pretty = implode("\n ", $result);
    return "{\n{$pretty}\n}";
}
