<?php

namespace App\Models;

class Item
{
    private string $amazonPrice; // USD
    private string $weight;      // kg
    private string $width;       // cm
    private string $height;      // cm
    private string $depth;       // cm

    public function __construct(
        string $amazonPrice,
        string $weight,
        string $width,
        string $height,
        string $depth
    ) {
        $this->amazonPrice = $amazonPrice;
        $this->weight = $weight;
        $this->width = $width;
        $this->height = $height;
        $this->depth = $depth;
    }

    public function getAmazonPrice(): string {
        return $this->amazonPrice;
    }

    public function getWeight(): string {
        return $this->weight;
    }

    public function getWidth(): string {
        return $this->width;
    }

    public function getHeight(): string {
        return $this->height;
    }

    public function getDepth(): string {
        return $this->depth;
    }
}
