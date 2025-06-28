<?php

declare(strict_types=1);
require_once '../vendor/autoload.php';

use App\Actions\V1\CalculateGrossPriceAction;
use App\Actions\V1\ValidateOrderAction;
use App\FeeAggregators\V1\SimpleFeeAggregator;
use App\Models\Order;
use App\Models\Item;

try {

    $order = new Order([
        new Item('10.50', '2.5', '10', '5', '3'),
        new Item('15.75', '1.8', '8', '4', '2'),
        new Item('15.75', '1.8', '8', '4', '2'),
    ]);

    $validateAction = new ValidateOrderAction();
    [$isValid, $errors] = $validateAction->execute($order);
    if (!$isValid) {
        $errorMessages = implode(', ', $errors);
        echo "Order validation failed: {$errorMessages} \n";
        return;
    }
    
    $feeAggregator       = new SimpleFeeAggregator('2.0', '2.0');
    $feeCalculator       = $feeAggregator->getAggregatedCalculator();
    $calGrossPriceAction = new CalculateGrossPriceAction($feeCalculator);
    $grossPrice          = $calGrossPriceAction->execute($order);
    
    echo "Gross Price: $" . $grossPrice . "\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
