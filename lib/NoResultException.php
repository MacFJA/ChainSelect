<?php
namespace MacFJA\ChainSelect;

/**
 * Class NoResultException.
 *
 * This exception is thrown when no result is found.
 * This can occur if:
 *
 *  - The runner don't have any handler
 *  - Every handler refuse the context
 *  - Not result have a minimum score
 *
 * @author  MacFJA
 * @license MIT
 * @codeCoverageIgnore
 */
class NoResultException extends \Exception
{
}
