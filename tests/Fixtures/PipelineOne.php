<?php

namespace Tests\Fixtures;

class PipelineOne
{
    public function handle($string, $next)
    {
        $string = "$string One";

        return $next($string);
    }

    public function other($string, $next)
    {
        $string = "$string other";

        return $next($string);
    }
}
