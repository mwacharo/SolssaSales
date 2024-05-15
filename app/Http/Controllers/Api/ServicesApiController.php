<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;

class ServicesApiController extends Controller
{
    public function index()
    {

        $services = Service::all();

        return response()->json([
            'services' => $services,
        ]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $service = Service::create($validatedData);

        return response()->json([
            'message' => 'Service created successfully',
            'data' => $service,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $service = Service::find($id);

        if (!$service) {
            return response()->json([
                'message' => 'Service not found',
            ], 404);
        }

        $validatedData = $request->validate([
            'name' => 'string|max:255',
        ]);

        $service->update($validatedData);

        return response()->json([
            'message' => 'Service updated successfully',
            'data' => $service,
        ]);
    }

    public function destroy(string $id)
    {

        $service = Service::find($id);

        if (!$service) {
            return response()->json(['message' => 'Service not found'], 404);
        }

        $service->delete();
        return response()->json(['message' => 'Service deleted successfully'], 204);
    }

    public function statistics()
    {
        // Fetch service data from the database
        $services = Service::all();

        // Define arrays of possible icon and color values
        $icons = ['bx-mobile-alt', 'bx-closet', 'bx-home-alt', 'bx-football'];
        $colors = ['primary', 'success', 'info', 'secondary'];

        // Construct the service statistics array
        $serviceStatistics = [];
        foreach ($services as $service) {
            // Generate random index for icon and color arrays
            $randomIconIndex = array_rand($icons);
            $randomColorIndex = array_rand($colors);

            // Get randomly selected icon and color
            $randomIcon = $icons[$randomIconIndex];
            $randomColor = $colors[$randomColorIndex];

            // Calculate change percentage based on historical data (assuming these fields exist in the Service model)
            $change = $service->current_value - $service->previous_value;
            $changeString = '';
            if ($service->previous_value != 0) {
                $changePercentage = ($change / $service->previous_value) * 100;
                $changeString = ($changePercentage >= 0 ? '+' : '') . number_format($changePercentage, 2) . '% from last month';
            } else {
                // Handle division by zero error
                $changeString = 'N/A (Previous value is zero)';
            }

            // Calculate progress percentage based on current value and target value (assuming these fields exist in the Service model)
            $progressString = '';
            if ($service->target_value != 0) {
                $progressPercentage = ($service->current_value / $service->target_value) * 100;
                $progressString = number_format($progressPercentage, 2) . '%';
            } else {
                // If target value is 0, set progress to 0
                $progressString = '0%';
            }

            // Add the service statistics to the array
            $serviceStatistics[] = [
                'icon' => $randomIcon,
                'color' => $randomColor,
                'title' => $service->name,
                'change' => $changeString,
                'progress' => $progressString,
            ];
        }

        return response()->json(['serviceStatistics' => $serviceStatistics]);
    }

}