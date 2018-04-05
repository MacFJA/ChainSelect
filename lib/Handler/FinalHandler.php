<?php
namespace MacFJA\ChainSelect\Handler;

use MacFJA\ChainSelect\HandlerInterface;
use MacFJA\ChainSelect\HandlerResult;

/**
 * Special handler that always return the higher score and a predefined value.
 *
 * This handler can be used as a default value provider (last resort handler)
 *
 * @author  MacFJA
 * @license MIT
 */
class FinalHandler implements HandlerInterface
{
    /** @var mixed */
    private $resultValue;

    /**
     * FinalHandler constructor.
     *
     * @param mixed $resultValue
     * @codeCoverageIgnore
     */
    public function __construct($resultValue)
    {
        $this->resultValue = $resultValue;
    }

    public function execute($context): HandlerResult
    {
        return new HandlerResult(1.0, $this->resultValue, $context, $this);
    }

    /**
     * All values are accepted
     *
     * @param $context
     * @return bool
     * @codeCoverageIgnore
     */
    public function accept($context): bool
    {
        return true;
    }
}
