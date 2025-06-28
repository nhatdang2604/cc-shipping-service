<?php

namespace App\Controllers;

use App\Actions\V1\FindCalculatorToApplyAction;
use App\Actions\V1\GrossPriceCalculateAction;
use App\Actions\V1\OrderValidateAction;
use App\Configs\Config;
use App\Models\Order;
use Throwable;

class MainController
{
    private array $config;
    private OrderValidateAction $orderValidateAction;
    private FindCalculatorToApplyAction $findCalAction;
    private GrossPriceCalculateAction $calPriceAction;

    public function __construct()
    {
        $this->config              = Config::get();
        $this->orderValidateAction = new OrderValidateAction();
        $this->findCalAction       = new FindCalculatorToApplyAction();
        $this->calPriceAction      = new GrossPriceCalculateAction();
    }

    /**
     * @param Order $order
     * @return array [isValid, grossPrice]
     */
    public function getGrossPrice(Order $order): string
    {
        
        try {
        
            // Validate order
            [$isValid, $errors] = $this->orderValidateAction->execute($order);
            if (!$isValid) {
                $toRender = array_merge(["Order validation failed:"], $errors);
                $toRender = implode(PHP_EOL, $toRender);
                return $this->println($toRender);
            }

            // Find fee calculator
            $feeCalculator = $this->findCalAction->execute($this->config);
            $this->calPriceAction->setFeeCalculator($feeCalculator);

            // Calculate gross price
            $grossPrice  = $this->calPriceAction->execute($order);

            // Print items
            $successResponse = $this->buildSuccessResponse($order->getItems(), $grossPrice);
            return $this->println($successResponse);
        } catch (Throwable $e) {
            $toRender = "Error: {$e->getMessage()}";
            return $this->println($toRender);
        }
    }

    private function buildSuccessResponse(array $items, string $grossPrice): string
    {
        $itemsStr = array_map(
            function ($item, $idx) {
                return "Item {$idx}: {$item->toJson()}";
            },
            $items,
            array_keys($items)
        );

        $toRender  = implode(PHP_EOL, $itemsStr);
        $toRender .= PHP_EOL;
        $toRender .= "Gross Price: {$grossPrice}";

        return $toRender;
    }

    private function println(string $str): string
    {
        return nl2br($str);
    }
}