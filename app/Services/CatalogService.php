<?php

namespace App\Services;

use App\Models\Product;

class CatalogService
{
    public function getCatalog()
    {
        return Product::all();
    }

}