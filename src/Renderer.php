<?php

namespace Differ\Renderer;

function render($diff)
{
    
    $recursive = function ($diff) use (&$recursive) {
        $result = [];
        foreach ($diff as $key => $value) {
            if (array_key_exists('child', $value)) {
                $result["  {$key}"] = $recursive($value['child']);
            } else {
                switch ($value['state']) {
                    case 'notModified':
                        $result["  {$key}"] = $value['value'];
                        break;
                    case 'modified':
                        $result["- {$key}"] = $value['oldValue'];
                        $result["+ {$key}"] = $value['newValue'];
                        break;
                    case 'deleted':
                        $result["- {$key}"] = $value['value'];
                        break;
                    case 'added':
                        $result["+ {$key}"] = $value['value'];
                        break;
                }
            }
        }
        return $result;
    };
    $res = $recursive($diff);
    return str_replace(['"', ','], '', json_encode($res, JSON_PRETTY_PRINT));
}
