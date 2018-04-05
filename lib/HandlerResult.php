<?php
namespace MacFJA\ChainSelect;

/**
 * Class HandlerResult
 *
 * This class is the representation of the result of an handler on a specific context.
 * It hold the score, the context, the handler result and the handler
 *
 * @author  MacFJA
 * @license MIT
 */
class HandlerResult
{
    /** @var float */
    private $score;
    /** @var mixed */
    private $result;
    /** @var mixed */
    private $context;
    /** @var HandlerInterface */
    private $handler;

    /**
     * HandlerResult constructor.
     *
     * @param float            $score
     * @param mixed            $result
     * @param mixed            $context
     * @param HandlerInterface $handler
     *
     * @codeCoverageIgnore
     */
    public function __construct(float $score, $result, $context, HandlerInterface $handler)
    {
        $this->score = $score;
        $this->result = $result;
        $this->context = $context;
        $this->handler = $handler;
    }

    /**
     * Get the score of the context within the handler
     * @return float
     * @codeCoverageIgnore
     */
    public function getScore(): float
    {
        return $this->score;
    }

    /**
     * Get the result of the handler with the context
     * @return mixed
     * @codeCoverageIgnore
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * Get the context used by the handler
     * @return mixed
     * @codeCoverageIgnore
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * The handler that generated this result
     * @return HandlerInterface
     * @codeCoverageIgnore
     */
    public function getHandler(): HandlerInterface
    {
        return $this->handler;
    }
    
    public function isBetterThan(HandlerResult $other): bool
    {
        return $this->score > $other->score;
    }

    public static function bestOf(HandlerResult $first, HandlerResult $second): HandlerResult
    {
        return $first->isBetterThan($second)? $first : $second;
    }
}
