<?php
namespace MacFJA\ChainSelect\Tests\Functional;

use MacFJA\ChainSelect\Handler\FinalHandler;
use MacFJA\ChainSelect\HandlerInterface;
use MacFJA\ChainSelect\HandlerResult;
use MacFJA\ChainSelect\Runner;
use PHPUnit\Framework\TestCase;

/**
 * This class is a function/almost true use case of the lib.
 *
 * @author  MacFJA
 * @license MIT
 * @SuppressWarnings(PHPMD.CamelCasePropertyName)
 * @SuppressWarnings(PHPMD.Superglobals)
 */
class ValueFinder extends TestCase
{
    // Simulate global variables
    // phpcs:disable
    static public $_GET = [];
    static public $_POST = [];
    static public $_ENV = [];
    static public $argv = [];
    // phpcs:enable
    
    private function fillGlobals($globals)
    {
        static::$_GET = $globals['_GET']??[];
        static::$_POST = $globals['_POST']??[];
        static::$_ENV = $globals['_ENV']??[];
        static::$argv = $globals['argv']??[];
    }

    /**
     * @dataProvider caseProvider
     * @coversNothing
     * @group functional
     *
     * @param null $parameter
     * @param      $globals
     * @param      $best
     * @param      $first
     * @throws \MacFJA\ChainSelect\NoResultException
     */
    public function testFindValue($parameter, $globals, $best, $first)
    {
        $this->fillGlobals($globals);
        
        $runner = new Runner();
        $globalsHandler = new class implements HandlerInterface {

            public function execute($context): HandlerResult
            {
                $paramName = 'name';
                $value = ValueFinder::$_GET[$paramName]??
                    ValueFinder::$_POST[$paramName]??
                    ValueFinder::$_ENV[$paramName]??
                    ValueFinder::$argv[$paramName]??
                    null;

                return new HandlerResult($value==null?0.0:1.0, $value, $context, $this);
            }

            public function accept($context): bool
            {
                return true;
            }
        };
        $functionParam = new class($parameter) implements HandlerInterface {

            private $parameter;

            public function __construct($parameter)
            {
                $this->parameter = $parameter;
            }

            public function execute($context): HandlerResult
            {
                return new HandlerResult($this->parameter==null?0.0:1.0, $this->parameter, $context, $this);
            }

            public function accept($context): bool
            {
                return true;
            }
        };

        $runner->addHandler($globalsHandler);
        $runner->addHandler($functionParam);
        $runner->addHandler(new FinalHandler('Doe'));
        
        static::assertEquals($best, $runner->getBestMatch()->getResult());
        static::assertEquals($first, $runner->getFirstAcceptable()->getResult());
    }
    
    public function caseProvider()
    {
        return [
            [
                null,
                [],
                'Doe',
                'Doe'
            ],
            [
                'John',
                [],
                'John',
                'John'
            ],
            [
                null,
                ['_GET' => ['name' => 'Jeanne']],
                'Jeanne',
                'Jeanne'
            ],
            [
                'Mark',
                ['_GET' => ['name' => 'Luke']],
                'Luke',
                'Luke'
            ],
        ];
    }
}
