<?php

namespace Tests\Fixtures;

class PipelineInvokeable
{
    public function __invoke($string, $next)
    {
        $string = "$string Invokeable";

        return $next($string);
    }
}
