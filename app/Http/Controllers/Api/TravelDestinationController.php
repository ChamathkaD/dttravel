<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TravelDestinationResource;
use App\Models\TravelDestination;
use Illuminate\Http\Request;

class TravelDestinationController extends Controller
{
    final public function index(Request $request)
    {
        $type = $request->type;
        $limit = $request->limit;
        $level = $request->level;

        $query = TravelDestination::with(['parent']);

        if ($type == 'sri-lanka') {
            $sl = TravelDestination::whereName('SRI LANKA')->first();
            $query->whereParentId($sl->id);
        } elseif ($type == 'international') {
            $sl = TravelDestination::whereName('SRI LANKA')->first();
            $query->where('id', '!=', $sl->id)->where('parent_id', '!=', $sl->id);
        }

        if (! empty($level)) {
            if ($level == 'level-1') {
                $query->whereNull('parent_id');
            } elseif ($level == 'level-2') {
                $query->whereNotNull('parent_id');
            }
        }

        if (! empty($limit) && $limit > 0) {
            $query->limit($limit);
        }

        return response()->json([
            'success' => true,
            'code' => 200,
            'message' => 'Travel destinations retrieved successfully',
            'data' => TravelDestinationResource::collection($query->has('travelPackages')->get()),
        ]);
    }
}
