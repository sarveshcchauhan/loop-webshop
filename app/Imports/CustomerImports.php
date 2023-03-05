<?php

namespace App\Imports;
use App\Models\Customer;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\WithProgressBar;
use Maatwebsite\Excel\Concerns\Importable;

class CustomerImports implements ToModel,WithChunkReading, WithProgressBar
{
    use Importable;

    public function model(array $row)
    {
        Log::info($row);
        
        return new Customer([
            'id' => $row[0],
            'job_title' => $row[1],
            'email' => $row[2],
            'full_name' => $row[3],
            'registered_since' => $row[4],
            'phone' => $row[5],
        ]);
    }
    
    public function chunkSize(): int
    {
        return 1000;
    }
}