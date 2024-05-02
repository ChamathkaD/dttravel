<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TravelPackageDetailResource;
use App\Http\Resources\TravelPackageResource;
use App\Models\TravelDestination;
use App\Models\TravelPackage;
use Illuminate\Http\Request;

class TravelPackageController extends Controller
{
    final public function index(Request $request)
    {
        $query = TravelPackage::query();

        if ($request->category) {
            $query->where('travel_category_id', $request->category);
        }

        if ($request->destination) {
            $query->where('travel_destination_id', $request->destination);
        }

        if ($request->type) {
            if ($request->type == 'sri-lanka') {
                $sl = TravelDestination::whereName('SRI LANKA')->first();
                $inSl = TravelDestination::whereParentId($sl->id)->pluck('id')->toArray();
                $inSl[] = $sl->id;
                $destinationList = $inSl;
                $query->whereIn('travel_destination_id', $destinationList);

            } elseif ($request->type == 'international') {
                $sl = TravelDestination::whereName('SRI LANKA')->first();
                $outSl = TravelDestination::where('id', '!=', $sl->id)->pluck('id')->toArray();
                $destinationList = $outSl;
                $query->whereIn('travel_destination_id', $destinationList);
            }
        }

        if (! empty($request->top_rated)) {
            $query->where('top_rated', 1);
        }

        $travel_packages = $query->get();

        return response()->json([
            'success' => true,
            'code' => 200,
            'message' => 'Travel packages retrieved successfully',
            'data' => TravelPackageResource::collection($travel_packages),
        ]);
    }

    final public function show(TravelPackage $travelPackage)
    {
        return new TravelPackageDetailResource($travelPackage);
    }

    public function searchPackage(Request $request)
    {
        $category = $request->category;
        $destination = $request->destination;

        $query = TravelPackage::query();

        if (! empty($category)) {
            $query->whereTravelCategoryId($category);
        }

        if (! empty($destination)) {
            $query->whereTravelDestinationId($destination);
        }

        $packages = $query->get();

        return response()->json([
            'success' => true,
            'code' => 200,
            'message' => 'Travel packages retrieved successfully',
            'data' => TravelPackageResource::collection($packages),
        ]);

    }
}
