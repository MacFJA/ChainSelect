<?php
namespace MacFJA\ChainSelect\Tests\Handler;

use MacFJA\ChainSelect\Handler\FinalHandler;
use PHPUnit\Framework\TestCase;

/**
 * This test class cover the class MacFJA\ChainSelect\Handler\FinalHandler
 *
 * @author  MacFJA
 * @license MIT
 */
class FinalHandlerTest extends TestCase
{
    /**
     * @param $value
     * @dataProvider dataProvider
     * @covers MacFJA\ChainSelect\Handler\FinalHandler::execute
     */
    public function testExecute($value)
    {
        $handler = new FinalHandler($value);
        $result = $handler->execute($value);
        static::assertEquals($value, $result->getResult());
        static::assertEquals($value, $result->getContext());
        static::assertEquals(1, $result->getScore());
    }

    public function dataProvider()
    {
        return [
            [null],
            [''],
            ['Hello'],
            [1],
            [0],
            [new \stdClass()]
        ];
    }
}
