<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Industry;
use Illuminate\Http\Request;

class IndustriesApiController extends Controller
{

    public function index()
    {
        $industries = Industry::all();

        return response()->json([
            'industries' => $industries,
        ]);
    }


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $industry = Industry::create([
            'name' => $request->name,
        ]);

        return response()->json([
            'industry' => $industry,
            'message' => 'Industry created successfully',
        ], 201);
    }


    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            // Add other validation rules as needed
        ]);

        $industry = Industry::findOrFail($id);
        $industry->update([
            'name' => $request->name,
            // Add other fields to be updated
        ]);

        return response()->json([
            'industry' => $industry,
            'message' => 'Industry updated successfully',
        ]);
    }


    public function destroy(string $id)
    {
        $industry = Industry::findOrFail($id);
        $industry->delete();

        return response()->json([
            'message' => 'Industry deleted successfully',
        ]);
    }
}