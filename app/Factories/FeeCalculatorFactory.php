<?php

namespace App\Factories;

use App\FeeCalculators\FeeByDimensionWithCoefficientCalculator;
use App\FeeCalculators\FeeByWeightWithCoefficientCalculator;
use App\FeeCalculators\FeeCalculator;
use App\FeeCalculators\FeeWithMaxCalculator;

class FeeCalculatorFactory
{
    public static function create(string $type, array $params): FeeCalculator
    {
        switch ($type) {
            case FeeByWeightWithCoefficientCalculator::ID:
                $weightCoefficient = $params[0];
                return new FeeByWeightWithCoefficientCalculator($weightCoefficient);
            case FeeByDimensionWithCoefficientCalculator::ID:
                $dimensionCoefficient = $params[0];
                return new FeeByDimensionWithCoefficientCalculator($dimensionCoefficient);
            case FeeWithMaxCalculator::ID:
                $feesToCompare = [];
                foreach ($params as $param) {
                    $subType         = $param['type'];
                    $subParams       = $param['params'];
                    $feesToCompare[] = self::create($subType, $subParams);
                }
                return new FeeWithMaxCalculator($feesToCompare);
            default:
                throw new \UnexpectedValueException("Invalid fee type: {$type}");
        }
    }
}
