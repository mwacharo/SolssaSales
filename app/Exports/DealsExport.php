<?php

namespace App\Exports;

use App\Models\Deal;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;

class DealsExport implements FromView
{
    use Exportable;

    public $deals;

    public function __construct($deals)
    {
        $this->deals = $deals;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    // public function collection()
    // {
    //     // return Deal::all();
    //     return $this->deals;

    // }
    
    public function view(): View
    {
        return view('reports.deals', [
            'deals' => $this->deals
        ]);
    }


//     public function headings(): array
//     {
//         return [
//             // 'ID', 'Title', 'Email', 'Phone', 'Status', 'Priority', 'Service', 'Branch', 'Industry'
//  'Title', 'Email', 'Phone', 'Status', 'Priority', 'Service', 'Branch', 'Industry'

//         ];
//     }
}
