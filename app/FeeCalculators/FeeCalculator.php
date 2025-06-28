<?php

namespace App\FeeCalculators;

use App\Models\Item;

interface FeeCalculator
{
    public function id(): string;
    public function calculate(Item $item): string;
}
