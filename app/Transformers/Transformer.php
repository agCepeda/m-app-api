<?php

namespace App\Transformers;

abstract class Transformer
{
    public function transformCollection(array $items)
    {
        $items = json_decode(json_encode($items), true);
        return array_map([$this, 'transform'], $items);
    }

    abstract public function transform($item);
}
