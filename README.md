# Console

Console is a lightweight console routing framework for PHP. It's easy to get started, requires almost zero configuration, and can run within existing projects without a major rewrite.

Installation:

In composer.json:
```
"require": {
    "shaggy8871/console": "dev-master"
}
```

Then run:
```
composer install
```

Example console.php file:

```php
<?php
include_once "vendor/autoload.php";

$console = new Console\Runner();

// Add one or more commands here
$console->registerAll([
    'app:command' => [
        'class' => 'Commands\CustomCommand',
        'description' => 'Add your custom command description'
    ]
]);

// Start your engines...
try {
    $console->run();
} catch(Console\Exception\CommandNotFoundException $e) {
    echo $e->getMessage() . "\n--------\n\n" . $console->getHelp();
    die();
}

```

Example command class:

```php
<?php

namespace Commands;

use Console\CommandInterface;
use Console\Args;

class CustomCommand implements CommandInterface
{

    public function execute(Args $args)
    {

        // Convert short-form to long-form arguments
        $args->setAliases([
            'l' => 'longform'
        ]);

        // Write custom code
        // ... Do something with $args->longform or $args->getAll()

    }

}
```

### Color decorating

To decorate output with colors or styles, use the following method:

```php
<?php

use Console\Decorate;

echo Decorate::color('Hello, world!', 'red bold bg_white');

```

### Color reference

| Colors       | Modifiers    | Backgrounds | 
| ------------ | ------------ | ----------- | 
| black        | bold         | bg_black    |
| red          | underline    | bg_red      |
| green        |              | bg_green    |
| yellow       |              | bg_yellow   |
| blue         |              | bg_blue     |
| purple       |              | bg_purple   |
| cyan         |              | bg_cyan     |
| light_gray   |              | bg_white    |
| dark_gray    |              |             |
| light_red    |              |             |
| light_green  |              |             |
| light_yellow |              |             |
| light_blue   |              |             |
| light_purple |              |             |
| light_cyan   |              |             |
| white        |              |             |

To introduce a [256-color VGA palette](https://jonasjacek.github.io/colors/), use the handy `vgaColor` and `vgaBackground` helpers.

```php
<?php

use Console\Decorate;

echo Decorate::color('Hello, world!', [
    Decorate::vgaBackground(9), 
    Decorate::vgaColor(52)
]);

```

## StdOut

Some console applications need the ability to enable or disable ansi output depending on whether output is rendering to the console or to a log file. The StdOut class allows for switching between the two.

To render one or more decorated strings:

```php
<?php

use Console\StdOut;

StdOut::write([
    ["Hello, world!\n", 'red'],
    ["This is a second line, but in green\n", 'green']
]);
```

To disable ansi but still output, call `disableAnsi()` first. All ansi characters will be removed in future output. You can call `enableAnsi()` to re-enable ansi mode.

```php
<?php

use Console\StdOut;

StdOut::disableAnsi();
StdOut::write([
    ["Hello, world!\n", 'red'],
    ["This is a second line, but in green\n", 'green']
]);

```

