<?php

namespace App\Services\Import;

use App\Models\Category;

class CategoryTreeService
{
    public function rebuild(): void
    {
        // old_id => new_id
        $map = Category::pluck('id', 'old_id');

        $categories = Category::all();

        foreach ($categories as $category) {

            $oldParentId = $category->parent_old_id;

            $category->update([
                'parent_id' => $oldParentId
                    ? ($map[$oldParentId] ?? null)
                    : null,
            ]);
        }
    }
}
