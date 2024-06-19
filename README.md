<p align="center">
<a href="https://github.com/beebmx/pipeline/actions"><img src="https://img.shields.io/github/actions/workflow/status/beebmx/pipeline/tests.yml?branch=main" alt="Build Status"></a>
<a href="https://packagist.org/packages/beebmx/pipeline"><img src="https://img.shields.io/packagist/dt/beebmx/pipeline" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/beebmx/pipeline"><img src="https://img.shields.io/packagist/v/beebmx/pipeline" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/beebmx/pipeline"><img src="https://img.shields.io/packagist/l/beebmx/pipeline" alt="License"></a>
</p>

# Pipeline

Package inspired by [Laravel Pipeline](https://github.com/illuminate/pipeline), but without `Laravel Container`

## Installation

Install using composer:

```bash
composer require beebmx/pipeline
```

## Usage

The basic use of `pipeline` is:

```php
use Beebmx\Pipeline\Pipeline;

$string = (new Pipeline)
    ->send('say')
    ->through([
        function($string, $next) {
            $string = $string.' hello';

            return $next($string);
        },
        function($string, $next) {
            $string = $string.' world';

            return $next($string);
        }
    ])->execute();

//$string will be 'say hello world'
```

> [!IMPORTANT]  
> You should always call the `$next` callback with the `pipe` variable as argument 
> for the next pipe or final result. 

You can use a class insted of a `Closure` if that more your need:

```php
use Beebmx\Pipeline\Pipeline;

class MyOwnPipe
{
    public function handle($myPipeObject, Closure $next)
    {
        $myPipeObject->process();
 
        return $next($myPipeObject);
    }
}

$result = (new Pipeline)
    ->send($someObject)
    ->through([
        MyOwnPipe::class,
        OtherPipe::class,
    ])->execute();
```

> [!NOTE]  
> By default `Pipeline` will triger the `handle` method. 

If you need to change the default `handle` method in your pipe classes, you can do it like:

```php
use Beebmx\Pipeline\Pipeline;

$result = (new Pipeline)
    ->send($someObject)
    ->via('myOwnMethod')
    ->through([
        MyOwnPipe::class,
        OtherPipe::class,
    ])->execute();
```

At the end of the pipe's flow, you can finish with the processing like:

```php
use Beebmx\Pipeline\Pipeline;

$string = (new Pipeline)
    ->send('say')
    ->through(function($string, $next) {
        $string = $string.' hello';

        return $next($string);
    })->then(function($string) {
        $string = $string.' world';

        return $string;
    });

//$string will be 'say hello world'
```

You and add more `pipes` to the pipeline flow:


```php
use Beebmx\Pipeline\Pipeline;

use Beebmx\Pipeline\Pipeline;

$string = (new Pipeline)
    ->send('say')
    ->through(function($string, $next) {
        $string = $string.' hello';

        return $next($string);
    })->pipe(function($string) {
        $string = $string.' world';

        return $string;
    })->execute();

//$string will be 'say hello world'
```

## Testing

```bash
composer test
```

## Credits

- Original repository [illuminate/pipeline](https://github.com/illuminate/pipeline)
- Fernando Gutierrez [@beebmx](https://github.com/beebmx)
- [All Contributors](../../contributors)