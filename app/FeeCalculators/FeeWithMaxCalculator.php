<?php

namespace App\FeeCalculators;

use App\Models\Item;

class FeeWithMaxCalculator implements FeeCalculator
{
    public const ID = 'fee-with-max';

    /** @var FeeCalculator[] */
    private array $calculators;

    public function __construct(array $calculators)
    {
        $this->calculators = $calculators;
    }

    /** @Override **/
    public function getId(): string
    {
        return self::ID;
    }

    /** @Override **/
    public function calculate(Item $item): string
    {
        if (empty($this->calculators)) {
            throw new \InvalidArgumentException('No fees provided');
        }

        $fees = array_map(function ($calculator) use ($item) {
            return $calculator->calculate($item);
        }, $this->calculators);

        return $this->findMax($fees);
    }

    /**
     * @param string[] $fees
     * @return string
     */
    private function findMax(array $fees): string
    {
        $max = $fees[0];
        foreach ($fees as $fee) {
            if (bccomp($fee, $max) > 0) { // If $fee > $max
                $max = $fee;
            }
        }

        return $max;
    }
}
