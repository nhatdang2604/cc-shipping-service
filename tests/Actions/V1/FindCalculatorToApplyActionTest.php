<?php

namespace Tests\FeeCalculators;

use App\Actions\V1\FindCalculatorToApplyAction;
use App\Configs\Config;
use App\FeeCalculators\FeeByDimensionWithCoefficientCalculator;
use App\FeeCalculators\FeeByWeightWithCoefficientCalculator;
use App\FeeCalculators\FeeWithMaxCalculator;

use PHPUnit\Framework\TestCase;

class FindCalculatorToApplyActionTest extends TestCase
{

    public function testLoadProperly()
    {
        $action = new FindCalculatorToApplyAction();
        $config = Config::get();
        $feeCalculator = $action->execute($config);

        $this->assertEquals(bcscale(), $config['bc_scale']);
        $this->assertEquals(FeeWithMaxCalculator::ID, $feeCalculator->getId());
    }

    public function testLoadFeeByDimension()
    {
        $action = new FindCalculatorToApplyAction();
        $config = [
            'bc_scale' => 13,
            'fee_config' => [
                [
                    'type'   => FeeByDimensionWithCoefficientCalculator::ID,
                    'params' => ['11'],
                ]
            ]
        ];

        $feeCalculator = $action->execute($config);
        $this->assertEquals(bcscale(), $config['bc_scale']);
        $this->assertEquals(FeeByDimensionWithCoefficientCalculator::ID, $feeCalculator->getId());
    }

    public function testLoadFeeByWeight()
    {
        $action = new FindCalculatorToApplyAction();
        $config = [
            'bc_scale' => 13,
            'fee_config' => [
                [
                    'type'   => FeeByWeightWithCoefficientCalculator::ID,
                    'params' => ['11'],
                ]
            ]
        ];

        $feeCalculator = $action->execute($config);
        $this->assertEquals(bcscale(), $config['bc_scale']);
        $this->assertEquals(FeeByWeightWithCoefficientCalculator::ID, $feeCalculator->getId());
    }
}