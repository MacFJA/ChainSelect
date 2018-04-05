<?php
namespace MacFJA\ChainSelect;

/**
 * Class NullHandlerResult
 *
 * This is a special class to represent an empty result.
 * This is use to detect that no result was found.
 *
 * @author  MacFJA
 * @license MIT
 * @internal
 * @codeCoverageIgnore
 */
final class NullHandlerResult extends HandlerResult
{
    public function __construct()
    {
        parent::__construct(0.0, null, null, new class implements HandlerInterface {
            public function execute($context): HandlerResult
            {
                throw new \BadFunctionCallException();
            }

            public function accept($context): bool
            {
                throw new \BadFunctionCallException();
            }
        });
    }
}
