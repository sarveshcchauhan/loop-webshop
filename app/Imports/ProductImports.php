<?php

namespace App\Imports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\WithProgressBar;
use Maatwebsite\Excel\Concerns\Importable;

class ProductImports implements ToModel, WithChunkReading, WithProgressBar
{
    use Importable;

    public function model(array $row)
    {
        Log::info($row);
        return new Product([
            'id' => $row[0],
            'product_name' => $row[1],
            'price' => $row[2],
        ]);
    }
    
    public function chunkSize(): int
    {
        return 1000;
    }
}