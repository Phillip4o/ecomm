<?php

namespace App\EventListener;

use App\Entity\Product;

class ProductListener
{
    public function preFlush(Product $product): void
    {
        dd('pre flush');
    }
}
