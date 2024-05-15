<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Imports\DealsImport;
use App\Models\ActivityLog;
use App\Models\Deal;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class DealsApiController extends Controller
{
    public function index()
    {
        $deals = Deal::with('user', 'branches', 'services', 'industries')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'deals' => $deals,
        ]);
    }


    public function store(Request $request)
    {


        // Validate incoming request data
        $validatedData = $request->validate([
            'title' => 'required|string',
            'email' => 'required|email',
            'branch_ids' => 'required|array', // Assuming branch_ids are sent as an array
            'service_ids' => 'required|array', // Assuming service_ids are sent as an array
            'industry_ids' => 'required|array', // Assuming industry_ids are sent as an array
            'website' => 'nullable|string|max:255',
            'deal_source' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'comment' => 'nullable|string',
            'priority' => 'nullable|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'close_date' => 'nullable|date',
            'user_id' => 'required|exists:users,id', // Ensure user_id is required and exists in the use
        ]);


        //  dd($validatedData);
        // Create a new deal instance
        $deal = new Deal();
        $deal->title = $validatedData['title'];
        $deal->email = $validatedData['email'];
        $deal->website = $validatedData['website'];
        $deal->deal_source = $validatedData['deal_source'];
        $deal->phone = $validatedData['phone'];
        $deal->comment = $validatedData['comment'];
        $deal->priority = $validatedData['priority'];
        $deal->contact_person = $validatedData['contact_person'];
        $deal->close_date = $validatedData['close_date'];
        $deal->user_id = $validatedData['user_id'];



        // Save the deal
        $deal->save();

        // Attach branches to the deal
        $deal->branches()->attach($validatedData['branch_ids']);

        // Attach services to the deal
        $deal->services()->attach($validatedData['service_ids']);

        // Attach industries to the deal
        $deal->industries()->attach($validatedData['industry_ids']);

        // Return a response or redirect as needed
        return response()->json(['message' => 'Deal created successfully', 'deal' => $deal], 201);
    }


    public function destroy(Request $request, Deal $deal)
    {
        $deal->delete();

        // Log activity
        $this->logActivity($deal, $request->user_id, 'Deleted');

        return response()->json([
            'message' => 'Deal deleted successfully',
        ]);
    }

    protected function logActivity(Deal $deal, $user_id, $action, $oldData = null)
    {
        $newData = $deal->getAttributes();

        // Log information for debugging
        // Log::info("Logging activity fo r Deal with ID: {$deal->id}, Action: $action, User ID: $user_id");

        // Create ActivityLog
        ActivityLog::create([
            'action' => $action,
            'user_id' => $user_id,
            'model' => Deal::class,
            'model_id' => $deal->id,
            'old_data' => $oldData ? json_encode($oldData) : null,
            'new_data' => json_encode($newData),
        ]);
    }
    // app/Http/Controllers/DealController.php
    public function search(Request $request)
    {
        $deals = Deal::query();

        if ($request->has('service_ids')) {
            $deals->whereHas('services', function ($query) use ($request) {
                $query->whereIn('services.id', $request->input('service_ids'));
            });
        }

        if ($request->has('industry_ids')) {
            $deals->whereHas('industries', function ($query) use ($request) {
                $query->whereIn('industries.id', $request->input('industry_ids'));
            });
        }

        if ($request->has('user_id')) {
            $deals->whereIn('user_id', $request->input('user_id'));
        }

        if ($request->has('branch_ids')) {
            $deals->whereHas('branches', function ($query) use ($request) {
                $query->whereIn('branches.id', $request->input('branch_ids'));
            });
        }

        if ($request->has('close_date')) {
            $endDate = Carbon::parse($request->input('close_date'))->endOfDay();
            $deals->whereDate('close_date', '>=', $endDate);
        }

        // Filter by date ranges
        if ($request->has('start_date') && $request->has('end_date')) {
            $startDate = Carbon::parse($request->input('start_date'))->startOfDay();
            $endDate = Carbon::parse($request->input('end_date'))->endOfDay();
            $deals->whereBetween('created_at', [$startDate, $endDate]);
        }
        if ($request->has('start_date')) {
            $startDate = Carbon::parse($request->input('start_date'))->startOfDay();
            $deals->whereDate('created_at', '>=', $startDate);
        }

        $deals = $deals->with('user', 'branches', 'services', 'industries')->get();
        // dd($deals);

        return response()->json(['deals' => $deals]);
    }




    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|string|max:255',
            'deal_source' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'status' => 'nullable|string|max:255',
            'comment' => 'nullable|string',
            'priority' => 'nullable|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'close_date' => 'nullable|date',
            'branch_ids' => 'nullable|array',
            'branch_ids.*' => 'exists:branches,id',
            'service_ids' => 'nullable|array', // Assuming service_ids are sent as an array
            'service_ids.*' => 'exists:services,id', // Validate service_ids
            'industry_ids' => 'nullable|array', // Assuming industry_ids are sent as an array
            'industry_ids.*' => 'exists:industries,id', // Validate industry_ids
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $deal = Deal::findOrFail($id);

        $dealData = $request->only([
            'title', 'email', 'website', 'deal_source', 'phone',
            'status', 'comment', 'priority', 'contact_person', 'close_date',
        ]);


        // $dealData['user_id'] = auth()->user()->id;

        $oldDealData = $deal->toArray(); // Get the original data before updating

        $deal->update($dealData);

        // Sync branches for the deal
        $deal->branches()->sync($request->input('branch_ids'));

        // Sync services for the deal
        $deal->services()->sync($request->input('service_ids'));

        // Sync industries for the deal
        $deal->industries()->sync($request->input('industry_ids'));

        // Log activity
        // $this->logActivity($deal, 'Updated', $oldDealData);

        return response()->json([
            'deal' => $deal,
            'message' => 'Deal updated successfully',
        ]);
    }


    public function updateStatus(Request $request, Deal $deal)
    {
        $validatedData = $request->validate([
            'status' => 'required|in:Initiated,InProgress,Won,Lost',
        ]);
        $deal->status = $validatedData['status'];
        $deal->save();

        // Log activity
        $this->logActivity($deal, $request->user_id, 'Updated');

        return response()->json(['message' => 'Status updated successfully'], 200);
    }

    public function upload(Request $request)
    {

        $request->validate([
            // 'user_id' => 'required|exists:properties,id',
            'file' => 'required|mimes:xlsx,xls,ods|max:10240', // Adjust the file size limit as needed
        ]);
        //   dd($request);
        $file = $request->file('file');

        try {
            $import = new DealsImport();
            Excel::import($import, $file);

            return response()->json(['message' => ' uploaded successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    
}
