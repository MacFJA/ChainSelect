<?php
namespace MacFJA\ChainSelect\Tests;

use MacFJA\ChainSelect\Handler\NotNullHandler;
use MacFJA\ChainSelect\HandlerResult;
use PHPUnit\Framework\TestCase;

/**
 * This test class cover the class MacFJA\ChainSelect\HandlerResult
 *
 * @author  MacFJA
 * @license MIT
 */
class HandlerResultTest extends TestCase
{
    /**
     * @param $leftScore
     * @param $rightScore
     * @param $expected
     *
     * @dataProvider dataProvider
     * @covers \MacFJA\ChainSelect\HandlerResult::isBetterThan
     */
    public function testComparator($leftScore, $rightScore, $expected)
    {
        $left = new HandlerResult($leftScore, null, null, new NotNullHandler());
        $right = new HandlerResult($rightScore, null, null, new NotNullHandler());

        static::assertEquals($expected, $left->isBetterThan($right));
    }

    /**
     * @covers \MacFJA\ChainSelect\HandlerResult::bestOf
     */
    public function testBestOf()
    {
        $best = new HandlerResult(0.9, null, null, new NotNullHandler()); 
        $worst = new HandlerResult(0.1, null, null, new NotNullHandler()); 
        $average = new HandlerResult(0.5, null, null, new NotNullHandler());
        
        static::assertSame($best, HandlerResult::bestOf($best, $worst));
        static::assertSame($best, HandlerResult::bestOf($worst, $best));
        static::assertSame($best, HandlerResult::bestOf($best, $average));
        static::assertSame($average, HandlerResult::bestOf($worst, $average));
    }
    
    public function dataProvider()
    {
        return [
            [0, 1, false],
            [1, 0, true],
            [0.5, 0, true],
            [0.5, 1, false],
            [0.5, 0.5, false],
        ];
    }
}
