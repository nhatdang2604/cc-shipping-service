<?php

namespace Tests\FeeCalculators;

use App\Configs\Config;
use App\FeeCalculators\FeeByWeightWithCoefficientCalculator;
use App\Models\Item;

use PHPUnit\Framework\TestCase;

class FeeByWeightWithCoefficientCalculatorTest extends TestCase
{
    public function setUp(): void
    {
        $config = Config::get();
        bcscale($config['bc_scale']);
    }

    public function testHappyCase()
    {
        $calculator = new FeeByWeightWithCoefficientCalculator('1.0');
        $item = new Item('1', '1', '1', '1', '1');

        $actual   = $calculator->calculate($item);
        $expected = '1.0';
        $isEqual  = bccomp($actual, $expected) === 0;
        $this->assertTrue($isEqual);
    }

    public function testZeroCoefficient()
    {
        $calculator = new FeeByWeightWithCoefficientCalculator('0');
        $item = new Item('1', '1', '1', '1', '1');

        $actual   = $calculator->calculate($item);
        $expected = '0';
        $isEqual  = bccomp($actual, $expected) === 0;
        $this->assertTrue($isEqual);
    }

    public function testFloatNegativeCoefficient()
    {
        $calculator = new FeeByWeightWithCoefficientCalculator('-1.553');
        $item = new Item('1', '13.43', '1.553', '2.13', '3.14');

        $actual   = $calculator->calculate($item);
        $expected = '-20.85679';
        $isEqual  = bccomp($actual, $expected) === 0;
        $this->assertTrue($isEqual);
    }

    public function testFloatPositiveCoefficient()
    {
        $calculator = new FeeByWeightWithCoefficientCalculator('1.553');
        $item = new Item('1', '1.553', '1.553', '2.13', '3.14');

        $actual = $calculator->calculate($item);
        $expected = '2.411809';
    
        $isEqual = bccomp($actual, $expected) === 0;
        $this->assertTrue($isEqual);
    }

    public function testLargeCoefficient()
    {
        $calculator = new FeeByWeightWithCoefficientCalculator('99999999999999999999999');
        $item = new Item('1', '10000000000', '1', '10000000000', '10000000000');

        $actual = $calculator->calculate($item);
        $expected = '999999999999999999999990000000000';

        $isEqual = bccomp($actual, $expected) === 0;
        $this->assertTrue($isEqual);
    }

    public function testLargeNegativeCoefficient()
    {
        $calculator = new FeeByWeightWithCoefficientCalculator('-99999999999999999999999');
        $item = new Item('1', '10000000000', '10000000000', '10000000000', '10000000000');

        $actual = $calculator->calculate($item);
        $expected = '-999999999999999999999990000000000';

        $isEqual = bccomp($actual, $expected) === 0;
        $this->assertTrue($isEqual);
    }
}