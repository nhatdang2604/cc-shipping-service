<?php

namespace App\Actions\V1;

use App\Models\Item;
use App\Models\Order;

class ValidateOrderAction
{
    public function execute(Order $order): array
    {
        $errors = [];
        
        [$isValid, $error] = $this->validateOrder($order);
        if (!$isValid) {
            $errors[] = $error;
        }

        $items = $order->getItems();
        foreach ($items as $item) {
            [$isValid, $error] = $this->validateItemWeight($item);
            if (!$isValid) {
                $errors[] = $error;
            }

            [$isValid, $error] = $this->validateItemDimension($item);
            if (!$isValid) {
                $errors[] = $error;
            }
        }

        $isValid = empty($errors);
        return [$isValid, $errors];
    }

    private function validateOrder(Order $order): array
    {
        $isValid = count($order->getItems()) > 0;
        $error   = $isValid ? null : 'Order must have at least one item';
        
        return [$isValid, $error];
    }

    private function validateItemWeight(Item $item): array
    {
        $isValid = bccomp($item->getWeight(), '0') > 0;
        $error   = $isValid ? null : 'Weight of item must be greater than 0';
        
        return [$isValid, $error];
    }

    private function validateItemDimension(Item $item): array
    {
        $isValidWidth  = bccomp($item->getWidth(), '0') > 0;
        $isValidHeight = bccomp($item->getHeight(), '0') > 0;
        $isValidDepth  = bccomp($item->getDepth(), '0') > 0;
        $isValid       = $isValidWidth && $isValidHeight && $isValidDepth;
        $error         = $isValid ? null : 'Dimension of item must be greater than 0';
        
        return [$isValid, $error];
    }
}
