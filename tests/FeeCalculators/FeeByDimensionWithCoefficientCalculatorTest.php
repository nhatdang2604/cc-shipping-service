<?php

namespace Tests\FeeCalculators;

use App\Configs\Config;
use App\FeeCalculators\FeeByDimensionWithCoefficientCalculator;
use App\Models\Item;

use PHPUnit\Framework\TestCase;

class FeeByDimensionWithCoefficientCalculatorTest extends TestCase
{
    public function setUp(): void
    {
        $config = Config::get();
        bcscale($config['bc_scale']);
    }

    public function testHappyCase()
    {
        $calculator = new FeeByDimensionWithCoefficientCalculator('1.0');
        $item = new Item('1', '1', '1', '1', '1');

        $actual   = $calculator->calculate($item);
        $expected = '1.0';
        $isEqual  = bccomp($actual, $expected) === 0;
        $this->assertTrue($isEqual);
    }

    public function testZeroCoefficient()
    {
        $calculator = new FeeByDimensionWithCoefficientCalculator('0');
        $item = new Item('1', '1', '1', '1', '1');

        $actual   = $calculator->calculate($item);
        $expected = '0';
        $isEqual  = bccomp($actual, $expected) === 0;
        $this->assertTrue($isEqual);
    }

    public function testFloatNegativeCoefficient()
    {
        $calculator = new FeeByDimensionWithCoefficientCalculator('-1.553');
        $item = new Item('1', '1', '1.553', '2.13', '3.14');

        $actual   = $calculator->calculate($item);
        $expected = '-16.1306609';
        $isEqual  = bccomp($actual, $expected) === 0;
        $this->assertTrue($isEqual);
    }

    public function testFloatPositiveCoefficient()
    {
        $calculator = new FeeByDimensionWithCoefficientCalculator('1.553');
        $item = new Item('1', '1', '1.553', '2.13', '3.14');

        $actual = $calculator->calculate($item);
        $expected = '16.1306609';
    
        $isEqual = bccomp($actual, $expected) === 0;
        $this->assertTrue($isEqual);
    }

    public function testLargeCoefficient()
    {
        $calculator = new FeeByDimensionWithCoefficientCalculator('99999999999999999999999');
        $item = new Item('1', '1', '10000000000', '10000000000', '10000000000');

        $actual = $calculator->calculate($item);
        $expected = '99999999999999999999999000000000000000000000000000000';

        $isEqual = bccomp($actual, $expected) === 0;
        $this->assertTrue($isEqual);
    }

    public function testLargeNegativeCoefficient()
    {
        $calculator = new FeeByDimensionWithCoefficientCalculator('-99999999999999999999999');
        $item = new Item('1', '1', '10000000000', '10000000000', '10000000000');

        $actual = $calculator->calculate($item);
        $expected = '-99999999999999999999999000000000000000000000000000000';

        $isEqual = bccomp($actual, $expected) === 0;
        $this->assertTrue($isEqual);
    }
}