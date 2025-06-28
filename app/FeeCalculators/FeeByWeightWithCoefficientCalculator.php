<?php

namespace App\FeeCalculators;

use App\Models\Item;

class FeeByWeightWithCoefficientCalculator implements FeeCalculator
{
    public const ID = 'fee-by-weight-with-coefficient';

    private string $weightCoefficient;

    public function __construct(string $weightCoefficient)
    {
        $this->weightCoefficient = $weightCoefficient;
    }

    /** @Override **/
    public function getId(): string
    {
        return self::ID;
    }

    /** @Override **/
    public function calculate(Item $item): string
    {
        return bcmul(
            $item->getWeight(),
            $this->weightCoefficient
        );
    }
}
