<?php
namespace MacFJA\ChainSelect\Tests\Handler;

use MacFJA\ChainSelect\Handler\NumberComparatorHandler;
use PHPUnit\Framework\TestCase;

/**
 * This test class cover the class MacFJA\ChainSelect\Handler\NumberComparatorHandler
 *
 * @author  MacFJA
 * @license MIT
 */
class NumberComparatorHandlerTest extends TestCase
{
    /**
     * @param $input
     * @param $expected
     * @covers \MacFJA\ChainSelect\Handler\NumberComparatorHandler::accept
     * @dataProvider dataProvider
     */
    public function testAccept($input, $expected)
    {
        $handler = new NumberComparatorHandler(NumberComparatorHandler::EQUALS_TO, 0);

        static::assertEquals($expected, $handler->accept($input));
    }

    /**
     * @param $operator
     * @param $reference
     * @param $context
     * @param $expected
     * @covers       \MacFJA\ChainSelect\Handler\NumberComparatorHandler::execute
     * @covers       \MacFJA\ChainSelect\Handler\NumberComparatorHandler::__construct
     * @dataProvider dataProvider
     */
    public function testExecute($operator, $reference, $context, $expected)
    {
        $handler = new NumberComparatorHandler($operator, $reference);
        $result = $handler->execute($context);

        static::assertEquals($expected?1:0, $result->getScore());
        static::assertEquals($expected, $result->getResult());
    }

    /**
     * @covers \MacFJA\ChainSelect\Handler\NumberComparatorHandler::equalsTo
     * @covers \MacFJA\ChainSelect\Handler\NumberComparatorHandler::greaterThan
     * @covers \MacFJA\ChainSelect\Handler\NumberComparatorHandler::greaterOrEquals
     * @covers \MacFJA\ChainSelect\Handler\NumberComparatorHandler::lessThan
     * @covers \MacFJA\ChainSelect\Handler\NumberComparatorHandler::lessOrEquals
     */
    public function testStaticConstructors()
    {
        static::assertEquals(
            new NumberComparatorHandler(NumberComparatorHandler::EQUALS_TO, 0),
            NumberComparatorHandler::equalsTo(0)
        );

        static::assertEquals(
            new NumberComparatorHandler(NumberComparatorHandler::GREATER_THAN, 0),
            NumberComparatorHandler::greaterThan(0)
        );

        static::assertEquals(
            new NumberComparatorHandler(NumberComparatorHandler::GREATER_OR_EQUALS, 0),
            NumberComparatorHandler::greaterOrEquals(0)
        );

        static::assertEquals(
            new NumberComparatorHandler(NumberComparatorHandler::LESS_THAN, 0),
            NumberComparatorHandler::lessThan(0)
        );

        static::assertEquals(
            new NumberComparatorHandler(NumberComparatorHandler::LESS_OR_EQUALS, 0),
            NumberComparatorHandler::lessOrEquals(0)
        );
    }

    /**
     * @param $operator
     * @dataProvider dataProvider
     * @expectedException \InvalidArgumentException
     * @covers \MacFJA\ChainSelect\Handler\NumberComparatorHandler::__construct
     */
    public function testConstructorFail($operator)
    {
        new NumberComparatorHandler($operator, 0);
    }

    public function dataProvider($testName)
    {
        if ($testName === 'testAccept') {
            return [
                ['0', true],
                ['2e2', true],
                [10, true],
                [0, true],
                [false, false],
                [true, false],
                ['hello', false]
            ];
        }
        if ($testName === 'testConstructorFail') {
            return [
                [1],
                [0],
                [false],
                [true],
                ['<>']
            ];
        }
        if ($testName === 'testExecute') {
            return [
                [NumberComparatorHandler::EQUALS_TO, 0, 0, true],
                [NumberComparatorHandler::EQUALS_TO, 2, 0, false],
                [NumberComparatorHandler::GREATER_THAN, 2, 0, false],
                [NumberComparatorHandler::GREATER_THAN, 2, 4, true],
                [NumberComparatorHandler::GREATER_THAN, 2, 2, false],
                [NumberComparatorHandler::GREATER_OR_EQUALS, 2, 0, false],
                [NumberComparatorHandler::GREATER_OR_EQUALS, 2, 4, true],
                [NumberComparatorHandler::GREATER_OR_EQUALS, 2, 2, true],
                [NumberComparatorHandler::LESS_THAN, 2, 0, true],
                [NumberComparatorHandler::LESS_THAN, 2, 4, false],
                [NumberComparatorHandler::LESS_THAN, 2, 2, false],
                [NumberComparatorHandler::LESS_OR_EQUALS, 2, 0, true],
                [NumberComparatorHandler::LESS_OR_EQUALS, 2, 4, false],
                [NumberComparatorHandler::LESS_OR_EQUALS, 2, 2, true]
            ];
        }
        return [];
    }
}
