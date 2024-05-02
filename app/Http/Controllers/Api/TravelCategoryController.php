<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TravelCategoryResource;
use App\Models\TravelCategory;
use Illuminate\Http\Request;

class TravelCategoryController extends Controller
{
    final public function index(Request $request)
    {
        $limit = $request->limit;
        $level = $request->level;

        $query = TravelCategory::with(['parent']);

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
            'message' => 'Travel categories retrieved successfully',
            'data' => TravelCategoryResource::collection($query->has('travelPackages')->get()),
        ]);
    }
}
