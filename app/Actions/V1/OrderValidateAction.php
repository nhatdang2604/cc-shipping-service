<?php

namespace App\Actions\V1;

use App\Models\Item;
use App\Models\Order;

class OrderValidateAction
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

            [$isValid, $error] = $this->validateItemPrice($item);
            if (!$isValid) {
                $errors[] = $error;
            }

            [$isValid, $error] = $this->validateItemWeight($item);
            if (!$isValid) {
                $errors[] = $error;
            }

            [$isValid, $dimensionErrors] = $this->validateItemDimension($item);
            if (!$isValid) {
                $errors = array_merge($errors, $dimensionErrors);
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

    private function validateItemPrice(Item $item): array
    {
        $isValid = true;

        $isValid = $this->validateNumeric($item->getAmazonPrice());
        if (!$isValid) {
            return [$isValid, 'Price of item must be a number'];
        }

        $isValid = $this->validateLargerOrEqualZero($item->getAmazonPrice());
        if (!$isValid) {
            return [$isValid, 'Price of item must be greater than or equal to 0'];
        }
        
        return [$isValid, null];
    }

    private function validateNumeric(string $value): bool
    {
        return is_numeric($value);
    }

    private function validateLargerOrEqualZero(string $value): bool
    {
        return bccomp($value, '0') >= 0;
    }

    private function validateLargerThanZero(string $value): bool
    {
        return bccomp($value, '0') > 0;
    }

    private function validateItemWeight(Item $item): array
    {
        $isValid = true;

        $isValid = $this->validateNumeric($item->getWeight());
        if (!$isValid) {
            return [$isValid, 'Weight of item must be a number'];
        }

        $isValid = $this->validateLargerThanZero($item->getWeight());
        if (!$isValid) {
            return [$isValid, 'Weight of item must be greater than 0'];
        }
        
        return [$isValid, null];
    }

    private function validateItemDimension(Item $item): array
    {
        $isValid = true;
        $errors  = [];

        $toValidates = [
            'Width'  => $item->getWidth(),
            'Height' => $item->getHeight(),
            'Depth'  => $item->getDepth(),
        ];

        foreach ($toValidates as $key => $value) {
            $isValid = $this->validateNumeric($value);
            
            if (!$isValid) {
                $errors[] = "{$key} of item must be a number";
                continue;
            }

            $isValid = $this->validateLargerThanZero($value);
            if (!$isValid) {
                $errors[] = "{$key} of item must be greater than 0";
                continue;
            }
        }

        return [$isValid, $errors];
    }
}
