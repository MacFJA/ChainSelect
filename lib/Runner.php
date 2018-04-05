<?php
namespace MacFJA\ChainSelect;

/**
 * Class Runner.
 *
 * This class is the main entry point of the lib.
 * It run a list of handlers on a specific context
 * and find either the best result of the first result with an acceptable score.
 *
 * @author  MacFJA
 * @license MIT
 */
class Runner
{
    const DEFAULT_ACCEPTABLE_SCORE = 0.5;
    /** @var HandlerInterface[] */
    private $handlers = [];
    /** @var float The minimum score to accept */
    private $minAcceptableScore = self::DEFAULT_ACCEPTABLE_SCORE;
    /** @var mixed The context to pass to every handlers */
    private $context;

    /**
     * Set the score value that will be used for finding the first acceptable result
     *
     * @see Runner::getFirstAcceptable
     *
     * @param float $minAcceptableScore
     * @return void
     * @throws \InvalidArgumentException If {@code $minAcceptableScore} in not in ]0.0, 1.0] range
     */
    public function setMinAcceptableScore(float $minAcceptableScore)
    {
        if ($minAcceptableScore <= 0.0 || $minAcceptableScore > 1.0) {
            throw new \InvalidArgumentException('$minAcceptableScore must be in ]0.0, 1.0]');
        }
        $this->minAcceptableScore = $minAcceptableScore;
    }

    /**
     * Add a new handler to the list of handlers of the Runner.
     * The order of addition is the order used by the Runner to execute handlers
     *
     * @param HandlerInterface $handler
     * @return void
     * @codeCoverageIgnore
     */
    public function addHandler(HandlerInterface $handler)
    {
        $this->handlers[] = $handler;
    }

    /**
     * Check if a score match the acceptation level
     *
     * @param float $score
     * @return bool
     */
    public function isAcceptable(float $score): bool
    {
        return $score >= $this->minAcceptableScore;
    }

    /**
     * Set the context.
     *
     * The context will be pass to each handler.
     * The context will also be found in the HandlerResult
     *
     * @param mixed $context
     * @codeCoverageIgnore
     * @return void
     */
    public function setContext($context)
    {
        $this->context = $context;
    }

    /**
     * Find the best result.
     *
     * The {@code $acceptableRequire} change when the Runner will stop its search.
     * When set to {@code true} the Runner will try to find the first result that match the minimum acceptable score,
     * otherwise the best result is return.
     *
     * @param bool $acceptableRequire If {@code true}, the first acceptable result will be return,
     *                                If no result is found then a exception is thrown.
     *                                If {@code false}, the best result will be return
     * @return HandlerResult
     * @throws NoResultException If no result is found
     */
    private function findAGoodResult(bool $acceptableRequire): HandlerResult
    {
        $bestScore = new NullHandlerResult();

        foreach ($this->handlers as $handler) {
            if (!$handler->accept($this->context)) {
                continue;
            }

            $score = $handler->execute($this->context);
            $bestScore = HandlerResult::bestOf($score, $bestScore);

            if ($acceptableRequire && $this->isAcceptable($bestScore->getScore())) {
                return $bestScore;
            }
        }

        if ($bestScore instanceof NullHandlerResult || $acceptableRequire) {
            throw new NoResultException();
        }

        return $bestScore;
    }

    /**
     * Get the best result (higher score).
     *
     * Execute every handler. Stop on the first with a score at 1.0.
     * If no result have a score to 1.0, the best is return.
     *
     * @return HandlerResult
     * @throws NoResultException If there are no handler, or if every handler don't support the context
     */
    public function getBestMatch(): HandlerResult
    {
        return $this->findAGoodResult(false);
    }

    /**
     * Get the first result that have a score greater or equals to the minimum acceptable score.
     *
     * Execute every handler in the adding order, and return the first that have a sufficient score.
     * If no result match the requirement, an {@link NoResultException} exception is thrown.
     *
     * @return HandlerResult
     * @throws NoResultException If there are no handler, or if every handler don't support the context,
     *                           or no result have a sufficient score.
     */
    public function getFirstAcceptable(): HandlerResult
    {
        return $this->findAGoodResult(true);
    }
}
