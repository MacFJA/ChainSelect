<?php
namespace MacFJA\ChainSelect\Tests\Handler;

use MacFJA\ChainSelect\Handler\SimilarTextHandler;
use PHPUnit\Framework\TestCase;

/**
 * This test class cover the class MacFJA\ChainSelect\Handler\SimilarTextHandler
 *
 * @author  MacFJA
 * @license MIT
 */
class SimilarTextHandlerTest extends TestCase
{
    /**
     * @param $input
     * @param $expected
     * @covers \MacFJA\ChainSelect\Handler\SimilarTextHandler::accept
     * @dataProvider dataProvider
     */
    public function testAccept($input, $expected)
    {
        $handler = new SimilarTextHandler('');

        static::assertEquals($expected, $handler->accept($input));
    }

    /**
     * @param $reference
     * @param $context
     * @param $score
     * @covers \MacFJA\ChainSelect\Handler\SimilarTextHandler::execute
     * @dataProvider dataProvider
     */
    public function testExecute($reference, $context, $score)
    {
        $handler = new SimilarTextHandler($reference);
        $result = $handler->execute($context);

        static::assertEquals($score, $result->getScore());
        static::assertEquals($reference, $result->getResult());
    }

    public function dataProvider($testName)
    {
        if ($testName === 'testAccept') {
            return [
                ['', true],
                [false, false],
                [null, false],
                [12, false],
                ['Hello World', true]
            ];
        }
        if ($testName === 'testExecute') {
            return [
                ['Hello', 'Hello', 1.0],
                ['Hello', '', 0],
                ['Hello', 'hello', 0.8]
            ];
        }

        return [];
    }
}
