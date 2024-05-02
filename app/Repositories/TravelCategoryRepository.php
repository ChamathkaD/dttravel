<?php

namespace App\Repositories;

use App\Interfaces\TravelCategoryRepositoryInterface;
use App\Models\TravelCategory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Str;

class TravelCategoryRepository implements TravelCategoryRepositoryInterface
{
    /**
     * Retrieve all travel categories with their descendants in descending order of creation.
     *
     * This method fetches all travel categories along with their descendants using eager loading.
     * The result is ordered by the latest creation date, with the newest categories appearing first.
     *
     * @return Collection|TravelCategory[]
     */
    public function getCategories(): Collection|array
    {
        return TravelCategory::with('descendants')->latest()->get()->toTree();
    }

    /**
     * Create a new travel category with the provided data.
     *
     * This method creates a new travel category based on the given data array, including
     * the name, slug (generated from the name), and description. If a parent category is
     * specified in the data, the new category is appended as a child to that parent category.
     *
     * Optionally, if a new category image is provided, it is updated for the newly created category.
     *
     * @param  array  $data An associative array containing data for creating the travel category.
     *                    Expected keys: 'name', 'slug', 'description', 'parent_category', 'photo'.
     */
    public function createCategory(array $data): void
    {
        $category = TravelCategory::create([
            'name' => $data['name'],
            'slug' => Str::slug($data['slug']),
            'description' => $data['description'],
        ]);

        if (isset($data['parent_category'])) {
            $parent = TravelCategory::find($data['parent_category']);
            $category->appendToNode($parent)->save();
        }

        // if a new category image is specified, update it
        if (isset($data['photo'])) {
            $category->updatePhoto($data['photo'], 'category_image_path', 'category-images');
        }

        activity()
            ->performedOn($category)
            ->log('Created Travel Category');
    }

    /**
     * Update the specified travel category with the provided data.
     *
     * This method updates the attributes of the given travel category based on the provided data array,
     * including the name, slug (generated from the name), and description. If a parent category is
     * specified in the data, the category is moved to become a child of that parent category.
     *
     * Optionally, if a new category image is provided, it is updated for the specified category.
     *
     * @param  array  $data An associative array containing data for updating the travel category.
     *                                   Expected keys: 'name', 'slug', 'description', 'parent_category', 'photo'.
     * @param  TravelCategory  $category The travel category instance to be updated.
     */
    public function updateCategory(array $data, TravelCategory $category): void
    {
        $category->update([
            'name' => $data['name'],
            'slug' => Str::slug($data['slug']),
            'description' => $data['description'],
        ]);

        if (isset($data['parent_category'])) {
            $parent = TravelCategory::find($data['parent_category']);
            $parent->appendNode($category);
        }

        // if a new category image is specified, update it
        if (isset($data['photo'])) {
            $category->updatePhoto($data['photo'], 'category_image_path', 'category-images');
        }

        activity()
            ->performedOn($category)
            ->log('Updated Travel Category');
    }

    /**
     * Delete the specified travel category.
     *
     * @param  TravelCategory  $category The travel category instance to be deleted.
     */
    public function deleteCategory(TravelCategory $category): void
    {
        $category->delete();

        activity()
            ->performedOn($category)
            ->log('Deleted travel category into trash');
    }

    /**
     * Restore a soft-deleted travel category by its ID.
     *
     * This method restores a travel category that has been soft-deleted. Soft deletion is a mechanism
     * where records are not permanently deleted from the database but are marked as "trashed"
     * or "soft-deleted." This function specifically restores a travel category by its unique identifier.
     *
     * @param  string  $category_id The unique identifier of the travel category to be restored.
     */
    public function restoreCategory(string $category_id): void
    {
        TravelCategory::onlyTrashed()->where('id', $category_id)->restore();

        $category = TravelCategory::find($category_id);

        activity()
            ->performedOn($category)
            ->log('Restored Travel Category');
    }

    /**
     * Permanently delete a travel category along with related data.
     *
     * This method performs a force delete on a travel category, removing it permanently from the
     * database. It also takes care of additional cleanup tasks such as deleting the associated category image.
     *
     * @param  string  $category_id The unique identifier of the travel category to be permanently deleted.
     *
     * @throws ModelNotFoundException If the specified travel category is not found.
     */
    public function forceDeleteCategory(string $category_id): void
    {
        // Get Travel Category
        $category = TravelCategory::onlyTrashed()->findOrFail($category_id);

        // Delete the photo
        if ($category->category_image_path) {
            $category->deletePhoto($category->category_image_path, 'category_image_path');
        }

        $category->forceDelete();

        activity()
            ->performedOn($category)
            ->withProperties(['category' => $category])
            ->log('Deleted Travel Category Permanently');
    }
}
