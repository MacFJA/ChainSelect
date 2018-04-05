<?php
namespace MacFJA\ChainSelect;

/**
 * Interface HandlerInterface.
 *
 * This interface describe what an handler must be capable of.
 *
 * @author  MacFJA
 * @license MIT
 */
interface HandlerInterface
{
    /**
     * Run the handler
     *
     * @param mixed $context
     * @return HandlerResult
     */
    public function execute($context): HandlerResult;

    /**
     * Check if the handler support the context
     *
     * @param mixed $context
     * @return bool
     */
    public function accept($context): bool;
}
