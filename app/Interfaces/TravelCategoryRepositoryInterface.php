<?php

namespace App\Interfaces;

use App\Models\TravelCategory;

interface TravelCategoryRepositoryInterface
{
    public function getCategories();

    public function createCategory(array $data);

    public function updateCategory(array $data, TravelCategory $category);

    public function deleteCategory(TravelCategory $category);

    public function restoreCategory(string $category_id);

    public function forceDeleteCategory(string $category_id);
}
