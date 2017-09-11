<?php

namespace framework\modules\Hook;

class Hook
{
    public function add($hook, array $callback)
    {
        add_action($hook, [$callback[0], $callback[1]]);
    }
}