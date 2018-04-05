<?php
namespace MacFJA\ChainSelect\Handler;

use MacFJA\ChainSelect\HandlerInterface;
use MacFJA\ChainSelect\HandlerResult;

/**
 * This handler calculate the difference between the context and the reference text.
 *
 * @author  MacFJA
 * @license MIT
 */
class SimilarTextHandler implements HandlerInterface
{
    /** @var string */
    private $reference;

    /**
     * SimilarTextHandler constructor.
     *
     * @param string $reference
     * @codeCoverageIgnore
     */
    public function __construct(string $reference)
    {
        $this->reference = $reference;
    }

    public function execute($context): HandlerResult
    {
        similar_text($context, $this->reference, $percent);
        
        return new HandlerResult($percent/100, $this->reference, $context, $this);
    }

    public function accept($context): bool
    {
        return is_string($context);
    }
}
