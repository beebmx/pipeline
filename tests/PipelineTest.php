<?php

use Beebmx\Pipeline\Pipeline;
use Tests\Fixtures\PipelineInvokeable;
use Tests\Fixtures\PipelineOne;
use Tests\Fixtures\PipelineParameter;
use Tests\Fixtures\PipelineTwo;

it('return pipe results with classes', function () {
    $string = (new Pipeline)->send('Pipes:')
        ->through([
            PipelineOne::class,
            PipelineTwo::class,
        ])->then(fn ($string) => $string);

    expect($string)
        ->toBe('Pipes: One Two');
});

it('return pipe results with closures', function () {
    $string = (new Pipeline)->send('Pipes:')
        ->through([
            function ($string, $next) {
                $string = "$string One";

                return $next($string);
            },
            function ($string, $next) {
                $string = "$string Two";

                return $next($string);
            },

        ])->then(fn ($string) => $string);

    expect($string)
        ->toBe('Pipes: One Two');
});

it('returns pipe results with invokable class', function () {
    $string = (new Pipeline)->send('Pipe:')
        ->through(PipelineInvokeable::class)
        ->execute();

    expect($string)
        ->toBe('Pipe: Invokeable');
});

it('returns pipe with parameters class', function () {
    $parameters = ['one', 'two'];

    $string = (new Pipeline)->send('Pipe:')
        ->through(
            PipelineParameter::class.':'.implode(',', $parameters)
        )->execute();

    expect($string)
        ->toBe('Pipe: one - two');
});

it('can add aditional pipe to pipeline', function () {
    $string = (new Pipeline)->send('Pipes:')
        ->through(function ($string, $next) {
            $string = "$string One";

            return $next($string);
        })
        ->pipe(function ($string, $next) {
            $string = "$string Two";

            return $next($string);
        })
        ->then(fn ($string) => $string);

    expect($string)
        ->toBe('Pipes: One Two');
});

it('return pipe results with classes and closures', function () {
    $string = (new Pipeline)->send('Pipes:')
        ->through([
            PipelineOne::class,
            function ($string, $next) {
                $string = "$string Two";

                return $next($string);
            },
        ])->then(fn ($string) => $string);

    expect($string)
        ->toBe('Pipes: One Two');
});

it('return pipe results with classes via other method', function () {
    $string = (new Pipeline)->send('Pipe with')
        ->via('other')
        ->through([
            PipelineOne::class,
            PipelineTwo::class,
        ])->then(fn ($string) => $string);

    expect($string)
        ->toBe('Pipe with other method');
});

it('return pipe results with classes via other method and closure', function () {
    $string = (new Pipeline)->send('Pipe with')
        ->via('other')
        ->through([
            PipelineOne::class,
            function ($string, $next) {
                $string = "$string method";

                return $next($string);
            },
        ])->then(fn ($string) => $string);

    expect($string)
        ->toBe('Pipe with other method');
});

it('return pipe results and then can finally process', function () {
    $string = (new Pipeline)->send('Pipe')
        ->through(fn ($string, $next) => $next($string))
        ->then(fn ($string) => 'Flow '.$string);

    expect($string)
        ->toBe('Flow Pipe');
});

it('return the pipe when execute', function () {
    $string = (new Pipeline)->send('Pipe')
        ->through([
            function ($string, $next) {
                return $next($string);
            },
            function ($string, $next) {
                return $next($string);
            },
        ])->execute();

    expect($string)
        ->toBe('Pipe');
});
