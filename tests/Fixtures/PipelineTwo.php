<?php

namespace Tests\Fixtures;

class PipelineTwo
{
    public function handle($string, $next)
    {
        $string = "$string Two";

        return $next($string);
    }

    public function other($string, $next)
    {
        $string = "$string method";

        return $next($string);
    }
}
