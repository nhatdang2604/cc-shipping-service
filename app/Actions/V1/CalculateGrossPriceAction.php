<?php

namespace App\Actions\V1;

use App\Models\Order;
use App\FeeCalculators\FeeCalculator;

class CalculateGrossPriceAction
{
    private FeeCalculator $feeCalculator;

    public function __construct(FeeCalculator $feeCalculator)
    {
        $this->feeCalculator = $feeCalculator;
    }

    public function execute(Order $order): string
    {
        $grossPrice = '0';
        foreach ($order->getItems() as $item) {
            $itemPrice  = bcadd($item->getAmazonPrice(), $this->feeCalculator->calculate($item));
            $grossPrice = bcadd($grossPrice, $itemPrice);
        }
        
        return $grossPrice;
    }
}
