<?php

namespace App\Actions\V1;

use App\Factories\FeeCalculatorFactory;
use App\FeeCalculators\FeeCalculator;

class FindCalculatorToApplyAction
{
    public function execute(array $config = []): FeeCalculator
    {
        $this->loadPrecision($config['bc_scale']);
        $feeCalculators = $this->loadFeeCalculator($config['fee_config']);
        
        return $feeCalculators[0];
    }

    private function loadPrecision(int $precision): int
    {
        return bcscale($precision);
    }

    private function loadFeeCalculator(array $config): array
    {
        $feeCalculators = [];
        foreach ($config as $calculatorConfig) {
            $type             = $calculatorConfig['type'];
            $params           = $calculatorConfig['params'];
            $feeCalculators[] = FeeCalculatorFactory::create($type, $params);
        }

        return $feeCalculators;
    }
}
