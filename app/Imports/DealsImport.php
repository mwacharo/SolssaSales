<?php

namespace App\Imports;

use App\Models\Deal;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;


class DealsImport implements ToCollection
{
    private $propertyId;

    public function __construct()
    {
    }

    /**
     * @param Collection $rows
     * 
     */
    public function collection(Collection $rows)
    {
        foreach ($rows as $key => $row) {
            // Skip the first row (headers)
            if ($key === 0) {
                continue;
            }
            Deal::firstOrCreate([
                'title' => $row[0],
                'email' => $row[1],
                'website' => $row[2],
                'user_id' => $row[3],
                'deal_source' => $row[4],
                'phone' => $row[5],
                'status' => $row[6],
                'comment' => $row[7],
                'priority' => $row[8],
                'contact_person' => $row[9],
                'close_date' => $row[10],
                'created_at' => $row[11],
                'updated_at' => $row[12],




                // 'user_id' => auth()->id(), // Assuming user_id is the authenticated user's id
            ]);
        }
    }
}
