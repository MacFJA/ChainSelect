<?php
namespace MacFJA\ChainSelect\Handler;

use MacFJA\ChainSelect\HandlerInterface;
use MacFJA\ChainSelect\HandlerResult;

/**
 * This handler check if the context is null or not.
 *
 * @author  MacFJA
 * @license MIT
 */
class NotNullHandler implements HandlerInterface
{
    public function execute($context): HandlerResult
    {
        $isNull = $context === null;
        return new HandlerResult($isNull === true? 0.0 : 1.0, $isNull === false, $context, $this);
    }

    /**
     * All value are accepted
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
