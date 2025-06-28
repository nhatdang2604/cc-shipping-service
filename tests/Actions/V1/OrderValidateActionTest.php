<?php

namespace Tests\FeeCalculators;

use App\Actions\V1\OrderValidateAction;
use App\Configs\Config;
use App\Models\Item;
use App\Models\Order;

use PHPUnit\Framework\TestCase;

class OrderValidateActionTest extends TestCase
{
    public function setUp(): void
    {
        $config = Config::get();
        bcscale($config['bc_scale']);
    }

    public function testHappyCase()
    {
        $order = new Order([
            new Item('1', '1', '1', '1', '1'),
            new Item('2', '2', '2', '2', '2'),
            new Item('3', '3', '3', '3', '3'),
            new Item('4', '4', '4', '4', '4'),
        ]);

        $action = new OrderValidateAction();
        $expected = [true, []];
        $actual   = $action->execute($order);

        $this->assertEquals($expected, $actual);
    }

    public function testEmptyOrder()
    {
        $order    = new Order([]);
        $action   = new OrderValidateAction();
        $expected = [false, ['Order must have at least one item']];
        $actual   = $action->execute($order);
        
        $this->assertEquals($expected, $actual);
    }

    public function testItemNonNumericFields()
    {
        $order = new Order([
            new Item('abc', 'abc', 'abc', 'abc', 'abc'),
        ]);

        $action   = new OrderValidateAction();
        $expected = [false, [
            'Price of item must be a number',
            'Weight of item must be a number',
            'Width of item must be a number',
            'Height of item must be a number',
            'Depth of item must be a number',
        ]];
        $actual   = $action->execute($order);

        $this->assertEquals($expected, $actual);
    }

    public function testItemHasZeroFields()
    {
        $order = new Order([
            new Item('0', '0', '0', '0', '0'),
        ]);

        $action   = new OrderValidateAction();
        $expected = [false, [
            'Weight of item must be greater than 0',
            'Width of item must be greater than 0',
            'Height of item must be greater than 0',
            'Depth of item must be greater than 0',
        ]];
        $actual = $action->execute($order);

        $this->assertEquals($expected, $actual);
    }

    public function testItemHasFloatNegativeFields()
    {
        $order = new Order([
            new Item('-1.12321', '-1.12321', '-1.12321', '-1.12321', '-1.12321'),
        ]);

        $action   = new OrderValidateAction();
        $expected = [false, [
            'Price of item must be greater than or equal to 0',
            'Weight of item must be greater than 0',
            'Width of item must be greater than 0',
            'Height of item must be greater than 0',
            'Depth of item must be greater than 0',
        ]];
        $actual = $action->execute($order);

        $this->assertEquals($expected, $actual);
    }

    public function testItemHasFloatPositiveFields()
    {
        $order = new Order([
            new Item('1.12321', '1.12321', '1.12321', '1.12321', '1.12321'),
        ]);

        $action   = new OrderValidateAction();
        $expected = [true, []];
        $actual = $action->execute($order);

        $this->assertEquals($expected, $actual);
    }
}