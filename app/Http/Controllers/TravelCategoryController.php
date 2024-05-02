<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTravelCategoryRequest;
use App\Http\Requests\UpdateTravelCategoryRequest;
use App\Interfaces\TravelCategoryRepositoryInterface;
use App\Models\TravelCategory;

class TravelCategoryController extends Controller
{
    private TravelCategoryRepositoryInterface $travelCategoryRepository;

    public function __construct(
        TravelCategoryRepositoryInterface $travelCategoryRepository,
    ) {
        $this->travelCategoryRepository = $travelCategoryRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (request()->trashed) {
            $categories = TravelCategory::onlyTrashed()->get();

            activity()
                ->log('Showed trashed travel categories');
        } else {
            $categories = $this->travelCategoryRepository->getCategories();

            activity()
                ->log('Showed travel categories');
        }

        return inertia('TravelCategories/TravelCategoryList', [
            'categories' => $categories,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTravelCategoryRequest $request)
    {
        $this->travelCategoryRepository->createCategory($request->all());
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTravelCategoryRequest $request, TravelCategory $travelCategory)
    {
        $this->travelCategoryRepository->updateCategory($request->all(), $travelCategory);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TravelCategory $travelCategory)
    {
        $this->travelCategoryRepository->deleteCategory($travelCategory);
    }

    public function restore($category_id = null)
    {
        $this->travelCategoryRepository->restoreCategory($category_id);
    }

    public function fDelete($category_id = null)
    {
        $this->travelCategoryRepository->forceDeleteCategory($category_id);
    }
}
