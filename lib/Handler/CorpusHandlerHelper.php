<?php
namespace MacFJA\ChainSelect\Handler;

use MacFJA\ChainSelect\Runner;

/**
 * This helper class simplify the add of {@see SimilarTextHandler}
 *
 * @author  MacFJA
 * @license MIT
 */
class CorpusHandlerHelper
{
    /** @var array */
    private $possibilities = [];

    /**
     * Add a reference text to compare
     *
     * @param string $possibility
     * @return void
     * @codeCoverageIgnore
     */
    public function addPossibility(string $possibility)
    {
        $this->possibilities[] = $possibility;
    }

    /**
     * Set all text to compare
     *
     * @param array $possibilities
     * @return void
     * @codeCoverageIgnore
     */
    public function setPossibilities(array $possibilities)
    {
        $this->possibilities = $possibilities;
    }

    /**
     * Add every possible text as an handler on a Runner
     *
     * @param Runner $runner
     * @return Runner
     * @codeCoverageIgnore
     */
    public function appendHandlers(Runner $runner): Runner
    {
        foreach ($this->possibilities as $possibility) {
            $runner->addHandler(new SimilarTextHandler($possibility));
        }
        return $runner;
    }

    /**
     * Shorthand method of add a corpus of text to a Runner.
     *
     * It will add an handler of every string of the corpus.
     * The addition is done in the same order than the corpus content.
     *
     * @param Runner $runner
     * @param array  $corpus
     * @return Runner
     */
    public static function addCorpus(Runner $runner, array $corpus): Runner
    {
        $corpusHandler = new static();
        $corpusHandler->setPossibilities($corpus);
        return $corpusHandler->appendHandlers($runner);
    }
}
