<?php

namespace Tests\FeeCalculators;

use App\Actions\V1\GrossPriceCalculateAction;
use App\Configs\Config;
use App\FeeCalculators\FeeByDimensionWithCoefficientCalculator;
use App\FeeCalculators\FeeByWeightWithCoefficientCalculator;
use App\FeeCalculators\FeeWithMaxCalculator;
use App\Models\Item;
use App\Models\Order;

use PHPUnit\Framework\TestCase;

class GrossPriceCalculateActionTest extends TestCase
{
    public function setUp(): void
    {
        $config = Config::get();
        bcscale($config['bc_scale']);
    }

    public function testHappyCase()
    {
        $order = new Order([
            new Item('1', '1', '1', '1', '1'), // 1 + 11*1*1*1 = 12
            new Item('2', '2', '2', '2', '2'), // 2 + 11*2*2*2 = 88
            new Item('3', '3', '3', '3', '3'), // 3 + 11*3*3*3 = 297
            new Item('4', '4', '4', '4', '4'), // 4 + 11*4*4*4 = 704
            new Item('5', '5', '5', '5', '5'), // 5 + 11*5*5*5 = 1375
        ]);

        // Set fee calculator
        $feeCalculator = new FeeWithMaxCalculator([
            new FeeByWeightWithCoefficientCalculator('11'),
            new FeeByDimensionWithCoefficientCalculator('11'),
        ]);
        $action = new GrossPriceCalculateAction();
        $action->setFeeCalculator($feeCalculator);
        
        $expected = '2490';
        $actual   = $action->execute($order);
        $isEqual  = bccomp($expected, $actual) === 0;
        $this->assertTrue($isEqual);
    }

    public function testOrderWithLargeItem()
    {
        $limit = 5000;
        $items = [];
        foreach (range(1, $limit) as $i) {
            $items[] = new Item($i, $i, '1', '1', '1');
        }
        $order = new Order($items);

        // Set fee calculator
        $feeCalculator = new FeeWithMaxCalculator([
            new FeeByWeightWithCoefficientCalculator('11'),
            new FeeByDimensionWithCoefficientCalculator('11'),
        ]);
        $action = new GrossPriceCalculateAction();
        $action->setFeeCalculator($feeCalculator);
            
        $expected = '150030000';
        $actual   = $action->execute($order);
        $isEqual  = bccomp($expected, $actual) === 0;
        $this->assertTrue($isEqual);
    }

    public function testOrderHasLargeFee()
    {
        $limit = 100000;
        $items = [];
        foreach (range(1, $limit) as $i) {
            $items[] = new Item($i, $i, '1', '1', '1');
        }
        $order = new Order($items);

        // Set fee calculator
        $feeCalculator = new FeeWithMaxCalculator([
            new FeeByWeightWithCoefficientCalculator('999999999'),       // 1 billion
            new FeeByDimensionWithCoefficientCalculator('999999999'), // 1 billion
        ]);
        $action = new GrossPriceCalculateAction();
        $action->setFeeCalculator($feeCalculator);
        
        $expected = '5000050000000000000';
        $actual   = $action->execute($order);
        $isEqual  = bccomp($expected, $actual) === 0;
        $this->assertTrue($isEqual);
    }

    public function testOrderWithWeightFeeCalculator()
    {
        $limit = 100000;
        $items = [];
        foreach (range(1, $limit) as $i) {
            $items[] = new Item($i, $i, '1', '1', '1');
        }
        $order = new Order($items);

        // Set fee calculator
        $feeCalculator = new FeeWithMaxCalculator([
            new FeeByWeightWithCoefficientCalculator('999999999'),       // 1 billion
        ]);
        $action = new GrossPriceCalculateAction();
        $action->setFeeCalculator($feeCalculator);
        
        $expected = '5000050000000000000';
        $actual   = $action->execute($order);
        $isEqual  = bccomp($expected, $actual) === 0;
        $this->assertTrue($isEqual);
    }

    public function testOrderWithDimensionFeeCalculator()
    {
        $order = new Order([
            new Item('1', '1', '1', '1', '1'), // 1 + 11*1*1*1 = 12
            new Item('2', '2', '2', '2', '2'), // 2 + 11*2*2*2 = 88
            new Item('3', '3', '3', '3', '3'), // 3 + 11*3*3*3 = 297
            new Item('4', '4', '4', '4', '4'), // 4 + 11*4*4*4 = 704
            new Item('5', '5', '5', '5', '5'), // 5 + 11*5*5*5 = 1375
        ]);

        // Set fee calculator
        $feeCalculator = new FeeWithMaxCalculator([
            new FeeByDimensionWithCoefficientCalculator('11'),
        ]);
        $action = new GrossPriceCalculateAction();
        $action->setFeeCalculator($feeCalculator);

        $expected = '2490';
        $actual   = $action->execute($order);
        $isEqual  = bccomp($expected, $actual) === 0;
        $this->assertTrue($isEqual);
    }
}