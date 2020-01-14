<?php

namespace Differ\Functions;

function flatten_assoc($array)
{
    return collect($array)->flatMap(fn ($item) => $item)->all();
}
