<?php
namespace MacFJA\ChainSelect\Tests\Handler;

use MacFJA\ChainSelect\Handler\CorpusHandlerHelper;
use MacFJA\ChainSelect\Handler\SimilarTextHandler;
use MacFJA\ChainSelect\Runner;
use PHPUnit\Framework\TestCase;

/**
 * This test class cover the class MacFJA\ChainSelect\Handler\CorpusHandlerHelper
 *
 * @author  MacFJA
 * @license MIT
 */
class CorpusHandlerHelperTest extends TestCase
{
    /**
     * @covers MacFJA\ChainSelect\Handler\CorpusHandlerHelper::addCorpus
     */
    public function testAddCorpus()
    {
        $expected = new Runner();
        $expected->addHandler(new SimilarTextHandler('Hello'));
        $expected->addHandler(new SimilarTextHandler('World'));

        $actual = CorpusHandlerHelper::addCorpus(new Runner(), ['Hello', 'World']);

        static::assertEquals($expected, $actual);
    }
}
