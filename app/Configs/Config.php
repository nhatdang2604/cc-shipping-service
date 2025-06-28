<?php

namespace App\Configs;

use App\FeeCalculators\FeeByDimensionWithCoefficientCalculator;
use App\FeeCalculators\FeeByWeightWithCoefficientCalculator;
use App\FeeCalculators\FeeWithMaxCalculator;

class Config
{
    public static function get(): array
    {
        return [
            'bc_scale'   => 7, // precision
            'fee_config' => [
                [
                    'type'   => FeeWithMaxCalculator::ID,
                    'params' => [
                        [
                            'type'   => FeeByWeightWithCoefficientCalculator::ID,
                            'params' => ['11'], // weight coefficient
                        ],
                        [
                            'type'   => FeeByDimensionWithCoefficientCalculator::ID,
                            'params' => ['11'], // dimension coefficient
                        ],
                    ],
                ],
            ],
        ];
    }
}
