# ChainSelect

Find the best option

## Installation

The best way to install this lib is to use Composer.
```
$ composer require macfja/chain-select
```

## Usage

```php
$runner = new Runner();
$runner->setMinAcceptableScore(0.75);
$runner->addHandler(new MyCustomHandler());
$runner->addHandler(new MyOtherHandler());
$runner->setContext($myContext);

$runner->getFirstAcceptable();
```

### Examples

Simple example with a corpus of text

```php
$existingKeywords = [/* your code here*/];
$runner = CorpusHandlerHelper::addCorpus(new Runner(), $existingKeywords);
$runner->setContext($userInput);
$result = $runner->getBestMatch();

if ($result->getScore() === 1.0) {
    // your code here. Example:
    //$page->addKeyword($result->getResult());
} else {
    // your code here. Example:
    //echo 'Did you mean "'.$result->getResult().'"?';
}
```

----

A more complex example

```php
$runner = new Runner();
$runner->setMinAcceptableScore(0.75);
$runner->addHandler(new class implements HandlerInterface {
    public function execute($context)
    {
        // Some external request that return a score/confidence value
        $percent = doSomethingAwesome($context);
        return new HandlerResult($percent/100, $percent, $context, $this);
    }
    public function accept($context)
    {
        return true;
    }
});
$runner->addHandler(new class implements HandlerInterface {
    public function execute($context)
    {
        // Some voodoo calculation with the context
        $noteOnTen = myHardWorkCalculation($context);
        return new HandlerResult($percent/10, $noteOnTen, $context, $this);
    }
    public function accept($context)
    {
        return true;
    }
});
$runner->setContext($myContext);

$result = $runner->getFirstAcceptable();

var_dump($result);
```

## Contributing

You are welcome to create new issues if you found a bug or have an idea to improve this lib.

You can also create Pull Request.

### Code quality

The code follow the PSR-1, PSR-2, PSR-4.

The code is validated/monitored with the following tools :

 > Php LOC, Php CPD, PhpCS, Pdepend, PHPMD, PhpMetrics, Parallel-Lint, PhpAssumption, PhpMagicNumberDetector, PhpStan, Psalm, PhpUnit, Infection

You can run the full test-suite with the command:

```
$ composer tests
```