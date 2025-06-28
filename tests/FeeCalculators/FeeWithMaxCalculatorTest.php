<?php

namespace Tests\FeeCalculators;

use App\Configs\Config;
use App\FeeCalculators\FeeByDimensionWithCoefficientCalculator;
use App\FeeCalculators\FeeByWeightWithCoefficientCalculator;
use App\FeeCalculators\FeeWithMaxCalculator;
use App\Models\Item;

use PHPUnit\Framework\TestCase;

class FeeWithMaxCalculatorTest extends TestCase
{
    public function setUp(): void
    {
        $config = Config::get();
        bcscale($config['bc_scale']);
    }

    public function testHappyCase()
    {
        $feeByDimension = new FeeByDimensionWithCoefficientCalculator('2.0');
        $feeByWeight    = new FeeByWeightWithCoefficientCalculator('1.0');
        $feeWithMax     = new FeeWithMaxCalculator([$feeByDimension, $feeByWeight]);
        $item           = new Item('1', '1', '1', '1', '1');

        $expected = $feeByDimension->calculate($item);
        $actual   = $feeWithMax->calculate($item);

        $isEqual = bccomp($actual, $expected) === 0;
        $this->assertTrue($isEqual);
    }

    public function testEmptyCalculators()
    {
        $feeWithMax = new FeeWithMaxCalculator([]);
        $item       = new Item('1', '1', '1', '1', '1');
        $this->expectException(\InvalidArgumentException::class);
        $feeWithMax->calculate($item);
    }

    public function testLargeAmountOfCalculators()
    {
        $limit = 100000;
        $calculators = [];
        for ($i = 0; $i < $limit; $i++) {
            $iAsStr = (string) $i;
            $calculators[] = new FeeByDimensionWithCoefficientCalculator($iAsStr);
            $calculators[] = new FeeByWeightWithCoefficientCalculator($iAsStr);
        }
        $feeWithMax = new FeeWithMaxCalculator($calculators);
        $item       = new Item('1', '100000', '100000', '100000', '100000');

        $expected = '99999000000000000000';
        $actual   = $feeWithMax->calculate($item);

        $isEqual = bccomp($actual, $expected) === 0;
        $this->assertTrue($isEqual);
    }

    public function testDeepNesting()
    {
        $limit = 100;
        $calculators = [];
        $preFeeWithMax = null;
        for ($i = 0; $i < $limit; $i++) {
            $iAsStr = (string) $i;
            $feeByDimension = new FeeByDimensionWithCoefficientCalculator($iAsStr);
            $feeByWeight    = new FeeByWeightWithCoefficientCalculator($iAsStr);
            
            if (empty($preFeeWithMax)) {
                $preFeeWithMax = new FeeWithMaxCalculator([$feeByDimension, $feeByWeight]);
            } else {
                $preFeeWithMax = new FeeWithMaxCalculator([$preFeeWithMax, $feeByDimension, $feeByWeight]);
            }
            $calculators[] = $preFeeWithMax;
        }
        $feeWithMax = new FeeWithMaxCalculator($calculators);
        $item       = new Item('1', '100000', '100000', '100000', '100000');

        $expected = '99000000000000000';
        $actual   = $feeWithMax->calculate($item);

        $isEqual = bccomp($actual, $expected) === 0;
        $this->assertTrue($isEqual);
    }
}