<?php

declare(strict_types=1);
require_once '../vendor/autoload.php';

use App\Actions\V1\GrossPriceCalculateAction;
use App\Actions\V1\FindCalculatorToApplyAction;
use App\Actions\V1\OrderValidateAction;
use App\Configs\Config;
use App\Models\Order;
use App\Models\Item;

try {

    $order = new Order([
        new Item('10.513', '2.5', '10', '5', '3'),
        new Item('15.75', '1.8', '8', '4', '2'),
        new Item('15.75', '1.8', '8', '4', '2'),
    ]);

    $orderValidateAction = new OrderValidateAction();
    [$isValid, $errors] = $orderValidateAction->execute($order);
    if (!$isValid) {
        $errorMessages = implode(', ', $errors);
        printString("Order validation failed: {$errorMessages}");
        return;
    }
     
    $config         = Config::get();
    $findCalAction  = new FindCalculatorToApplyAction();
    $feeCalculator  = $findCalAction->execute($config);
    $calPriceAction = new GrossPriceCalculateAction($feeCalculator);
    $grossPrice     = $calPriceAction->execute($order);
    
    foreach ($order->getItems() as $idx => $item) {
        printString("Item {$idx}: {$item->toJson()}");
    }
    printString("Gross Price: {$grossPrice}");
    
} catch (Exception $e) {
    printString("Error: {$e->getMessage()}");
}

function printString(string $string): void
{
    echo nl2br($string . PHP_EOL);
}
