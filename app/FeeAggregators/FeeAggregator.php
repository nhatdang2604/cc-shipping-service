<?php

namespace App\FeeAggregators;

use App\FeeCalculators\FeeCalculator;

interface FeeAggregator
{
    public function getAggregatedCalculator(): FeeCalculator;
}
