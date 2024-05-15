<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\Deal;
use App\Exports\DealsExport;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ReportApiController extends Controller
{

    public function generate(Request $request)
    {
        DB::enableQueryLog();
        $deals = Deal::query();

        // Dynamically handle incoming request parameters
        $deals->when($request->filled('status'), function ($q) use ($request) {
            return $q->where('status', $request->status);
        })->when($request->filled('sales_person_id'), function ($q) use ($request) {
            return $q->where('user_id', $request->sales_person_id);

            
        })->when($request->filled('priority'), function ($q) use ($request) {
            return $q->where('priority', $request->priority);
        });

        // Handling date range
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $startDate = Carbon::parse($request->start_date)->startOfDay();
            $endDate = Carbon::parse($request->end_date)->endOfDay();
            $deals->whereBetween('created_at', [$startDate, $endDate]);
        }

        // Execute the query with eager loading of relationships
        $deals = $deals->with(['user', 'branches', 'services', 'industries'])->get();
        // dd(DB::getQueryLog()); // Debug to check SQL queries generated

        // Generate the Excel file and save it
        Excel::store(new DealsExport($deals), 'public/report.xlsx');

        // Return the path or a token linked to the file
        return response()->json(['url' => asset('storage/report.xlsx')]);
    }



    // public function generate(Request $request)
    // {
    //     DB::enableQueryLog();
    //     $deals = Deal::query();

    //     // $deals->when($request->status, function ($q) use ($request) {
    //     //     return  $q->where('status', $request->status);
    //     // })->when(!empty($request->service_ids), function ($q) use ($request) {
    //     //     $q->where('service_ids', $request->service_ids);
    //     // });

    //     if ($request->has('sales_person_id')) {
    //         $query->whereIn('sales_person_id', $request->sales_person_id);
    //     }

    //     if ($request->has('branch_id')) {
    //         $query->whereIn('branch_id', $request->branch_id);
    //     }

    //     if ($request->has('status')) {
    //         $query->whereIn('status', $request->status);
    //     }

    //     if ($request->has('industry_id')) {
    //         $query->whereIn('industry_id', $request->industry_id);
    //     }

    //     if ($request->has('service_id')) {
    //         $query->whereIn('service_id', $request->service_id);
    //     }

    //     if ($request->has('start_date')) {
    //         $query->whereDate('created_at', '>=', $request->start_date);
    //     }

    //     if ($request->has('end_date')) {
    //         $query->whereDate('created_at', '<=', $request->end_date);
    //     }

    //     if ($request->has('priority')) {
    //         $query->whereIn('priority', $request->priority);
    //     }


    //     // Execute the query with eager loading of relationships
    //     $deals = $deals->with(['user', 'branches', 'services', 'industries'])->get();
    //     //  dd($deals);
    //     dd(DB::getQueryLog());
    //     // Generate the Excel file and save it to a public directory
    //     //  Excel::store(new DealsExport($request->all()), 'public/' .  'report.xlsx' );
    //     Excel::store(new DealsExport($deals), 'public/' .  'report.xlsx');


    //     // Return the path or a token linked to the file
    //     return response()->json(['url' => asset('storage/' .  'report.xlsx')]);
    // }



}

