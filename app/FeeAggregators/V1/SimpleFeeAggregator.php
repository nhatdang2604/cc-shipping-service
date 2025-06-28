<?php

namespace App\FeeAggregators\V1;

use App\FeeAggregators\FeeAggregator;
use App\FeeCalculators\FeeByDimensionWithCoefficientCalculator;
use App\FeeCalculators\FeeByWeightWithCoefficientCalculator;
use App\FeeCalculators\FeeCalculator;
use App\FeeCalculators\FeeWithMaxCalculator;

class SimpleFeeAggregator implements FeeAggregator
{
    private string $weightCoefficient;
    private string $dimensionCoefficient;

    public function __construct(
        string $weightCoefficient,
        string $dimensionCoefficient
    )
    {
        $this->weightCoefficient    = $weightCoefficient;
        $this->dimensionCoefficient = $dimensionCoefficient;
    }
    
    /**
     * @Override
     */
    public function getAggregatedCalculator(): FeeCalculator
    {
        $feeWithWeight    = new FeeByWeightWithCoefficientCalculator($this->weightCoefficient);
        $feeWithDimension = new FeeByDimensionWithCoefficientCalculator($this->dimensionCoefficient);
        
        return new FeeWithMaxCalculator([
            $feeWithWeight,
            $feeWithDimension,
        ]);
    }
}
