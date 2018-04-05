<?php
namespace MacFJA\ChainSelect\Handler;

use MacFJA\ChainSelect\HandlerInterface;
use MacFJA\ChainSelect\HandlerResult;

/**
 * This handler compare the context with an other fix number.
 *
 * The supported operators are:
 *
 *  - Greater than: ">"
 *  - Greater or equals to: ">="
 *  - Equals to: "="
 *  - Less than: "<"
 *  - Less or equals to: "<="
 *
 * @author  MacFJA
 * @license MIT
 */
class NumberComparatorHandler implements HandlerInterface
{
    const GREATER_THAN = '>';
    const GREATER_OR_EQUALS = '>=';
    const EQUALS_TO = '=';
    const LESS_THAN = '<';
    const LESS_OR_EQUALS = '<=';

    /**
     * List of compare callable
     * @var array<string,callable>
     */
    private $evaluators = [];

    /** @var string */
    private $operator;
    /** @var int|float */
    private $reference;

    /**
     * NumberComparatorHandler constructor.
     *
     * @param string    $operator
     * @param int|float $reference
     * @throws \InvalidArgumentException
     */
    public function __construct(string $operator, $reference)
    {
        $this->evaluators[static::GREATER_THAN] =
            /**
             * @param int|float $left
             * @param int|float $right
             * @return bool
             */
            function ($left, $right): bool {
                return $left > $right;
            };
        $this->evaluators[static::GREATER_OR_EQUALS] =
            /**
             * @param int|float $left
             * @param int|float $right
             * @return bool
             */
            function ($left, $right): bool {
                return $left >= $right;
            };
        $this->evaluators[static::EQUALS_TO] =
            /**
             * @param int|float $left
             * @param int|float $right
             * @return bool
             */
            function ($left, $right): bool {
                // Not the strict operator, to handle `1.0 == 1`
                return $left == $right;
            };
        $this->evaluators[static::LESS_OR_EQUALS] =
            /**
             * @param int|float $left
             * @param int|float $right
             * @return bool
             */
            function ($left, $right): bool {
                return $left <= $right;
            };
        $this->evaluators[static::LESS_THAN] =
            /**
             * @param int|float $left
             * @param int|float $right
             * @return bool
             */
            function ($left, $right): bool {
                return $left < $right;
            };

        if (!array_key_exists($operator, $this->evaluators)) {
            throw new \InvalidArgumentException();
        }
        $this->operator = $operator;
        $this->reference = $reference;
    }
    
    public function execute($context): HandlerResult
    {
        $comparatorResult = $this->evaluators[$this->operator]($context, $this->reference);
        
        return new HandlerResult($comparatorResult === true? 1.0 : 0.0, $comparatorResult, $context, $this);
    }

    public function accept($context): bool
    {
        return is_numeric($context);
    }

    /**
     * Shorthand method to create an "Greater than" number comparator
     *
     * @param int|float $reference
     * @return NumberComparatorHandler
     */
    public static function greaterThan($reference)
    {
        return new static(static::GREATER_THAN, $reference);
    }

    /**
     * Shorthand method to create an "Greater or equals to" number comparator
     *
     * @param int|float $reference
     * @return NumberComparatorHandler
     */
    public static function greaterOrEquals($reference)
    {
        return new static(static::GREATER_OR_EQUALS, $reference);
    }

    /**
     * Shorthand method to create an "Less than" number comparator
     *
     * @param int|float $reference
     * @return NumberComparatorHandler
     */
    public static function lessThan($reference)
    {
        return new static(static::LESS_THAN, $reference);
    }

    /**
     * Shorthand method to create an "Less or equals to" number comparator
     *
     * @param int|float $reference
     * @return NumberComparatorHandler
     */
    public static function lessOrEquals($reference)
    {
        return new static(static::LESS_OR_EQUALS, $reference);
    }

    /**
     * Shorthand method to create an "Equals to" number comparator
     *
     * @param int|float $reference
     * @return NumberComparatorHandler
     */
    public static function equalsTo($reference)
    {
        return new static(static::EQUALS_TO, $reference);
    }
}
