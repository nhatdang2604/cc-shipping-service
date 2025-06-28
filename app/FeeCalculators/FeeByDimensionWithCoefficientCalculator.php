<?php

namespace App\FeeCalculators;

use App\Models\Item;

class FeeByDimensionWithCoefficientCalculator implements FeeCalculator
{
    public const ID = 'fee-by-dimension-with-coefficient';

    private string $dimensionCoefficient;

    public function __construct(string $dimensionCoefficient)
    {
        $this->dimensionCoefficient = $dimensionCoefficient;
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
            bcmul(
                $item->getWidth(),
                $item->getHeight()
            ),
            bcmul(
                $item->getDepth(),
                $this->dimensionCoefficient
            )
        );
    }
}
