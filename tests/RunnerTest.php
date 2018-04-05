<?php
namespace MacFJA\ChainSelect\Tests;

use MacFJA\ChainSelect\Handler\CorpusHandlerHelper;
use MacFJA\ChainSelect\Handler\FinalHandler;
use MacFJA\ChainSelect\Handler\NotNullHandler;
use MacFJA\ChainSelect\HandlerInterface;
use MacFJA\ChainSelect\HandlerResult;
use MacFJA\ChainSelect\Runner;
use PHPUnit\Framework\TestCase;

/**
 * This test class cover the class MacFJA\ChainSelect\Runner
 *
 * @author  MacFJA
 * @license MIT
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 */
class RunnerTest extends TestCase
{
    /**
     * @param $score
     * @dataProvider dataProvider
     * @covers \MacFJA\ChainSelect\Runner::setMinAcceptableScore
     */
    public function testSetValidAcceptable($score)
    {
        $runner = new Runner();
        $runner->setMinAcceptableScore($score);

        static::assertTrue(true);
    }

    /**
     * @param $score
     * @dataProvider dataProvider
     * @covers \MacFJA\ChainSelect\Runner::setMinAcceptableScore
     * @expectedException \InvalidArgumentException
     */
    public function testSetInvalidAcceptable($score)
    {
        $runner = new Runner();
        $runner->setMinAcceptableScore($score);
    }

    /**
     * @param $minScore
     * @param $testValue
     * @param bool $expected
     *
     * @dataProvider dataProvider
     * @covers \MacFJA\ChainSelect\Runner::isAcceptable
     * @depends testSetValidAcceptable
     * @depends testSetInvalidAcceptable
     */
    public function testAcceptableScore($minScore, $testValue, $expected)
    {
        $runner = new Runner();
        $runner->setMinAcceptableScore($minScore);
        static::assertEquals($expected, $runner->isAcceptable($testValue));
    }

    /**
     * @covers \MacFJA\ChainSelect\Runner::getFirstAcceptable
     * @covers \MacFJA\ChainSelect\Runner::findAGoodResult
     */
    public function testFirstAcceptable()
    {
        $runner = new Runner();
        $handler1 = new NotNullHandler();
        $handler2 = new FinalHandler('Hi Planet');
        $runner->setContext('Hello World');
        $runner->addHandler($handler1);
        $runner->addHandler($handler2);

        $result = $runner->getFirstAcceptable();

        static::assertEquals('Hello World', $result->getContext());
        static::assertEquals($handler1, $result->getHandler());
    }

    /**
     * @dataProvider dataProvider
     * @covers \MacFJA\ChainSelect\Runner::getFirstAcceptable
     * @covers \MacFJA\ChainSelect\Runner::findAGoodResult
     */
    public function testFirstAcceptableScore($minAcceptableScore, $expected)
    {
        $runner = new Runner();
        $runner->setMinAcceptableScore($minAcceptableScore);
        $runner->setContext('Hello World');
        CorpusHandlerHelper::addCorpus($runner, ['H3110 W0r1d', 'Hello x0rld', 'H3ll0 W0rld', 'Hello world']);

        $result = $runner->getFirstAcceptable();

        static::assertEquals($expected, $result->getResult());
    }

    /**
     * @expectedException \MacFJA\ChainSelect\NoResultException
     * @covers \MacFJA\ChainSelect\Runner::getFirstAcceptable
     * @covers \MacFJA\ChainSelect\Runner::findAGoodResult
     */
    public function testFirstAcceptableScoreFail()
    {
        $runner = new Runner();
        $runner->setContext('Hello World');
        $runner->setMinAcceptableScore(0.75);
        CorpusHandlerHelper::addCorpus($runner, ['H3110 W0r1d', 'H3ll0 W0rld', 'Hi Planet']);

        $runner->getFirstAcceptable();
    }

    /**
     * @covers \MacFJA\ChainSelect\Runner::getBestMatch
     * @covers \MacFJA\ChainSelect\Runner::findAGoodResult
     */
    public function testBestScore()
    {
        $runner = new Runner();
        $runner->setContext('Hello World');
        $runner->setMinAcceptableScore(0.75);
        CorpusHandlerHelper::addCorpus($runner, ['H3110 W0r1d', 'H3ll0 W0rld', 'Hi Planet']);

        $best = $runner->getBestMatch();
        
        static::assertEquals('H3ll0 W0rld', $best->getResult());
    }

    /**
     * @expectedException \MacFJA\ChainSelect\NoResultException
     * @covers \MacFJA\ChainSelect\Runner::getBestMatch
     * @covers \MacFJA\ChainSelect\Runner::findAGoodResult
     */
    public function testNoHandler1()
    {
        $runner = new Runner();

        $runner->getBestMatch();
    }

    /**
     * @expectedException \MacFJA\ChainSelect\NoResultException
     * @covers \MacFJA\ChainSelect\Runner::getFirstAcceptable
     * @covers \MacFJA\ChainSelect\Runner::findAGoodResult
     */
    public function testNoHandler2()
    {
        $runner = new Runner();

        $runner->getFirstAcceptable();
    }

    /**
     * @covers \MacFJA\ChainSelect\Runner::getFirstAcceptable
     * @covers \MacFJA\ChainSelect\Runner::findAGoodResult
     */
    public function testNotSupported()
    {
        $runner = new Runner();
        
        $runner->addHandler(new class implements HandlerInterface {
            public function execute($context): HandlerResult
            {
                return new HandlerResult(1, 'Fail', null, $this);
            }

            public function accept($context): bool
            {
                return false;
            }
        });

        $runner->addHandler(new class implements HandlerInterface {
            public function execute($context): HandlerResult
            {
                return new HandlerResult(1, 'OK', null, $this);
            }

            public function accept($context): bool
            {
                return true;
            }
        });

        $result = $runner->getFirstAcceptable();

        static::assertEquals('OK', $result->getResult());
    }

    public function dataProvider($testName): array
    {
        if ($testName === 'testAcceptableScore') {
            return [
                [0.5, 0.5, true],
                [0.5, 0.6, true],
                [0.5, 0.4, false],
                [0.75, 0.5, false],
                [0.75, 0.6, false],
                [0.75, 0.4, false]
            ];
        }
        if ($testName === 'testSetValidAcceptable') {
            return [
                [0.1],
                [0.5],
                [1.0]
            ];
        }
        if ($testName === 'testSetInvalidAcceptable') {
            return [
                [0.0],
                [-1],
                [1.1]
            ];
        }
        if ($testName === 'testFirstAcceptableScore') {
            return [
                [0.5, 'Hello x0rld'],
                [0.15, 'H3110 W0r1d'],
                [0.90, 'Hello world'],
            ];
        }
        return [];
    }
}
