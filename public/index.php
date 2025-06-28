<?php

declare(strict_types=1);
require_once '../vendor/autoload.php';

use App\Controllers\MainController;
use App\Models\Order;
use App\Models\Item;

$order = new Order([
    new Item('10.513', '2.5', '10', '5', '3'),
    new Item('15.75', '1.8', '8', '4', '2'),
    new Item('15.75', '1.8', '8', '4', '2'),
    new Item('999999999999999999.125', '2.5', '10', '5', '3'),
]);

$mainController = new MainController();
echo $mainController->getGrossPrice($order);