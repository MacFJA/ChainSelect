<?php
namespace MacFJA\ChainSelect\Tests\Handler;

use MacFJA\ChainSelect\Handler\NotNullHandler;
use PHPUnit\Framework\TestCase;

/**
 * This test class cover the class MacFJA\ChainSelect\Handler\NotNullHandler
 *
 * @author  MacFJA
 * @license MIT
 */
class NotNullHandlerTest extends TestCase
{
    /**
     * @param $value
     * @param $expected
     * @dataProvider dataProvider
     * @covers MacFJA\ChainSelect\Handler\NotNullHandler::execute
     */
    public function testExecute($value, $expected)
    {
        $handler = new NotNullHandler();
        $result = $handler->execute($value);
        static::assertEquals($expected, $result->getResult());
        static::assertEquals($expected?1:0, $result->getScore());
    }

    public function dataProvider()
    {
        return [
            [null, false],
            ['Hello', true],
            [1, true],
            [0, true],
            [new \stdClass(), true]
        ];
    }
}
