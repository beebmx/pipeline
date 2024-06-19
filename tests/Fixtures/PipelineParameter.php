<?php

namespace Tests\Fixtures;

class PipelineParameter
{
    public function handle($string, $next, $parameter1 = null, $parameter2 = null)
    {
        $string = "$string $parameter1 - $parameter2";

        return $next($string);
    }
}
